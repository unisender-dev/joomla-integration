<?php
/**
 * Unisender Entry Point
 *
 * @author Unisender Inc
 * @copyright (c)2011 All rights reserved
 *
 */
error_reporting(E_ALL);

defined('_JEXEC') or die('Access denied.');

require_once (dirname(__FILE__).DS.'helper.php');
require_once (dirname(__FILE__).DS.'stub.php');


// Get html content
// $params->get('UNISENDER_KEY')
$helper = new ModUnisenderHelper();
//$data = $helper->getUserGroups();
$data = $helper->renderForm($params->get('UNISENDER_FORM'));

// Render form
require (JModuleHelper::getLayoutPath('mod_unisender'));


if (isset($_POST['unisender_subscribe']))
{
    $data = array();

    foreach($_POST as $key=>$value)
    {
        if ($key == 'unisender_subscribe')
            continue;
        if ($key == 'limit')
            continue;

        $data[$key] = $value;
    }

    $helper->processForm($data);
}
// $_SERVER['REQUEST_URI']



