<?php
/**
* @version      $Id: category.php 14401 2010-01-26 14:10:00Z louis $
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

jimport( 'joomla.language.language' );
jimport( 'joomla.html.parameter.element' );
//$language =& JFactory::getLanguage();
//$language->load('signs' , dirname(__FILE__), 'phones', true);
//$prev_lang = $language->setLanguage('phones');
//$sign_name = JText::_('S'.$sign_id);
require_once (dirname(dirname(__FILE__)).DS.'stub.php');


/**
 * Renders a category element
 *
 * @package     Joomla.Framework
 * @subpackage      Parameter
 * @since       1.5
 */
//class JElementUnisenderImport extends JElement
class JFormFieldUnisenderImport extends JFormField
{
    /**
    * Element name
    *
    * @access   protected
    * @var      string
    */
    var $PLUGIN_PATH;
    var $PLUGIN_FOLDER_NAME;
    var $_name = 'UnisenderImport';
    var $formAction = "";
    var $fieldsOption = 'unisender_fields';
    protected $type = 'UnisenderImport';


    //function fetchElement($name, $value, &$node, $control_name)
    public function getInput()
    {
        // Joomla 1.6
        $name = $this->name;
        $value = $this->value;
        $node = $this->node;
        $control_name = $this->control_name;
        // end of Joomla 1.6

        $module_url = JURI::base().'modules/mod_unisender';
        $module_url = str_replace('/administrator/', '/', $module_url);
        $module_path = dirname(dirname(__FILE__));
        $formAction = $this->formAction;

        $helper_file = $module_path.'/helper.php';
        require_once($helper_file);
        $helper = new ModUnisenderHelper();
        $params = $helper->getParams('mod_unisender');
        $fields = $helper->getFields($params);

        $html = '';
        $html .= '<input type="hidden" id="id-unisender_list_name_preset" value="'.$params['UNISENDER_LIST'].'">';
        $html .= '<input type="hidden" id="id-unisender_list_title" name="unisender_list_title" value="">';
        $html .= '<input type="hidden" id="id-proxyurl" name="unisender_proxyurl" value="'.$module_url.'/apiproxy.php">';

        // add jQuery
        $document = &JFactory::getDocument();
        $document->addScript( $module_url.'/js/jquery.js' );
        $document->addCustomTag( '<script type="text/javascript">jQuery.noConflict();</script>' );
        $document->addScript( $module_url.'/js/ui.core.js' );
        $document->addScript( $module_url.'/js/ui.widget.js' );
        $document->addScript( $module_url.'/js/ui.mouse.js' );
        $document->addScript( $module_url.'/js/ui.draggable.js' );
        $document->addScript( $module_url.'/js/ui.droppable.js' );
        $document->addScript( $module_url.'/js/ui.dialog.js' );
        $document->addScript( $module_url.'/js/ui.sortable.js' );

        // Unisender scripts
        $document->addScript($module_url.'/js/init.js');
        $document->addScript($module_url.'/js/listloader.js');
        $document->addScript($module_url.'/js/init_options.js');
        $document->addScript($module_url.'/js/formbuilder.js');
        $document->addScript($module_url.'/js/i18n.js.php');

        // css
        //JHTML::stylesheet($module_url.'/css/stylesheet.css');
        JHTML::stylesheet('style.css', $module_url.'/css/');
        JHTML::stylesheet('jquery-ui.css', $module_url.'/css/');

        // Form builder
        ob_start();
        include($module_path.'/tmpl/formbuilder.tpl');
        $html .= ob_get_contents();
        ob_end_clean();

        return $html;
    }

    public function getLabel()
    {
        return '<label id="jform_params_UNISENDER_IMPORT-lbl" for="jform_params_UNISENDER_IMPORT" aria-invalid="false">Form</label>';
    }
}
