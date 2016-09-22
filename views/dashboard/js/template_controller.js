var targetDiv = "#main-content";
var method = "post";
var templatePath = "http://localhost:8888/brewCom/views/dashboard/";
var requestUrl = "http://joelmeister.net/brewCom/controllers/";
var orderStatusOptions = ['Void', 'Shipped', 'Open'];
var deliveryMethodOptions = ['Delivery', 'Pick-up'];
var orderDetailFields = [
    "bill_to_name",
    "bill_to_address_one",
    "bill_to_address_two",
    "bill_to_city",
    "bill_to_state",
    "bill_to_zip",
    "ship_to_name",
    "ship_to_address_one",
    "ship_to_address_two",
    "ship_to_city",
    "ship_to_state",
    "ship_to_zip",
    "delivery_date",
    "delivery_method",
    "total_amount",
    "shipping_comments",
    "comments"
];

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

    var url = requestUrl + "customer_controller.php";

    var req = new XMLHttpRequest();
    req.open(method, url, true);

    var template;
    var data;

    req.onreadystatechange = function(){
        if (req.readyState == 4 && req.status == 200){
            var responseObj = JSON.parse(req.responseText);
            removeCookies();

            if (responseObj.status === "success"){
                userId = responseObj.response.user_id;
                token = responseObj.response.token;

                if (userId){
                    setCookie("userId", userId, 1);
                }
                if (token){
                    setCookie("token", token, 1);
                }
                showNavLinks();
                getOrderList();
                return;
            } else {
                showError(responseObj.message);
                return;
            }
        }
    };

    req.send(JSON.stringify(requestData));

    return;
} 


var getOrderList = function(){
    var url = requestUrl + "order_controller.php";
    var template = templatePath + "order_list.html";

    // var requestData = {
    //     "function": "get_orders",
    //     "status": "open"
    // };

    var requestData = {
        "function": "get_orders"
    }

    buildHttpRequestForTemplate(method, url, template, requestData);
    
    displayTable("order-table");
    return;
}

var getCustomerList = function(){
    var url = requestUrl + "customer_controller.php";
    var template = templatePath + "customer_list.html";
    var requestData = {
        "function": "get_customers"
    };

    buildHttpRequestForTemplate(method, url, template, requestData);
    return;
}

var getProductList = function(){
    var url = requestUrl + "product_controller.php";
    var template = templatePath + "product_list.html";
    var requestData = {
        "function": "get_products"
    };

    buildHttpRequestForTemplate(method, url, template, requestData);
    return;
}

var getSettings = function(){
    var url = templatePath + "settings.html";
    loadTemplate(url, null);
    return;
}

var getOrderDetail = function(orderNumber){
    var url = requestUrl + "order_controller.php";

    var requestData = {
        "function": "get_order_detail",
        "order_id": orderNumber
    };

    buildHttpRequestCustomParse(method, url, requestData, buildOrderDetail);
    return;
}

var buildOrderDetail = function(jsonResponse){
    var template = templatePath + "order_detail.html";
    var templateData = {
        "billToName": jsonResponse.bill_to_name,
        "billToAddressOne": jsonResponse.bill_to_addr1,
        "billToAddressTwo": jsonResponse.bill_to_addr2,
        "billToCity": jsonResponse.bill_to_city,
        "billToState": jsonResponse.bill_to_state,
        "billToZip": jsonResponse.bill_to_zip,
        "shipToName": jsonResponse.ship_to_name,
        "shipToAddressOne": jsonResponse.ship_to_addr1,
        "shipToAddressTwo": jsonResponse.ship_to_addr2,
        "shipToCity": jsonResponse.ship_to_city,
        "shipToState": jsonResponse.ship_to_state,
        "shipToZip": jsonResponse.ship_to_zip,
        "deliveryDate": jsonResponse.delivery_date,
        "totalAmount": jsonResponse.total_price,
        "shippingComments": jsonResponse.shipping_comments,
        "comments": jsonResponse.comments,
        "lines": jsonResponse.lines,
        "orderId": jsonResponse.order_id
    };

    templateData.status = {
        "current": jsonResponse.status_description,
        "options": parseRemainingOptions(jsonResponse.status_description, orderStatusOptions)
    };

    templateData.deliveryMethod = {
        "current": jsonResponse.delivery_method,
        "options": parseRemainingOptions(jsonResponse.delivery_method, deliveryMethodOptions)
    };

    loadTemplate(template, templateData);

    return;
}

var parseRemainingOptions = function(selected, optionsList){
    var remainingOptions = [];
    for(var i=0; i < optionsList.length; i++){
        if(optionsList[i] != selected){
            remainingOptions.push(optionsList[i]);
        }
    }

    return remainingOptions;
}

var getProducts = function(){
    var url = requestUrl + "product_controller.php";
    var requestData = {
        "function": "get_products"
    };

    buildHttpRequestCustomParse(method, url, requestData, addLinePrompt);
    return;
}

