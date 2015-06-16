<?php

     require_once("../includes/config.php");
     $total_qty = 0;
     //empty cart by distroying current session
     if(isset($_GET["emptycart"]) && $_GET["emptycart"]==1)
     {     
          session_destroy();
     }

     //add item in shopping cart
     if(isset($_GET["type"]) && ( ($_GET["type"]=='add') || ($_GET["type"] == 'update') ))
     {
          $product_id   = filter_var($_GET["product_id"], FILTER_SANITIZE_STRING); //product code
          if(is_numeric($_GET["product_qty"]))
               $product_qty    = filter_var($_GET["product_qty"], FILTER_SANITIZE_NUMBER_INT); //product code
          else
               $product_qty = 1;
      
          //limit quantity for single product
          if($product_qty > 10)
          {
               $product_qty = 9; 
          }

          //MySqli query - get details of item from db using product code
          $mysqli = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

          // ensure that PDO::prepare returns false when passed invalid SQL
          $mysqli->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
          $stmt = $mysqli->prepare('SELECT name,price,image FROM products WHERE productId = :productid LIMIT 1');
          $stmt->bindParam(':productid', $product_id, PDO::PARAM_STR);
          $stmt->execute();
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          if (!$results)
          {
               error_log("error retrieving from products db in cart_update. productid:".$productid);
               exit();
          }
          
          if ($results)
          {   //we have the product info 
               //prepare array for the session variable
               $new_product = array(array('name'=>$results[0]['name'], 'code'=>$product_id, 'img' => $results[0]['image'],'qty'=>$product_qty, 'price'=>$results[0]['price']));
         
               if(isset($_SESSION["products"])) //if we have the session
               {
                    $found = false; //set found item to false
             
                    foreach ($_SESSION["products"] as $cart_itm) //loop through session array
                    {
                         if($cart_itm["code"] == $product_id)
                         { //the item exist in array
                              if($_GET["type"]=='add')
                                   $new_qty = $cart_itm["qty"] + $product_qty;
                              elseif ($_GET["type"]=='update')
                                   $new_qty = $product_qty;
                              if($new_qty > 9)
                                   $new_qty = 9;
                              elseif($new_qty < 0)
                                   $new_qty = 1;
                        
                              if($new_qty > 0)
                                   $product[] = array('name'=>$cart_itm["name"], 'code'=>$cart_itm["code"], 'img' => $cart_itm["img"], 'qty'=>$new_qty, 'price'=>$cart_itm["price"]);
                              $found = true;
                         }
                         else
                         {
                              //item doesn't exist in the list, just retrive old info and prepare array for session var
                              $product[] = array('name'=>$cart_itm["name"], 'code'=>$cart_itm["code"], 'img' => $cart_itm["img"],'qty'=>$cart_itm["qty"], 'price'=>$cart_itm["price"]);
                         }
                    }
             
                    if($found == false) //we didn't find item in array
                    {
                         //add new user item in array
                         $_SESSION["products"] = array_merge($product, $new_product);
                    }
                    else
                    {
                         //found user item in array list, and increased the quantity
                         $_SESSION["products"] = $product;
                    }
             
               }
               else
               {
                    //create a new session var if does not exist
                    $_SESSION["products"] = $new_product;
               }
         
          }
     
          if(isset($_SESSION["products"]))
          foreach ($_SESSION["products"] as $cart_itm) //loop through session array
               {
                    $total_qty = $total_qty + $cart_itm["qty"];
               }
          $_SESSION["cart_qty"] = $total_qty;     
          $params = array("title" => "shopping cart");
          render("shopping_cart.php", $params);
     }

     //remove item from shopping cart
     if(isset($_GET["removep"]) &&  isset($_SESSION["products"]))
     {
          $product_code   = $_GET["removep"]; //get the product code to remove
          $product = NULL;
     
          foreach ($_SESSION["products"] as $cart_itm) //loop through session array var
          {
               if($cart_itm["code"]!=$product_code)
               { //item does,t exist in the list
                    $product[] = array('name'=>$cart_itm["name"], 'code'=>$cart_itm["code"],'img' => $cart_itm["img"], 'qty'=>$cart_itm["qty"], 'price'=>$cart_itm["price"]);          
               }
               $_SESSION["products"] = $product; // create a new product list for cart
          }
     
          if(isset($_SESSION["products"]))
               foreach ($_SESSION["products"] as $cart_itm) //loop through session array
               {
                    $total_qty = $total_qty + $cart_itm["qty"];
               }
          $_SESSION["cart_qty"] = $total_qty;
          $params = array("title" => "shopping cart");
          render("shopping_cart.php", $params);
     }