<?php
    require_once("../includes/config.php");
    //save current URL of the Page. 
    
    $current_url= base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    $_SESSION["return_url"] = $current_url;
    

?>
      <!-- Slider -->
     <div id="page-wrap">
        <div id="content">
            <div id="slider2">
                  <img src="img/SP-Model_Clutch.jpg" alt="Model_clutch" height="450" width="630" />
                <img src="img/SP-Model_Pole.jpg" alt="Model_Black" height="450" width="630" />
                  <img src="img/SP-Model_Walk.jpg" alt="bags" id = "BG2005" class="gopp" height="450" width="630" />
                  <img src="img/SP-Owner_Clutch.jpg" alt="Owner_Clutch" height="450" width="630" />
                  <img src="img/Bag_leaves.jpg" alt="bag_leaves" id = "BG2018" class="gopp" height="450" width="630" />
                  <img src="img/SP-Bags_on_deck.jpg" alt="bags_on_deck" id = "BX2001" class="gopp" height="450" width="630" />
                  <img src="img/SP-Bag_model.jpg" alt="bag_model" id = "BX2004" class="gopp" height="450" width="630" />
                  <img src="img/SP-Pillows.jpg" alt="Pillows" id = "PL2001" class="gopp" height="450" width="630" />
                  <img src="img/SP-Tree_Art.jpg" alt="Tree_Art" height="450" width="630" />
                  
            </div>
        </div><!--content-->
     </div><!--page wrap-->
