<?php
use Phpfastcache\Config\ConfigurationOption;
use Phpfastcache\Helper\Psr16Adapter;

error_reporting(0);
$_cacheEngine = new Psr16Adapter("files", new ConfigurationOption([
    "path"             => CONFIG_DIR,
    "itemDetailedDate" => false,
]));
$config = [];
function cacheEngine(){
    global $_cacheEngine;
    return $_cacheEngine;
}
if (file_exists(CONFIG_DIR . "config.php") && is_readable(CONFIG_DIR . "config.php")) {
    $config = include CONFIG_DIR . "config.php";
}
function site_url()
{
    $uri = explode("?", $_SERVER['REQUEST_URI']);
    return sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && 'off' != $_SERVER['HTTPS'] ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        trim(str_replace($_SERVER['SCRIPT_NAME'], "", $uri[0]), "/")
    ) . "/";
}
function hashIt($password = "")
{
    return sha1(md5($password));
}
function delTemp()
{
    @array_walk(glob(CONFIG_DIR . 'temp/[0-9][0-9][0-9][0-9][0-9]'), create_function('&$v, $k', 'unlink($v);'));
}
function doAuth($user, $pass)
{
    $userdata = include CONFIG_DIR . "userdata.php";
    if ($userdata['username'] === $user && hashIt($pass) === $userdata['password']) {
        $_SESSION['is_admin'] = hashIt($userdata['username'] . $userdata['password']);
        return true;
    }
    return false;
}

function get_param($key, $default = "")
{
    if (isset($_GET[$key])) {
        return filter_var($_GET[$key], FILTER_SANITIZE_STRING);
    }
    return $default;
}

function get_option($key, $default = "")
{
    global $config;
    if (isset($config[$key])) {
        return $config[$key];
    }
    return $default;
}
function normalize($string)
{
    $string = preg_replace("/([^a-z0-9])/", "-", strtolower($string));
    $string = preg_replace("/(\s+)/", "-", strtolower($string));
    $string = preg_replace("/([-]+){2,}/", "-", strtolower($string));
    return $string;
}
function get_remote($url, $json = true, $headers = [])
{
    if (!is_null(cacheEngine()->get(normalize($url)))) {
        return cacheEngine()->get(normalize($url));
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
    if (is_array($headers) && !empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $data = curl_exec($ch);
    curl_close($ch);
    if ($json) {
        $data = @json_decode($data);
    }
    if (isset($data->error) && true === $data->error) {
        return false;
    }
    cacheEngine()->set(normalize($url), $data, 3600); // cache for 1 hours
    return $data;
}
function force_download($url, $filename)
{
    ob_start();
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header("Content-Transfer-Encoding: binary");
    header('Expires: 0');
    header('Pragma: public');
    if (isset($_SERVER['HTTP_REQUEST_USER_AGENT']) && strpos($_SERVER['HTTP_REQUEST_USER_AGENT'], 'MSIE') !== false) {
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    }
    header('Connection: Close');
    ob_clean();
    flush();
    readfile($url, "", stream_context_create([
        "ssl" => [
            "verify_peer"      => false,
            "verify_peer_name" => false,
        ],
    ]));
    exit;
}
