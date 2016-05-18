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

var comparePasswords = function(password, password2){
	if (password === "" && password2 !== ""){
		showAlert(errorAlert, "Passwords do not match!");
		$('password').focus();
		return false;
	} else if (password !== "" && password2 === ""){
		showAlert(errorAlert, "Passwords do not match!");
		$('password2').focus();
		return false;
	} else if (password !== "" && password2 !== "" 
		&& password !== password2){
		showAlert(errorAlert, "Passwords do not match!");
		$('password2').focus();
		return false;
	}

	return true;
}

var parseGetVariable = function(val) {
    var result = null,
        tmp = [];
    var items = location.search.substr(1).split("&");
    for (var index = 0; index < items.length; index++) {
        tmp = items[index].split("=");
        if (tmp[0] === val) result = decodeURIComponent(tmp[1]);
    }
    return result;
}

var clearUpdateInfoForm = function(){
	$('email').value = "";
	$('password').value = "";
	$('password2').value = "";
	$('username').value = "";
	return;
}

var verifyLogin = function(){
	var username = document.getElementById("username").value;
	if (!username){
		alert("A username is required!");
		return false;
	}
	
	var password = document.getElementById("password").value;
	if (!password){
		alert("A password is required!");
		return false;
	}
}

var buildHttpRequestForTemplate = function(method, url, templatePath, data){
	var req = new XMLHttpRequest();
    req.open(method, url, true);
    hideAlerts();

    req.onreadystatechange = function(){
    	try {
        	if (req.readyState == 4 && req.status == 200){
        		var resp = JSON.parse(req.responseText);
        		if (resp.status === "success"){
	        		loadTemplate(templatePath, resp.response);
        		} else {
	        		showAlert(errorAlert, resp.message);
        		}
        	}
        } catch(err) {
        	showAlert(errorAlert, "An error occurred. Please try again or contact customer service.");
        }
    };

    req.send(JSON.stringify(data));
}

var buildHttpRequest = function(method, url, data, callback, callbackParam){
	var req = new XMLHttpRequest();
    req.open(method, url, true);
    hideAlerts();

    req.onreadystatechange = function(){
    	try {
    		if (req.readyState == 4 && req.status == 200){
	    		var resp = JSON.parse(req.responseText);
    			if (resp.status === "success"){
	        		if (callback){
        				callback(callbackParam);
        			}
        		} else {
	        		showAlert(errorAlert, resp.message);
        		}
    		}
    	} catch(err) {
    		showAlert(errorAlert, "An error occurred. Please try again or contact customer service.");
    	}
    }
    req.send(JSON.stringify(data));
}

var hideMobileNav = function() {
	var mobileNav = document.getElementById('simple-menu');
	mobileNav.className += " hidden";

	mobileNav = document.getElementById('sidr-div');
	mobileNav.className = "hidden";

	return;
}

var showMobileNav = function() {
	var mobileNav = document.getElementById('simple-menu');
	var classes = mobileNav.className.split(" ");
	classes.pop();
	mobileNav.className = classes.join(" ");

	mobileNav = document.getElementById('sidr-div');
	mobileNav.className = "";

	return;
}

//add "hidden" class to all navlinks
var hideNavLinks = function(){
	var navLinks = document.getElementsByClassName("nav-link");
	var i;

	for (i = 0; i < navLinks.length; i++){
		navLinks[i].className = navLinks[i].className + " hidden";
	}

	return;
}

//remove "hidden" class from all navlinks
var showNavLinks = function(){
	var navLinks = document.getElementsByClassName("nav-link");
	var i;
	var classes;
	var index;

	for (i = 0; i < navLinks.length; i++){
		classes = navLinks[i].className.split(" ");
		index = classes.indexOf("hidden");
		if (index > -1){
			classes.splice(index, 1);
		}
		navLinks[i].className = classes.join(" ");
	}

	showMobileNav();
	
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
