var errorAlert = "error-message";
var warningAlert = "warning-message";
var infoAlert = "info-message";
var successAlert = "success-message";
var alertList = [
	errorAlert,
	warningAlert,
	infoAlert,
	successAlert
];

var deliveryMethodCodeMappings = {
	"Pick-up": "pu",
	"Standard Delivery": "stnd",
	"One Day Delivery": "1day"
};

var getDeliveryCodeFromDescription = function(description){
	return deliveryMethodCodeMappings[description];
}

var validateQuantity = function(quantityField){
	var quantity = quantityField.value;
	if (isNaN(quantity) || quantity < 0){
		alert("You must enter a valid number!");
		quantityField.focus();
	}
	return;
}

var validateEmail = function(emailField){
	var pattern = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
	var email = emailField.value;
	if (!email){
		return;
	}

	if (!pattern.test(email)){
		alert("You must enter a valid email address!");
		emailField.focus();
	}

	return;
}

var validateDate = function(dateField){
	var pattern = /[0-9]{4}-[0-9]{2}-[0-9]{2}/;
	var date = dateField.value;
	if (!date){
		return;
	}

	if (!pattern.test(date)){
		alert("You must enter a valid delivery date of format YYYY-MM-DD!");
		dateField.focus();
	}

	return;
}

var comparePasswords = function(password2){
	var password1 = document.getElementById("password");
	if (password1.value !== password2.value){
		alert("Passwords do not match!");
		password2.focus();
	}

	return;
}

/*
 * Sends HTTP request and plugs response directly into template
 */
var buildHttpRequestForTemplate = function(method, url, templatePath, data){
	var req = new XMLHttpRequest();
    req.open(method, url, true);

    req.onreadystatechange = function(){
        if (req.readyState == 4 && req.status == 200){
        	var resp = JSON.parse(req.responseText);
        	loadTemplate(templatePath, resp.response);
        }
    };

    req.send(JSON.stringify(data));
}

/*
 * Sends HTTP request and calls custom function when done
 */
var buildHttpRequest = function(method, url, data, callback, callbackParam){
	var req = new XMLHttpRequest();
    req.open(method, url, true);

    req.onreadystatechange = function(){
    	if (req.readyState == 4 && req.status == 200){
    		if (callback){
    			callback(callbackParam);
    		}
    	}
    }
    req.send(JSON.stringify(data));
}


/*
 * Sends HTTP request and calls custom function with JSON response
 */
var buildHttpRequestCustomParse = function(method, url, data, callback){
	var req = new XMLHttpRequest();
	req.open(method, url, true);

	req.onreadystatechange = function(){
    	if (req.readyState == 4 && req.status == 200){
    		if (callback){
    			callback(JSON.parse(req.responseText).response);
    		}
    	}
    }
    req.send(JSON.stringify(data));
}

var displayTable = function(tableName){
	tableName = "#" + tableName;
	$(tableName).dataTable();
}

var hideNavLinks = function(){
	var nav = document.getElementById('main-nav');
	nav.className += " hidden";

	return;
}

var showNavLinks = function(){
	var nav = document.getElementById('main-nav');
	var classes = nav.className.split(" ");
	classes.pop();
	nav.className = classes.join(" ");

	return;
}

var removeLine = function(id){
	table = document.getElementById("lines");
	row = document.getElementById(id);
	if(table && row){
		table.removeChild(row);
	}
}

var updateLine = function(lineNumber) {
	var qty = document.getElementById("quantity_" + lineNumber).value;
	if (qty === "0"){
		removeLine(lineNumber);
		return;
	}
	
	var price = document.getElementById("price_" + lineNumber).value;
	var newTotal = qty * price;
	newTotal = parseFloat(Math.round(newTotal * 100) / 100).toFixed(2);
	document.getElementById("total_" + lineNumber).innerHTML = "$" + newTotal;
	
	return;
}

var showConfirmation = function(message){
	showAlert(successAlert, message);
	return;
}

var showAlert = function(alertId, message){
	if (isAlertDisplayed(errorAlert)){
		hideAlert(errorAlert);
	}

	var alert = document.getElementById(alertId);
	if (alert){
		var classes = alert.className.split(" ");
		classes.pop();
		alert.className = classes.join(" ");
		alert.innerHTML = message;
	}
	return;
}

var hideAlerts = function(){
	var i;
	var alert;

	for (i = 0; i < alertList.length; i++){
		alert = alertList[i];
		if (isAlertDisplayed(alert)){
			hideAlert(alert);
		}
	}
	return;
}

var hideAlert = function(alertId){
	var alert = document.getElementById(alertId);
	if (alert){
		var classes = alert.className.split(" ");
		classes.push("hidden");
		alert.className = classes.join(" ");
	}
	return;
}

var isAlertDisplayed = function(alertId){
	var alert = document.getElementById(alertId);
	if (alert && alert.className.split(" ").indexOf("hidden") == -1){
		return true;
	}

	return false;
}

var setCookie = function(name, val, numDays){
	var d = new Date();
	d.setTime(d.getTime() + (numDays*24*60*60*1000));
	var expires = "expires=" + d.toUTCString();
	document.cookie = name + "=" + val + "; " + expires;
	return;
}

var getCookie = function(cname) {
	var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
}

var removeCookies = function() {
	setCookie("userId", "", -1);
	setCookie("token", "", -1);
	return;
}