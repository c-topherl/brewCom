



<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie ie6 lt-ie9 lt-ie8 lt-ie7 no-js"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 lt-ie9 lt-ie8 no-js"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 lt-ie9 no-js"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="notie no-js"> <!--<![endif]-->

	<head>
	   
		<!-- META -->
		<meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Online Ordering</title>
<meta name="description" content="We focus on belgian inspired session ales, oak barrel-aging, and alternative fermentation. Our pursuit of balance inspires effort to bring order to random.">
<meta name="keywords" content="penrose brewing, craft beer, craft, belgian, session ales, oak barrel-aging, alternative fermentation">


		
		<!-- HTML HEADER -->
		<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<link rel="shortcut icon" href="images/favicon.ico" />
<link href='https://fonts.googleapis.com/css?family=Wire+One' rel='stylesheet' type='text/css'>

<!-- CSS -->
<!-- ////////////////////////////////////////// --> 

<link rel="stylesheet" href="css/brewery.css">
<link rel="stylesheet" href="css/login.css">



<!-- TYPEKIT -->
<!-- ////////////////////////////////////////// --> 
<script type="text/javascript" src="//use.typekit.net/xeh2zrw.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>


<!-- MODERNIZER -->
<!-- ////////////////////////////////////////// --> 
<script src="js/modernizr-2.6.1.min.js"></script>

	</head>

	<body class="beer beer-landing">
		<script type="text/javascript">document.getElementsByTagName('body')[0].className+=' js'</script>

		<!-- HEADER -->
		<!-- ////////////////////////////////////////// --> 
		<a id="top"></a>

<header class="header-global">
    <div class="wrapper">

        <!-- Logo -->
    	<h1><a class="logo" href="/">Penrose Brewing Company</a></h1>
    </div>   
</header>


<!-- Main Nav -->
<nav class="nav-main nav-header nav-short">
    <ul>
        <!-- Left Side -->
        <li class="about"><a href="/about">About</a></li>
        <li class="beer"><a href="/beer">Beer</a></li>
        <li class="news"><a href="/news">News</a></li>
        <li class="events"><a href="/events">Events</a></li>
        <li class="shop"><a href="https://estore.bfcprint.com/penrose/start_new_order.cgi">Shop</a></li>
        <!-- Logo -->
        <li class="home"><a href="/">Penrose Brewing Company</a></li>
        <!-- Right Side -->
        <li class="tasting-room"><a href="/taproom">Taproom</a></li>
        <li class="tours is-short"><a href="/taproom/detail/brewery-tours">Tours</a></li>
        <li class="pritave-events is-short"><a href="/taproom/detail/private-events">Private Events</a></li>
        <li class="contact is-short"><a href="/contact">Contact</a></li>
    </ul>
</nav>


		<header class="header-simple">
			<h2 class="header-fade fade-in">Online Ordering</h2>
		</header>

		<!-- BEER LISTING -->
		<section class="section-holder section-light glyph glyph-dark">

		<div class="glyph-holder glyph-holder-0">
    		<div class="glyph-art glyph-art-1"></div>
    		<div class="glyph-art glyph-art-2"></div>
    		<div class="glyph-art glyph-art-3"></div>
    		<div class="glyph-art glyph-art-4"></div>
    		<div class="glyph-art glyph-art-5"></div>
		</div>

			<hr class="section-rule">

			<div class="wrapper">
			   <!--
				<div class="beer-grid no-headline-rule">
				</div>
			-->

<div id="login-div">
    <div id="something">
        <form id="addorder-form" onsubmit="return submitCreateOrder()" method="post">
            user id: <input type="text" name="user_id" id="user_id" value=123>
            type (ship/pickup): <input type="text" name="type" id="type" value="pickup">
            ship/pickup date: <input type="text" name="ship_date" id="ship_date" value="2016-02-10">
            product code: <input type="text" name="detail[0][product_code]" id="product_code" value="zoe"><br>
            <input type="submit" id="login-btn">
        </form>
    </div>
    <div id="login-info">
        <p>Orders:</p>
    </div>
