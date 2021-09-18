<?php
    require_once $_SERVER["DOCUMENT_ROOT"]."/ecommerceProject/core/db.php";
	$grandparentID = (int)$_POST["grandparentID"];
	$selected = $_POST["selected"];
	$Sql = "select * from cat where parent = '$grandparentID' order by category;";
	$Result = mysqli_query($conn, $Sql);
	ob_start(); ?>
	    <option value=""></option>
		<?php while($Category = mysqli_fetch_assoc($Result)): ?>
		    <option value="<?=$Category["id"];?>"<?=(($selected == $Category["id"]) ? "selected" : '');?>><?=$Category["category"];?></option>
		<?php endwhile; ?>
	<?php echo ob_get_clean(); ?>

<script>
    function get_category(selected){
		if(typeof selected === "undefined"){
			var selected ="";
		}
		var grandparentID = $("#grandcategory").val();
		jQuery.ajax({
			url : "/ecommerceProject/admin/rough1.php",
			type : "POST",
			data : {grandparentID : grandparentID, selected : selected},
			success : function(data){
				$("#category").html(data);
			},
			error : function(){
				alert("Something went wrong with the sub category option!");
			}
		});
	}
	$("select[name='grandcategory']").change(function(){
		get_category();
	});
</script>

<Script>
    $("document").ready(function(){
		get_category("<?=$category;?>");
	});
</script>