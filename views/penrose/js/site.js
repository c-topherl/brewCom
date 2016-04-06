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
	
	return;
}

var displayTables = function(){
	var tableList = [
		"order-table",
		"cart-table"
	];

	var table;
	for (var i = 0; i < tableList.length; i++){
		table = document.getElementById(tableList[i]);
		if (table){
			displayTable(tableList[i]);
			break;
		}
	}

	return;
}
