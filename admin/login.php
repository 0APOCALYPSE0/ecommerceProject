<?php

	include "includes/header.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/ecommerceProject/core/db.php";
	//backend code ====
	$email = ((isset($_POST["email"])) ? sanitize($_POST["email"]) : '');
	$email = rtrim($email);
	$password = ((isset($_POST["password"])) ? sanitize($_POST["password"]) : '');
	$password = rtrim($password);
	$errors = array();

?>
    <style>
	    body{
			background-image: url("/ecommerceProject/images/headerlogo/background.png");
			background-size: 100vw 100vh;
			background-attachment: fixed;
		}
	</style>

    <div id="login-form">
	    <div>
		    <?php
			    if($_POST){
					//form validation...
					if(empty($_POST["email"]) || empty($_POST["password"])){
						$errors[] = "You must provide email and password.";
					}
					//validate email...
					if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
						$errors[] = "You must enter a valid email.";
					}
					//password must more than 6 character...
					if(strlen($password) < 6){
						$errors[] = "Password must be atleast 6 characters.";
					}
					//check if email exist in the databse...
					$emailSql = "select * from users where email = '$email';";
					$emailResult = mysqli_query($conn, $emailSql);
					$emailCount = mysqli_num_rows($emailResult);
					$user = mysqli_fetch_assoc($emailResult);
					if($emailCount < 1){
						$errors[] = "This email doesn't exist in our databse.";
					}
					
					//password verification...
					if(!password_verify($password, $user["password"])){
						$errors[] = "The password doesn't match our records. Please try again.";
					}

					//check errors...
					if(!empty($errors)){
						echo display_error($errors);
					}else{
						// log user in...
						$user_id = $user["id"];
						login($user_id);
					}
				}
			?>
		</div>
    	<h2 class="text-center">Login Page</h2>
    	<form action="login.php" method="POST">
    		<div class="form-group">
			    <label for="email">Email: </label>
				<input type="text" name="email" class="form-control" id="email" value="<?=$email;?>">
			</div>
    		<div class="form-group">
			    <label for="password">Password: </label>
				<input type="password" name="password" class="form-control" id="password" value="<?=$password;?>">
			</div>
    		<div class="form-group">
			    <input type="submit" name="submit" value="Login" class="btn btn-primary">
			</div>
    	</form>
		<p class="text-right"><a href="/ecommerceProject/index.php" alt="home">Visit Site</a></p>
    </div>

<?php
    include "includes/footer.php";
?>
