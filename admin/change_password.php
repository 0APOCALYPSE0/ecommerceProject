<?php
    
	include "includes/header.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/MyPhpFolder/ecommerceProject/core/db.php";
	if(!is_logged_in()){
		login_error_redirect();
	}
	//backend code ====
	$hashed = $user_data["password"];
	$old_password = ((isset($_POST["old_password"])) ? sanitize($_POST["old_password"]) : '');
	$old_password = rtrim($old_password);
	$password = ((isset($_POST["password"])) ? sanitize($_POST["password"]) : '');
	$password = rtrim($password);
	$confirm = ((isset($_POST["confirm"])) ? sanitize($_POST["confirm"]) : '');
	$confirm = rtrim($confirm);
	$new_hashed = password_hash($password, PASSWORD_DEFAULT);
	$user_id = $user_data["id"];
	$errors = array();
	
?>
    <style>
	    body{
			background-image: url("/MyPhpFolder/ecommerceProject/images/headerlogo/background.png");
			background-size: 100vw 100vh;
			background-attachment: fixed;
		}
	</style>

    <div id="login-form">
	    <div>
		    <?php
			    if($_POST){
					//form validation...
				if(empty($_POST["old_password"]) || empty($_POST["password"]) || empty($_POST["confirm"])){
						$errors[] = "You must fill out all field.";
					}
					
					//password must more than 6 character...
					if(strlen($password) < 6){
						$errors[] = "Password must be atleast 6 characters.";
					}
					
					//if new password matches confirm...
					if($password != $confirm){
						$errors[] = "The new password and confrim new password does not match!";
					}
					
					//password verification...
					if(!password_verify($old_password, $hashed)){
						$errors[] = "Your old password does not match our records.";
					}
					
					//check errors...
					if(!empty($errors)){
						echo display_error($errors);
					}else{
						// change password...
						$conn->query("update users set password = '$new_hashed' where id ='$user_id'");
						$_SESSION["success_flash"] = "Your password has been changed!";
						header("Location: index.php");
					}
				}
			?>
		</div>
    	<h2 class="text-center">Change Password Page</h2>
    	<form action="change_password.php" method="POST">
    		<div class="form-group">
			    <label for="old_password">Old Password: </label>
				<input type="password" name="old_password" class="form-control" id="old_password" value="<?=$old_password;?>">
			</div>
    		<div class="form-group">
			    <label for="password">New Password: </label>
				<input type="password" name="password" class="form-control" id="password" value="<?=$password;?>">
			</div>
			<div class="form-group">
			    <label for="confirm">Confirm New Password: </label>
				<input type="password" name="confirm" class="form-control" id="confirm" value="<?=$confirm;?>">
			</div>
    		<div class="form-group">
			    <a href="index.php" class="btn btn-default">Cancel</a>
			    <input type="submit" name="submit" value="Login" class="btn btn-primary">
			</div>
    	</form>
		<p class="text-right"><a href="/MyPhpFolder/ecommerceProject/index.php" alt="home">Visit Site</a></p>
    </div>

<?php
    include "includes/footer.php";
?>
old_password