<?php
// configuration
    require("../includes/config.php");
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
    /* Anything that goes in here is only performed if the form is submitted */
    
   if(empty($_POST["contact_name"]))
    {
        
        $message = 'Please enter all required fields';
        $params = array("title" => "Akotosghboutique ContactUs", "message" => $message);
        render("contactus.php",  $params );
        exit();
    }
    elseif (empty($_POST['contact_email']))
    {
        $message = 'Please enter all required fields';
        $params = array("title" => "Akotosghboutique ContactUs", "message" => $message);
        render("contactus.php",  $params );
        exit();
    }
    elseif (empty($_POST['contact_message']))
    {
        $message = 'Please enter all required fields';
        $params = array("title" => "Akotosghboutique ContactUs", "message" => $message);
        render("contactus.php",  $params );
        exit();
    }
    
    $name = $_POST['contact_name'];
    $email = $_POST['contact_email'];
    if(!empty($_POST['contact_phone']))
        {$phone = $_POST['contact_phone'];}
        else
        {$phone = "none";}
    $message = $_POST['contact_message'];
    $to = 'contact@akotosghboutique.com'; 
    $subject = 'Contact Form';
    $human = '4';
    
    // message lines should not exceed 70 characters (PHP rule), so wrap it
    //$message = wordwrap($message, 70);
    
    $body = "From: $name\n E-Mail: $email\n Phone: $phone \n Message:\n $message";
    if ($human == '4') {				 
        if (mail ($to, $subject, $body)) { 
	    
            $message = 'Your message has been sent!';
            $params = array("title" => "msgsent", "message" => $message);
	    render("apology.php",  $params );
	    exit();
	} else { 
           $message = 'Something went wrong, go back and try again!!';
            $params = array("title" => "msgerror", "message" => $message);
	    render("apology.php",  $params );
	    exit(); 
	} 
    } else if ($human != '4') {
	echo '<p>You answered the anti-spam question incorrectly!</p>';
    }

    }
    else
    {    
        $param = array("title" => "Akotosghboutique ContactUs");
        // render form
        render("contactus.php", $param );
    }
?>