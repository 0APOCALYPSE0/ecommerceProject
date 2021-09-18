<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/ecommerceProject/core/db.php";
	include "includes/header.php";
	include "includes/navigation.php";
	if(!is_logged_in()){
		login_error_redirect();
	}

	//Back end code ========
	$sql = "select * from categories where parent =0;";
	$result = mysqli_query($conn, $sql);
	$errors = array();
	$category ="";
	$parent ="";

		//edit category...
	if(isset($_GET["edit"]) && !empty($_GET["edit"])){
		$edit_id = (int)$_GET["edit"];
		$edit_id = sanitize($edit_id);
		$edit_sql = "select * from categories where id='$edit_id';";
		$edit_result = mysqli_query($conn, $edit_sql);
		$edit_category = mysqli_fetch_assoc($edit_result);

	}
	//delete category...
	if(isset($_GET["delete"]) && !empty($_GET["delete"])){
		$delete_id = (int)$_GET["delete"];
		$delete_id = sanitize($delete_id);
		$sql = "select * from categories where id='$delete_id';";
		$result = mysqli_query($conn, $sql);
		$category = mysqli_fetch_assoc($result);
		if($category["parent"] == 0){
			$sql = "delete from categories where parent ='$delete_id';";
			$result = mysqli_query($conn, $sql);
		}
		$dsql = "delete from categories where id='$delete_id';";
		$dresult = mysqli_query($conn, $dsql);
		header('Location: categories.php');
		}
	//form process
	if(isset($_POST["submit"]) && !empty($_POST["submit"])){
		$parent = sanitize($_POST["parent"]);
		$category = sanitize($_POST["category"]);
		$sqlform = "select * from categories where parent = '$parent' and category = '$category';";
		if(isset($_GET["edit"])){
			$id = $edit_category["id"];
			$sqlform = "select * from categories where parent = '$parent' and category = '$category' and id != '$id';";
		}
		$fresult = mysqli_query($conn, $sqlform);
		$count = mysqli_num_rows($fresult);
		//if category is blank...
		if($category == ""){
			$errors[] = "The category can not be left blank!";
		}
		//if category is already exit in database...
		if($count>0){
			$errors[] .= $category." already exists. Please choose another category!";
		}
		//Display errors and update database...
		if(!empty($errors)){
		    $display = display_error($errors); ?>
			<script>
			    $('document').ready(function(){
					$('#errors').html("<?php echo $display; ?>");
				});
			</script>
		<?php
		}else{
			//update database...
			$updateSql = "insert into categories (category, parent) values('$category', '$parent');";
			if(isset($_GET["edit"])){
			    $updateSql = "update categories set category = '$category', parent = '$parent' where id = '$edit_id' ;";
			}
			$updateResult = mysqli_query($conn, $updateSql);
			header("Location: categories.php");
		}
	}

	$category_value ="";
	$parent_value = 0;
	if(isset($_GET["edit"])){
		$category_value = $edit_category["category"];
		$parent_value = $edit_category["parent"];
	}else{
		$category_value = $category;
		$parent_value = $parent;
	}
	$parent_value = "";
?>
    <h2 class="text-center">Categories</h2><hr>
	<div class="row" style="margin-left:0px;margin-right:-2px;">

	    <!-- form -->
		<div class="col-md-6">
		<legend><?= ((isset($_GET["edit"])) ? "Edit" : "Add A"); ?> Category</legend><hr>
		<div id="errors"></div>
		    <form class="form" action="categories.php<?=((isset($_GET["edit"])) ? "?edit=".$edit_id : "" );?>" method="post">
		    	<div class="form-group">
				    <label for="parent">Parent</label>
					<select name="parent" id="parent" class="form-control">
		    				<option value="0"<?=(($parent_value == 0) ? 'selected = "selected"' : '' );?>>Parent</option>
							<?php while($data = mysqli_fetch_assoc($result)):  ?>
		    				<option value="<?= $data["id"]; ?>"<?=(($parent_value == $data["id"]) ? 'selected = "selected"' : '' );?>><?= $data["category"]; ?></option>
							<?php endwhile; ?>
		    		</select>
				</div>
				<div class="form-group">
				    <label for="category">Category</label>
					<input type="text" name="category" id="category" class="form-control" value="<?=$category_value;?>">
				</div>
				<div class="form-group">
				    <input type="submit" name="submit" class="btn btn-success" value="<?= ((isset($_GET["edit"])) ? "Edit" : "Add"); ?> Category">
				</div>
		    </form>
		</div>

		<!-- category table -->
		<div class="col-md-6">
			<table class=" table table-bordered">
				<thead>
					<th>Category</th>
					<th>Parent</th>
					<th>Edit / Delete</th>
				</thead>
				<tbody>
				<?php
				    $sql = "select * from categories where parent =0;";
	                $result = mysqli_query($conn, $sql);
      				while($data = mysqli_fetch_assoc($result)):
				    $parent_id = (int)$data["id"];
					$sql1 = "select * from categories where parent  = '$parent_id';";
					$result1 = mysqli_query($conn, $sql1);
				?>
					<tr class="bg-primary">
						<td><?= $data["category"]; ?></td>
						<td>Parent</td>
						<td><a href="categories.php?edit=<?php echo $data["id"]; ?>" class="btn btn-xs btn-default btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>
						    <a href="categories.php?delete=<?php echo $data["id"]; ?>" class="btn btn-xs btn-default btn-danger"><span class="glyphicon glyphicon-trash"></span></a></td>
					</tr>
					<?php while($data1 = mysqli_fetch_assoc($result1)): ?>
					    <tr>
						<td><?php echo $data1["category"]; ?></td>
						<td><?= $data["category"]; ?></td>
						<td><a href="categories.php?edit=<?php echo $data1["id"]; ?>" class="btn btn-xs btn-default btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>
						    <a href="categories.php?delete=<?php echo $data1["id"]; ?>" class="btn btn-xs btn-default btn-danger"><span class="glyphicon glyphicon-trash"></span></a></td>
					</tr>
					<?php endwhile; ?>
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
<?php
    include "includes/footer.php";
?>