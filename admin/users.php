<?php
    require_once "../core/db.php";
	
	if(!is_logged_in()){
		login_error_redirect();
	}
	if(!has_permission("admin")){
		permission_error_redirect("index.php");
	}
	
	include "includes/header.php";
	include "includes/navigation.php";

	//delete user...
	if(isset($_GET["delete"])){
		$delete_id = sanitize($_GET["delete"]);
		$conn->query("delete from users where id = '$delete_id'");
		$_SESSION["success_flash"] = "User has been deleted!";
		header("Location: users.php");
	}
	
	//add new user...
	if(isset($_GET["add"])){
		$name = ((isset($_POST["name"])) ? sanitize($_POST["name"]) : '');
	    $email = ((isset($_POST["email"])) ? sanitize($_POST["email"]) : '');
		$password = ((isset($_POST["password"])) ? sanitize($_POST["password"]) : '');
		$confirm = ((isset($_POST["confirm"])) ? sanitize($_POST["confirm"]) : '');
		$permissions = ((isset($_POST["permissions"])) ? sanitize($_POST["permissions"]) : '');
		
		$errors = array();
		if($_POST){
			
			//check if email exist in the databse...
			$emailSql = "select * from users where email = '$email';";
			$emailResult = mysqli_query($conn, $emailSql);
			$emailCount = mysqli_num_rows($emailResult);
			$user = mysqli_fetch_assoc($emailResult);
			if($emailCount != 0){
				$errors[] = "This email is already exist in our databse.";
			}
			
			//check all fields are filled...
			$required = array('name', 'email', 'password', 'confirm', 'permissions');
			foreach($required as $f){
				if(empty($_POST[$f])){
					$errors[] = "You must fill out all fields.";
					break;
				}
			}
			
			//validate email...
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$errors[] = "You must enter a valid email.";
			}
			
			//password must more than 6 character...
			if(strlen($password) < 6){
				$errors[] = "Password must be atleast 6 characters.";
			}
			
			//if new password matches confirm...
			if($password != $confirm){
				$errors[] = "The new password and confrim new password does not match!";
			}
			
			//display errors...
			if(!empty($errors)){
				echo display_error($errors);
			}else{
				//add user to databse....
				$hashed = password_hash($password, PASSWORD_DEFAULT);
				$conn->query("insert into users(full_name, email, password, permission) values('$name', '$email', '$hashed', '$permissions')");
				$_SESSION["success_flash"] = "User has been added.";
				header("Location: users.php");
			}
		}
		?>
		
		<h2 class="text-center">Add A New User</h2>
		<form action="users.php?add=1" method="POST">
		    <div class="form-group col-md-6">
		    	<label for="name">Name: </label>
		    	<input type="text" name="name" class="form-control" id="name" value="<?=$name;?>">
			</div>
			<div class="form-group col-md-6">
				<label for="email">Email: </label>
				<input type="text" name="email" class="form-control" id="email" value="<?=$email;?>">
			</div>
			<div class="form-group col-md-6">
				<label for="password">Password: </label>
				<input type="password" name="password" class="form-control" id="password" value="<?=$password;?>">
			</div>
			<div class="form-group col-md-6">
				<label for="confirm">Confirm Password: </label>
				<input type="password" name="confirm" class="form-control" id="confirm" value="<?=$confirm;?>">
		    </div>
			<div class="form-group col-md-6">
				<label for="permissions">Permissions: </label>
				<select name="permissions" class="form-control">
					<option value=""<?=(($permissions == '') ? "selected" : '');?>></option>
					<option value="editor"<?=(($permissions == 'editor') ? "selected" : '');?>>Editor</option>
					<option value="admin,editor"<?=(($permissions == 'admin,editor') ? "selected" : '');?>>Admin</option>
				</select>
			</div>
		   <div class="form-group col-md-6 text-right" style="margin-top:28px;">
		        <a href="users.php" class="btn btn-default">Cancel</a>
				<input type="submit" value="Add User" class="btn btn-primary">
		   </div>
		</form>
		
		<?php
	}else{
	$userQuery = $conn->query("select * from users order by full_name");
?>
     
	<h2 class="text-center">Users</h2>
	<a href="users.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add New User</a>
	<hr>
	<table class="table table-condensed table-bordered table-stripped">
	    <thead>
	    	<th></th>
	    	<th>Name</th>
	    	<th>Email</th>
	    	<th>Join Date</th>
	    	<th>Last Login</th>
	    	<th>Permission</th>
	    </thead>
	    <tbody>
		<?php while($user = mysqli_fetch_assoc($userQuery)): ?>
	    	<tr>
	    		<td>
				    <?php if($user["id"] != $user_data["id"]): ?>
					<a href="users.php?delete=<?=$user["id"];?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
					<?php endif; ?>
				</td>
	    		<td><?=$user["full_name"];?></td>
	    		<td><?=$user["email"];?></td>
	    		<td><?=pretty_date($user["join_date"]);?></td>
	    		<td><?=(($user["last_login"] == '0000-00-00 00:00:00') ? "Never" : pretty_date($user["last_login"]));?></td>
	    		<td><?=$user["permission"];?></td>
	    	</tr>
		<?php endwhile; ?>	
	    </tbody>
	</table>
	 
<?php
    } include "includes/footer.php";
?>