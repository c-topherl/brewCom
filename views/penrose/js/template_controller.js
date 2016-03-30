var targetDiv = "#main-content";
var method = "post";

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

	if (!user.value || !pass.value){
		showError("You must enter a username and password!");
		return;
	}

	var url = "http://joelmeister.net/brewCom/controllers/customer_controller.php";

	var req = new XMLHttpRequest();
    req.open(method, url, true);
    var requestData = {
    	"function": "verify_user",
    	"username": user.value,
    	"password": pass.value
    };

    var template;
    var data;

    req.onreadystatechange = function(){
        if (req.readyState == 4 && req.status == 200){
        	var responseObj = JSON.parse(req.responseText);
        	if (responseObj.status === "success"){
        		data = responseObj.response.cart;
        		if (!data || data.length === 0){
        			data = responseObj.response.delivery_options;
        			template = "http://localhost/brewCom/views/penrose/delivery_date.html";
        			loadTemplate(template, data);
        		} else {
        			data = responseObj.response.cart;
        			template = "http://localhost/brewCom/views/penrose/cart.html";
        			loadTemplate(template, data);
        		}

        		showNavLinks("Test");
        	} else {
        		showError(responseObj.message);
        	}
        }
    };

    req.send(JSON.stringify(requestData));

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
	var customerCode = document.getElementById("customer-code").innerHTML;
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
	var templatePath = "http://localhost/brewCom/views/penrose/open_orders.html";

	var requestData = {
    	"function": "get_orders",
    	"customer_code": customerCode,
    	"status": "open"
    };

    buildHttpRequestForTemplate(method, url, templatePath, requestData);
    
    displayTable("order-table");
    return;
}

var getOrderDetail = function(orderNumber){	
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
	var templatePath = "http://localhost/brewCom/views/penrose/order_detail.html";

	var requestData = {
    	"function": "get_order_detail",
    	"order_id": orderNumber
    };

	buildHttpRequestForTemplate(method, url, templatePath, requestData);
    
    displayTable("order-table");
    return;
}

var getDeliveryOptions = function(){
	var customerCode = document.getElementById("customer-code").innerHTML;
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
	var templatePath = "http://localhost/brewCom/views/penrose/delivery_date.html";

	var requestData = {
    	"function": "get_delivery_options",
    	"customer_code": customerCode
    };

	buildHttpRequestForTemplate(method, url, templatePath, requestData);

    return;
}

var buildCartHeader = function(){
	var customerCode = document.getElementById("customer-code").innerHTML;
	var shipDate = document.getElementById("delivery-date");
	shipDate = shipDate.options[shipDate.selectedIndex].text;
	var deliveryMethod = document.getElementById("delivery-method");
	deliveryMethod = deliveryMethod.options[deliveryMethod.selectedIndex].text;
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";

	var requestData = {
    	"function": "add_cart_header",
    	"customer": customerCode,
    	"ship_date": shipDate,
    	"type": deliveryMethod,
    	"user_id": "test",
    	"shipping_type": "standard"
    };

	buildHttpRequest(method, url, requestData);

	getOrderPage();
    return;
}

var getOrderPage = function(){
	var customerCode = document.getElementById("customer-code").innerHTML;
	var templatePath = "http://localhost/brewCom/views/penrose/order.html";
	url = "http://joelmeister.net/brewCom/controllers/product_controller.php";

	requestData = {
    	"function": "get_product_units",
    	"customer_code": customerCode
    };

    buildHttpRequestForTemplate(method, url, templatePath, requestData);
}

var buildCart = function(){
	var url = "http://localhost/brewCom/views/penrose/cart.html";

	var productId;
	var quantityId;
	var lineCounter = 0;
	var currentQtyField;
	var currentQty;
	var data = {lines:[]};
	var lineObj = {};
	var totalPrice = 0;
	var currPrice;
	var currQty;

	var quantityFields = document.getElementsByClassName("order-quantity");

	for (var i=0; i < quantityFields.length; i++){
		currentQtyField = quantityFields[i];
		currentQty = currentQtyField.value;
		if (currentQty !== "" && currentQty > 0){
			quantityId = currentQtyField.id;
			productId = quantityId.substr(quantityId.length - 1);
			lineObj = {};
			lineObj.product = document.getElementById("product_" + productId).innerHTML;
			lineObj.desc = document.getElementById("desc_" + productId).innerHTML;
			lineObj.unit = document.getElementById("unit_" + productId).innerHTML;
			lineObj.price = document.getElementById("price_" + productId).innerHTML;
			lineObj.quantity = currentQty;

			data.lines[lineCounter] = lineObj;

			currPrice = parseFloat(lineObj.price.slice(1));
			currQty = parseFloat(lineObj.quantity);
			totalPrice += currPrice * currQty;
			totalPrice = Math.round(totalPrice * 100) / 100;
			lineCounter++;
		}
	}

	data.totalPrice = totalPrice;
    loadTemplate(url, data);

    buildHttpRequest(method, url, data);
    
    return;
}

var buildCheckoutPage = function(){
	var url = "http://localhost/brewCom/views/penrose/checkout.html";
    loadTemplate(url, null);
    
    return;
}

var submitOrder = function(){
	var customerCode = document.getElementById("customer-code").innerHTML;
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
	var templatePath = "http://localhost/brewCom/views/penrose/confirmation.html";

	var requestData = {
    	"function": "submit_order",
    	"customer_code": customerCode
    };

	var successMessage = "Your information has been updated successfully!";

	buildHttpRequest(method, url, showConfirmation, successMessage);
	

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


