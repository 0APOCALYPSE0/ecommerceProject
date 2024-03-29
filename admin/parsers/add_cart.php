<?php
    require_once $_SERVER["DOCUMENT_ROOT"]."/ecommerceProject/core/db.php";
	$product_id = sanitize($_POST["product_id"]);
	$size = sanitize($_POST["size"]);
	$available = sanitize($_POST["available"]);
	$quantity = sanitize($_POST["quantity"]);
	$item = array();
	$item[] = array(
	    "id"        =>  $product_id,
		"size"      =>  $size,
		"quantity"  =>  $quantity,
	);

	$domain = ($_SERVER["HTTP_HOST"] != "localhost") ? '.'.$_SERVER["HTTP_HOST"] : false;
	$query = $conn->query("select * from products where id ='$product_id'");
	$product = mysqli_fetch_assoc($query);
	$_SESSION["success_flash"] = $product["title"]." was added to your cart.";

	//check if the cart cookies exists.....
	if($cart_id != ''){
		$cartQ = $conn->query("select * from cart where id ='$cart_id';");
		$cart = mysqli_fetch_assoc($cartQ);
		$previous_items = json_decode($cart["items"],true);
		$item_match = 0;
		$new_items = array();
		foreach($previous_items as $pitem){
			if($item[0]["id"] == $pitem["id"] && $item[0]["size"] == $pitem["size"]){
				$pitem["quantity"] = $pitem["quantity"] + $item[0]["quantity"];
				if($pitem["quantity"] > $available){
					$pitem["quantity"] = $available;
				}
				$item_match = 1;
			}
			$new_items[] = $pitem;
		}
		if($item_match != 1){
			$new_items = array_merge($item, $previous_items);
		}
		$items_json = json_encode($new_items);
		$cart_expire = date("Y-m-d H:i:s", strtotime("+30 days"));
		$conn->query("update cart set items = '$items_json', expire_date = '$cart_expire' where id = '$cart_id';");
		setcookie(CART_COOKIE, '', 1, "/", $domain, false);
		setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, "/", $domain, false);
	}else{
		//add the cart to database and set cookies...
		$items_json = json_encode($item);
		$cart_expire = date("Y-m-d H:i:s", strtotime("+30 days"));
		$conn->query("insert into cart (items, expire_date) values ('$items_json', '$cart_expire')");
		$cart_id = mysqli_insert_id($conn);

		setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, "/", $domain, false);
	}
?>