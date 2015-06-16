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
    
    $PayPalMode 			= 'live'; 
    
    $PayPalApiUsername 		        = 'akoto_api1.optonline.net'; 
    
    $PayPalApiPassword 		        = 'J28HL4KJ66RCLWP3';
    
    $PayPalApiSignature 	        = 'A0LRq.WbvcTEmiFVN2vFi89sB-ysA9bMCtMYWRbVuKtEE8.YUqbtnsgt';
    $PayPalCurrencyCode 	= 'USD'; //Paypal Currency Code
    
    
    $PayPalReturnURL 		= 'http://www.akotosghboutique.com/process_paypal.php';
    
    
    $PayPalCancelURL 		= 'http://www.akotosghboutique.com/cancel_url.php';
?>