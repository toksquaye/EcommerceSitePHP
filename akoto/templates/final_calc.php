<div class="display_page">
    <div class="prod_list_row">
        <h1 class="prod_list_heading">FINAL CHECKOUT</h1>

<?php
    require_once("../includes/config.php");

    $form_data = $_SESSION['your_form'];
    //calculate total, tax, shipping
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
			    $message = "Internal error at final_calc:31. Please try again.";
			    $params = array("title" => "error", "message" => $message);
			    render("apology.php",  $params );
			    exit();
                            
                        }
                    
                    echo '<tr>';
                    echo '<td><div id="checkout_img"><img src="img/'.$results[0]["image"].'" alt="'. $results[0]["name"] .'" /></div></td>';
		    echo '<td>'.$results[0]["name"].' (Product No.'.$product_id.')</td> ';
                    echo '<td>'.$cart_itm["qty"].'</td>';
                    
		    $subtotal = ($results[0]["price"]*$cart_itm["qty"]);
		    $total = ($total + $subtotal);
                    $tprice = number_format($subtotal,2,'.',',');
                    echo '<td>'.currency.$tprice;
		    echo '<input type="hidden" name="item_name['.$cart_items.']" value="'. $results[0]["name"].'" />';
		    echo '<input type="hidden" name="item_code['.$cart_items.']" value="'.$product_id.'" />';
		    echo '<input type="hidden" name="item_desc['.$cart_items.']" value="'.$results[0]["description"].'" />';
		    echo '<input type="hidden" name="item_qty['.$cart_items.']" value="'.$cart_itm["qty"].'"/> </td>';
		    $cart_items ++;
                    echo '</tr>';
                    echo "\n\r\n\r";
			
                }
                //echo '</ul>';
                //echo '<tr>';
                //echo '<td></td>';
                //echo '<td></td>';
                //echo '<td></td>';
                //echo '<td><strong>Total : '.currency.number_format($total,2,'.',',').'</strong><br><div id="cartnote">Excluding tax and shipping</div></td>';
                //echo '</tr>';
                echo '</table>';
		echo "\n\r\n\r";
                
		//calculate NY tax by default.
		$mysqli = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);
                $mysqli->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
                $tax_stmt = $mysqli->prepare('SELECT Tax FROM sales_tax WHERE State = :state LIMIT 1');
                
                $state 	= filter_var($form_data["shipto_state"], FILTER_SANITIZE_STRING); 
                $tax_stmt->bindParam(':state', $state, PDO::PARAM_STR);
                $tax_stmt->execute();
                $tax_results = $tax_stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if(!$tax_results)
		{
                    error_log("warning: unable to retrieve state tax rate. default to 8.47");
		    $tax_rate = 8.47/100;
		}
                else
                {
                    $tax_rate = $tax_results[0]["Tax"] / 100;
                }
		$tax = $total * $tax_rate;
                $_SESSION["total"] = $total;
                $_SESSION["tax"] = number_format($tax,2,'.','');
                
                //extract shipping method
                $shipping_cost = number_format($form_data["ship_method"],2,'.','');;
                $_SESSION["shipping_cost"] = $shipping_cost;
                
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
                            echo '<td>'.currency.number_format($tax,2,'.',','). '</td>'; 
                        echo '</tr>';
                
                        echo '<tr>';
                            echo '<th>SHIPPING</th>';
                            echo '<td>'.currency.number_format($shipping_cost,2,'.',','). '</td>'; 
                        echo '</tr>';
                
                $order_total= $total + $tax + $shipping_cost ;
                        echo '<tr>';
                            echo '<th>ORDER TOTAL</th>';
                            echo '<td><b>'.currency.number_format($order_total,2,'.',','). '</b></td>'; 
                        echo '</tr>';
                    echo '</table>';
                echo '</div>';
                
                
                echo '<div id="final_place_order">';
		echo '<input type="submit" class="add_to_cart" value="Place Order" />';
		echo '</div>';
		echo '</form>';
            }
            else{
		echo '<h1>Your Cart is empty</h1> <br>';
	}

?>
    </div>
</div>