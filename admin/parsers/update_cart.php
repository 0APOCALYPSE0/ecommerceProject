<?php
    require_once $_SERVER["DOCUMENT_ROOT"]."/ecommerceProject/core/db.php";
	$mode = sanitize($_POST["mode"]);
	$edit_id = sanitize($_POST["edit_id"]);
	$edit_size = sanitize($_POST["edit_size"]);
	$cartQ = $conn->query("select * from cart where id = '$cart_id';");
	$result = mysqli_fetch_assoc($cartQ);
	$items = json_decode($result["items"], true);
	$updated_items = array();
	$domain = (($_SERVER["HTTP_HOST"] != "localhost") ? '.'.$_SERVER["HTTP_HOST"] : false);
	if($mode == "removeone"){
		foreach($items as $item){
			if($item["id"] == $edit_id && $item["size"] == $edit_size){
				$item["quantity"] = $item["quantity"] - 1;
			}
			if($item["quantity"] > 0){
				$updated_items[] = $item;
			}
		}
	}

	if($mode == "addone"){
		foreach($items as $item){
			if($item["id"] == $edit_id && $item["size"] == $edit_size){
				$item["quantity"] = $item["quantity"] + 1;
			}
			$updated_items[] = $item;
		}
	}

	if(!empty($updated_items)){
		$json_updated = json_encode($updated_items);
		$conn->query("update cart set items = '$json_updated' where id = '$cart_id';");
		$_SESSION["success_flash"] = "Your cart has been updated!";
	}

	if(empty($updated_items)){
		$conn->query("delete from cart where id = '$cart_id';");
		setcookie(CART_COOKIE, "", 1, "/", $domain, false);
	}

?>