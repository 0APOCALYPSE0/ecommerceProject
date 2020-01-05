<?php
    require_once "../core/db.php";
	include "includes/header.php";
	include "includes/navigation.php";
	if(!is_logged_in()){
		login_error_redirect();
	}
	
	//IF brand is added...
	$sql = "select * from brand order by brand";
	$result = mysqli_query($conn, $sql);
	$errors = array();
	
	//edit brand...
	if(isset($_GET["edit"]) && !empty($_GET["edit"])){
		$edit_id = $_GET["edit"];
		$edit_id = sanitize($edit_id);
		$sql = "select * from brand where id = '$edit_id';";
		$result = mysqli_query($conn, $sql);
		$data1 = mysqli_fetch_assoc($result);
	}
	
	//delete brand...
	if(isset($_GET["delete"]) && !empty($_GET["delete"])){
		$delete_id = $_GET["delete"];
		$delete_id = sanitize($delete_id);
		$sql = "delete from brand where id = '$delete_id';";
		$result = mysqli_query($conn, $sql);
		header("Location: brand.php");
	}
	
	//if add form is submitted...
	if(isset($_POST["submit"])){
		$brand = sanitize($_POST["brand"]);
		//check if brand is blank...
		if($_POST["brand"] == ''){
			$errors[] .= "You must enter a brand!";
		}
		
		$sql = "select * from brand where brand = '$brand';";
		if(isset($_GET["edit"])){
			$sql = "select * from brand where brand = '$brand' and id != '$edit_id';";
		}
		$result = mysqli_query($conn, $sql);
		$count = mysqli_num_rows($result);
		if($count>0){
			$errors[] .= $brand." Brand is already exist. Please choose another brand name!";
		}
		//check if is brand already exist...
		if(!empty($errors)){
			echo display_error($errors);
		}else{
			//add brand to database...
			$sql = "insert into brand (brand) values('$brand');";
			if(isset($_GET["edit"])){
				$sql = "update brand set brand = '$brand' where id = '$edit_id';";
			}
			$result = mysqli_query($conn, $sql);
			header("location: brand.php");
		}
	}
	
	//Get brand from database using php...
	$sql = "select * from brand order by brand";
	$result = mysqli_query($conn, $sql);
?>
<h2 class="text-center">Brands</h2><hr>
    <div class="text-center">
    	<form action="brand.php<?=((isset($_GET['edit'])) ? '?edit='.$edit_id : '');?>" class="form-inline" method="post">
    		<div class="form-group">
    			<label for="brand"><?=((isset($_GET['edit'])) ? 'Edit' : 'Add A');?> Brand</label>
				<?php
				    $brand_value ="";
				    if(isset($_GET["edit"])){
						$brand_value = $data1["brand"];
					}else{
						if(isset($_POST["brand"])){
							$brand_value = sanitize($_POST["brand"]);
						}
					}
				?>
    			<input type="text" name="brand" id="brand" class="form-control" value="<?=$brand_value;?>" placeholder="Enter Brand Name...">
    			<input type="submit" name="submit" class="btn btn-success" value="<?=((isset($_GET['edit'])) ? 'Update' : 'Add');?> Brand">
				<?php if(isset($_GET["edit"])): ?>
				    <a href="brand.php" class="btn btn-default">Cancel</a>
				<?php endif; ?>
    		</div>
    	</form>
    </div><hr>

    <table class="table table-bordered table-striped table-auto">
	    <thead>
		    <th>Edit</th>
			<th>Brand</th>
			<th>Delete</th>
		</thead>
		<tbody>
		<?php while($data = mysqli_fetch_assoc($result)): ?>
		    <tr>
		    	<td><a href="brand.php?edit=<?php echo $data["id"]; ?>" class='btn btn-xs btn-primary'><span class="glyphicon glyphicon-pencil"></span></a></td>
		    	<td><?php echo $data["brand"]; ?></td>
		    	<td><a href="brand.php?delete=<?php echo $data["id"]; ?>" class='btn btn-xs btn-danger'><span class="glyphicon glyphicon-trash"></span></a></td>
		    </tr>
		<?php endwhile; ?>	
		</tbody>
	</table>
<?php
    include "includes/footer.php";
?>