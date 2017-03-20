<?php

global $unisender_plugin_config;

$unisender_plugin_config = array();

$unisender_plugin_config['domain'] = 'http://api.unisender.com/';

// Path the the location of UniSender API. Usually, it should not be changed
$unisender_plugin_config['api_url'] = $unisender_plugin_config['domain'] . "ru/api";


// A URL address of the page which will be shown to the subscriber after he enters his e-mail address
$unisender_plugin_config['redirect_before_subscribe'] = $unisender_plugin_config['domain'] . "ru/before_subscribe";


// URL-request type. Can be "post" or "get". POST-method is more secure.
// (if "post" then check if curl php module enabled)
$unisender_plugin_config['api_connect_method'] = "post"; 


?>