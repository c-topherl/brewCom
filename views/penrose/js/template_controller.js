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

	if (!user.value || !pass.value){
		showError("You must enter a username and password!");
		return;
	}

	var url = "http://joelmeister.net/brewCom/controllers/customer_controller.php?function=verify_user&username=" + user.value + "&password=" + pass.value;

	var req = new XMLHttpRequest();
    req.open("post", url, true);
    var requestData = {
    	"function": "verify_user",
    	"username": user.value,
    	"password": pass.value
    };

    var template;

    req.onreadystatechange = function(){
        if (req.readyState == 4 && req.status == 200){
        	var responseObj = JSON.parse(req.responseText);
        	if (responseObj.status === "success"){
        		data = responseObj.response.cart;
        		if (!data || data.length === 0){
        			//data = responseArr[0].response.product_unit;
        			//template = "http://localhost/brewCom/views/penrose/delivery_date.html";
        			getDeliveryOptions();
        		} else {
        			data = responseObj.response.cart;
        			template = "http://localhost/brewCom/views/penrose/cart.html";
        			loadTemplate(template, data);
        		}

        		//loadTemplate(template, data);
        		showNavLinks();
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
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php?function=get_orders&customer_code="
		+ customerCode + "&status=open";
	var templatePath = "http://localhost/brewCom/views/penrose/open_orders.html";

    buildHttpRequestForTemplate("post", url, templatePath);
    
    displayTable("order-table");
    return;
}

var getOrderDetail = function(orderNumber){	
	var method = "post";
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php?function=get_order_detail&order_id=" + orderNumber;
	var templatePath = "http://localhost/brewCom/views/penrose/order_detail.html";

	buildHttpRequestForTemplate(method, url, templatePath);
    
    displayTable("order-table");
    return;
}

var getDeliveryOptions = function(){
	var customerCode = document.getElementById("customer-code").innerHTML;
	var method = "post";
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php?function=get_delivery_options&customer_code=" 
		+ customerCode;
	var templatePath = "http://localhost/brewCom/views/penrose/delivery_date.html";

	buildHttpRequestForTemplate(method, url, templatePath);

    return;
}

var getOrderPage = function(){
	var method = "post";
	var customer = document.getElementById("customer-code").innerHTML;
	var shipDate = document.getElementById("delivery-date").value;
	var deliveryMethod = document.getElementById("delivery-method").value;
	var func = "add_cart_header";
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php?"
	url += "function=" + func;
	url += "&customer=" + customer;
	url += "&ship_date=" + shipDate;
	url += "&deliveryMethod=" + deliveryMethod;

	buildHttpRequest(method, url, null, null);

	
	var templatePath = "http://localhost/brewCom/views/penrose/order.html";
	url = "http://joelmeister.net/brewCom/controllers/product_controller.php?function=get_product_units"
		+ "&customer_code=" + customer;

    buildHttpRequestForTemplate(method, url, templatePath);
    return;
}

var buildCart = function(){
	var url = "http://localhost/brewCom/views/penrose/cart.html";

	var productCounter = 0;
	var lineCounter = 0;
	var orderId = "order_";
	var currentQtyField = document.getElementById(orderId + productCounter);
	var currentQty;
	var data = {lines:[]};
	var lineObj = {};
	var totalPrice = 0;
	var currPrice;
	var currQty;

	while (currentQtyField != null){
		currentQty = currentQtyField.value;
		if (currentQty !== "" && currentQty > 0){
			lineObj = {};
			lineObj.product = document.getElementById("product_" + productCounter).innerHTML;
			lineObj.desc = document.getElementById("desc_" + productCounter).innerHTML;
			lineObj.unit = document.getElementById("unit_" + productCounter).innerHTML;
			lineObj.price = document.getElementById("price_" + productCounter).innerHTML;
			lineObj.quantity = currentQty;

			data.lines[lineCounter] = lineObj;

			currPrice = parseFloat(lineObj.price.slice(1));
			currQty = parseFloat(lineObj.quantity);
			totalPrice += currPrice * currQty;
			totalPrice = Math.round(totalPrice * 100) / 100;
			lineCounter++;
		}

		productCounter++;
		currentQtyField = document.getElementById(orderId + productCounter);
	}

	data.totalPrice = totalPrice;
    loadTemplate(url, data);

    buildHttpRequest(data);
    
    return;
}

var buildCheckoutPage = function(){
	var url = "http://localhost/brewCom/views/penrose/checkout.html";
    loadTemplate(url, null);
    
    return;
}

var submitOrder = function(){
	var method = "post";
	var customer = document.getElementById("customer-code").innerHTML;
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php?function=submitOrder&customer_code=" + customer;
	var templatePath = "http://localhost/brewCom/views/penrose/confirmation.html";
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