var addLinePrompt = function(resp){
    var product_list = resp.products;
    var current_id;
    var lines = getCurrentLines();

    for(var i = 0; i < product_list.length; i++){
        for(var j = 0; j < lines.length; j++){
            if(lines[j].product_code === product_list[i].product_code){
                product_list.splice(i, 1);
            }
        }
    }

    var source   = $("#entry-template").html();
    var template = Handlebars.compile(source);
    var html = template({"products": product_list});
    $("#dialog").html(html);
    $("#dialog").dialog("open");

    return;
}

var getCustomerDetail = function(customerId){
    var url = requestUrl + "customer_controller.php";
    var template = templatePath + "customer_detail.html"

    var requestData = {
        "function": "get_customer_detail",
        "customer_id": customerId
    };

    //buildHttpRequestForTemplate(method, url, template, requestData);
    //displayTable("customer_table");
    alert("Not yet implemented");
    return;
}

var getProductDetail = function(productId){
    var url = requestUrl + "product_controller.php";
    var template = templatePath + "product_detail.html"

    var requestData = {
        "function": "get_product_detail",
        "product_id": productId
    };

    //buildHttpRequestForTemplate(method, url, template, requestData);
    //displayTable("product_table");
    alert("Not yet implemented");
    return;
}

var addProduct = function(){
    alert("Not yet implemented.");
    $("#dialog").dialog("close");
}

var loadOrderSearch = function(){
    var url = templatePath + "order_search.html";
    loadTemplate(url, null);
    return;
}

var loadCustomerSearch = function(){
    alert("Not yet implemented.");
}

var searchOrders = function(){
    var url = requestUrl + "order_controller.php";
    var template = templatePath + "order_list.html";

    var customerCode = document.getElementById("customer").value;
    var startDate = document.getElementById("start-date").value;
    var endDate = document.getElementById("end-date").value;
    //var deliveryType = document.getElementById("delivery-type").value;
    //var status = document.querySelector("status").value;
    var minAmount = document.getElementById("min-amount").value;
    var maxAmount = document.getElementById("max-amount").value;

    var requestData = {
        "function": "get_orders",
        "customer": customerCode,
        "start_date": startDate,
        "end_date": endDate,
        //"delivery_method": deliveryType,
        //"status": status,
        "minimum_amount": minAmount,
        "maximum_amount": maxAmount
    };

    buildHttpRequestForTemplate(method, url, template, requestData);

    return;
}

var searchCustomers = function(){
    var url = requestUrl + "customer_controller.php";
    var template = templatePath + "order_list.html";

    var requestData = {
        "function": "get_customers"
    };

    //buildHttpRequestForTemplate(method, url, template, requestData);
    alert("Not yet implemented.");
    return;
}

var getCurrentLines = function(){
    var lines = [];
    var currentLine;
    var rows = document.getElementById("lines").childNodes;
    for(var i = 0; i < rows.length; i++){
        row = rows[i];
        if(row.tagName === "TR"){
            currentLine = {
                "line_id": row.id,
                "product_code": document.getElementById("code_" + row.id).innerHTML,
                "unit_code": document.getElementById("unit_" + row.id).innerHTML,
                "quantity": document.getElementById("quantity_" + row.id).value,
                "unit_price": document.getElementById("price_" + row.id).value,
                "line_price": document.getElementById("total_" + row.id).innerHTML,
                "product_description": document.getElementById("desc_" + row.id).innerHTML
            };
            lines.push(currentLine);
        }
    };

    return lines;
}

var updateOrder = function(id){
    var url = requestUrl + "order_controller.php";
    var lines = getCurrentLines();

    var requestData = {
        "function": "update_order",
        "order_id": id,
        "lines": lines,
        "bill_to_name": document.getElementById("bill_to_name").value,
        "bill_to_addr1": document.getElementById("bill_to_addr1").value,
        "bill_to_addr2": document.getElementById("bill_to_addr2").value,
        "bill_to_city": document.getElementById("bill_to_city").value,
        "bill_to_state": document.getElementById("bill_to_state").value,
        "bill_to_zip": document.getElementById("bill_to_zip").value,
        "ship_to_name": document.getElementById("ship_to_name").value,
        "ship_to_addr1": document.getElementById("ship_to_addr1").value,
        "ship_to_addr2": document.getElementById("ship_to_addr2").value,
        "ship_to_city": document.getElementById("ship_to_city").value,
        "ship_to_state": document.getElementById("ship_to_state").value,
        "ship_to_zip": document.getElementById("ship_to_zip").value,
        "delivery_date": document.getElementById("delivery_date").value,
        "total_amount": document.getElementById("total_amount").value,
        "shipping_comments": document.getElementById("shipping_comments").value,
        "comments": document.getElementById("comments").value,
        "status": $("#status :selected").text(),
        "delivery_method": getDeliveryCodeFromDescription($("#delivery_method :selected").text()),
        "delivery_date": document.getElementById("delivery_date").value
    };

    buildHttpRequest(method, url, requestData, showConfirmation, "Order updated successfully.");
    return;
}