<?php define('ENCRYPTION_KEY', '__^%&Q@$&*!@#$%^&*^__');
$string = "This is the original string!";

if (!function_exists('enc')) {
    function enc($q)
    {
        $password = "password";
		return openssl_encrypt($q, "AES-128-ECB", $password);
    }
}

if (!function_exists('dec')) {
    function dec($q)
    {
        $password = "password";
		return openssl_decrypt($q, "AES-128-ECB", $password);
    }
}

if (!function_exists('check_dec')) {
    function check_dec($param, $param_encryp)
    {
        $param = enc($param);
        if ($param === $param_encryp){
            return true;
        } else {
            return false;
        }
        
    }
}
