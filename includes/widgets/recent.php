<!-- recent widget -->
    <h3>Popular Items</h3>
	<?php
	    $transQ = $conn->query("select * from cart where paid = 1 order by id desc limit 5");
		$results = array();
		while($row = mysqli_fetch_assoc($transQ)){
			$results[] = $row;
		}
		$row_count = mysqli_num_rows($transQ);
		$used_ids = array();
		for($i=0; $i<$row_count; $i++){
			$json_items = $results[$i]["items"];
			$items = json_decode($json_items, true);
			foreach($items as $item){
				if(!in_array($item["id"], $used_ids)){
					$used_ids[] = $item["id"];
				}
			}
		}
	?>
	<div id="recent-widget">
		<table class="table table-condensed text-left">
		<?php
		    foreach($used_ids as $id):
				$productQ = $conn->query("select * from products where id = '$id';");
				$product = mysqli_fetch_assoc($productQ);		
		?>
			<tr>
				<td><?=$product["title"];?></td>
				<td><a class="text-primary" onclick="detailsmodal('<?=$id;?>');">View</a></td>
			</tr>
		<?php endforeach; ?>	
		</table>
	</div>