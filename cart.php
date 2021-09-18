<?php
    require_once "core/db.php";
	include "includes/head.php";
	include "includes/navigation.php";
	include "includes/headerpartial.php";

	if($cart_id != ""){
		$cartQ = $conn->query("select * from cart where id = '$cart_id';");
		$result = mysqli_fetch_assoc($cartQ);
		$items = json_decode($result["items"],true);
		$i = 1;
		$sub_total = 0;
		$item_count = 0;
	}
?>

    <div class="col-md-12">
    	<div class="row">
    		<h2 class="text-center">My Shopping Cart</h2><hr>
			<?php if($cart_id == ""): ?>
    			<div class="bg-danger">
    				<p class="text-danger text-center">Your shopping cart is empty!</p>
    			</div>
    		<?php else: ?>
			    <table class="table table-condensed table-stripped table-bordered">
			    	<thead>
			    		<th>#</th>
			    		<th>Item</th>
			    		<th>Price</th>
			    		<th>Quantity</th>
			    		<th>Size</th>
			    		<th>Sub Total</th>
			    	</thead>
			    	<tbody>
					<?php foreach($items as $item){
		                $product_id = $item["id"];
                        $productQ = $conn->query("select * from products where id = '$product_id';");
                        $product = mysqli_fetch_assoc($productQ);
                        $sArray = explode(",",$product["sizes"]);
                        foreach($sArray as $sizeString){
							$s = explode(":",$sizeString);
							if($s[0] == $item["size"]){
								$available = $s[1];
							}
						}
					?>
			    		<tr>
			    			<td><?=$i;?></td>
			    			<td><?=$product["title"];?></td>
			    			<td><?=money($product["price"]);?></td>
			    			<td>
							   <button class="btn btn-xs btn-default" onclick="update_cart('removeone', '<?=$product["id"];?>', '<?=$item["size"];?>');">-</button>
							    <?=$item["quantity"];?>
								<?php if($item["quantity"] < $available): ?>
							   <button class="btn btn-xs btn-default" onclick="update_cart('addone', '<?=$product["id"];?>', '<?=$item["size"];?>');">+</button>
							   <?php else: ?>
							        <span class="text-danger">Max</span>
							   <?php endif; ?>
							</td>
			    			<td><?=$item["size"];?></td>
			    			<td><?=money($item["quantity"] * $product["price"]);?></td>
			    		</tr>
						<?php
						    $i++;
						    $item_count += $item["quantity"];
							$sub_total += ($item["quantity"] * $product["price"]);
					}
							$tax = TAXRATE * $sub_total;
							$tax = number_format($tax,2);
							$grand_total = $tax + $sub_total;
						?>
			    	</tbody>
			    </table>
				<table class="table table-bordered table-condensed text-right">
				    <legend style="margin-top:20px;margin-left:16px;">Totals</legend>
					<thead class="totals-table-header">
						<th>Total Items</th>
						<th>Sub Total</th>
						<th>Tax</th>
						<th>Grand Total</th>
					</thead>
					<tbody>
						<tr>
							<td><?=$item_count;?></td>
							<td><?=money($sub_total);?></td>
							<td><?=money($tax);?></td>
							<td class="bg-success"><?=money($grand_total);?></td>
						</tr>
					</tbody>
				</table>

				<!-- Checkout Button -->
				<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#checkoutModal" style="margin-top:15px; margin-right:15px;">
				 <span class="glyphicon glyphicon-shopping-cart"></span> Check Out >>
				</button>

				<!-- Modal -->
				<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
				  <div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
					  <div class="modal-header">
						<h5 class="modal-title" id="checkoutModalLabel">Shipping Address</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
						</button>
					  </div>
					  <div class="modal-body">
					    <div class="row">
							<form action="thankyou.php" method="POST" id="payment-form">
							    <span class="bg-danger" id="payment-errors"></span>
								<input type="hidden" name="tax" value="<?=$tax;?>">
								<input type="hidden" name="sub_total" value="<?=$sub_total;?>">
								<input type="hidden" name="grand_total" value="<?=$grand_total;?>">
								<input type="hidden" name="cart_id" value="<?=$cart_id;?>">
								<input type="hidden" name="description" value="<?=$item_count." item".(($item_count>1)?"s":"")." from Shauntas Boutique.";?>">
								<div style="display:block" id="step1">
									<div class="form-group col-md-6">
										<label for="full_name">Full Name: </label>
										<input type="text" name="full_name" class="form-control" id="full_name">
									</div>
									<div class="form-group col-md-6">
										<label for="email">Email: </label>
										<input type="text" name="email" class="form-control" id="email">
									</div>
									<div class="form-group col-md-6">
										<label for="street">Street Address: </label>
										<input type="text" name="street" class="form-control" id="street" data-stripe="address_line1">
									</div>
									<div class="form-group col-md-6">
										<label for="street2">Street Address 2: </label>
										<input type="text" name="street2" class="form-control" id="street2" data-stripe="address_line2">
									</div>
									<div class="form-group col-md-6">
										<label for="city">City: </label>
										<input type="text" name="city" class="form-control" id="city" data-stripe="address_city">
									</div>
									<div class="form-group col-md-6">
										<label for="state">State: </label>
										<input type="text" name="state" class="form-control" id="state" data-stripe="address_state">
									</div>
									<div class="form-group col-md-6">
										<label for="zip_code">Zip Code: </label>
										<input type="text" name="zip_code" class="form-control" id="zip_code" data-stripe="address_zip">
									</div>
									<div class="form-group col-md-6">
										<label for="country">Country: </label>
										<input type="text" name="country" class="form-control" id="country" data-stripe="address_country">
									</div>
                                </div>
								<div id="step2" style="display:none">
								    <div class="form-group col-md-3">
									    <label for="name">Name on Card: </label>
										<input type="text" id="name" class="form-control" data-stripe="name">
									</div>
									<div class="form-group col-md-3">
									    <label for="number">Card Number: </label>
										<input type="text" id="number" class="form-control" data-stripe="number">
									</div>
									<div class="form-group col-md-2">
									    <label for="cvc">CVC: </label>
										<input type="text" id="cvc" class="form-control" data-stripe="cvc">
									</div>
									<div class="form-group col-md-2">
									    <label for="name">Expire Month: </label>
										<select id="exp-month" class="form-control" data-stripe="exp_month">
										    <option value=""></option>
										<?php for($i=1; $i<13; $i++): ?>
											<option value="<?=$i;?>"><?=$i;?></option>
										<?php endfor; ?>
										</select>
									</div>
									<div class="form-group col-md-2">
									    <label for="exp-year">Expire Year: </label>
										<select id="exp-year" class="form-control" data-stripe="exp_year">
                                            <option value=""></option>
										<?php $y = date("Y"); ?>
										<?php for($i=0; $i<11; $i++): ?>
											<option value="<?=$i+$y;?>"><?=$i+$y;?></option>
										<?php endfor; ?>
										</select>
									</div>
								</div>
						</div>
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" onclick="check_address();" id="next_button">Next >></button>
						<button type="button" class="btn btn-primary" onclick="back_address();" id="back_button" style="display:none"><< Back</button>
						<button type="submit" class="btn btn-primary" id="check_out_button" style="display:none">Check Out >></button>
					</form>
					  </div>
					</div>
				  </div>
				</div>

			<?php
            if($item_count <= 0){
		        setcookie(CART_COOKIE, '', 1, "/", $domain, false);
	          }
			endif; ?>
    	</div>
    </div>

	<script>

	    function back_address(){
			$("#payment-errors").html("");
						$("#step1").css("display","block");
						$("#step2").css("display","none");
						$("#next_button").css("display","inline-block");
						$("#back_button").css("display","none");
						$("#check_out_button").css("display","none");
						$("#checkoutModalLabel").html("Shipping Address");
		}

	    function check_address(){
			var data = {
				"full_name" : $("#full_name").val(),
				"email" : $("#email").val(),
				"street" : $("#street").val(),
				"street2" : $("#street2").val(),
				"city" : $("#city").val(),
				"state" : $("#state").val(),
				"zip_code" : $("#zip_code").val(),
				"country" : $("#country").val(),
			};
			$.ajax({
				url : "/ecommerceProject/admin/parsers/check_address.php",
				method : "post",
				data : data,
				success : function(data){
					if(data == 0){
						$("#payment-errors").html("");
						$("#step1").css("display","none");
						$("#step2").css("display","block");
						$("#next_button").css("display","none");
						$("#back_button").css("display","inline-block");
						$("#check_out_button").css("display","inline-block");
						$("#checkoutModalLabel").html("Enter Your Card Details");
					}else{
						$("#payment-errors").html(data);
					}
				},
				error : function(){alert("Something went wrong!");},
			});
		}

		Stripe.setPublishableKey('<?=STRIPE_PUBLIC;?>');

		function stripeResponseHandler(status, response){
			var $form = $("#payment-form");

			if(response.error){
				// Show the errors on the form
				$form.find("#payment-errors").text(response.error.message);
				$form.find("button").prop("disable", true);
			} else {
				//response contains id and card, which contains additional card details
				var token = response.id;
				//insert the token into the form so its get submitted to the server
				$form.append($('<input type="hidden" name="stripeToken" />').val(token));
				//and submit
				$form.get(0).submit();
			}
		};

		$(function($) {
			$("#payment-form").submit(function(event) {
				var $form = $(this);

				//Disable the submit button to prevent from repeated clicks
				$form.find("button").prop("disable", true);

				Stripe.card.createToken($form, stripeResponseHandler);

				//Prevent the form from submitting with the default action
				return false;
			});
		});

		/*function stripeTokenHandler(token) {
		  // Insert the token ID into the form so it gets submitted to the server
		  var form = document.getElementById('#payment-form');
		  var hiddenInput = document.createElement('input');
		  hiddenInput.setAttribute('type', 'hidden');
		  hiddenInput.setAttribute('name', 'stripeToken');
		  hiddenInput.setAttribute('value', token.id);
		  form.appendChild(hiddenInput);

		  // Submit the form
		  form.submit();
		}*/

		/*// Create a token or display an error when the form is submitted.
		var form = document.getElementById('#payment-form');
		form.addEventListener('submit', function(event) {
		  event.preventDefault();

		  stripe.createToken(card).then(function(result) {
			if (result.error) {
			  // Inform the customer that there was an error.
			  var errorElement = document.getElementById('card-errors');
			  errorElement.textContent = result.error.message;
			} else {
			  // Send the token to your server.
			  stripeTokenHandler(result.token);
			}
		  });
		});*/
	</script>

<?php

    include "includes/footer.php";
?>