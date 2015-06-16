<div class="productpage">
<?php
    require_once("../includes/config.php");
    //save current URL of the Page. 
    
    $current_url= base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    $_SESSION["return_url"] = $current_url;
    
    echo '<div class="ind_prod_cont">';
    echo    '<form method="GET" action="cart_update.php">';
    echo    '<div class="ind_prod">'; 
    echo        "<img src='img/".$product[0]['image']. "' title='".$product[0]['name']."' alt='".$product[0]['name']."' id='main_image'/>" ;
    if($product[0]['image2'] != "")
    {
     echo    "<img src='img/".$product[0]['image2']. "' title='".$product[0]['name']."' alt='".$product[0]['name']."' id='mini_image'/>" ;
    }
    echo    '</div>';
    echo    '<div id="ind_prod_sale_info">';
    echo        '<div class="product-name">'.$product[0]['name'].'</div>';
    echo        '<div class="product-id">PRODUCT NO. '.$product[0]['productId'].'</div>';
    $price = number_format($product[0]["price"],2,'.',',');
    echo        '<div class="product-price">'.currency.$price .'</div>';
    echo        '<div class="product-desc">DESCRIPTION <br/>'.$product[0]['description'].'</div>';
    echo        '<div class="product-size">DIMENSIONS <br/>'.$product[0]['dimensions'].'</div>';
    $quantity = $product[0]["quantity"];
    //set option values based on quantity
    echo        '<div class="product-quant">QUANTITY <br/> <select name="product_qty">';
    $option = 1;
    while(($option <= $quantity) && ($option <= 9))
    {
        echo '<option value = '. $option . '> '. $option . ' </option>';
        $option++;
    }
    echo '</select> </div>';
    // <option value = 1> 1 </option><option value = 2> 2 </option>
    //                <option value = 3> 3 </option><option value = 4> 4 </option> <option value = 5> 5 </option> <option value = 6> 6 </option>
    //                <option value = 7> 7 </option> <option value = 8> 8 </option><option value = 9> 9 </option> </select> </div>';
     echo       '<div><button class="add_to_cart">ADD TO SHOPPING BAG</button></div>';
    echo    '</div>';
    echo    '<input type="hidden" name="product_id" value="'.$product[0]['productId'].'" />';
    echo    '<input type="hidden" name="type" value="add" />';
    echo    '<input type="hidden" name="return_url" value="'.$current_url.'" />';
    echo    '</form>';
    echo'</div>';
    
?>
 </div>