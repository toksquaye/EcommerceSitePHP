<div class="display_page">
    <div class="prod_list_row">
        
<?php
    echo '<h1 class="prod_list_heading">SEARCH RESULTS</h1>';
    if($productRows)
        {
            echo '<p id="search_msg">Your search for <b>"'.$search.'"</b> returned the following results:</p>';
        }
    else
        {
            echo '<p id="search_msg">We are sorry, but we are unable to find any products that match <b>"'.$search.'"</b></p>';
        }
    foreach($productRows as $productRow) 
    { 
        print("<div class='product-holder'>");
        print("<div class='product'>");
        print("<img src='img/".$productRow['image']. "' alt='".$productRow['name'] ."' id='" . $productRow['productId']. "' class = 'gopp' />");
        print("<div class ='desc'>");
        print("<span>". $productRow['name']."</span>");
        $price = number_format($productRow["price"],2,'.',',');
        print("<span class='dollar'>$". $price . "</span>");
        print("</div>");
        print("</div>");
        print("</div>");
    } 
?>
    </div>
</div>
