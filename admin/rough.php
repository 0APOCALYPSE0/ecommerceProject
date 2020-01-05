<link rel="stylesheet" href="../css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<?php
    require_once $_SERVER["DOCUMENT_ROOT"]."/MyPhpFolder/ecommerceProject/core/db.php";
    $pcategory = "select * from cat where parent=0 order by category;";
    $pResult = mysqli_query($conn, $pcategory);
	$subcategory = ((isset($_POST["subcategory"]) && $_POST["subcategory"] != '') ? sanitize($_POST['subcategory']) : "");
	$category = ((isset($_POST["category"]) && $_POST["category"] != '') ? sanitize($_POST['category']) : "");
	$grandcategory = ((isset($_POST["grandcategory"]) && $_POST["grandcategory"] != '') ? sanitize($_POST['grandcategory']) : "");
?>


            <div class="form-group col-md-3">
			    <label for="grandcategory">Main Category</label>
				<select name="grandcategory" id="grandcategory" class="form-control">
					<option value=""<?=(($grandcategory == '') ? "selected" : '');?>></option>
					<?php while($c = mysqli_fetch_assoc($pResult)): ?>
					<option value="<?=$c["id"];?>"<?=(($grandcategory == $c["id"]) ? "selected" : '');?>><?=$c["category"];?></option>
					<?php endwhile; ?>
				</select>
			</div>
			<div class="form-group col-md-3">
			    <label for="category">Category</label>
				<select name="category" id="category" class="form-control">
				</select>
			</div>
			<div class="form-group col-md-3">
			    <label for="subcategory">Sub Category</label>
				<select name="subcategory" id="subcategory" class="form-control">
				</select>
			</div>
	
<script>
    function get_category(selected){
		if(typeof selected === "undefined"){
			var selected ="";
		}
		var grandparentID = $("#grandcategory").val();
		jQuery.ajax({
			url : "/MyPhpFolder/ecommerceProject/admin/rough1.php",
			type : "POST",
			data : {grandparentID : grandparentID, selected : selected},
			success : function(data){
				$("#category").html(data);
			},
			error : function(){
				alert("Something went wrong with the category option!");
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

<Script>
	function get_sub_category(selected){
		if(typeof selected === "undefined"){
			var selected ="";
		}
		var parentID = $("#category").val();
		jQuery.ajax({
			url : "/MyPhpFolder/ecommerceProject/admin/rough2.php",
			type : "POST",
			data : {parentID : parentID, selected : selected},
			success : function(data){
				$("#subcategory").html(data);
			},
			error : function(){
				alert("Something went wrong with the sub category option!");
			}
		});			
	}
	$("select[name='category']").change(function(){
		get_sub_category();
	});
	
    $("document").ready(function(){
		get_sub_category("<?=$subcategory;?>");
	});
   </script>