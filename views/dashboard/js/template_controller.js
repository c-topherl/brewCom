var targetDiv = "#main-content";
var method="post";

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
                getOpenOrders();
            } else {
                showError(responseObj.message);
            }
        }
    };

    req.send(JSON.stringify(requestData));

    return;
} 


var getOpenOrders = function(){
    var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
    var templatePath = "http://localhost/brewCom/views/dashboard/order_headers.html";

    var requestData = {
        "function": "get_orders",
        "status": "open"
    };

    buildHttpRequestForTemplate(method, url, templatePath, requestData);
    
    displayTable("order-table");
    return;
}

var getOrderDetail = function(orderNumber){
    var url = "http://joelmeister.net/brewCom/controllers/order_controller.php";
    var templatePath = "http://localhost/brewCom/views/dashboard/order_detail.html";

    var requestData = {
        "function": "get_order_detail",
        "order_id": orderNumber
    };

    buildHttpRequestForTemplate(method, url, templatePath, requestData);
    
    displayTable("order-table");
    return;
}

var loadOrderSearch = function(){
    var url = "http://localhost/brewCom/views/dashboard/order_search.html";
    loadTemplate(url, null);
    return;
}

var searchOrders = function(){
    alert("Not implemented yet.");
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

