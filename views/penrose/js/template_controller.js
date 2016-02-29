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

    document.getElementById("customer-code").innerHTML = "Customer: Test";

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


	var data = {
    	orders: [
        	{orderNumber:1234,deliveryDate:"03/06/16",deliveryMethod:"Delivery",totalAmount:189.99},
        	{orderNumber:1286,deliveryDate:"03/08/16",deliveryMethod:"Delivery",totalAmount:18.98},
        	{orderNumber:1225,deliveryDate:"03/04/16",deliveryMethod:"Pickup",totalAmount:94.99},
        	{orderNumber:1235,deliveryDate:"04/06/16",deliveryMethod:"Delivery",totalAmount:243.96},
    	]
	};

    loadTemplate(url, data);
    
    displayTable("order-table");
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

	var data = {
		orderNumber: "1234",
		deliveryMethod: "Delivery",
		deliveryDate: "03/06/2016",
		orderTotal: "846.28",
    	lines: [
        	{product:"Devoir",desc:"Saison Ale",unit:"Keg",price:"89.99",quantity:10},
        	{product:"Devoir",desc:"Saison Ale",unit:"Bottles (12)",price:"12.99",quantity:15},
        	{product:"Desirous",desc:"White IPA",unit:"Bottles (6)",price:"6.99",quantity:35},
        	{product:"Fractal",desc:"Belgian IPA",unit:"Bottles (12)",price:"12.99",quantity:20},
        	{product:"Fractal",desc:"Belgian IPA",unit:"Keg",price:"89.99",quantity:10}
    	]
	};

    loadTemplate(url, data);
    
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

	var data = {
    	deliveryDates: [
        	{value:"20160106",title:"01/06/16"},
        	{value:"20160107",title:"01/07/16"},
        	{value:"20160108",title:"01/08/16"}
    	],
    	deliveryMethods: [
    		{value:0,title:"Delivery"},
    		{value:1,title:"Pickup"}
    	],
    	//value can be warehouse code/id if that's a thing?
    	warehouses: [
    		{value:0,title:"Warehouse A"},
    		{value:1,title:"Warehouse B"}
    	]
	};

	loadTemplate(templatePath, data);

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

	name, type, unit, price
	buildHttpRequest(method, url, loadTemplate, templatePath);
	*/

	//temporary - this will go away
	var url = "http://localhost/brewCom/views/penrose/order.html";

	var data = {
    	products: [
        	{id:0,product:"Devoir",desc:"Saison Ale",unit:"Keg",price:"$89.99"},
        	{id:1,product:"Devoir",desc:"Saison Ale",unit:"Bottles (12)",price:"$12.99"},
        	{id:2,product:"Desirous",desc:"White IPA",unit:"Bottles (6)",price:"$6.99"},
        	{id:3,product:"Fractal",desc:"Belgian IPA",unit:"Bottles (12)",price:"$12.99"},
        	{id:4,product:"Fractal",desc:"Belgian IPA",unit:"Keg",price:"$89.99"}
    	]
	};

    loadTemplate(url, data);
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

    //submit this data object containing lines to back end to be added to cart-overwrite all existing lines
    
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
	var url = "submit_order.php?customer=CUST_CODE";
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

