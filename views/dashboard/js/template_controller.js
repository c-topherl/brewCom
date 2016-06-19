var targetDiv = "#main-content";
var method = "post";
var templatePath = "http://localhost:8888/brewCom/views/dashboard/";
var requestUrl = "http://joelmeister.net/brewCom/controllers/"

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

    var requestData = {
        "function": "get_orders",
        "status": "open"
    };

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
    var template = templatePath + "order_detail.html";

    var requestData = {
        "function": "get_order_detail",
        "order_id": orderNumber
    };

    buildHttpRequestForTemplate(method, url, template, requestData);
    
    displayTable("order-table");
    return;
}

var getCustomerDetail = function(customerId){
    var url = requesturl + "customer_controller.php";
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

var deleteLine = function(lineNumber){
    var userId = getCookie('userId');
    var url = requestUrl + "order_controller.php";

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

