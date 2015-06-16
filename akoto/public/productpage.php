<?php
    require("../includes/config.php");

    if(isset($_GET["pid"]))
    {
        $productid = $_GET["pid"];
        $mysqli = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);
        // ensure that PDO::prepare returns false when passed invalid SQL
        $mysqli->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
        /* check connection */
        /*if ($mysqli->connect_errno) {
            printf("Connect failed: %s\n", $mysqli->connect_error);
            exit();
        }*/

        $stmt = $mysqli->prepare('SELECT * FROM products WHERE productId = :productid');
        $stmt->bindParam(':productid', $productid, PDO::PARAM_STR);
        $stmt->execute();
        $product = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$product)
            {
                $message = "Error retrieving the product. Please try again.";
                $params = array("title" => "error", "message" => $message);
		render("apology.php",  $params );
		exit();
            }
        
        $params = array("title" => $product[0]['name'],"product" => $product);
        render("productpage.php", $params);
    }
    
?>
