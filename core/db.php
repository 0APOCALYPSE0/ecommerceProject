<?php

    $serverName = "localhost";
	$userName = "root";
	$password = "";
	$dbName = "ecommerce";
	
	$conn = mysqli_connect($serverName, $userName, $password, $dbName);
	if(!$conn){
		echo "Database connection failed due to following error: ".mysqli_connect_errno();
		die();
	}
	session_start();
	
	define("URL", "/MyPhpFolder/");
	
	
    require_once $_SERVER["DOCUMENT_ROOT"]."/MyPhpFolder/ecommerceProject/config.php";
	require_once BASEURL."helpers/helper.php";
	require BASEURL."vendor/autoload.php";
     	
	$cart_id = "";
	if(isset($_COOKIE[CART_COOKIE])){
		$cart_id = sanitize($_COOKIE[CART_COOKIE]);
	}
	
	if(isset($_SESSION["SBuser"])){
		$user_id = $_SESSION["SBuser"];
		$query = $conn->query("select * from users where id = '$user_id';");
		$user_data = mysqli_fetch_assoc($query);
		$fn = explode(' ', $user_data["full_name"]);
		$user_data["first"] = $fn[0];
		$user_data["last"] = $fn[1];
	}
	
	if(isset($_SESSION["success_flash"])){
		echo "<div class='bg-success'><p class='text-success text-center'>".$_SESSION['success_flash']."</p></div>";
		unset($_SESSION["success_flash"]);
	}
	
	if(isset($_SESSION["error_flash"])){
		echo "<div class='bg-danger'><p class='text-danger text-center'>".$_SESSION['error_flash']."</p></div>";
		unset($_SESSION["error_flash"]);
	}
	
?>