<?php
    // display errors, warnings, and notices
    ini_set("display_errors", true);
    error_reporting(E_ALL);

    // requirements
    require("constants.php");
    require("functions.php");
    

    $mysqli = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);
    $mysqli->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
    // enable sessions
    session_start();

    //paypal settings
    $PayPalMode 			= 'sandbox'; // sandbox or live
    //$PayPalMode 			= 'live'; 
    
    $PayPalApiUsername 		= 'toks22_api1.hotmail.com'; //test
    //$PayPalApiUsername 		        = 'akoto_api1.optonline.net'; //live 
    
    $PayPalApiPassword 		= 'A47VJMP297Q7S5GD'; //test
    //$PayPalApiPassword 		        = 'J28HL4KJ66RCLWP3'; //live
    
    $PayPalApiSignature 	        = 'AFcWxV21C7fd0v3bYYYRCpSSRl31AxK8PJo4CQx1cc1cGdYXQjhieFTK'; //test
    //$PayPalApiSignature 	        = 'A0LRq.WbvcTEmiFVN2vFi89sB-ysA9bMCtMYWRbVuKtEE8.YUqbtnsgt'; //live
    
    $PayPalCurrencyCode 	= 'USD'; //Paypal Currency Code
    
    $PayPalReturnURL 		= 'http://localhost/akoto/public/process_paypal.php';   //test
    //$PayPalReturnURL 		= 'http://www.akotosghboutique.com/process_paypal.php'; //live
    
    $PayPalCancelURL 		= 'http://localhost/akoto/public/cancel_url.php'; //test
    //$PayPalCancelURL 		= 'http://www.akotosghboutique.com/cancel_url.php'; //live
?>