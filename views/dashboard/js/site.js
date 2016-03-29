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

var comparePasswords = function(password2){
	var password1 = document.getElementById("password");
	if (password1.value !== password2.value){
		alert("Passwords do not match!");
		password2.focus();
	}

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

var buildHttpRequestForTemplate = function(method, url, templatePath){
	var req = new XMLHttpRequest();
    req.open(method, url, true);

    req.onreadystatechange = function(){
        if (req.readyState == 4 && req.status == 200){
        	loadTemplate(templatePath, req.responseText);
        }
    };

    req.send();
}

var buildHttpRequest = function(method, url, data, callback){
	var req = new XMLHttpRequest();
	req.open(method, url, true);
	req.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
	req.onreadystatechange = function(){
        if (req.readyState == 4 && req.status == 200 && callback){
        	callback();
        }
    };
    if (data){
		req.send(JSON.stringify(data));
	} else {
		req.send();
	}
	return;
}

var displayTable = function(tableName){
	tableName = "#" + tableName;
	$(tableName).dataTable();
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

	document.getElementById("customer-code").innerHTML = "Customer: Test";

	return;
}

var removeLine = function(lineNumber) {
	alert("This doesn't work yet!");
}

var updateLine = function(lineNumber) {
	var qty = document.getElementById("quantity_" + lineNumber).value;
	var price = document.getElementById("price_" + lineNumber).value;
	var newTotal = qty * price;
	newTotal = parseFloat(Math.round(newTotal * 100) / 100).toFixed(2);
	document.getElementById("total_" + lineNumber).innerHTML = "$" + newTotal;
	return;
}