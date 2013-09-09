<?php
global $hd_curl, $hd_curl_info;
function hd_curl($url, $timeout=10, $header=false){
	global $hd_curl, $hd_curl_info;
    $hd_curl = curl_init();
    curl_setopt($hd_curl, CURLOPT_URL, $url);

    // Set a referer
    curl_setopt($hd_curl, CURLOPT_REFERER, $_SERVER['PHP_SELF']);
    curl_setopt($hd_curl, CURLOPT_USERAGENT, "hdUKMno");

    curl_setopt($hd_curl, CURLOPT_HEADER, true);
    curl_setopt($hd_curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($hd_curl, CURLOPT_TIMEOUT, $timeout);

    if($header) {
	    curl_setopt($hd_curl, CURLOPT_FILETIME, true);
	    curl_setopt($hd_curl, CURLOPT_NOBODY, true);
    }

    $output = curl_exec($hd_curl);
    $hd_curl_info = curl_getinfo($hd_curl);
    if(!$header)
	    curl_close($hd_curl);

    return $output;
}

function hd_curlpost($url, $postdata, $timeout=10) {
	global $hd_curl, $hd_curl_info;
	$hd_curl = curl_init();

	curl_setopt($hd_curl, CURLOPT_URL, $url);
	curl_setopt($hd_curl, CURLOPT_POST, true);
	curl_setopt($hd_curl, CURLOPT_POSTFIELDS, $postdata);
	
	curl_setopt($hd_curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($hd_curl, CURLOPT_TIMEOUT, $timeout);

    $output = curl_exec($hd_curl);
    $hd_curl_info = curl_getinfo($hd_curl);

    if(!$header)	
		curl_close ($hd_curl);
}

function hd_curl_header($url, $timeout=10) {
	return hd_curl($url, $timeout, true);
}

function hd_curl_last_modified($url, $timeout=10) {
	global $hd_curl, $hd_curl_info;
	hd_curl_header($url, $timeout);
	var_dump($hd_curl_info);
    curl_close($hd_curl);
#	return curl_getinfo($hd_curl, CURLINFO_FILETIME);
#	return date('Y-m-d H:i:s', $timestamp);
}
?>