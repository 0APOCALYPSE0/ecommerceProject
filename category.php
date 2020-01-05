    <?php
	    require_once "core/db.php";
	    include "includes/head.php";
		include "includes/navigation.php";
		include "includes/headerpartial.php";
		include "includes/leftbar.php";
		
		if(isset($_GET["cat"])){
			$cat_id = sanitize($_GET["cat"]);
		}else{
			$cat_id = "";
		}
		$sql = "select * from products where categories = '$cat_id';";
		$productQ = mysqli_query($conn, $sql);
		$category = get_category($cat_id);
	?>

	<!-- main content -->
	<div class="col-md-8">
		<div class="row">
			<h2 class="text-center"><?=$category["category"]." ".$category["subcategory"];?></h2>
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