<?php
    require_once "../core/db.php";
	include "includes/header.php";
	include "includes/navigation.php";

	if(!is_logged_in()){
		header("Location: login.php");
	}
?>
<!-- order to fill -->
<?php
    $txnQuery = "select t.id, t.cart_id, t.full_name, t.description, t.txn_date, t.grand_total, c.items, c.paid, c.shipped
	             from transactions t
				 left join cart c
				 on t.cart_id = c.id
				 where c.paid = 1 and c.shipped = 0
				 order by t.txn_date";
	$txnResults = $conn->query($txnQuery);
?>
	<div class="col-md-12">
		<h3 class="text-center">Orders To Ship</h3>
		<table class="table table-condensed table-bordered table-stripped">
			<thead>
				<th>Details</th>
				<th>Name</th>
				<th>Description</th>
				<th>Total</th>
				<th>Date</th>
			</thead>
			<tbody>
			<?php
			if(mysqli_num_rows($txnResults) > 0){
				while($order = mysqli_fetch_assoc($txnResults)): ?>
					<tr>
						<td><a href="orders.php?txn_id=<?=$order['id'];?>" class="btn btn-xs btn-info">Details</a></td>
						<td><?=$order["full_name"];?></td>
						<td><?=$order["description"];?></td>
						<td><?=money($order["grand_total"]);?></td>
						<td><?=pretty_date($order["txn_date"]);?></td>
					</tr>
				<?php endwhile;
			}
			?>
			</tbody>
		</table>
	</div>

	<div class="row">
	    <!-- sales by month -->
		<?php
		    $thisYr = date("Y");
			$lastYr = $thisYr - 1;
			$thisYrQ = $conn->query("select grand_total, txn_date from transactions where YEAR(txn_date) = '$thisYr'");
			$lastYrQ = $conn->query("select grand_total, txn_date from transactions where YEAR(txn_date) = '$lastYr'");
			$current = array();
			$last = array();
			$currentTotal = 0;
			$lastTotal = 0;
			while($x = mysqli_fetch_assoc($thisYrQ)){
				$month = date("m", strtotime($x["txn_date"]));
				if(!array_key_exists($month, $current)){
					$current[(int)$month] = $x["grand_total"];
				}else{
					$current[(int)$month] += $x["grand_total"];
				}
				$currentTotal += $x["grand_total"];
			}
			while($y = mysqli_fetch_assoc($lastYrQ)){
				$month = date("m", strtotime($y["txn_date"]));
				if(!array_key_exists($month, $last)){
					$last[(int)$month] = $y["grand_total"];
				}else{
					$last[(int)$month] += $y["grand_total"];
				}
				$lastTotal += $y["grand_total"];
			}
		?>
		<div class="col-md-4" style="margin-left:25px;">
			<h3 class="text-center">Sales By Month</h3>
			<table class="table table-condensed table-bordered table-stripped">
				<thead>
					<th>Months</th>
					<th><?=$lastYr;?></th>
					<th><?=$thisYr;?></th>
				</thead>
				<tbody>
				<?php
				    for($i=1; $i<=12; $i++){
						$dt = DateTime::createFromFormat("!m",$i);
				?>
					<tr <?=(date("m") == $i) ? "class='info'" : '';?>>
						<td><?=$dt->format("F");?></td>
						<td><?=(array_key_exists($i,$last)) ? money($last[$i]) : money(0);?></td>
						<td><?=(array_key_exists($i,$current)) ? money($current[$i]) : money(0);?></td>
					</tr>
				<?php }?>
				    <tr Style="background-color:#e6fff2;">
				    	<td>Total</td>
				    	<td><?=money($lastTotal);?></td>
				    	<td><?=money($currentTotal);?></td>
				    </tr>
				</tbody>
			</table>
		</div>

		<!-- Inventory -->
		<?php
		    $iQuery = $conn->query("select * from products where deleted = 0");
			$lowItems = array();
			while($product = mysqli_fetch_assoc($iQuery)){
				$item = array();
				$sizes = sizesToArray($product["sizes"]);
				foreach($sizes as $size){
					if($size["quantity"] <= $size["threshold"]){
						$cat = get_category($product["categories"]);
						$item = array(
						    "title"      => $product["title"],
							"size"       => $size["size"],
							"quantity"   => $size["quantity"],
							"threshold"  => $size["threshold"],
							"category"   => $cat["category"]." ~ ".$cat["subcategory"]
						);
						$lowItems[] = $item;
					}
				}
			}
		?>
		<div class="col-md-8" style="width:64%;">
			<h3 class="text-center">Low Inventory</h3>
			<table class="table table-condensed table-bordered table-stripped">
				<thead>
					<th>Product</th>
					<th>Category</th>
					<th>Size</th>
					<th>Quantity</th>
					<th>Threshold</th>
				</thead>
				<tbody>
				<?php foreach($lowItems as $item): ?>
					<tr <?=($item["quantity"] == 0) ? "class='danger'" : '';?>>
						<td><?=$item["title"];?></td>
						<td><?=$item["category"];?></td>
						<td><?=$item["size"];?></td>
						<td><?=$item["quantity"];?></td>
						<td><?=$item["threshold"];?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
<?php
    include "includes/footer.php";
?>