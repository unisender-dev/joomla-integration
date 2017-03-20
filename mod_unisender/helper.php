<?php
/**
 * mod_unisender Helper class
 *
 * @author Unisender
 * @copyright (c)2011 All rights reserved
 *
 */

defined('_JEXEC') or die('Access denied.');

require_once(dirname(__FILE__) . DS . 'unisender_class.php');


class ModUnisenderHelper
{
	var $params = array();


	function __construct()
	{
		$params = $this->getParams('mod_unisender');
		$this->params = is_array($params) ? $params : array();
	}


	function getFields()
	{
		$params = $this->params;
		$fields = array();

		// read params
		foreach ($params as $key => $value) {
			if (substr($key, 0, 6) != 'field_')
				continue;

			$exploded = explode('_', $key);

			if (count($exploded) < 3)
				continue;

			$tag = $exploded[0];
			$i = $exploded[1];
			$name = $exploded[2];

			$fields[$i][$name] = $value;
		}

		// clear
		$filtered = array();
		foreach ($fields as $i => $field) {
			if (isset($fields[$i]['name']) && empty($fields[$i]['name']))
				continue;

			$filtered[] = $field;
		}

		$fields = $filtered;

		$hasEmail = false;
		if (!empty($fields) && is_array($fields)) {
			for ($i = 0, $len = count($fields); $i < $len; $i++) {
				if ($fields[$i]['name'] == "email") {
					$hasEmail = true;
					break;
				}
			}
		}

		if (!$hasEmail) {
			$fields = (is_array($fields) && !empty($fields)) ? $fields : array();
			$fields[count($fields)] = array('name' => 'email', 'title' => "Email", 'mand' => 1);
			//update_option($this->fieldsOption, serialize($fields));
		}

		return $fields;
	}


	function getParams($module)
	{
		$db =& JFactory::getDBO();
		$params = array();

		$sql = "SELECT params FROM #__modules WHERE module = '" . $module . "'";
		$db->setQuery($sql);
		$modules = $db->loadObjectList();

		if (count($modules) == 0)
			return false;

		$data = $modules[0]->params;
		$params = json_decode($data, TRUE);
		/*
				$exploded = explode("\n", $data);

				foreach($exploded as $p)
				{
					$pair = explode('=', $p, 2);

					if (count($pair) < 2)
						continue;

					$pname = trim($pair[0]);
					$pvalue = trim($pair[1]);
					$params[$pname] = $pvalue;
				}
		*/

		return $params;
	}


	// 1. Groups
	function getGroups()
	{
		$db = JFactory::getDbo();
		//$sql = 'SELECT * FROM #__core_acl_aro_groups j';
		// Joomla 1.5
		if (self::isVersion('1.5')) {
			$sql = '
                SELECT
                    u.gid as group_id,
                    u.usertype as name,
                    count(*) as cnt
                FROM #__users u
                GROUP BY u.usertype
                ORDER BY u.usertype';
		} // Joomla 1.6
		else {
			$sql = '
                SELECT
                    g.id as group_id,
                    g.title as name,
                    count(*) as cnt
                FROM #__usergroups g
                JOIN #__user_usergroup_map m ON m.group_id = g.id
                JOIN #__users u ON u.id = m.user_id
                GROUP BY g.id
                ORDER BY g.title';
		}
		$res = $db->setQuery($sql);
		$groups = $db->loadAssocList();
		/*
		$attribs   = ' ';
		$attribs   .= 'size="'.count($groups).'"';
		$attribs   .= 'class="inputbox"';
		$attribs   .= 'multiple="multiple"';
		*/
		//return JHTML::_('select.genericlist', $groups, $name, $attribs, 'value', 'text', $value, $id );
		return $groups;
	}

	/**
	 * Get a list of the user groups.
	 *
	 * @return array
	 * @since 1.6
	 */
	/*
		protected function getUserGroups()
		{
			// Initialise variables.
			$db = JFactory::getDBO();
			$query = $db->getQuery(true)
				->select('a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level, a.parent_id')
				->from('#__usergroups AS a')
				->leftJoin('`#__usergroups` AS b ON a.lft > b.lft AND a.rgt < b.rgt')
				->group('a.id')
				->order('a.lft ASC');
			$db->setQuery($query);
			$options = $db->loadObjectList();
			return $options;
		}
	*/

	// 2. Users by group
	function getUsers($group_id, $offset = 0, $limit = 50)
	{
		$group_id = (int)$group_id;

		$db = JFactory::getDbo();
		$sql = '
            SELECT
                u.name as Name,
                u.email
            FROM #__users u
            JOIN #__user_usergroup_map m ON m.user_id = u.id
            WHERE m.group_id = ' . $group_id . '
            LIMIT ' . $offset . ',' . $limit;

		$res = $db->setQuery($sql);
		$users = $db->loadAssocList();

		return $users;

		/*
		SELECT g.id as group_id, g.name as group_name, u.id as user_id, u.name as user_name, u.email
		FROM jos_core_acl_aro_groups g
		JOIN jos_core_acl_groups_aro_map m ON m.group_id = g.id
		JOIN jos_core_acl_aro a ON a.id = m.aro_id
		JOIN jos_users u ON u.id = a.value
		*/
	}


