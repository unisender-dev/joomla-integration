<?php
/**
 * @version      $Id: list.php 14401 2010-01-26 14:10:00Z louis $
 * @package      Joomla.Framework
 * @subpackage   Parameter
 * @copyright    Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license      GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

// Joomla 1.5
jimport('joomla.html.parameter.element');
// Joomla 1.6
jimport('joomla.html.html');
jimport('joomla.form.formfield'); //import the necessary class definition for formfield

/**
 * Renders a list element
 *
 * @package     Joomla.Framework
 * @subpackage      Parameter
 * @since       1.5
 */
// Joomla 1.5
//class JElementUnisenderList extends JElement
// Joomla 1.6
class JFormFieldUnisenderList extends JFormField
{
	/**
	 * Element type
	 *
	 * @access   protected
	 * @var      string
	 */
	var $_name = 'UnisenderList';
	protected $type = 'UnisenderList';

	// Joomla 1.5
	//function fetchElement($name, $value, &$node, $control_name)
	// Joomla 1.6
	public function getInput()
	{
		// Joomla 1.6
		$name = $this->name;
		$value = $this->value;
		$node = $this->node;
		$control_name = $this->control_name;
		// end of Joomla 1.6

		$html = '';
		$module_path = dirname(dirname(__FILE__));
		$helper_file = $module_path . '/helper.php';
		require_once($helper_file);
		$helper = new ModUnisenderHelper();
		$params = $helper->getParams('mod_unisender');
		// restore list id
		$list_id = isset($params['UNISENDER_LIST'])
			? $params['UNISENDER_LIST']
			: '';
		$list_id = empty($list_id) && isset($params['UNISENDER_IMPORT_LIST'])
			? $params['UNISENDER_IMPORT_LIST']
			: $list_id;
		$group_id = (int)$value;
		$PORTION = 5;

		// import
		if ($group_id != 0 && !empty($list_id)) {
			// split to $PORTION (default:50) items
			$imported = isset($params['UNISENDER_IMPORTED'])
				? (int)$params['UNISENDER_IMPORTED']
				: 0;
			$users = $helper->getUsers($group_id, $imported, $PORTION);

			// get users
			if ($users !== false && count($users) > 0) {
				$result = $helper->importUsers($users, $list_id);
				$imported = $imported + $result['total'];
				$document = & JFactory::getDocument();
				$document->addCustomTag('<script type="text/javascript">
                    window.addEvent("domready", function(){
                        document.getElementById("jform_params_UNISENDER_IMPORT_LIST").value=' . $list_id . ' ;
                        document.getElementById("jform_params_UNISENDER_IMPORTED").value=' . $imported . ' ;
                        Joomla.submitbutton(\'module.apply\');
                        });</script>');
				$document->addCustomTag('<script type="text/javascript">window.addEvent("domready", function(){ Joomla.submitbutton(\'module.apply\'); });</script>');
				//$html .= '<input type="hidden" name="jform[params][UNISENDER_IMPORT_LIST]" value="'.$list_id.'">';
			} else {
				// end of import - reset variables
				$value = '';
				$imported = 0;
				$document = & JFactory::getDocument();
				$document->addCustomTag('<script type="text/javascript">
                    window.addEvent("domready", function() {
                        document.getElementById("jform_params_UNISENDER_IMPORTED").value=' . $imported . ' ;
                        });</script>');
				//$document->addCustomTag( '<script type="text/javascript">window.addEvent("domready", function(){ document.getElementById("jform_params_UNISENDER_IMPORT_LIST").value="" ; });</script>' );
				//$document->addCustomTag( '<script type="text/javascript">window.addEvent("domready", function(){ alert(1); });</script>' );
			}

			//$html .= '<input type="hidden" name="jform[params][UNISENDER_IMPORTED]" value="'.$imported.'">';
		}

		// html element
		//$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );
		$class = "inputbox";
		$groups = $helper->getGroups();
		$options = array();
		$options[] = JHTML::_('select.option', '', JText::_('-- None --'));

		foreach ($groups as $group) {
			$val = $group['group_id'];
			$text = $group['name'] . ' (' . $group['cnt'] . ')';
			$options[] = JHTML::_('select.option', $val, $text);
		}

		$html .= JHTML::_('select.genericlist', $options, '' . $name, $class, 'value', 'text', $value, $name);
		$html .= '<input type="button" value="Import" onclick="javascript:Joomla.submitbutton(\'module.apply\')">';

		if (!empty($imported))
			$html .= '<br><br><div>' . JText::_('Imported:') . ' <input type="text" disabled="disabled" value="' . $imported . '"></div>';

		// paramsUNISENDER_LIST
		return $html;
	}

	public function getLabel()
	{
		return '<label id="jform_params_UNISENDER_GROUP-lbl" for="jform_params_UNISENDER_GROUP" aria-invalid="false">Group</label>';
	}
}
