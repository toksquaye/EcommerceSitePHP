<div class="display_page">
    <div class="prod_list_row">
        <?php
            $_SESSION["return_url"] = base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            switch($menuOpt)
            {
                case "handbag":
                    echo '<h1 class="prod_list_heading" id="handbags_page">AFRICAN PRINT HANDBAGS</h1>'; break;
                case "clutch":
                    echo '<h1 class="prod_list_heading" id="clutches_page">AFRICAN PRINT CLUTCH PURSES</h1>'; break;
                case "homeacc":
                    echo '<h2 class="prod_list_heading" id="homeacc_page">AFRICAN PAINTINGS AND THROW PILLOWS </h2>'; break;
                case "specoff":
                    echo '<h1 class="prod_list_heading" id="specoff_page">SPECIAL OFFERS</h1>'; break;
                case "sale":
                    echo '<h1 class="prod_list_heading" id="sale_page">SALE</h1>'; break;
                case "newarr":
                   echo '<h1 class="prod_list_heading" id="newarrivals_page">NEW ARRIVALS</h1>'; break;
                default:
                    echo '<h1 class="prod_list_heading" id="handbags_page">AFRICAN PRINT HANDBAGS</h1>'; break;
                
            }
            
        ?>
<?php
    foreach($productRows as $productRow) 
    { 
        print("<div class='product-holder'>");
        print("<div class='product'>");
        if($productRow['quantity'] > 0)
        {
            print("<img src='img/".$productRow['image']. "' title='".$productRow['name'] ."' alt='".$productRow['name'] ."' id='" . $productRow['productId']. "' class = 'gopp' />");
        }
        else
        {
            print("<img src='img/".$productRow['image']. "' alt='".$productRow['name'] ."' id='" . $productRow['productId']. "' />");
            print("<img src='img/sold-out-watermark.png' alt='sold-out' id='sold-out' title='".$productRow['name']."'/>");
            
        }
        print("</div>");
        print("<div class ='desc'>");
        print("<span>". $productRow['name']."</span>");
        $price = number_format($productRow["price"],2,'.',',');
        print("<span class='dollar'>$". $price . "</span>");
        print("</div>");
        print("</div>");
        
    } 
?>
    </div>
</div>

