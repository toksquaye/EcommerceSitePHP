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
    
    $PayPalApiUsername 		= '********.*******.com'; //test
    //$PayPalApiUsername 		        = '*********.******.net'; //live 
    
    $PayPalApiPassword 		= '********************'; //test
    //$PayPalApiPassword 		        = '********************'; //live
    
    $PayPalApiSignature 	        = '***************'; //test
    //$PayPalApiSignature 	        = '************'; //live
    
    $PayPalCurrencyCode 	= 'USD'; //Paypal Currency Code
    
    $PayPalReturnURL 		= 'http://localhost/akoto/public/process_paypal.php';   //test
    
    
    $PayPalCancelURL 		= 'http://localhost/akoto/public/cancel_url.php'; //test
    
?>
