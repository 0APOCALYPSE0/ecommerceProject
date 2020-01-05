<!-- cart widget -->	
	<h3>Shopping Cart</h3><br>
	<div>
		<?php if(empty($cart_id)): ?>
			<p>Your shopping cart is empty.</p>
		<?php else: 
			$cartQ = $conn->query("select * from cart where id = '$cart_id';");
			$results = mysqli_fetch_assoc($cartQ);
			$items = json_decode($results['items'], true);
			$sub_total = 0;
		?>
			<table class="table table-condensed text-left" id="cart-widget">
			    <thead>
				    <th>QTY</th>
				    <th>TITLE</th>
				    <th>PRICE</th>
				</thead>
				<tbody>
				<?php foreach($items as $item):
					$productQ = $conn->query("select * from products where id = '{$item['id']}';");
					$product = mysqli_fetch_assoc($productQ);
				?>
					<tr>
						<td><?=$item["quantity"];?></td>
						<td><?=$product["title"];?></td>
						<td><?=money($item["quantity"] * $product["price"]);?></td>
					</tr>
				<?php
                    $sub_total += $item["quantity"] * $product["price"];
				endforeach; ?>				
				</tbody>
				<tfoot style="background-color:#e6fff2;">
					<tr>
						<td></td>
						<td>Sub Total</td>
						<td><?=money($sub_total);?></td>
					</tr>
				</tfoot>
			</table>
			<a href="cart.php" class="btn btn-xs btn-primary pull-right" style="margin-top:5px;">View Cart</a>
			<div class="clear-fix"></div>
		<?php endif; ?>	
	</div>
	