<div>
<?php 
include "../controllers/get_orders.php";
$getUser['email'] = "joelmeister1209@gmail.com";
$orders = (array)get_orders($getUser,$error);
echo "<script>console.log('$error');</script>";
echo "<table border=\"1\">";
echo "<tr>";
echo "<th>order_id</th>";
echo "<th>user_id</th>";
echo "<th>status</th>";
echo "<th>order_date</th>";
echo "<th>ship_date</th>";
echo "<th>products</th>";
echo "</tr>";
foreach($orders as $o)
{
    echo "<tr>";
    echo "<td>".$o['order_id']."</td>";
    echo "<td>".$o['user_id']."</td>";
    echo "<td>".$o['status']."</td>";
    echo "<td>".$o['order_date']."</td>";
    echo "<td>".$o['ship_date']."</td>";
    if(isset($o['detail']) && is_array($o['detail']) && count($o['detail']) > 0)
    {
        foreach($o['detail'] as $d)
        {
            echo "<td>".$d['product_code']."</td>";
        }
    }
    else
    {
        echo "<td>no details</td>";

    }
    echo "</tr>";
}
echo "</table>";
?>
</div>
				</div>
					
			</div>    
		</section>		 

		

		<!-- FOOTER -->
		<!-- ////////////////////////////////////////// --> 
		<footer class="footer-global">

	<div class="wrapper">
		<!--
        <a class="link-top anchorSlide" href="#top">Top</a>
		-->
	    <p class="copyright">&copy; Penrose Brewing 2016<br>Design by <a href="http://www.mightyfew.com" target="_">Mighty Few</a> &amp; <a href="http://thisisstatic.com/" target="_">Static</a> <br>Site by Chris &amp; Joel</p>
	    <a class="facebook social" href="https://www.facebook.com/pages/Penrose-Brewing/320419804738106" target="_blank">Facebook</a>
		<a class="twitter social" href="https://twitter.com/PenroseBrewing" target="_blank">Twitter</a>
	</div>
    
</footer>

		<!-- JS : FOOTER -->
		<!-- ////////////////////////////////////////// -->     
		

<!-- JQUERY --> 

<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/EasePack.min.js"></script>
<script src="js/TweenMax.min.js"></script>
<script src="js/ScrollToPlugin.min.js"></script>
<script src="js/jquery.backstretch.min.js"></script>
<script src="js/jquery.bigclick.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/jquery.fitvids.js"></script>
<script src="js/imagesloaded.pkgd.min.js"></script>
<script src="js/masonry.pkgd.min.js"></script>
<script src="js/jquery.royalslider.min.js"></script>
<script src="js/jquery.smooth-scroll.min.js"></script>
<script src="js/jquery.jpanelmenu-transform-build.js"></script>
<script src="js/jquery.slides.min.js"></script>
<script src="js/jquery.ui.core.js"></script>
<script src="js/jquery.ui.widget.js"></script>
<script src="js/jquery.ui.position.js"></script>
<script src="js/jquery.ui.selectmenu.js"></script>   
<script src="js/jquery.magnific-popup.min.js"></script>
<!--<script src="js/popup.magnific.js"></script>-->
<script src="js/waypoints.js"></script>
<script src="js/module.header-animation.js"></script>
<script src="js/loader.js"></script>
<script src="js/dataTables.min.js"></script>
<script src="js/module.grid-rollover.js"></script>
<script src="js/animation.beer.js"></script>
<script src="js/page.beer-landing.js"></script>
<script src="js/site.js"></script>

<script type="text/javascript">

function submitCreateOrder(){
    var fields = $("#addorder-form").serialize();
    fields += "&function=add_order";
    $.ajax({
        type: "POST",
        url: "../controllers/order_controller.php",
        data: fields,
        dataType: 'json',
        success: function(response) {
            console.log("success: " + response.message);
        },		  	
        error: function(jqXHR){
           var json=jqXHR.responseText;
           console.log("failure: " + json);
        }
    });
    return false;
}
$(document).ready(function () {
    $('.module-beer').bigClick({
        anchorSelector: '.btn-more-alt',
        addClass:'hover'
        });
});
    
</script>
</body>

</html>
