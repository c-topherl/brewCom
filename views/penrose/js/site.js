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