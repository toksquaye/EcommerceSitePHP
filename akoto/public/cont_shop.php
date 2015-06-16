<?php
    require("../includes/config.php");
    
    if (isset($_SESSION['return_url']) && !empty($_SESSION['return_url']))
    {
        $cont_shop = base64_decode($_SESSION["return_url"]); //return url
        header('Location:'.$cont_shop);
        exit();
    }
    else
    {
        redirect("index.php");
    }

?>