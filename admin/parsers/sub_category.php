<?php
  require_once $_SERVER["DOCUMENT_ROOT"]."/ecommerceProject/core/db.php";
	$parentID = (int)$_POST["parentID"];
	$selected = $_POST["selected"];
	$subSql = "select * from categories where parent = '$parentID' order by category;";
	$subResult = mysqli_query($conn, $subSql);
	ob_start(); ?>
	    <option value=""></option>
		<?php while($subCategory = mysqli_fetch_assoc($subResult)): ?>
		    <option value="<?=$subCategory["id"];?>"<?=(($selected == $subCategory["id"]) ? "selected" : '');?>><?=$subCategory["category"];?></option>
		<?php endwhile; ?>
	<?php echo ob_get_clean(); ?>