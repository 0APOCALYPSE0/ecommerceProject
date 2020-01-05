    <?php
	    require_once "core/db.php";
	    include "includes/head.php";
		include "includes/navigation.php";
		include "includes/headerpartial.php";
		include "includes/leftbar.php";
		
        $sql = "select * from products";
		$cat_id = (($_POST["cat"] != "") ? sanitize($_POST["cat"]) : "");
		if($cat_id == ""){
			$sql .= " where deleted = 0";
		}else{
			$sql .= " where categories = '$cat_id' and deleted = 0";
		}
		$price_sort = (($_POST["price_sort"] != "") ? sanitize($_POST["price_sort"]) : '');
		$min_price = (($_POST["min_price"] != "") ? sanitize($_POST["min_price"]) : '');
		$max_price = (($_POST["max_price"] != "") ? sanitize($_POST["max_price"]) : '');
		$brand = (($_POST["brand"] != "") ? sanitize($_POST["brand"]) : '');
		if($min_price != ""){
			$sql .= " and price >= '$min_price'";
		}
		if($max_price != ""){
			$sql .= " and price <= '$max_price'";
		}
		if($brand != ""){
			$sql .= " and brand = '$brand'";
		}
		if($price_sort == "low"){
			$sql .= " order by price";
		}
		if($price_sort == "high"){
			$sql .= " order by price desc";
		}
		$productQ = mysqli_query($conn, $sql);
		$category = get_category($cat_id);
	?>

	<!-- main content -->
	<div class="col-md-8">
		<div class="row">
		<?php if($cat_id != ""): ?>
			<h2 class="text-center"><?=$category["category"]." ".$category["subcategory"];?></h2>
		<?php else: ?>
		    <h2 class="text-center">Shaunta's Boutique</h2>
        <?php endif; ?>		
			<?php while($data = mysqli_fetch_assoc($productQ)): ?>
			<div class="col-md-3 col-sm-12 col-xs-12 text-center">
				<h4><?php echo $data["title"]; ?></h4>
				<?php $photos = explode(',',$data["image"]); ?>
				<img src="<?php echo $photos[0]; ?>" alt="<?php echo $data["title"]; ?>" class="img-thumb">
				<p class="list-price text-danger">List Price: <s>$<?php echo $data["list_price"]; ?></s></p>
				<p class="price">Our Price: $<?php echo $data["price"]; ?></p>
				<button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?php echo $data['id']; ?>)">Details</button>
			</div><!-- col -->
			<?php endwhile; ?>
		</div><!-- row -->
	</div><!-- col -->
	
	<?php
	    include "includes/rightbar.php";
		include "includes/footer.php";
	?>