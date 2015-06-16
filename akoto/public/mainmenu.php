<?php
    // configuration
    require("../includes/config.php");
    if(isset($_GET["pType"]))
    {
        $productType = $_GET["pType"];
    }
    else
    {
        error_log("warning: product type is not set in mainmenu.php - default to handbags");
        $productType = "handbag";
    }
    
        switch($productType)
    {
        case "handbag":
            $page_title = "African Purses | African Bags |  African Print Purses"; break;
        case "clutch":
            $page_title = "African Print Clutch Purse | African Bags"; break;
        case "homeacc":
            $page_title = "Ghana Made Paintings | African Home Accessories";break;
        case "specoff":
            $page_title = "African Purses | African Bags |  African Print Purses"; break;
        case "sale":
            $page_title = "African Purses | African Bags |  African Print Purses"; break;
        case "newarr":
            $page_title = "African Purses | African Bags |  African Print Purses"; break;
        default:
            $page_title = "African Purses | African Bags |  African Print Purses";
            $productType = "handbag";
            break;
    }   

        
    $mysqli = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);
    // ensure that PDO::prepare returns false when passed invalid SQL
    $mysqli->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
        
        
    $query = 'SELECT * FROM products WHERE productType LIKE ?';
    $params = array("%$productType%");
    $stmt = $mysqli->prepare($query);
    $stmt->execute($params);
 
    $productRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$productRows)
    {   //apologize
        error_log("warning: empty product from products db in mainmenu.php:".$productType);    
    }
        
    switch($productType)
    {
        case "handbag":
            $page_title = "African Purses | African Bags |  African Print Purses"; break;
        case "clutch":
            $page_title = "African Print Clutch Purse | African Bags"; break;
        case "homeacc":
            $page_title = "Ghana Made Paintings | African Home Accessories";break;
        case "specoff":
            $page_title = "African Purses | African Bags |  African Print Purses"; break;
        case "sale":
            $page_title = "African Purses | African Bags |  African Print Purses"; break;
        case "newarr":
            $page_title = "African Purses | African Bags |  African Print Purses"; break;
        default:
            $page_title = "African Purses | African Bags |  African Print Purses"; break;
    }   
    $params = array("title" => $page_title, "productRows" => $productRows, "menuOpt" => $productType);
    render("menu_page.php", $params);
    
?>

