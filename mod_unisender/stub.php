<?php


if (!function_exists('_e'))
{
    function _e($str, $domain)
    {
        echo JText::_($str);
    }
}


if (!function_exists('__'))
{
    function __($str, $domain)
    {
        echo JText::_($str);
    }
}


if (!function_exists('get_option'))
{
    function get_option($option_name)
    {
        switch ($option_name)
        {
            case 'unisender_list_title':
                return 'unisender_list_title';
                break;

            case 'unisender_fields':
                return serialize(array());
                break;

            default:
                return $option_name;
        }
    }
}


if (!function_exists('update_option'))
{
    function update_option($option_name, $value)
    {
        return true;
    }
}
