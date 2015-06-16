<div class="display_page">
    <div class="prod_list_row">
        <h1 class="prod_list_heading" id="cart_page">CART</h1>
        <div id="cart_table_cont" >

<?php
if(isset($_SESSION["products"]))
{
    echo '<table>';
    echo '<tr>';
    echo '<th><span id="carthp">PRODUCT</span></th>';
    echo '<th></th>';
    echo '<th>PRICE</th>'; 
    echo '<th>QUANTITY</th>';
    echo '<th>TOTAL</th>';
    echo '<th></th>';
    echo '</tr>';

     $total = 0; $t_qty=0;
     foreach ($_SESSION["products"] as $cart_itm)
     {
         echo '<tr>';
         print(" <td> <div id='cart_img'> <img src='img/".$cart_itm['img']. "' alt='".$cart_itm['name'] ."' id='" . $cart_itm['code']. "' class = 'gopp'/> </div> </td>");
         //echo '<span class="remove-itm"><a href="cart_update.php?removep='.$cart_itm["code"].'>&times;</a></span>';
         print("<td><div id='cartpname'>".$cart_itm['name']."</div><div id='cartpnum'>PRODUCT NO. ".$cart_itm['code']."</div></td>");
         $subtotal = ($cart_itm["price"]*$cart_itm["qty"]);
         $tprice = number_format($subtotal,2,'.',',');
         print("<td>".currency.number_format($cart_itm['price'],2,'.',',')."</td>");
         
         print("<td><form id='cart_qty' method='GET' action='cart_update.php'><input type='text' size='1' name='product_qty' autocomplete = 'off' value= '".$cart_itm['qty'].               
               "'/><input type='hidden' name='product_id' value='".$cart_itm['code']."' />" .
               "<input type='hidden' name='type' value='update' />" .    
               "<button id='update_button'>UPDATE</button></form></td>");
         print("<td>" .currency.$tprice. "</td>");
         print("<td><a href='cart_update.php?removep=". $cart_itm['code']. "' id='remove_link'>REMOVE</a></td>");
         print("</tr>");
         $total = ($total + $subtotal);
         //$t_qty = $t_qty + $cart_itm["qty"];
         
     }
    //$_SESSION["cart_qty"] = $t_qty;
   
    print("<tr id='carttotal'>");
    print("<td></td>");
    print("<td></td>");
    print("<td></td>");
    print("<td></td>");
    print("<td></td>");
    print("<td><div id='carttotalprice'>".currency.number_format($total,2,'.',',')."</div><br/><div id='cartnote'>Excluding tax and shipping</div></td>");
    print("</tr>");
    echo '</table>';
}else{
     echo '<h1>Your Cart is empty</h1> <br/>';
}
?>
 

        </div>
        
            
        <button id="cart_continue" onclick="document.location.href='cont_shop.php'">CONTINUE SHOPPING</button>
        <button id="cart_checkout" onclick="document.location.href='checkout.php'">CHECKOUT</button>     
    </div>
 </div>
