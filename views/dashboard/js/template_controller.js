var targetDiv = "#main-content";
var method = "post";
var templatePath = "http://localhost:8888/brewCom/views/dashboard/";

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

    var url = "http://joelmeister.net/brewCom/controllers/customer_controller.php";

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
                getOpenOrders();
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


var getOpenOrders = function(){
    var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
    var template = templatePath + "order_headers.html";

    var requestData = {
        "function": "get_orders",
        "status": "open"
    };

    buildHttpRequestForTemplate(method, url, template, requestData);
    
    displayTable("order-table");
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

var loadOrderSearch = function(){
    var url = templatePath + "order_search.html";
    loadTemplate(url, null);
    return;
}

var searchOrders = function(){
    var customerCode = document.getElementById("customer").value;
    var startDate = document.getElementById("start-date").value;
    var endDate = document.getElementById("end-date").value;
    //var deliveryType = document.getElementById("delivery-type").value;
    //var status = document.querySelector("status").value;
    var minAmount = documnet.getElementById("min-amount").value;
    var maxAmount = documnet.getElementById("max-amount").value;

    var requestData = {
        "function": "get_orders",
        "customer": customerCode,
        "start_date": startDate,
        "end_date": endDate,
        "delivery_method": deliveryType,
        "status": status,
        "minimum_amount": minAmount,
        "maximum_amount": maxAmount
    };

    var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
    var template = templatePath + "order_search_results.html";

    buildHttpRequestForTemplate(method, url, template, requestData);

    return;
}

var getCustomerList = function(){
    alert("Not yet implemented!");
    return;
}

var getProductList = function(){
    alert("Not yet implemented!");
}

var getSettings = function(){
    alert("Not yet implemented!");
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

