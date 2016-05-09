var targetDiv = "#main-content";
var method = "post";
var templatePath = "http://localhost/brewCom/views/penrose/";
var userCart;

var loadTemplate = function(templateName, content){
	hideAlerts();
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

var forgotPassword = function(){
	var template = templatePath + "forgot_password.html";
	loadTemplate(template, null);
	return;
}

var forgotPasswordSubmit = function(){
	location.reload();
	showAlert(infoAlert, "You will receive instructions to reset your password shortly.");
}

var sessionExists = function(){
	var userId = getCookie('userId');
	if (!userId || userId === ""){
		userId = null;
		return false;
	}

	var token = getCookie('token');
	if (!token || token === ""){
		userId = null;
		token = null;
		return false;
	}
	
	return true;
}

var verifyLogin = function(){
	var requestData = {
    	"function": "verify_user"
    };
    
    var userId = getCookie('userId');
    var token = getCookie('token');

    if (userId && token){
    	requestData.user_id = userId;
    	requestData.token = token;
    } else {
		var user = document.getElementById("username");
		var pass = document.getElementById("password");
		
		if (!user.value || !pass.value){
			showAlert(errorAlert, "You must enter a username and password!");
			return;
		}

		requestData.username = user.value;
		requestData.password = pass.value;
		hideAlerts();
	}

	var url = "http://joelmeister.net/brewCom/controllers/customer_controller.php";

	var req = new XMLHttpRequest();
    req.open(method, url, true);
    
    var template;
    var data;
    var userId;
    var userDiv;
    var lines;

    req.onreadystatechange = function(){
        if (req.readyState == 4 && req.status == 200){
        	var responseObj = JSON.parse(req.responseText);
        	if (responseObj.status === "success"){
        		data = responseObj.response;

        		removeCookies();
        		userId = responseObj.response.user_id;
        		token = responseObj.response.token;
        		setCookie("userId", userId, 1);
        		setCookie("token", token, 1);
			
				lines = data.lines;
        		if (!lines){
        			template = templatePath + "delivery_date.html";
        			loadTemplate(template, data);
        		} else {
        			if (lines.length === 0){
        				getOrderPage();
        			} else {
        				template = templatePath + "cart.html";
        				userCart = data;
        				loadTemplate(template, data);
        				showAlert(warningAlert, "You already have an order in progress.");
        			}
        		}

        		showNavLinks();
        	} else {
        		showAlert(errorAlert, responseObj.message);
        	}
        }
    };

    req.send(JSON.stringify(requestData));

    return;
} 


var getOpenOrders = function(){
	var userId = getCookie('userId');
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
	if (userCart){
		getCart();
		showAlert(warningAlert, "You already have an order in progress.");
		return;
	}

	var userId = getCookie('userId');
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
	var userId = getCookie('userId');
	var shipDate = document.getElementById("delivery-date");
	shipDate = shipDate.options[shipDate.selectedIndex].value;
	var deliveryMethod = document.getElementById("delivery-method");
	deliveryMethod = deliveryMethod.options[deliveryMethod.selectedIndex].value;
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";

	var requestData = {
    	"function": "add_cart_header",
    	"user_id": userId,
    	"delivery_date": shipDate,
    	"delivery_method": deliveryMethod,
    	"shipping_type": "standard"
    };

	buildHttpRequest(method, url, requestData, getOrderPage, null);

    return;
}

var getOrderPage = function(){
	var temp = userCart;
	var userId = getCookie('userId');
	var template = templatePath + "order.html";
	url = "http://joelmeister.net/brewCom/controllers/product_controller.php";

	requestData = {
    	"function": "get_product_units",
    	"user_id": userId
    };

    buildHttpRequestForTemplate(method, url, template, requestData);
}

Handlebars.registerHelper('getQuantity', function(productId, unitId){
	var quantity = "";

	if (!userCart){
		return quantity;
	}

	var i;
	var currentLine;

	for (i = 0; i < userCart.lines.length; i++){
		currentLine = userCart.lines[i];
		if (currentLine.product_id === productId 
			&& currentLine.unit_id === unitId){
			quantity = currentLine.quantity;
			break;
		}
	}

	return quantity;

})

var buildCart = function(){
	var template = templatePath + "cart.html";
	var userId = getCookie('userId');

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

	var quantityFields = document.getElementsByClassName("quantity-field");

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

	if(lineCounter === 0){
		showAlert(errorAlert, "Your cart is empty! Add an item to continue.");
		return;
	}

	data.total_price = totalPrice;
	userCart = data;
    loadTemplate(template, data);

    var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";

    buildHttpRequest(method, url, data);
    
    return;
}

var getCart = function() {
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
	var userId = getCookie('userId');
	var template = templatePath + "cart.html";

	var requestData = {
    	"function": "get_cart",
    	"user_id": userId,
    };

    buildHttpRequestForTemplate(method, url, template, requestData);
}

var deleteCart = function() {
	var warningMessage = "WARNING: this will erase your current order progress. Are you sure you want to continue?";
	if (!confirm(warningMessage)){
		return;
	}

	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
	var userId = getCookie('userId');

	var requestData = {
    	"function": "delete_cart",
    	"user_id": userId,
    };

    buildHttpRequest(method, url, requestData, getDeliveryOptions);

    return;
}

var buildCheckoutPage = function(){
	var template = templatePath + "checkout.html";
    loadTemplate(template, null);
    
    return;
}

var submitOrder = function(){
	var userId = getCookie('userId');
	var token = getCookie('token');
	var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
	var template = templatePath + "order_detail.html";

	var comments = document.getElementById("comments").value;
	var email = document.getElementById("email").value;

	var requestData = {
    	"function": "submit_order",
    	"user_id": userId,
    	"token": token,
    	"comments": comments,
    	"email": email
    };

	userCart = null;
	buildHttpRequestForTemplate(method, url, template, requestData);
	
    return;
}

var getCustomerInfoForm = function(){	
	var template = templatePath + "update_info.html";
	loadTemplate(template, null);

    return;
}

var updateCustomerInfo = function(){

	//temporary - this will go away
	var userId = getCookie('userId');
	var message = "Your information has been updated successfully!";
	showConfirmation(message);

    return;
}

var deleteLine = function(lineNumber){
	var userId = getCookie('userId');
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

	var remainingRows = document.getElementById("cart-table").getElementsByTagName("tr").length;
	if (remainingRows <= 1){
		getOrderPage();
	}

	return;
}

var logout = function(){
	removeCookies();
	hideMobileNav();
	location.reload();
	return;
}
