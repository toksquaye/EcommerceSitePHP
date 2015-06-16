<!DOCTYPE html>
<html>
 
    <head>
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
        <link href="css/bootstrap-theme.min.css" rel="stylesheet"/>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <link href='https://fonts.googleapis.com/css?family=Oranienbaum' rel='stylesheet' type='text/css'>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,700" rel="stylesheet" type="text/css" />
        <link rel="icon" type="image/x-icon" href="img/logo_ico1.ico" />
        <script src="https://www.google.com/jsapi"></script>
        <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src='js/jquery.easing.1.3.js'></script>
        <script type="text/javascript" src='js/jquery.plusslider.js'></script>
        <script type="text/javascript" src='js/shipping_costs.js'></script>
        <script type="text/javascript" src='js/funcs.js'></script>
        <script type="text/javascript">
        $(document).ready(function(){
            $('#action-bar-qty').show(function() {
            var qty = <?php if (isset($_SESSION["cart_qty"])) echo $_SESSION["cart_qty"]; else echo "0";?>;
            $("#action-bar-qty").html(qty)});           
            
        });
        
        
        </script>
        <?php if (isset($title)): ?>
            <title>AkotosGHBoutique: <?php echo $title; ?></title>
        <?php else: ?>
            <title>AkotosGHBoutique: African Purses | African Bags |  African Print Purses</title>
        <?php endif ?>
        
        <meta name="Keywords" content="African Purses|African Bags| African Print Purses" />
        <meta name="Description" content="African Purse: We carry African bags from Ghana. Buy unique African print purses at our website. Find a great selection of African style purses online" />
        <meta name="author" content="Akoto's Boutique" />
        
         <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

    </head>
 
    <body>
 <?php include_once("analyticstracking.php") ?>
        <div id="page_container">
            <div id="top">
                <div class="wrapper">
                    <div id="logo-container"><h1 id="logo"><a title="Akoto's Boutique" href="#" id="logopic"></a></h1>
                    <span></span>
                    </div>
                    <nav>
                        <ul id="menu">
                            <li><a href="index.php" id="home_menu">Home</a></li>
                            <li> <a href="mainmenu.php?pType=newarr" id="newarrivals_menu">New Arrivals</a></li>
                            <li><a href="mainmenu.php?pType=handbag" id="handbags_menu">Handbags</a></li>
                            <li><a href="mainmenu.php?pType=clutch" id="clutches_menu">Clutches</a></li>
                            <li> <a href="mainmenu.php?pType=homeacc" id="homeacc_menu">Home Accessories</a></li>
                            <li> <a href="mainmenu.php?pType=specoff" id="specoff_menu">Special Offers</a></li>
                            <li> <a href="mainmenu.php?pType=sale" id="sale_menu">Sale</a></li>
                            
                        </ul>
                    </nav>            
                </div><!-- wrapper -->
            </div><!--top-->
            <aside id="topbar">
                <div class="wrap">
                
                
                <!--<div id="action-bar"> <a href="#"><span id="action-bar-qty"> 0</span> Item(s)</a> <a href="#" id="ckout-link">Checkout</a>-->
                <div id="action-bar"> <a href="goto_cart.php"><span id="action-bar-qty"> 0</span> Item(s)</a> <a href="checkout.php" id="ckout-link">Checkout</a>
                <p class="ship_note">$5 FLAT-RATE SHIPPING ON ALL ORDERS</p>
                <p class="free_note"><b>Free </b>make-up bag with purchase of $150+</p>
                
                
                <form method="GET" action="search.php" id="search_bar"> 
                    <input type="text" id="product_search" name="product_search" autocomplete="off" placeholder="Find something special..." /><button type="submit">Search</button>
                </form>
                </div>
                </div>
            </aside><!--topbar-->

            <div id="middle">
