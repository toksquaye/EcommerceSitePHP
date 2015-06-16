<?php
require_once("../includes/config.php");
    
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $phone = preg_replace('/[^0-9]/', '', $_POST['shipto_phone']);
    //echo print_r($_POST);
    //save form values
    //if (!empty($_POST))
    //{
        foreach($_POST as $key => $value) {
            $_SESSION['your_form'][$key] = $value;
        }
    //}

    //if($_POST) //Post Data received from product list page.
    //check if all mandatory fields are filled in. if not, go back to checkout page
    if(empty($_POST["shipto_fname"]))
    {
        
        $message = 'Please enter your first name';
        $params = array("title" => "Checkout", "message" => $message);
        render("checkout.php",  $params );
        exit();
    }
    elseif (empty($_POST['shipto_lname']))
    {
        $message = 'Please enter your last name';
        $params = array("title" => "Checkout", "message" => $message);
        render("checkout.php",  $params );
        exit();
    }
    elseif (empty($_POST['shipto_streetadd']))
    {
        $message = 'Please enter your street address';
        $params = array("title" => "Checkout", "message" => $message);
        render("checkout.php",  $params );
        exit();
    }
    elseif (empty($_POST['shipto_city']))
    {
        $message = 'Please enter your city';
        $params = array("title" => "Checkout", "message" => $message);
        render("checkout.php",  $params );
        exit();
    }
    elseif (empty($_POST['shipto_state']))
    {
        $message = 'Please enter your state';
        $params = array("title" => "Checkout", "message" => $message);
        render("checkout.php",  $params );
        exit();
    }
    elseif (empty($_POST['shipto_zip']))
    {
        $message = 'Please enter your zip code';
        $params = array("title" => "Checkout", "message" => $message);
        render("checkout.php",  $params );
        exit();
    }
    elseif (empty($_POST['shipto_phone']))
    {
        $message = 'Please enter your phone number';
        $params = array("title" => "Checkout", "message" => $message);
        render("checkout.php",  $params );
        exit();
    }
    elseif (strlen($phone) !== 10)
    {
        $message = 'Please enter a valid phone number : (###)###-####';
        $params = array("title" => "Checkout", "message" => $message);
        render("checkout.php",  $params );
        exit();
    }
    elseif (empty($_POST['shipto_email']))
    {
        $message = 'Please enter your email address';
        $params = array("title" => "Checkout", "message" => $message);
        render("checkout.php",  $params );
        exit();
    }
    elseif (!filter_var($_POST['shipto_email'],FILTER_VALIDATE_EMAIL))
    {
        $message = 'Please enter a valid email address';
        $params = array("title" => "Checkout", "message" => $message);
        render("checkout.php",  $params );
        exit();
    }
    elseif(!filter_var($_POST['ship_method'], FILTER_VALIDATE_FLOAT))
    {
        $message = 'Please select valid shipping method';
        $params = array("title" => "Checkout", "message" => $message);
        render("checkout.php",  $params );
        exit();
    }
    else
    {
        $params = array("title" => "Final Checkout");
        render("final_calc.php",$params);
    }
}
?>