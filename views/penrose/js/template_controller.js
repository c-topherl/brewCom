var targetDiv = "#main-content";

var loadTemplate = function(templateName, content){

	var req = new XMLHttpRequest();
    req.open("get", templateName, true);

    req.onreadystatechange = function(){
        if (req.readyState == 4 && req.status == 200){
        	var divText = req.response;
    		var compiledTemplate = Handlebars.compile(req.responseText);
    		var compiledHtml = compiledTemplate(content);
    		$(targetDiv).html(compiledHtml);
        }
    };

    req.send();

    return;
}

var verifyLogin = function(){
	var user = document.getElementById("username");
	var pass = document.getElementById("password");
	var url = "login.php?username=" + user.value + "&password=" + pass.value;

	if (!user.value || !pass.value){
		alert("You must enter a username and password!");
		return;
	}

	/*
	LOGIN LOGIC

	var method = "post";
	var templatePath = "http://localhost/brewCom/views/penrose/home.html";

	buildHttpRequest(method, url, loadTemplate, templatePath);
	*/

	//temporary
	showLandingPage();
    showNavLinks();

    return;
} 

var showLandingPage = function(){
	/*
	REQUEST TO GET WHATEVER WE WANT ON LANDING PAGE

	var method = "get";
	var url = "get_landing_page.php";
	var templatePath = "http://localhost/brewCom/views/penrose/home.html";

	buildHttpRequest(method, url, loadTemplate, templatePath);
	*/

	//temporary - this will go away
	var url = "http://localhost/brewCom/views/penrose/home.html";
    loadTemplate(url, null);
    
    return;
}


var getOpenOrders = function(){
	/*
	REQUEST TO GET OPEN ORDERS
	
	var method = "get";
	var url = "get_open_orders.php";
	var templatePath = "http://localhost/brewCom/views/penrose/open_orders.html";

	buildHttpRequest(method, url, loadTemplate, templatePath);
	*/

	//temporary - this will go away
	var url = "http://localhost/brewCom/views/penrose/open_orders.html";
    loadTemplate(url, null);
    
    return;
}

var getOrderDetail = function(orderNumber){
	/*
	REQUEST TO GET OPEN ORDERS
	
	var method = "get";
	var url = "order_detail.php?orderNumber=" + orderNumber;
	var templatePath = "http://localhost/brewCom/views/penrose/order_detail.html";

	buildHttpRequest(method, url, loadTemplate, templatePath);
	*/

	//temporary - this will go away
	var url = "http://localhost/brewCom/views/penrose/order_detail.html";
	var content = {"orderNumber": "12345"};
    loadTemplate(url, content);
    
    return;
}

var getDeliveryOptions = function(){
	/*
	REQUEST TO GET DELIVERY OPTIONS
	
	var method = "get";
	var url = "get_delivery_options.php";
	var templatePath = "http://localhost/brewCom/views/penrose/delivery_date.html";

	buildHttpRequest(method, url, loadTemplate, templatePath);
	*/

	//temporary - this will go away
	var templatePath = "http://localhost/brewCom/views/penrose/delivery_date.html";
	loadTemplate(templatePath, null);

    return;
}

var getOrderPage = function(){
	/*
	REQUEST TO GET ORDER PAGE CONTENTS (PRODUCTS FOR NOW)
	
	var method = "get";
	var url = "order_detail.php?";
	url += "deliveryMethod=" + deliveryMethod;
	url += "&deliveryDate=" + deliveryDate;
	url += "&warehouse=" + warehouse;
	var templatePath = "http://localhost/brewCom/views/penrose/order.html";

	buildHttpRequest(method, url, loadTemplate, templatePath);
	*/

	//temporary - this will go away
	var url = "http://localhost/brewCom/views/penrose/order.html";
    loadTemplate(url, null);
    
    return;
}

var buildCart = function(){
	/*
	REQUEST TO GET CART CONTENTS
	
	var method = "get";
	var url = "get_cart.php?";
	var templatePath = "http://localhost/brewCom/views/penrose/cart.html";

	buildHttpRequest(method, url, loadTemplate, templatePath);
	*/

	//temporary - this will go away
	var url = "http://localhost/brewCom/views/penrose/cart.html";
    loadTemplate(url, null);
    
    return;
}

var buildCheckoutPage = function(){
	var url = "http://localhost/brewCom/views/penrose/checkout.html";
    loadTemplate(url, null);
    
    return;
}

var submitOrder = function(){
	/*
	REQUEST TO GET CART CONTENTS
	
	var method = "post";
	var url = "submit_order.php";
	var templatePath = "http://localhost/brewCom/views/penrose/confirmation.html";
	var successMessage = "Your information has been updated successfully!";

	buildHttpRequest(method, url, showConfirmation, successMessage);
	*/

	//temporary - this will go away
	var message = "Your order has been submitted successfully!";
	showConfirmation(message);
    
    return;
}

var getCustomerInfoForm = function(){	
	var templatePath = "http://localhost/brewCom/views/penrose/update_info.html";
	loadTemplate(templatePath, null);

    return;
}

var updateCustomerInfo = function(){
	/*
	REQUEST TO UPDATE CUSTOMER INFO
	
	var method = "post";
	var url = "update_customer_info.php";
	var templatePath = "http://localhost/brewCom/views/penrose/confirmation.html";
	var successMessage = "Your information has been updated successfully!";

	buildHttpRequest(method, url, showConfirmation, successMessage);
	*/

	//temporary - this will go away
	var message = "Your information has been updated successfully!";
	showConfirmation(message);

    return;
}

var showConfirmation = function(message){
	var templatePath = "http://localhost/brewCom/views/penrose/confirmation.html";
	var content = {"message": message};
	loadTemplate(templatePath, content);

	return;
}

var showError = function(message){
	alert(message);

	return;
}

