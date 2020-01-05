<?php
    require_once "../core/db.php";
    $id = $_POST['id'];
	$id = (int)$id;
	$sql = "select * from products where id = '$id';";
	$result = mysqli_query($conn, $sql);
	$data = mysqli_fetch_assoc($result);
	$brand_id = $data["brand"]; 
	$sql1 = "select * from brand where id = '$brand_id';";
	$result1 = mysqli_query($conn, $sql1);
	$data1 = mysqli_fetch_assoc($result1);
	$sizeString = $data["sizes"];
	$sizeString = rtrim($sizeString,',');
	$sizeArray = explode(',', $sizeString);
?>

<!-- Details Modal -->
<?php ob_start(); ?>
	<div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
		<div class="modal-dialog modal-lg">
		    <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" onclick="closeModal()" aria-label="close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title text-center" ><?php echo $data["title"]; ?></h4>
				</div><!-- modal-header -->
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
						   <span id="modal_errors" class="bg-danger"></span>
							<div class="col-sm-6 fotorama">
							<?php $photos = explode(',',$data["image"]); 
							    foreach($photos as $photo):
							?>
							<!--<div class="center-block">-->
								<img src="<?= $photo; ?>" alt="<?php echo $data["title"]; ?>" class="details img-responsive">
							<!--</div>	-->
							<?php endforeach; ?>	
							</div><!-- col -->
							<div class="col-sm-6">
								<h4>Details</h4>
								<p><?php echo nl2br($data["description"]); ?></p>
								<hr>
								<p>Price: $<?php echo $data["price"]; ?></p>
								<p>Brand: <?php echo $data1["brand"]; ?></p>
								<form action="add-cart.php" method="POST" id="add_product_form">
								    <input type="hidden" name="product_id" value="<?=$id;?>">
								    <input type="hidden" name="available" id="available" value="">
								    <div class="form-group">
									    <div class="col-xs-3" style="margin-left:-16px!important;">
										    <label for="quantity">Quantity:</label>
											<input type="number" id="quantity" class="form-control" name="quantity" min="0">
										</div><!--col-xs-3--><div class="col-xs-9"></div>
									</div><!-- form-group --><br><br><br><br>
									<div class="form-group">
									    <label for="size">Size: </label>
										<select name="size" id="size" class="form-control">
										    <option value=""></option>
										<?php foreach($sizeArray as $string){ 
										    $stringArray = explode(':', $string);
											print_r($stringArray);
											$size = $stringArray[0];
											$available = $stringArray[1];
											if($available > 0){
											echo "<option value='$size' data-available='$available'>$size ($available Available)</option>";
											}
									    } ?>
										</select>
									</div><!-- form-group -->
								</form>
							</div><!-- col -->
						</div><!-- row -->
					</div><!-- container-fluid -->
				</div><!-- modal-body -->
				<div class="modal-footer">
					<button class="btn btn-default" onclick="closeModal()">Close</button>
					<button onclick="add_to_cart();return false;" class="btn btn-warning"><span class="glyphicon glyphicon-shopping-cart"></span> Add To Cart</button>
				</div><!-- modal-footer -->
			</div><!-- modal-dialog -->
		<div><!-- modal-content -->
	</div><!-- modal-->
	<script>
		
	    $("#size").change(function(){
			var available = $("#size option:selected").data("available");
			$("#available").val(available);
		});
		
		$(function () {
            $('.fotorama').fotorama({'loop':true,'autoplay':true});
        });
	
	    function closeModal(){
			$("#details-modal").modal("hide");
			setTimeout(function(){
				$("#details-modal").remove();
				$(".modal-backdrop").remove();
			}, 500)
		}
	</script>
	<?php echo ob_get_clean(); ?>