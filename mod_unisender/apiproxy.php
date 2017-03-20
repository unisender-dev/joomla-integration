<?php
header("Content-type:text/html; charset=utf-8;");
require_once 'config.php';

function doPostRequest($url, $data="", $optional_headers = null)
{
    $params = array('http' => array(
              'method' => 'POST',
              'content' =>$data
           ));
    if ($optional_headers !== null) {
        $params['http']['header'] = $optional_headers;
    }
    
    $ctx = @stream_context_create($params);
    $fp = @fopen($url, 'rb', false, $ctx);
    if (!$fp)
    {
        return null;
    }
    
    $response = @stream_get_contents($fp);
    
    if ($response === false)
    {
        return null;
    }
    return $response;
}

if(!empty($_POST['function']))
{
    $params = !(empty($_POST['data']))?urldecode(trim($_POST['data'])):'';
    $func = trim($_POST['function']);
    $res = doPostRequest(trim($unisender_plugin_config['api_url'],"/")."/".$func."?format=json",$params);
    if(!is_null($res))
    {
        echo $res;
    }
    else
    {
        echo "";
    }
}
?>