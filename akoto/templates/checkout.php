<div class="display_page">
    <div class="prod_list_row">
        <h1 class="prod_list_heading">CHECKOUT</h1>
 	
        <?php
            require_once("../includes/config.php");
            $current_url = base64_encode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            
            $prev_data = false;  //no previous data on form
            if (isset($_SESSION['your_form']) && !empty($_SESSION['your_form'])) {
            $form_data = $_SESSION['your_form'];
            unset($_SESSION['your_form']);
            $prev_data = true;  //there was previous data in form
            }

            if(isset($_SESSION["products"]))
            {
                $total = 0;
                echo '<h2 id="os_header"> Order Summary </h2>';
                echo '<form method="post" action="process_form.php">';
                echo '<table id="checkout_table">';
                echo '<tr>';
                echo '<th></th>';
                echo '<th>PRODUCT</th>';
                echo '<th>QUANTITY</th>'; 
                echo '<th>PRICE</th>';
                echo '</tr>';
		echo "\n\r";
		
		$cart_items = 0;
		foreach ($_SESSION["products"] as $cart_itm)
                {
                    $product_id = $cart_itm["code"];
                    $mysqli = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);
                    $mysqli->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
                    $stmt = $mysqli->prepare('SELECT name,price,description,image FROM products WHERE productId = :productid LIMIT 1');
                    $stmt->bindParam(':productid', $product_id, PDO::PARAM_STR);
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (!$results)
                        {
                            error_log("error:session products item not found in database");
			    //$message = "Internal error at checkout:43. Please try again.";
			    //$params = array("title" => "error", "message" => $message);
			    //render("apology.php",  $params );
			    //exit();
                            
                        }
                    
                    echo '<tr>';
                    echo '<td><div id="checkout_img"><img src="img/'.$results[0]["image"].'" alt="'. $results[0]["name"] .'" /></div></td>';
		    echo '<td>'.$results[0]["name"].' (Product No.'.$product_id.')</td> ';
                    echo '<td>'.$cart_itm["qty"].'</td>';
                    
		    $subtotal = ($results[0]["price"]*$cart_itm["qty"]);
		    $total = ($total + $subtotal);
                    $tprice = number_format($subtotal,2,'.',',');
                    echo '<td>'.currency.$tprice.'<span class="remove-itm"><a href="cart_update.php?removep='.$cart_itm["code"].'&amp;return_url='.$current_url.'">&times;</a></span>';
		    echo '</td>';
		    $cart_items ++;
                    echo '</tr>';
                    echo "\n\r\n\r";
			
                }
                //echo '</ul>';
                echo '<tr>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td><strong>Total : '.currency.number_format($total,2,'.',',').'</strong><br><div id="cartnote">Excluding tax and shipping</div></td>';
                echo '</tr>';
                echo '</table>';
		echo "\n\r\n\r";
                
		
		
		
		
		
                //echo any error msgs
                if(isset($message))
                   echo '<p id="checkout_msg">'. $message. '</p>';
                //get shipping address
                echo '<div id="get_shipping_info">';
                    echo '<h2> Shipping Address </h2>';
                if (!$prev_data)  //no previous data on form
                {
                    echo '<p class = "form_entry"> <label>First Name:</label> <input type="text" name="shipto_fname" autocomplete="off" maxlength="30"/></p>' . "\n\r";
                    echo '<p class = "form_entry"> <label>Last Name:</label> <input type="text" name="shipto_lname" autocomplete="off" maxlength="30" /></p>'. "\n\r";
                    echo '<p class = "form_entry"> <label>Street Address:</label> <input type="text" autocomplete="off" name="shipto_streetadd"/></p>'. "\n\r";
                    echo '<p class = "form_entry"> <label>Street Address2:</label> <input type="text" autocomplete="off" name="shipto_streetadd2"/></p>'. "\n\r";
                    echo '<p class = "form_entry"> <label>City:</label> <input type="text" autocomplete="off" name="shipto_city"/></p>'. "\n\r"; 
		    echo '<p class = "form_entry"> <label>State:</label> <select name="shipto_state" id="shipto_state_id"> <option value="">Select State</option></select></p>';

		    // '<p class = "form_entry"> <label>State:</label> <input type="text" name="shipto_state" autocomplete="off"/></p>'. "\n\r";
                    echo '<p class = "form_entry"> <label>Zip Code:</label> <input type="text" name="shipto_zip" autocomplete="off" /></p>'. "\n\r";
                    echo '<p class = "form_entry"> <label>Phone Number:</label> <input type="text" name="shipto_phone" autocomplete="off"/></p>'. "\n\r";
                    echo '<p class = "form_entry"> <label>Email Address:</label> <input type="text" name="shipto_email" autocomplete="off"/></p>'. "\n\r";
                }
                else    //there was data on form that needs to be repopulated
                {
                    if (isset($form_data["shipto_fname"]))
                        echo '<p class = "form_entry"> <label>First Name:</label> <input type="text" name="shipto_fname" autocomplete="off" maxlength="30" value="' . $form_data["shipto_fname"] .'"</p>'. "\n\r";
                    else
                        echo '<p class = "form_entry"> <label>First Name:</label> <input type="text" name="shipto_fname" autocomplete="off" maxlength="30"/></p>'. "\n\r";
                        
                    if (isset($form_data["shipto_lname"]))
                        echo '<p class = "form_entry"> <label>Last Name:</label> <input type="text" name="shipto_lname" autocomplete="off" maxlength="30" value="' . $form_data["shipto_lname"] .'"</p>'. "\n\r";
                    else
                        echo '<p class = "form_entry"> <label>Last Name:</label> <input type="text" name="shipto_lname" autocomplete="off" maxlength="30"/></p>'. "\n\r";
                        
                    if (isset($form_data["shipto_streetadd"]))
                        echo '<p class = "form_entry"> <label>Street Address:</label> <input type="text" name="shipto_streetadd" autocomplete="off"  maxlength="30" value="' . $form_data["shipto_streetadd"] .'"</p>'. "\n\r";
                    else
                        echo '<p class = "form_entry"> <label>Street Address:</label> <input type="text" name="shipto_streetadd" autocomplete="off" maxlength="30"/></p>'. "\n\r";
                        
                    if (isset($form_data["shipto_streetadd2"]))
                        echo '<p class = "form_entry"> <label>Street Address2:</label> <input type="text" name="shipto_streetadd2" autocomplete="off" maxlength="30" value="' . $form_data["shipto_streetadd2"] .'"</p>'. "\n\r";
                    else
                        echo '<p class = "form_entry"> <label>Street Address2:</label> <input type="text" name="shipto_streetadd2" autocomplete="off" maxlength="30"/></p>'. "\n\r";
                        
                    if (isset($form_data["shipto_city"]))
                        echo '<p class = "form_entry"> <label>City:</label> <input type="text" name="shipto_city" maxlength="30" autocomplete="off" value="' . $form_data["shipto_city"] .'"</p>'. "\n\r";
                    else
                        echo '<p class = "form_entry"> <label>City:</label> <input type="text" name="shipto_lname" autocomplete="off" maxlength="30"/></p>'. "\n\r";
                        
                    if (isset($form_data["shipto_state"]))
                        echo '<p class = "form_entry"> <label>State:</label> <select name="shipto_state" id="shipto_state_id"> <option value="' .$form_data["shipto_state"]. '" selected = "Selected">'. $form_data["shipto_state"].'</option></select></p>'. "\n\r";
                    else
                        echo '<p class = "form_entry"> <label>State:</label> <select name="shipto_state" id="shipto_state_id"> <option value="">Select State</option>label = "Select" </select></p>'. "\n\r";

                    if (isset($form_data["shipto_zip"]))
                        echo '<p class = "form_entry"> <label>Zip:</label> <input type="text" name="shipto_zip" autocomplete="off" maxlength="30" value="' . $form_data["shipto_zip"] .'"</p>'. "\n\r";
                    else
                        echo '<p class = "form_entry"> <label>Zip:</label> <input type="text" name="shipto_zip" autocomplete="off" maxlength="30"/></p>'. "\n\r";
                        
                    if (isset($form_data["shipto_phone"]))
                        echo '<p class = "form_entry"> <label>Phone:</label> <input type="text" name="shipto_phone" autocomplete="off" maxlength="30" value="' . $form_data["shipto_phone"] .'"</p>'. "\n\r";
                    else
                        echo '<p class = "form_entry"> <label>Phone:</label> <input type="text" name="shipto_phone" autocomplete="off" maxlength="30"/></p>'. "\n\r";

                    if (isset($form_data["shipto_email"]))
                        echo '<p class = "form_entry"> <label>Email:</label> <input type="text" name="shipto_email" autocomplete="off" maxlength="30" value="' . $form_data["shipto_email"] .'"</p>'. "\n\r";
                    else
                        echo '<p class = "form_entry"> <label>Email:</label> <input type="text" name="shipto_email" autocomplete="off" maxlength="30"/></p>'. "\n\r";

                }
                echo '</div>';
                
                
                
                echo '<div id = "shipping">';
                    echo '<h2> Select Shipping Method </h2>';
                    echo '<select name="ship_method" id="ship_method_id"> <option value="5">Ground Delivery: $5.00</option></select><br>';
		    
		    echo '<div id="place_order">';
		echo '<input type="submit" class="add_to_cart" value="Next" />';
		echo '</div>';
		    
                echo '</div>';
                
                
                
		echo '</form>';
		
    }else{
		echo '<h1>Your Cart is empty</h1> <br>';
	}
	
    ?>
    </div>
</div>