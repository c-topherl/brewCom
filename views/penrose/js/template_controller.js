var targetDiv = "#main-content";
var method = "post";
var templatePath = "http://localhost/brewCom/views/penrose/";

var loadTemplate = function(templateName, content){

	var req = new XMLHttpRequest();
    req.open("get", templateName, true);

    req.onreadystatechange = function(){
        if (req.readyState == 4 && req.status == 200){
        	var divText = req.response;
    		var compiledTemplate = Handlebars.compile(req.responseText);
    		var compiledHtml = compiledTemplate(content);
    		$(targetDiv).html(compiledHtml);
    		displayTables();
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
        			template = templatePath + "delivery_date.html";
        			loadTemplate(template, data);
        		} else {
        			data = responseObj.response.cart;
        			lines = data.lines;
        			if (lines.length === 0){
        				getOrderPage();
        			} else {
        				template = templatePath + "cart.html";
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
	var template = templatePath + "open_orders.html";

	var requestData = {
    	"function": "get_orders",
    	"user_id": userId,
    	"status": "open"
    };

    buildHttpRequestForTemplate(method, url, template, requestData);
    
    
    return;
}

var getOrderDetail = function(orderNumber){	
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
	var template = templatePath + "order_detail.html";

	var requestData = {
    	"function": "get_order_detail",
    	"order_id": orderNumber
    };

	buildHttpRequestForTemplate(method, url, template, requestData);
    
    displayTable("order-table");
    return;
}

var getDeliveryOptions = function(){
	var userId = document.getElementById("user-id").innerHTML;
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
	var template = templatePath + "delivery_date.html";

	var requestData = {
    	"function": "get_delivery_options",
    	"user_id": userId
    };

	buildHttpRequestForTemplate(method, url, template, requestData);

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
    	"delivery_date": shipDate,
    	"delivery_method": deliveryMethod,
    	"shipping_type": "standard"
    };

	buildHttpRequest(method, url, requestData);

	getOrderPage();
    return;
}

var getOrderPage = function(){
	var userId = document.getElementById("user-id").innerHTML;
	var template = templatePath + "order.html";
	url = "http://joelmeister.net/brewCom/controllers/product_controller.php";

	requestData = {
    	"function": "get_product_units",
    	"user_id": userId
    };

    buildHttpRequestForTemplate(method, url, template, requestData);
}

var buildCart = function(){
	var template = templatePath + "cart.html";
	var userId = document.getElementById("user-id").innerHTML;

	var lineId;
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
			lineId = currentQtyField.id.split("_");
			lineId = lineId[lineId.length-1];
			lineObj = {};
			lineObj.product_code = document.getElementById("product_" + lineId).innerHTML;
			lineObj.product_description = document.getElementById("desc_" + lineId).innerHTML;
			lineObj.unit_id = document.getElementById("unit_id_" + lineId).innerHTML;
			lineObj.product_id = document.getElementById("product_id_" + lineId).innerHTML;
			lineObj.unit_description = document.getElementById("unit_" + lineId).innerHTML;
			lineObj.price = parseFloat(document.getElementById("price_" + lineId).innerHTML.slice(1));
			lineObj.line_id = i;
			lineObj.quantity = currentQty;
			lineObj.product_unit_id = lineId;

			data.lines[lineCounter] = lineObj;

			currQty = parseFloat(lineObj.quantity);
			totalPrice += lineObj.price * currQty;
			totalPrice = Math.round(totalPrice * 100) / 100;
			lineCounter++;
		}
	}

	data.total_price = totalPrice;
    loadTemplate(template, data);

    var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";

    buildHttpRequest(method, url, data);
    
    return;
}

var buildCheckoutPage = function(){
	var template = templatePath + "checkout.html";
    loadTemplate(template, null);
    
    return;
}

var submitOrder = function(){
	var userId = document.getElementById("user-id").innerHTML;
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
	var template = templatePath + "confirmation.html";

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
	var template = templatePath + "update_info.html";
	loadTemplate(template, null);

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

var deleteLine = function(lineNumber){
	var userId = document.getElementById("user-id").innerHTML;
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";

	var requestData = {
    	"function": "delete_cart_detail",
    	"user_id": userId,
    	"line_id": lineNumber
    };

    buildHttpRequest(method, url, requestData, removeRow, lineNumber);
}

var removeRow = function(lineNumber){
	var currentTotal = parseFloat(document.getElementById("total-price").innerHTML);
	var rowToRemove = document.getElementById("line_" + lineNumber);
	var elements = rowToRemove.getElementsByTagName("td");
	var price;
	var qty;
	for (var i = 0; i < elements.length; i++){
		if (elements[i].className === "price"){
			price = elements[i].innerHTML;
		} else if (elements[i].className === "quantity"){
			qty = elements[i].innerHTML;
		}
	}

	price = price.slice(1);
	currentTotal -= parseFloat(price * qty);
	document.getElementById("total-price").innerHTML = currentTotal;

	rowToRemove.remove();
	return;
}

var showConfirmation = function(message){
	var template = templatePath + "confirmation.html";
	var content = {"message": message};
	loadTemplate(template, content);

	return;
}

var showError = function(message){
	alert(message);
	return;
}


