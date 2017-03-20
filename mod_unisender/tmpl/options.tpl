<script src="<?php echo WP_PLUGIN_URL.'/'.$this->PLUGIN_FOLDER_NAME.'/js/ru/unisender/init_options.js';?>"></script>
<script>
<?php 
$this->_translateJs();
?>
</script>
<div class="wrap">
    <h2 class="us-title"><?php
        _e("Unisender plugin options","unisender");
        ?></h2>
    <form method="post" action="options.php">
        <input type="hidden" id="id-wp_plugin_url" value="<?php echo WP_PLUGIN_URL;?>"/>
        <input type="hidden" id="id-proxyurl" value="<?php echo WP_PLUGIN_URL."/".$this->PLUGIN_FOLDER_NAME."/apiproxy.php";?>"/>
        <?php wp_nonce_field('update-options'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e("API key","unisender");?></th>                
                <td><input type="text" id="id-apikey" class="regular-text code" name="unisender_api_key" value="<?php echo get_option('unisender_api_key');?>"/></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Mailing list","unisender");?></th>
            <input type="hidden" id="id-unisender_list_name_preset" value="<?php echo get_option('unisender_list_name');?>"/>
            <input type="hidden" id="id-unisender_list_title" name="unisender_list_title"/>
                <td><select name="unisender_list_name" id="id-unisender_list_name"></select></td>
            </tr>
        </table>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="unisender_list_name,unisender_api_key,unisender_list_title" />
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes','unisender') ?>" />
        </p>
    </form>
</div>