	// 3. Run unisender import
	function importUsers($users, $list_id)
	{
		$res = false;
		$api_key = $this->params['UNISENDER_KEY'];
		$uni = new UniAPI($api_key);
		$POST = array();

		// field names
		$field_names = array_keys($users[0]);
		$field_names[] = 'email_list_ids';
		$list_fidx = array_search('email_list_ids', $field_names);
		foreach ($field_names as $fidx => $fname) {
			$postkey = 'field_names[' . $fidx . ']';
			$POST[$postkey] = $fname;
		}

		// data
		foreach ($users as $uidx => $user) {
			foreach ($field_names as $fidx => $field) {
				// data
				$postkey = 'data[' . $uidx . '][' . $fidx . ']';
				// value or unisender list id
				$postvalue = ($fidx == $list_fidx) ? $list_id : $user[$field];

				$POST[$postkey] = $postvalue;
			}
		}

		if (($res = $uni->importContacts($POST)) !== false) {
			flush();
		} else
			$uni->showError();

		return $res;
	}


	function renderForm($form)
	{
		return $form;
	}


	function doApiPost($url, $data = "", $optional_headers = null)
	{
		$params = array('http' => array(
			'method' => 'POST',
			'content' => $data
		));
		if ($optional_headers !== null) {
			$params['http']['header'] = $optional_headers;
		}

		$ctx = stream_context_create($params);
		$fp = @fopen($url, 'rb', false, $ctx);
		if (!$fp) {
			return null;
		}
		$response = @stream_get_contents($fp);
		if ($response === false) {
			return null;
		}
		return $response;
	}

	function processForm()
	{
		/*
				add_option($this->fieldsOption);
				if(!empty($_POST['unisender_form_email']))
				{
					$errors = array();
					if(!empty($_POST['fields']))
					{
						update_option($this->fieldsOption, serialize($_POST['fields']));
						$this->set('fields',$_POST['fields']);
					}

					if(!empty($_POST['unisender_subscribe_form']))
					{
						add_option('unisender_subscribe_form');
						update_option('unisender_subscribe_form', $this->_antiMq($_POST['unisender_subscribe_form']));
					}

				}
		*/
		if (!empty($_POST['unisender_subscribe'])) {
			global $unisender_subscribe_form_errors;
			//global $unisender_plugin_config;
			include(dirname(__FILE__) . DS . 'config.php');
			jimport('joomla.mail.helper');

			$unisender_subscribe_form_errors = array();
			$module_params = $this->getParams('mod_unisender');
			$fields = $this->getFields($module_params);


			$params = array();
			if (!empty($fields)) {
				for ($i = 0, $len = count($fields); $i < $len; $i++) {
					$val = !empty($_POST[$fields[$i]['name']])
						? trim($_POST[$fields[$i]['name']])
						: '';
					$params[] = "fields[" . $fields[$i]['name'] . "]=" . $val;

					if ($fields[$i]['mand']) {
						if (strlen($val) == 0) {
							$unisender_subscribe_form_errors[] = __("Field", "unisender") . " " . $fields[$i]['title'] . " " . __('is required to be filled', "unisender");
						}
					}

					if ($fields[$i]['name'] == 'email') {
						if (!JMailHelper::isEmailAddress($val)) {
							$unisender_subscribe_form_errors[] = __("Invalid email format", "unisender");
						}
					}
				}

				if (empty($unisender_subscribe_form_errors)) {
					$url = trim($unisender_plugin_config['api_url'], "/") . "/subscribe?format=json";
					$data4Api = "list_ids=" . $module_params["UNISENDER_LIST"] . "&" . implode("&", $params) . "&api_key=" . $module_params["UNISENDER_KEY"];
					$res = $this->doApiPost($url, $data4Api);

					$json = $this->_jsonDecode($res);
					if (empty($json)) {
						$unisender_subscribe_form_errors[] = __("Sorry. Unisender is not available now. Please try again later.", "unisender");
					} else {
						if (!empty($json->error)) {
							$unisender_subscribe_form_errors[] = __("Sorry. Errors were detected during subscription", "unisender") . (!empty($json->code) ? ": error=" . $json->error . ", code=" . $json->code : ".");
						} else {
							ob_get_clean();
							header("Location:" . $unisender_plugin_config['redirect_before_subscribe']);
							exit;
						}
					}
				}
			}
		}
	}


	function _jsonDecode($data)
	{
		//Thanks to www at walidator dot info (http://www.php.net/manual/en/function.json-decode.php#91216)
		if (!function_exists('json_decode')) {
			function json_decode($json)
			{
				$comment = false;
				$out = '$x=';

				for ($i = 0; $i < strlen($json); $i++) {
					if (!$comment) {
						if ($json[$i] == '{') $out .= ' array(';
						else if ($json[$i] == '}') $out .= ')';
						else if ($json[$i] == ':') $out .= '=>';
						else                         $out .= $json[$i];
					} else $out .= $json[$i];
					if ($json[$i] == '"') $comment = !$comment;
				}
				eval($out . ';');
				return $x;
			}
		}
		return json_decode($data);
	}


	function isVersion($version)
	{
		$version = new JVersion();

		if ($version->RELEASE == $version) // '1.5', '1.6'
			return TRUE;
		else
			return FALSE;
	}
}

