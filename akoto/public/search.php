<?php
    require_once("../includes/config.php");

    if( isset( $_GET['product_search'] ) && $_GET['product_search'] != '' )
    {
        $search = filter_var($_GET['product_search'], FILTER_SANITIZE_STRING);
        //echo $search;
	$mysqli = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);
        $mysqli->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $searchQ = str_replace(" ","%",$search); //replace space with %
        $searchQ = str_replace("s","%",$searchQ); //replace s with %
        $query = 'SELECT * FROM products WHERE name LIKE ? OR criteria LIKE ?';
        $params = array("%$searchQ%", "%$searchQ%");
        $stmt = $mysqli->prepare($query);
        $stmt->execute($params);
        $productRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($productRows === false)
            {
		echo "error";
                exit(); //**************do better error checking
            }
            
        $params = array("title" => "Search Results", "productRows" => $productRows, "search" => $search);
        render("search.php", $params);
    }
    else
    {
        redirect("index.php");
    }
?>