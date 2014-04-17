<?php

require "config.php";

if(PROXY_SUBFOLDER != '')
	$uri = str_replace(PROXY_SUBFOLDER, '',$_SERVER['REQUEST_URI']);

$url = MASKED_DOMAIN . $uri;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FOLLOW_LOCATION); 
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

$cookies = "";
foreach($_COOKIE as $k=>$v)
	$cookies .= $k.'='.$v.';';

curl_setopt($ch, CURLOPT_COOKIE, '"'.$cookies. '"');
$data = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($data, 0, $header_len);
$body = substr($data, $header_len);

curl_close($ch);

/* get cookies from curl'd data */
preg_match('/^Set-Cookie:\s*([^;]*)/mi', $data, $m);

if(isset($m[1]))
{
	parse_str($m[1], $cookies);
	foreach($cookies as $key => $value)
	{
		$expire = time()+3600*24*30;
		setcookie($key,$value,$expire,'/',$_SERVER['HTTP_HOST']); // set cookies again
	}
}


if($http_code == 301 || $http_code == 302)
	header('Location: '. get_redirect_url($data));
else
{
	echo $body;
}


function get_redirect_url($header) {
    if(preg_match('/^Location:\s+(.*)$/mi', $header, $m)) {
        return trim($m[1]);
    }

    return "";
}

?>