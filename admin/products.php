<?php
  require_once $_SERVER["DOCUMENT_ROOT"]."/ecommerceProject/core/db.php";
	include "includes/header.php";
	include "includes/navigation.php";
	if(!is_logged_in()){
		login_error_redirect();
	}

	//backend code ===

	//deleted product...
	if(isset($_GET["delete"])){
		$delete_id = sanitize($_GET["delete"]);
		$deleteSql = "update products set deleted = 1 where id= '$delete_id';";
		$deleteResult = mysqli_query($conn, $deleteSql);
	    header("Location: products.php");
	}

	if(isset($_GET["add"])  || isset($_GET["edit"])){
	  $brandSql = "select * from brand order by brand;";
		$pcategory = "select * from categories where parent=0 order by category;";
	  $brandResult = mysqli_query($conn, $brandSql);
		$pResult = mysqli_query($conn, $pcategory);
		$title = ((isset($_POST["title"]) && $_POST["title"] != '') ? sanitize($_POST['title']) : "");
		$brand = ((isset($_POST["brand"]) && $_POST["brand"] != '') ? sanitize($_POST['brand']) : "");
		$price = ((isset($_POST["price"]) && $_POST["price"] != '') ? sanitize($_POST['price']) : "");
		$list_price = ((isset($_POST["list_price"]) && $_POST["list_price"] != '') ? sanitize($_POST['list_price']) : "");
		$description = ((isset($_POST["description"]) && $_POST["description"] != '') ? sanitize($_POST['description']) : "");
		$category = ((isset($_POST["category"]) && $_POST["category"] != '') ? sanitize($_POST['category']) : "");
		$subcategory = ((isset($_POST["subcategory"]) && $_POST["subcategory"] != '') ? sanitize($_POST['subcategory']) : "");
		$sizes = ((isset($_POST["sizes"]) && $_POST["sizes"] != '') ? sanitize($_POST['sizes']) : "");
		$sizes = rtrim($sizes, ',');
		$saved_image ="";

		if(isset($_GET["edit"])){
			$edit_id = (int)$_GET["edit"];
			$productSql = "select * from products where id='$edit_id';";
			$productResult = mysqli_query($conn, $productSql);
			$product = mysqli_fetch_assoc($productResult);
			if(isset($_GET["delete_image"])){
				$imgi = (int)$_GET["imgi"] - 1;
				$images = explode(',',$product["image"]);
				$img_url = $_SERVER["DOCUMENT_ROOT"].$images[$imgi];
				unset($images[$imgi]);
				unlink($img_url);
				$imageString = implode(',',$images);
				$imageSql = "update products set image = '$imageString' where id ='$edit_id';";
				$imageResult = mysqli_query($conn, $imageSql);
				header("Location: products.php?edit=".$edit_id);
			}
			$title = ((isset($_POST["title"]) && $_POST["title"] != '') ? sanitize($_POST['title']) : $product["title"]);
			$brand = ((isset($_POST["brand"]) && $_POST["brand"] != '') ? sanitize($_POST['brand']) : $product["brand"]);
			$price = ((isset($_POST["price"]) && $_POST["price"] != '') ? sanitize($_POST['price']) : $product["price"]);
			$list_price = ((isset($_POST["list_price"])) ? sanitize($_POST['list_price']) : $product["list_price"]);
			$description = ((isset($_POST["description"])) ? sanitize($_POST['description']) : $product["description"]);
			$subcategory = ((isset($_POST["subcategory"]) && $_POST["subcategory"] != '') ? sanitize($_POST['subcategory']) : $product["categories"]);
			$sql1 = "select * from categories where id='$subcategory'";
			$result1 = mysqli_query($conn, $sql1);
			$data1 = mysqli_fetch_assoc($result1);
			$category = ((isset($_POST["category"]) && $_POST["category"] != '') ? sanitize($_POST['category']) : $data1["parent"]);
			$sizes = ((isset($_POST["sizes"]) && $_POST["sizes"] != '') ? sanitize($_POST['sizes']) : $product["sizes"]);
			$sizes = rtrim($sizes, ',');
			$saved_image = (($product["image"] != '') ? $product["image"] : "");
			$dbPath = $saved_image;

		}
		if(!empty($sizes)){
				$sizeString = sanitize($sizes);
				$sizeString = rtrim($sizeString, ",");
				$sizesArray = explode(",",$sizeString);
				$sArray = array();
				$qArray = array();
				$tArray = array();
				foreach($sizesArray as $ss){
					$s = explode(":",$ss);
					$sArray[] = $s["0"];
					$qArray[] = $s["1"];
					$tArray[] = $s["2"];
				}
			}else{
			$sizesArray = array();
		    }

		if($_POST){

			$errors = array();
			$required = array('title', 'brand', 'price', 'category', 'subcategory', 'sizes');
			$allowed = array('png', 'jpeg', 'jpg', 'gif');
			$uploadPath = array();
			$tmpLoc = array();
			foreach($required as $field){
				if($_POST[$field] == ''){
					$errors[] = "All fields are required.";
					break;
				}
			}
			$photoCount = count($_FILES["image"]["name"]);
			if($photoCount > 0){
				for($i=0; $i<$photoCount; $i++){
					$name = $_FILES["image"]["name"][$i];
					$nameArray = explode('.',$name);
					$fileName = $nameArray[0];
					$fileExt = $nameArray[1];
					$mime = explode('/',$_FILES["image"]["type"][$i]);
					$mimeType = $mime[0];
					$mimeExt = $mime[1];
					$tmpLoc[] = $_FILES["image"]["tmp_name"][$i];
					$fileSize = $_FILES["image"]["size"][$i];
					$uploadName = md5(microtime()).'.'.$fileExt;
					$uploadPath[] = BASEURL."/images/products/".$uploadName;
					if($i != 0){
						$dbPath .= ",";
					}
					$dbPath .= "/ecommerceProject/images/products/".$uploadName;
					if($mimeType != "image"){
						$errors[] = "The file must be an image.";
					}
					if(!in_array($fileExt, $allowed)){
						$errors[] = "The file extension must be a png, jpeg, jpg, or gif.";
					}
					if($fileSize > 5000000){
						$errors[] = "The file size must be under 5mb.";
					}
					if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){
						$errors[] = "File extension does not match the file.";
					}
			    }
			}
			//display error if present otherwise update database...
			if(!empty($errors)){
				echo display_error($errors);
			}else{
				//upload files and insert into database....
				if($photoCount > 0){
					for($i=0; $i<$photoCount; $i++){
				        move_uploaded_file($tmpLoc[$i], $uploadPath[$i]);
					}
				}
				$insertSql = "insert into products (title, price, list_price, brand, categories, image, description, sizes) values('$title', '$price', '$list_price', '$brand', '$subcategory', '$dbPath', '$description', '$sizes');";
				if(isset($_GET["edit"])){
					$insertSql = "update products set title='$title', price='$price', list_price='$list_price', brand='$brand', categories='$subcategory', image='$dbPath', description='$description', sizes='$sizes' where id = '$edit_id';";
				}
                $insertResult = mysqli_query($conn, $insertSql);
                header("Location: products.php");
			}
		}
	?>
		<h2 class="text-center"><?=((isset($_GET["edit"])) ? "Edit" : "Add New")?> Product</h2><hr>
		<form action="products.php?<?=((isset($_GET["edit"])) ? "edit=".$edit_id : "add=1")?>" method="post" enctype="multipart/form-data">
			<div class="form-group col-md-3">
			    <label for="title">Title</label>
				<input type="text" name="title" class="form-control" id="title" value="<?=$title;?>">
			</div>
			<div class="form-group col-md-3">
			    <label for="brand">Brand</label>
				<select name="brand" id="brand" class="form-control">
					<option value=""<?=(($brand == '') ? "selected" : '');?>></option>
					<?php while($b = mysqli_fetch_assoc($brandResult)): ?>
					<option value="<?=$b["id"];?>"<?=(($brand == $b["id"]) ? "selected" : '');?>><?=$b["brand"];?></option>
					<?php endwhile; ?>
				</select>
			</div>
			<div class="form-group col-md-3">
			    <label for="category">Category</label>
				<select name="category" id="category" class="form-control">
					<option value=""<?=(($category == '') ? "selected" : '');?>></option>
					<?php while($c = mysqli_fetch_assoc($pResult)): ?>
					<option value="<?=$c["id"];?>"<?=(($category == $c["id"]) ? "selected" : '');?>><?=$c["category"];?></option>
					<?php endwhile; ?>
				</select>
			</div>
			<div class="form-group col-md-3">
			    <label for="subcategory">Sub Category</label>
				<select name="subcategory" id="subcategory" class="form-control">
				</select>
			</div>
            <div class="form-group col-md-3">
			    <label for="price">Price</label>
				<input type="text" name="price" class="form-control" id="price" value="<?=$price;?>">
			</div>
            <div class="form-group col-md-3">
			    <label for="list_price">List Price</label>
				<input type="text" name="list_price" class="form-control" id="list_price" value="<?=$list_price;?>">
			</div>
            <div class="form-group col-md-3">
			    <label for="">Quantity & Sizes</label>
				<button class="btn btn-default form-control" onclick="$('#sizeModal').modal('toggle');return false;">Quantity & Sizes</button>
			</div>
            <div class="form-group col-md-3">
			    <label for="sizes">Sizes & Qty Preview</label>
				<input type="text" name="sizes" class="form-control" id="sizes" value="<?=$sizes;?>" readonly>
			</div>
            <div class="form-group col-md-6">
			    <?php if($saved_image != ""): ?>
				<?php
				    $imgi = 1;
					$images = explode(',',$saved_image);
				?>
				<?php foreach($images as $image): ?>
				    <div class="saved-image col-md-4">
					    <img src="<?=$image;?>" alt="saved-image"/><br>
						<a href="products.php?delete_image=1&edit=<?=$edit_id?>&imgi=<?=$imgi;?>" class="text-danger">Delete</a>
					</div>

				<?php
				$imgi++;
				endforeach; ?>
				<?php else: ?>
			    <label for="image">Image</label>
				<input type="file" name="image[]" class="form-control" id="image" multiple>
				<?php endif; ?>
			</div>
            <div class="form-group col-md-6">
			    <label for="description">Description</label>
				<textarea name="description" class="form-control" id="description" rows="6"><?=$description;?></textarea>
			</div>
			<div class="col-md-3 pull-right">
			    <a href="products.php" class="btn btn-default">Cancel</a>
                <input type="submit" class="btn btn-success" value="<?=((isset($_GET["edit"])) ? "Edit" : "Add")?> Product">
            </div><div class="clearfix"></div>
		</form>
		<!-- Modal -->
		<div id="sizeModal" class="modal fade" role="dialog">
		  <div class="modal-dialog modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Modal Header</h4>
			  </div>
			  <div class="modal-body">
			    <div class="container-fluid">
					<?php for($i=1; $i<=12; $i++): ?>
						<div class="form-group col-md-2">
							<label for="size<?=$i;?>">Size:</label>
							<input type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?=((!empty($sArray[$i-1])) ? $sArray[$i-1] : "");?>" class="form-control">
						</div>
						<div class="form-group col-md-2">
							<label for="qty<?=$i;?>">Quantity:</label>
							<input type="number" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?=((!empty($qArray[$i-1])) ? $qArray[$i-1] : "");?>" class="form-control" min="0">
						</div>
						<div class="form-group col-md-2">
							<label for="threshold<?=$i;?>">Threshold:</label>
							<input type="number" name="threshold<?=$i;?>" id="threshold<?=$i;?>" value="<?=((!empty($tArray[$i-1])) ? $tArray[$i-1] : "");?>" class="form-control" min="0">
						</div>
					<?php endfor; ?>
				</div>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="updateSizes();$('#sizeModal').modal('toggle');return false;">Save Changes</button>
			  </div>
			</div>

		  </div>
		</div>
	<?php }else{
	$sql = "select * from products where deleted = 0;";
	$result = mysqli_query($conn, $sql);
	//featured code...
	if(isset($_GET["featured"])){
		$id = (int)$_GET["id"];
		$id = sanitize($id);
		$featured = (int)$_GET["featured"];
		$featured = sanitize($featured);
		$featuredSql = "update products set featured = '$featured' where id = '$id';";
		$featuredResult = mysqli_query($conn, $featuredSql);
		header("Location: products.php");
	}
?>
    <h2 class="text-center">Products</h2>
	<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Product</a>
	<div class="clearfix"></div><hr>
	<table class="table table-bordered table-condensed table-stripped">
		<thead>
			<th>Edit / Delete</th>
			<th>Product</th>
			<th>Price</th>
			<th>Category</th>
			<th>Featured</th>
			<th>Sold</th>
		</thead>
		<tbody>
		<?php while($data = mysqli_fetch_assoc($result)):
		  $childID = $data["categories"];
			$catSql = "select * from categories where id ='$childID';";
			$catResult = mysqli_query($conn, $catSql);
			$subcat = mysqli_fetch_assoc($catResult);
			$parentID = $subcat["parent"];
			$pSql = "select * from categories where id = '$parentID';";
			$pResult = mysqli_query($conn, $pSql);
			$cat = mysqli_fetch_assoc($pResult);
			$category = $cat["category"].' - '.$subcat["category"];
			?>
		    <tr>
		    	<td><a href="products.php?edit=<?= $data["id"];?>" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>
				    <a href="products.php?delete=<?= $data["id"];?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a></td>
		    	<td><?= $data["title"];?></td>
		    	<td><?= money($data["price"]);?></td>
		    	<td><?= $category;?></td>
		    	<td><a href="products.php?featured=<?=(($data["featured"] == 0) ? "1" : "0" );?>&id=<?=$data["id"];?>" class="btn btn-xs btn-default">
						<span class="glyphicon glyphicon-<?=(($data["featured"] == 1) ? "minus" : "plus");?>"></span>
						</a>&nbsp;<?= (($data["featured"] == 1) ? " Featured Product" : ""); ?></td>
		    	<td><?=$data["deleted"];?></td>
		    </tr>
		<?php endwhile; ?>
		</tbody>
	</table>

<?php
    }
    include "includes/footer.php";
?>
<Script>
	$("document").ready(function(){
		get_sub_category("<?=isset($subcategory) ? $subcategory : '';?>");
	});
</script>