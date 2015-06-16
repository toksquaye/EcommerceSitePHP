<?php
require_once("../includes/PHPMailer-Master/class.phpmailer.php");

echo "here";
//$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail = new PHPMailer();
        //$mail->SMTPAuth    = TRUE; // enable SMTP authentication
        //$mail->SMTPSecure  = "ssl"; //Secure conection
        //$mail->Port        = 465; // set the SMTP port
        $mail->Host = "smtp.mail.yahoo.com";
        //$mail->SetFrom("olubimpe.olatokunbo@yahoo.com");
        $mail->AddReplyTo("olubimpe.olatokunbo@yahoo.com","First Last");
        $mail->SetFrom('olubimpe.olatokunbo@yahoo.com', 'First Last');
        $address = "toksquaye@yahoo.com";
        $mail->AddAddress($address, "Toks Quaye");
        $mail->Password    = "Testcases1";
        //$mail->AddAddress("toksquaye@yahoo.com");
        $mail->Subject = "Stocks Purchase Receipt";
        $mail->Body =
        "This is a receipt of the stocks you recently purchased.\n" ;
        //"Stock Symbol: ". $_POST["symbol"] . "\n" .
        //"# of Shares: " . $_POST["shares"] . "\n" .
        //"Cost per share: " . $quote["price"] . "\n" .
        //"Total Cost: " . $cost . "\n" ;
        echo "before"; 
        if(!$mail->Send())
        {
            echo "not sent!";
            echo "Mailer Error: " . $mail->ErrorInfo;
            echo 'Not sent: <pre>'.print_r(error_get_last(), true).'</pre>';


            
        }
        else
        {echo "sent!";}
        
         echo "after";

?>