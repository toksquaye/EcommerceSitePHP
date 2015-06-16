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
                echo '<form method="post" action="process_paypal.php">';
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
                    if ($results === false)
                        {
                            echo "error";
                            exit(); //**************do better error checking
                        }
                    
                    echo '<tr>';
                    echo '<td><div id="checkout_img"><img src="img/'.$results[0]["image"].'" alt="'. $results[0]["name"] .'" /></div></td>';
		    echo '<td>'.$results[0]["name"].' (Product No.'.$product_id.')</td> ';
                    echo '<td>'.$cart_itm["qty"].'</td>';
                    
		    $subtotal = ($results[0]["price"]*$cart_itm["qty"]);
		    $total = ($total + $subtotal);
                    $tprice = number_format($subtotal,2,'.',',');
                    echo '<td>'.currency.$tprice.'<span class="remove-itm"><a href="cart_update.php?removep='.$cart_itm["code"].'&amp;return_url='.$current_url.'">&times;</a></span>';
		    echo '<input type="hidden" name="item_name['.$cart_items.']" value="'. $results[0]["name"].'" />';
		    echo '<input type="hidden" name="item_code['.$cart_items.']" value="'.$product_id.'" />';
		    echo '<input type="hidden" name="item_desc['.$cart_items.']" value="'.$results[0]["description"].'" />';
		    echo '<input type="hidden" name="item_qty['.$cart_items.']" value="'.$cart_itm["qty"].'"/> </td>';
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
                    echo '<p class = "form_entry"> <label>State:</label> <select name="shipto_state" id="shipto_state_id"> <option value="">Select State<option></select></p>';
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
                        echo '<p class = "form_entry"> <label>State:</label> <select name="shipto_state" id="shipto_state_id"> <option value="' .$form_data["shipto_state"]. '" selected = "Selected">'. $form_data["shipto_state"].'<option></select></p>'. "\n\r";
                    else
                        echo '<p class = "form_entry"> <label>State:</label> <select name="shipto_state" id="shipto_state_id"> <option value="">Select State<option></select></p>'. "\n\r";

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
                
                
                echo '<div id="get_billing_info">';
                    echo '<h2> Billing Address </h2>';
                    echo '<p><input type="checkbox" id="ship_same_bill" value="same" />Billing Information is the same as Shipping Address</p>';
                    echo '<div id="get_billing_add">';
                    if(!$prev_data)
                    {
                        echo '<p class = "form_entry"><label>First Name:</label> <input type="text" autocomplete="off" name="billto_fname"/></p>'. "\n\r";
                        echo '<p class = "form_entry"><label>Last Name:</label> <input type="text" autocomplete="off" name="billto_lname"/></p>'. "\n\r";
                        echo '<p class = "form_entry"><label>Street Address:</label> <input type="text" autocomplete="off" name="billto_streetadd"/></p>'. "\n\r";
                        echo '<p class = "form_entry"><label>Street Address2:</label> <input type="text" autocomplete="off" name="billto_streetadd2"/></p>'. "\n\r";
                        echo '<p class = "form_entry"><label>City:</label> <input type="text" autocomplete="off" name="billto_city"/></p>'. "\n\r";
                        echo '<p class = "form_entry"> <label>State:</label> <select name="billto_state" id="billto_state_id"> <option value="">Select State<option></select></p>'. "\n\r";
                        //echo '<p class = "form_entry"><label>State:</label> <input type="text" autocomplete="off" name="billto_state"/></p>'. "\n\r";
                        echo '<p class = "form_entry"><label>Zip Code:</label> <input type="text" autocomplete="off" name="billto_zip"/></p>'. "\n\r";
                    }
                    else
                    {
                        if(isset($form_data["billto_fname"]))
                            echo '<p class = "form_entry"><label>First Name:</label> <input type="text" autocomplete="off" name="billto_fname" value="' .$form_data["billto_fname"] .'"</p>'. "\n\r";
                        else
                            echo '<p class = "form_entry"><label>First Name:</label> <input type="text" autocomplete="off" name="billto_fname"/></p>'. "\n\r";
                        
                        if(isset($form_data["billto_lname"]))
                            echo '<p class = "form_entry"><label>Last Name:</label> <input type="text" autocomplete="off" name="billto_lname" value="' .$form_data["billto_lname"] .'"</p>'. "\n\r";
                        else
                            echo '<p class = "form_entry"><label>Last Name:</label> <input type="text" autocomplete="off" name="billto_lname"/></p>'. "\n\r";
                            
                        if(isset($form_data["billto_streetadd"]))
                            echo '<p class = "form_entry"><label>Street Address:</label> <input type="text" autocomplete="off" name="billto_streetadd" value="' .$form_data["billto_streetadd"] .'"</p>'. "\n\r";
                        else
                            echo '<p class = "form_entry"><label>Street Address:</label> <input type="text" autocomplete="off" name="billto_streetadd"/></p>'. "\n\r";
                        
                        if(isset($form_data["billto_streetadd2"]))
                            echo '<p class = "form_entry"><label>Street Address2:</label> <input type="text" autocomplete="off" name="billto_streetadd2" value="' .$form_data["billto_streetadd2"] .'"</p>'. "\n\r";
                        else
                            echo '<p class = "form_entry"><label>Street Address2:</label> <input type="text" autocomplete="off" name="billto_streetadd2"/></p>'. "\n\r";
                        
                        if(isset($form_data["billto_city"]))
                            echo '<p class = "form_entry"><label>City:</label> <input type="text" autocomplete="off" name="billto_city" value="' .$form_data["billto_city"] .'"</p>'. "\n\r";
                        else
                            echo '<p class = "form_entry"><label>City:</label> <input type="text" autocomplete="off" name="billto_city"/></p>'. "\n\r";
                        
                        if (isset($form_data["billto_state"]))
                        echo '<p class = "form_entry"> <label>State:</label> <select name="billto_state" id="billto_state_id"> <option value="' .$form_data["billto_state"]. '" selected = "Selected">'. $form_data["billto_state"].'<option></select></p>'. "\n\r";
                    else
                        echo '<p class = "form_entry"> <label>State:</label> <select name="billto_state" id="billto_state_id"> <option value="">Select State<option></select></p>'. "\n\r";
                        
                        if(isset($form_data["billto_zip"]))
                            echo '<p class = "form_entry"><label>Zip Code:</label> <input type="text" autocomplete="off" name="billto_zip" value="' .$form_data["billto_zip"] .'"</p>'. "\n\r";
                        else
                            echo '<p class = "form_entry"><label>Zip Code:</label> <input type="text" autocomplete="off" name="billto_zip"/></p>'. "\n\r";
                        
                    }
                    
                    echo '</div>';
                echo '</div>';
                
                
                echo '<div id = "shipping">';
                    echo '<h2> Select Shipping Method </h2>';
                    echo '<select name="ship_method" id="ship_method_id"> <option value="5">Ground Delivery: $5.00</option></select><br>';
                echo '</div>';
                
                echo '<div id="payment_table_container">';
                    echo '<h2> Payment Method </h2>';
                    echo '<div id = "payment">';
                        echo '<input type="radio" name="payment" value="paypal" checked />';
                        echo '<img src="https://www.paypalobjects.com/webstatic/en_MY/mktg/logos/AM_SbyPP_mc_vs_dc_ae.jpg" alt="PayPal Acceptance Mark"  id="payment_icons" />';
                    echo '</div>';
                echo '</div>';
                
                echo '<div id = "order_table_div">';
                    echo '<table id="order_table">';
                        echo '<tr>';
                            echo '<th>SUBTOTAL</th>';
                            echo '<td>'.currency.number_format($total,2,'.',','). '</td>'; 
                        echo '</tr>';
		
                        echo '<tr>';
                            echo '<th>TAX</th>';
                            echo '<td>$15.15</td>'; 
                        echo '</tr>';
                
                        echo '<tr>';
                            echo '<th>SHIPPING</th>';
                            echo '<td>$5.00</td>'; 
                        echo '</tr>';
                
                $order_total= $total + 15.15 + 5 ;
                        echo '<tr>';
                            echo '<th>ORDER TOTAL</th>';
                            echo '<td><b>'.currency.number_format($order_total,2,'.',','). '</b></td>'; 
                        echo '</tr>';
                    echo '</table>';
                echo '</div>';
                
                echo '<div id="place_order">';
		echo '<input type="submit" class="add_to_cart" value="Place Order" />';
		echo '</div>';
		echo '</form>';
		
    }else{
		echo '<h1>Your Cart is empty</h1> <br>';
	}
	
    ?>
    </div>
</div>