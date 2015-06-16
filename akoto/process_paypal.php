<?php

        require_once("../includes/config.php");
        include_once("../includes/paypal.class.php");


        $paypalmode = ($PayPalMode=='sandbox') ? '.sandbox' : '';
        if(!isset($_GET["token"]) && !isset($_GET["PayerID"]))
        {
                $form_data = $_SESSION['your_form'];
                
                //Other important variables like tax, shipping cost. ****** need to calculate tax. get shipping costs from database
                $TotalTaxAmount 	= $_SESSION["tax"];  //Sum of tax for all items in this order. 
                $HandalingCost 		= 00.00;  //Handling cost for this order.
                $InsuranceCost 		= 0.00;  //shipping insurance cost for this order.
                $ShippinDiscount 	= -0.00; //Shipping discount for this order. Specify this as negative number.
                $ShippinCost 		= $_SESSION["shipping_cost"]; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.

                //we need 4 variables from product page Item Name, Item Price, Item Number and Item Quantity.
                $paypal_data ='';
                $ItemTotalPrice = 0;
                $dbitem=0;
                $itemnames = '';
                $itemprices = '';
                $itemcodes = '';
                $itemqtys = '';
                foreach($_POST['item_name'] as $key=>$itmname)
                {
                        $product_id 	= filter_var($_POST['item_code'][$key], FILTER_SANITIZE_STRING); 
                        $mysqli = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);
                        $mysqli->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
                        $stmt = $mysqli->prepare('SELECT name,price,quantity FROM products WHERE productId = :productid LIMIT 1');
                        $stmt->bindParam(':productid', $product_id, PDO::PARAM_STR);
                        $stmt->execute();
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if (!$results )
                        {
                                error_log("database fetch is empty : error getting data from products db: process_paypal 38");
                                $message = "Error retrieving order information. Please try again.";
                                $params = array("title" => "error", "message" => $message);
                                render("apology.php",  $params );
                                exit();
                        }	
		
                        //check that the item being purchased isnt sold out.
                        //if it is, remove from cart and redirect back to checkout page with msg notification
                        if($results[0]["quantity"] <= 0)
                        {
                                $product = NULL;
                                if(isset($_SESSION["products"]))
                                {
                                        foreach ($_SESSION["products"] as $cart_itm) //loop through session array var
                                        {
                                                if($cart_itm["code"]!=$product_id)
                                                { //item does,t exist in the list
                                                        $product[] = array('name'=>$cart_itm["name"], 'code'=>$cart_itm["code"],'img' => $cart_itm["img"], 'qty'=>$cart_itm["qty"], 'price'=>$cart_itm["price"]);            
                                                }
                                                $_SESSION["products"] = $product; // create a new product list for cart
                                        }
                                }
                                $total_qty = 0;
                                if(isset($_SESSION["products"]))
                                {
                                        foreach ($_SESSION["products"] as $cart_itm) //loop through session array to see # of items 
                                        {
                                                $total_qty = $total_qty + $cart_itm["qty"];
                                        }
                                }
	    
                                $_SESSION["cart_qty"] = $total_qty;
	    
                                if($total_qty > 0)
                                {
                                        $message = 'Sorry! The product '. $results[0]["name"]. ' just SOLD OUT. It has been removed from your cart.';
                                        $params = array("title" => "Checkout", "message" => $message);
                                        render("checkout.php",  $params );
                                        exit();
                                }
                                else
                                {
                                        $message = 'Sorry! The product '. $results[0]["name"]. ' just SOLD OUT. It has been removed from your cart.';
                                        $params = array("title" => "sold out", "message" => $message);
                                        render("apology.php",  $params );
                                        exit();
                                }
                        }
                        $paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($results[0]["name"]);
                        $paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($_POST['item_code'][$key]);
                        $paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode(number_format($results[0]["price"],2,'.','') );		
                        $paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='. urlencode($_POST['item_qty'][$key]);
        
                        // item price X quantity
                        $subtotal = ($results[0]["price"]*$_POST['item_qty'][$key]);
		
                        //total price
                        $ItemTotalPrice = $ItemTotalPrice + $subtotal;
		
                        //create items for session
                        $paypal_product['items'][] = array('itm_name'=>$results[0]["name"],
					'itm_price'=>$results[0]["price"],
					'itm_code'=>$_POST['item_code'][$key], 
					'itm_qty'=>$_POST['item_qty'][$key]);
                        $temp = number_format($results[0]["price"],2,'.','');
                        $itemnames .= $results[0]["name"] . '*';
                        
                        $itemprices .= $temp. '*';
                        $itemcodes .= $_POST['item_code'][$key] . '*';
                        $itemqtys .= $_POST['item_qty'][$key] . '*';
                        $dbitem++;
                        
                }
                //Grand total including all tax, insurance, shipping cost and discount
                $ItemTotalPrice = number_format($ItemTotalPrice,2,'.','');
                $GrandTotal = ($ItemTotalPrice + $TotalTaxAmount + $HandalingCost + $InsuranceCost + $ShippinCost + $ShippinDiscount);
                $GrandTotal = number_format($GrandTotal,2,'.','');
    
								
                $paypal_product['assets'] = array('tax_total'=>$TotalTaxAmount, 
					'handaling_cost'=>$HandalingCost, 
					'insurance_cost'=>$InsuranceCost,
					'shippin_discount'=>$ShippinDiscount,
					'shippin_cost'=>$ShippinCost,
					'grand_total'=>$GrandTotal);
	
                $transaction_buyerid=$form_data['shipto_fname'] . $form_data['shipto_lname'] . rand(2,3000);
                $status = 'pending';
                
                
                $mysqli = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);
		$mysqli->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
                $stmt = $mysqli->prepare("INSERT INTO pendingtransactions(id, number, item_names, item_prices,item_codes, item_qtys, tax, shipping, shipdiscount,insurance, handling, grandtotal, status)
					VALUES(:id,:number, :item_names,:item_prices,:item_codes,:item_qtys, :tax,:shipping,:shipdiscount,:insurance,:handling,:grandtotal, :status)"  );
					
                if (!$stmt)
                {
			error_log( "\nPDO::errorInfo(): unable to prepare statement pendingtransactions db. process_paypal 136\n"); /* administrator error */
			error_log(print_r($mysqli->errorInfo()));
		}

		if(!$stmt->execute(array(':id' => $transaction_buyerid,
                                         ':number' => $dbitem,
					 ':item_names' => $itemnames,
					 ':item_prices' => $itemprices,
					 ':item_codes' => $itemcodes,
					 ':item_qtys' => $itemqtys,
					 ':tax' => $TotalTaxAmount,
					 ':shipping' => $ShippinCost,
                                         ':shipdiscount' => $ShippinDiscount,
                                         ':insurance' => $InsuranceCost,
                                         ':handling' => $HandalingCost,
                                         'grandtotal' => $GrandTotal,
					 ':status' => $status)))
		{
                        error_log( "unable to insert into pendingtransactions db. process_paypal 154");
                        error_log(print_r($mysqli->errorInfo()));
                }

                //create session array for later use
                $_SESSION["paypal_products"] = $paypal_product;
                $space = ' ';
	
                //Parameters for SetExpressCheckout, which will be sent to PayPal
                $padata = 	'&METHOD=SetExpressCheckout'.
		'&RETURNURL='.urlencode($PayPalReturnURL ).
		'&CANCELURL='.urlencode($PayPalCancelURL).
		'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
		$paypal_data.				
		'&NOSHIPPING=0'. //set 1 to hide buyer's shipping address, in-case products that does not require shipping
		'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
		'&PAYMENTREQUEST_0_TAXAMT='.urlencode($TotalTaxAmount).
		'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($ShippinCost).
		'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandalingCost).
		'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($ShippinDiscount).
		'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($InsuranceCost).
		'&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
		'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
		'&ADDROVERRIDE=1'.
		'&PAYMENTREQUEST_0_SHIPTONAME='.urlencode($form_data['shipto_fname']). urlencode($space) . urlencode($form_data['shipto_lname']).
		'&PAYMENTREQUEST_0_SHIPTOSTREET='.urlencode($form_data['shipto_streetadd']).
		'&PAYMENTREQUEST_0_SHIPTOCITY='.urlencode($form_data['shipto_city']).
		'&PAYMENTREQUEST_0_SHIPTOSTATE='.urlencode($form_data['shipto_state']).
		'&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE=US'.
		'&PAYMENTREQUEST_0_SHIPTOZIP='.urlencode($form_data['shipto_zip']).
		'&PAYMENTREQUEST_0_EMAIL='.urlencode($form_data['shipto_email']).
		'&PAYMENTREQUEST_0_SHIPTOPHONENUM='.urlencode($form_data['shipto_phone']).
		'&LOCALECODE=US'. //PayPal pages to match the language on your website.
		//'&PAGESTYLE=Custom1'.
		//'&LOGOIMG=http://localhost/akoto/public/img/logo_paypal.png'. //site logo
                '&LOGOIMG=http://www.akotosghboutique.com/img/logo_paypal.png'. 
		'&CARTBORDERCOLOR=FFFFFF'. //border color of cart
		'&ALLOWNOTE=1'.
                '&SOLUTIONTYPE=SOLE'.
		'&PAYMENTREQUEST_0_INVNUM='.$transaction_buyerid; //identifier for pending transaction
                
                //We need to execute the "SetExpressCheckOut" method to obtain paypal token
                $paypal= new MyPayPal();
                $httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
		
                //Respond according to message we receive from Paypal
                if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
                {
                        //Redirect user to PayPal store with Token received.
                        $paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
                        header('Location: '.$paypalurl);
                        exit();
                }
                else
                {
                        //Show error message
                        error_log("Paypal connection error\n" . urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]));
                        $message = "Paypal connection error\n" . urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
                        $params = array("title" => "error", "message" => $message);
                        render("apology.php",  $params );
                        exit();
                }

        }

        //Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
        if(isset($_GET["token"]) && isset($_GET["PayerID"]))
        {
                //we will be using these two variables to execute the "DoExpressCheckoutPayment"
                //Note: we haven't received any payment yet.
	
                $token = $_GET["token"];
                $payer_id = $_GET["PayerID"];
                
                $gtdata = 	'&TOKEN='.urlencode($token);
                $paypal= new MyPayPal();
                $httpParsedResponseAr = $paypal->PPHttpPost('GetExpressCheckoutDetails', $gtdata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
                
                $trans = urldecode($httpParsedResponseAr["INVNUM"]);
                
                
                $trans 	= filter_var($trans, FILTER_SANITIZE_STRING); 
                $mysqli = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);
                $mysqli->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
                $stmt = $mysqli->prepare('SELECT * FROM pendingtransactions WHERE id = :id LIMIT 1');
                $stmt->bindParam(':id', $trans, PDO::PARAM_STR);
                $stmt->execute();
                $purchase = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (!$purchase)
                {
                        error_log("unable to retrieve order from pendingtransactions db:".$trans);
                        $message = "Unable to retrieve order information. Please try again.";
                        $params = array("title" => "error", "message" => $message);
                        render("apology.php",  $params );
                        exit();
                }	
                
                $paypal_data = '';
                $ItemTotalPrice = 0;

                $itm_qty = explode('*',$purchase[0]["item_qtys"]);
                $itm_price = explode('*',$purchase[0]["item_prices"]);
                $itm_name = explode('*',$purchase[0]["item_names"]);
                $itm_code = explode('*',$purchase[0]["item_codes"]);
                
                $order = 0;
                while($order < $purchase[0]["number"])
                {                        
                        $paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$order.'='. urlencode($itm_qty[$order]);
                        $paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$order.'='.urlencode($itm_price[$order]);
                        $paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$order.'='.urlencode($itm_name[$order]);
                        $paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$order.'='.urlencode($itm_code[$order]);
        
                        // item price X quantity
                        $subtotal = $itm_price[$order] * $itm_qty[$order];
                        
                        //total price
                        $ItemTotalPrice = ($ItemTotalPrice + $subtotal);
                        $order++;
                }

                $padata = 	'&TOKEN='.urlencode($token).
				'&PAYERID='.urlencode($payer_id).
				'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
				$paypal_data.
				'&PAYMENTREQUEST_0_ITEMAMT='.urlencode(number_format($ItemTotalPrice,2,'.','')).
				'&PAYMENTREQUEST_0_TAXAMT='.urlencode(number_format($purchase[0]['tax'],2,'.','')).
				'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($purchase[0]['shipping']).
				'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($purchase[0]['handling']).
				'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($purchase[0]['shipdiscount']).
				'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($purchase[0]['insurance']).
				'&PAYMENTREQUEST_0_AMT='.urlencode(number_format($purchase[0]['grandtotal'],2,'.','')).
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode);
				//'&PAYMENTREQUEST_0_PAGESTYLE=Custom1';
		
                //We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
                $paypal= new MyPayPal();
                $httpParsedResponseAr = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
	
                //Check if everything went ok..
                if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
                {

			echo '<h2>Thank You!</h2>';
			echo 'Your Transaction ID : '.urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
			if (isset($_SESSION["products"]))
                                session_destroy();//empty the current session, since products have been bought.
                                
                        $status = "complete";
                        $mysqli = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);
			$mysqli->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$sql = "UPDATE pendingtransactions SET status=? WHERE id=?" ;
			$stmt = $mysqli->prepare($sql);
			$stmt->execute(array($status,$trans));
			
			/*
			//Sometimes Payment are kept pending even when transaction is complete. 
			//hence we need to notify user about it and ask him manually approve the transiction
			*/
				
			if('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"])
			{
				echo '<div style="color:green">Payment Received! Your product will be shipped within the next 5 business days!</div>';
			}
			elseif('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"])
			{
				echo '<div style="color:red">Transaction Complete, but payment is still pending! '.
				'You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
			}

			// retrive transection details using GetExpressCheckoutDetails
			// GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
			$padata = 	'&TOKEN='.urlencode($token);
			$paypal= new MyPayPal();
			$httpParsedResponseAr = $paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
			{
				$buyerName = urldecode($httpParsedResponseAr["FIRSTNAME"]).' '.urldecode($httpParsedResponseAr["LASTNAME"]);
				$buyerEmail = urldecode($httpParsedResponseAr["EMAIL"]);
				$transId = urldecode($httpParsedResponseAr["PAYMENTREQUESTINFO_0_TRANSACTIONID"]);
				$totalprice = urldecode($httpParsedResponseAr["AMT"]);
				$buyerShipAdd = urldecode($httpParsedResponseAr["SHIPTOSTREET"]).' '.urldecode($httpParsedResponseAr["SHIPTOCITY"]).' '.
						urldecode($httpParsedResponseAr["SHIPTOSTATE"]).' '.urldecode($httpParsedResponseAr["SHIPTOZIP"]).' '.
						urldecode($httpParsedResponseAr["SHIPTOPHONENUM"]);
							
				//append all items purchased
				$itemnum=0;
				$field = "L_NAME".$itemnum;
				$ItemNames = '';
				while(isset($httpParsedResponseAr[$field]))
				{
					$ItemNames .= 'Item'. ++$itemnum . '-' .urldecode($httpParsedResponseAr[$field]).' ';
                                        $field = "L_NAME".$itemnum;
				}
					
				//append Product Numbers
				$itemnum=0;
				$field = "L_NUMBER".$itemnum;
				$ItemNumbers = '';
				while(isset($httpParsedResponseAr[$field]))
				{				     
					//Extract quantity of product from database and update inventory
                                        $sold_productid = filter_var(urldecode($httpParsedResponseAr[$field]), FILTER_SANITIZE_STRING); 
                                        $mysqli = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);
                                        $mysqli->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
                                        $stmt = $mysqli->prepare('SELECT quantity FROM products WHERE productId = :productid LIMIT 1');
					$stmt->bindParam(':productid', $sold_productid, PDO::PARAM_STR);
					$stmt->execute();
					$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
					    
					if(!$results )
					{
						//log error
                                                error_log("process_paypal:unable to update inventory".$sold_productid);
                                                $to = 'contact@akotosghboutique.com'; 
                                                $subject = 'Unable to update inventory!';
                                                $body = "Unable to update inventory on a sale: $sold_productid.\n";
                                                mail ($to, $subject, $body);
					}
					else
					{
						$current_quantity= $results[0]["quantity"];
						$sold_quantity = $httpParsedResponseAr["L_QTY".$itemnum];
						$new_quantity = $current_quantity - $sold_quantity;
						if ($new_quantity < 0)
						{
                                                        //flag "purchase while out of stock message"
                                                        //send email to site owner
                                                        $to = 'contact@akotosghboutique.com'; 
                                                        $subject = 'Sale made while product is out of stock!';
                                                        $body = "Product sold while out of stock: $sold_productid.\n";
                                                        mail ($to, $subject, $body);
						}
						
						$mysqli = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);
						$mysqli->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
						$sql = "UPDATE products SET quantity=? WHERE productId=?" ;
						$stmt = $mysqli->prepare($sql);
						$stmt->execute(array($new_quantity,$sold_productid));
						
					}
					    
					//prep values for transactions database
					$ItemNumbers .= 'Item'. ++$itemnum . '-' .urldecode($httpParsedResponseAr[$field]).' ';
					//$itemnum++;
					$field = "L_NUMBER".$itemnum;
				}

				//append product Quantities
				$itemnum=0;
				$field = "L_QTY".$itemnum;
				$ItemQtys = '';
				while(isset($httpParsedResponseAr[$field]))
				{
					$ItemQtys .= 'Item'. ++$itemnum . '-' .urldecode($httpParsedResponseAr[$field]).' ';
					//$itemnum++;
					$field = "L_QTY".$itemnum;
				}
					
				//append prices
				$itemnum=0;
				$field = "L_AMT".$itemnum;
				$ItemAmts = '';
				while(isset($httpParsedResponseAr[$field]))
				{
					$ItemAmts .= 'Item'. ++$itemnum . '-' .urldecode($httpParsedResponseAr[$field]).' ';
					//$itemnum++;
					$field = "L_AMT".$itemnum;
				}

				$mysqli = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);
				$mysqli->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
                                $stmt = $mysqli->prepare("INSERT INTO transactions(TransID, PayerName, PayerAddress,PayerEmail, ItemName, ItemQty,ItemPrice, ProductNo)
							VALUES(:TransID,:PayerName,:PayerAddress,:PayerEmail,:ItemName, :ItemQty,:ItemPrice,:ProductNo)"  );
					
                                if (!$stmt)
                                {
					error_log( "\nPDO::errorInfo(): prepare statement for transactions db failed\n"); /* administrator error */
					error_log(print_r($mysqli->errorInfo()));
				}

				if(!$stmt->execute(array(':TransID' => $transId,
							':PayerName' => $buyerName,
							':PayerAddress' => $buyerShipAdd,
							':PayerEmail' => $buyerEmail,
							':ItemName' => $ItemNames,
							':ItemQty' => $ItemQtys,
							':ItemPrice' => $ItemAmts,
							':ProductNo' => $ItemNumbers)))
				{
                                        error_log( "Insert into Transaction Query Failed");
                                        error_log(print_r($mysqli->errorInfo()));
                                }
					    
				echo"<br><b><a href='index.php'>Return to AkotosGHBoutique</a></b>";
					
			}
                        else
                        {
				error_log("Paypal response error after payment completed");
                                error_log(urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]));				
				error_log(print_r($httpParsedResponseAr));
                                echo "Paypal Response Error";
                                
                                exit();
				
			}
	
                }
                else
                {
                        error_log( urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]));
                        error_log(print_r($httpParsedResponseAr));
                        $message = "Sorry! Transaction error from PayPal. Please try again";
                        $params = array("title" => "error", "message" => $message);
                        render("apology.php",  $params );
                        exit();
                }
        }
?>
