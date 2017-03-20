<?php
if (!function_exists('_e'))
{
    function _e($str, $domain)
    {
        echo $str;
    }
}
?>

if(ru && ru.unisender && ru.unisender.i18n)
{
    ru.unisender.i18n['Invalid api key'] = '<?php _e('Invalid api key',"unisender");?>';
    ru.unisender.i18n['Enter your unisender api key'] = '<?php _e('Enter your unisender api key',"unisender");?>';
    ru.unisender.i18n['Some fields were not filled properly'] = '<?php _e('Some fields were not filled properly',"unisender");?>';
    ru.unisender.i18n['Title is empty'] = '<?php _e('Title is empty',"unisender");?>';
    ru.unisender.i18n['Name is empty'] = '<?php _e('Name is empty',"unisender");?>';
    ru.unisender.i18n['Field with this name already exists'] = '<?php _e('Field with this name already exists',"unisender");?>';
    ru.unisender.i18n['Subscribe'] = '<?php _e('Subscribe',"unisender");?>';
    ru.unisender.i18n['Unisender API not available now. Try again later.'] = '<?php _e('Unisender API not available now. Try again later.',"unisender");?>';
    ru.unisender.i18n['API response'] = '<?php _e('API response',"unisender");?>';
}
