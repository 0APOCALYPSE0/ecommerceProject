<?php
    require_once $_SERVER["DOCUMENT_ROOT"]."/ecommerceProject/core/db.php";
	$name = sanitize($_POST["full_name"]);
	$email = sanitize($_POST["email"]);
	$street = sanitize($_POST["street"]);
	$street2 = sanitize($_POST["street2"]);
	$city = sanitize($_POST["city"]);
	$state = sanitize($_POST["state"]);
	$zip_code = sanitize($_POST["zip_code"]);
	$country = sanitize($_POST["country"]);
	$errors = array();
	$required = array(
	    "full_name"   =>  "Full Name",
		"email"       =>  "Email",
		"street"      =>  "Street Address",
		"city"        =>  "City",
		"state"       =>  "State",
		"zip_code"    =>  "Zip Code",
		"country"     =>  "Country",
	);

	//check if all required fields are filled out....
	foreach($required as $f => $d){
		if(empty($_POST[$f]) || $_POST[$f] == ""){
			$errors[] = $d." is required.";
		}
	}
	//check email is valid or not...
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$errors[] = "Please enter a valid email.";
	}

	//check zip code is valid or not...
	if(!preg_match('/^[0-9]{6}$/', $zip_code)){
        $errors[] = "Please enter a valid zip code.";
	}

	if(!empty($errors)){
		echo display_error($errors);
	}else{
		echo 0;
	}
?>