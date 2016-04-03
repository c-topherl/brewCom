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
    var userId;
    var userDiv;
    var lines;

    req.onreadystatechange = function(){
        if (req.readyState == 4 && req.status == 200){
        	var responseObj = JSON.parse(req.responseText);
        	if (responseObj.status === "success"){
        		data = responseObj.response.cart;
        		userId = responseObj.response.user_id;
        		userDiv = document.getElementById("user-id");
        		if (userId && userDiv){
        			userDiv.innerHTML = userId;
        		}

        		if (!data || data.length === 0){
        			data = responseObj.response.delivery_options;
        			template = "http://localhost/brewCom/views/penrose/delivery_date.html";
        			loadTemplate(template, data);
        		} else {
        			data = responseObj.response.cart;
        			lines = data.lines;
        			if (lines.length === 0){
        				getOrderPage();
        			} else {
        				template = "http://localhost/brewCom/views/penrose/cart.html";
        				loadTemplate(template, data);
        			}
        		}

        		showNavLinks();
        	} else {
        		showError(responseObj.message);
        	}
        }
    };

    req.send(JSON.stringify(requestData));

    return;
} 


var getOpenOrders = function(){
	var userId = document.getElementById("user-id").innerHTML;
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
	var templatePath = "http://localhost/brewCom/views/penrose/open_orders.html";

	var requestData = {
    	"function": "get_orders",
    	"user_id": userId,
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
	var userId = document.getElementById("user-id").innerHTML;
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
	var templatePath = "http://localhost/brewCom/views/penrose/delivery_date.html";

	var requestData = {
    	"function": "get_delivery_options",
    	"user_id": userId
    };

	buildHttpRequestForTemplate(method, url, templatePath, requestData);

    return;
}

var buildCartHeader = function(){
	var userId = document.getElementById("user-id").innerHTML;
	var shipDate = document.getElementById("delivery-date");
	shipDate = shipDate.options[shipDate.selectedIndex].text;
	var deliveryMethod = document.getElementById("delivery-method");
	deliveryMethod = deliveryMethod.options[deliveryMethod.selectedIndex].text;
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";

	var requestData = {
    	"function": "add_cart_header",
    	"user_id": userId,
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
	var userId = document.getElementById("user-id").innerHTML;
	var templatePath = "http://localhost/brewCom/views/penrose/order.html";
	url = "http://joelmeister.net/brewCom/controllers/product_controller.php";

	requestData = {
    	"function": "get_product_units",
    	"user_id": userId
    };

    buildHttpRequestForTemplate(method, url, templatePath, requestData);
}

var buildCart = function(){
	var url = "http://localhost/brewCom/views/penrose/cart.html";
	var userId = document.getElementById("user-id").innerHTML;

	var productId;
	var quantityId;
	var lineCounter = 0;
	var currentQtyField;
	var currentQty;
	var data = {
		lines:[],
		"function": "add_cart_detail",
		"user_id": userId
	};
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
			lineObj.product_code = document.getElementById("product_" + productId).innerHTML;
			lineObj.product_description = document.getElementById("desc_" + productId).innerHTML;
			lineObj.unit_id = document.getElementById("unit_id_" + productId).innerHTML;
			lineObj.unit_description = document.getElementById("unit_" + productId).innerHTML;
			lineObj.price = parseFloat(document.getElementById("price_" + productId).innerHTML.slice(1));
			lineObj.quantity = currentQty;
			lineObj.product_id = productId;

			data.lines[lineCounter] = lineObj;

			currQty = parseFloat(lineObj.quantity);
			totalPrice += lineObj.price * currQty;
			totalPrice = Math.round(totalPrice * 100) / 100;
			lineCounter++;
		}
	}

	data.total_price = totalPrice;
    loadTemplate(url, data);

    url = "http://joelmeister.net/brewCom/controllers/order_controller.php";

    buildHttpRequest(method, url, data);
    
    return;
}

var buildCheckoutPage = function(){
	var url = "http://localhost/brewCom/views/penrose/checkout.html";
    loadTemplate(url, null);
    
    return;
}

var submitOrder = function(){
	var userId = document.getElementById("user-id").innerHTML;
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
	var templatePath = "http://localhost/brewCom/views/penrose/confirmation.html";

	var requestData = {
    	"function": "submit_order",
    	"user_id": userId,
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
	var userId = document.getElementById("user-id").innerHTML;
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


