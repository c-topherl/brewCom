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

var getOpenOrders = function(){
    /*
    REQUEST TO GET OPEN ORDERS
    
    var method = "get";
    var url = "get_open_orders.php";
    var templatePath = "http://localhost/brewCom/views/penrose/open_orders.html";

    buildHttpRequest(method, url, loadTemplate, templatePath);
    */

    //temporary - this will go away
    var url = "http://localhost/brewCom/views/dashboard/order_headers.html";


    var data = {
        orders: [
            {orderNumber:1234,orderStatus:"Open",createDate:"03/04/16",deliveryDate:"03/06/16",deliveryMethod:"Delivery",totalAmount:189.99},
            {orderNumber:1286,orderStatus:"Open",createDate:"03/07/16",deliveryDate:"03/08/16",deliveryMethod:"Delivery",totalAmount:18.98},
            {orderNumber:1225,orderStatus:"Shipped",createDate:"03/03/16",deliveryDate:"03/04/16",deliveryMethod:"Pickup",totalAmount:94.99},
            {orderNumber:1235,orderStatus:"Open",createDate:"04/01/16",deliveryDate:"04/06/16",deliveryMethod:"Delivery",totalAmount:243.96}
        ]
    };

    loadTemplate(url, data);
    
    displayTable("order-table");
    return;
}

var getOrderDetail = function(orderNumber){
    /*
    REQUEST TO GET ORDER DETAIL
    */
    var url = "http://localhost/brewCom/views/dashboard/order_detail.html";

    var data = {
        orderNumber: "1234",
        billToName: "Frank's Bar",
        billToAddressOne: "999 West St.",
        billToAddressTwo: "Chicago, IL",
        shipToName: "Frank's Bar",
        shipToAddressOne: "999 West St.",
        shipToAddressTwo: "Chicago, IL",
        orderStatus: "Open",
        deliveryMethod: "Delivery",
        deliveryDate: "03/06/2016",
        orderTotal: "846.28",
        shippingComments: "Arrive by 9am",
        generalComments: "Stop in for a beer!",
        lines: [
            {product:"Devoir",desc:"Saison Ale",unit:"Keg",unitPrice:"89.99",orderQuantity:10,totalLineAmount:"900.00",id:0},
            {product:"Devoir",desc:"Saison Ale",unit:"Bottles (12)",unitPrice:"12.99",orderQuantity:15,totalLineAmount:"220.00",id:1},
            {product:"Desirous",desc:"White IPA",unit:"Bottles (6)",unitPrice:"6.99",orderQuantity:35,totalLineAmount:"240.00",id:2},
            {product:"Fractal",desc:"Belgian IPA",unit:"Bottles (12)",unitPrice:"12.99",orderQuantity:20,totalLineAmount:"260.00",id:3},
            {product:"Fractal",desc:"Belgian IPA",unit:"Keg",unitPrice:"89.99",orderQuantity:10,totalLineAmount:"900.00",id:4}
        ]
    };

    loadTemplate(url, data);
    
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

