<?php

    function display_error($errors){
		$display = "<ul class='bg-danger'>";
		foreach($errors as $error){
			$display .= "<li class='text-danger'>".$error."</li>";
		}
		$display .= "</ul>";
		return $display;
	}
	
	function sanitize($dirty){
		return htmlentities($dirty, ENT_QUOTES, "UTF-8");
	}
	
	function money($number){
		return "$".number_format($number,2);
	}
	
	function login($user_id){
		$_SESSION["SBuser"] = $user_id;
		GLOBAL $conn;
		$date = date("Y-m-d H:i:s");
		$conn->query("update users set last_login = '$date' where id = '$user_id';");
		$_SESSION["success_flash"] = "You are now logged in!";
		header("Location: index.php");
	}
	
	function is_logged_in(){
		if(isset($_SESSION["SBuser"]) && $_SESSION["SBuser"] > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function login_error_redirect($url = "login.php"){
		$_SESSION["error_flash"] = "You must be logged in to access the page!";
		header("Location: ".$url);
	}

function has_permission($permission = "admin"){
	    global $user_data;
		$permissions = explode(',', $user_data["permission"]);
		if(in_array($permission, $permissions, true)){
			return true;
		}else{
			return false;
		}
	}
	
	function permission_error_redirect($url = "login.php"){
		$_SESSION["error_flash"] = "You do not have permission to access that page!";
		header("Location: ".$url);
	}
	
	function pretty_date($date){
		return date("M d, Y h:i A", strtotime($date));
	}
	
	function get_category($subcat_id){
		global $conn;
		$id = sanitize($subcat_id);
		$sql = "select p.id as 'pid', p.category as 'category', c.id as 'cid', c.category as 'subcategory'
		        from categories c inner join categories p
				on c.parent = p.id
				where c.id = '$id';";
		$query = $conn->query($sql);
        $category = mysqli_fetch_assoc($query);
        return $category;		
	}
	
	function sizesToArray($string){
		$sizesArray = explode(',',$string);
		$returnArray = array();
		foreach($sizesArray as $size){
			$s = explode(':',$size);
			$returnArray[] = array("size" => $s[0], "quantity" =>$s[1], "threshold" => $s[2]);
		}
		return $returnArray;
	}
	
	function sizesToString($sizes){
		$sizeString = "";
		foreach($sizes as $size){
			$sizeString .= $size["size"].":".$size["quantity"].":".$size["threshold"].',';
		}
		$trimmed = rtrim($sizeString, ",");
		return $trimmed;
	}
?>



