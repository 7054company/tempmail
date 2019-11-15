<?php
@session_name('xTempMail');
@session_start();

error_reporting(0);
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

$url = explode("/",$_GET['url']);

@define(xLOCATION, str_replace('/index.php','', $_SERVER["PHP_SELF"]));
@define(xVERSION, "1.0");

// if(substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();

include 'inc/xTempMail.php';
$xTempMail = new xTempMail();
$xTempMail->CHECKCHMOD();
switch ($url[0]) {
	// case 'home':
		// $xTempMail->TEMPLATE('home');
		// break;
	case 'source':
		$xTempMail->TEMPLATE('source');
		$xTempMail->PARAMETER($url[1]);
		break;
	case 'download':
		$xTempMail->TEMPLATE('download');
		$xTempMail->PARAMETER($url[1]);
		break;
	default:
		$xTempMail->TEMPLATE('home');
		break;
		// exit('<meta http-equiv="refresh" content="0; URL=\'home\'" />');
		// break;
}
// error_reporting(E_ALL);
$xTempMail->LANGUAGE('en');
$xTempMail->RENDER();

?>