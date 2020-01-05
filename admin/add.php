<link rel="stylesheet" href="../css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<?php
        require_once $_SERVER['DOCUMENT_ROOT']."/MyPhpFolder/ecommerceProject/core/db.php";
       	$sql = "select * from cat where parent =0;";
	    $result = mysqli_query($conn, $sql);
		$grandparent = ((isset($_POST["grandparent"]) && $_POST["grandparent"] != '') ? sanitize($_POST['grandparent']) : "");
	    $parent = ((isset($_POST["parent"]) && $_POST["parent"] != '') ? sanitize($_POST['parent']) : "");
		$category = ((isset($_POST["categ"]) && $_POST["categ"] != '') ? sanitize($_POST['categ']) : "");
	if(isset($_POST["submit"]) && !empty($_POST["submit"])){
		$grandparent = sanitize($_POST["grandparent"]);
		$parent = sanitize($_POST["parent"]);
		$category = sanitize($_POST["category"]);
			if($parent > $grandparent){
				$parent1 = $parent;
			}else if($grandparent > $parent){
				$parent1 = $grandparent;
			}else{
				$parent1 = 0;
			}
			//update database...
			$updateSql = "insert into cat (category, parent) values('$category', '$parent1');";
			$updateResult = mysqli_query($conn, $updateSql);
			header("Location: add.php");
	}
	?>



<!-- form -->
		<div class="col-md-6">
		<legend><?= ((isset($_GET["edit"])) ? "Edit" : "Add A"); ?> Category</legend>
		<div id="errors"></div>
		    <form class="form" action="add.php" method="post">
			    <div class="form-group">
				    <label for="grandparent">Grand Parent</label>
					<select name="grandparent" id="grandparent" class="form-control">
		    				<option value="0"<?=(($grandparent == "") ? 'selected = "selected"' : '' );?>>Parent</option>
							<?php while($data = mysqli_fetch_assoc($result)):  ?>
		    				<option value="<?= $data["id"]; ?>"<?=(($grandparent == $data["id"]) ? 'selected = "selected"' : '' );?>><?= $data["category"]; ?></option>
							<?php endwhile; ?>
		    		</select>
				</div>
		    	<div class="form-group">
				    <label for="parent">Parent</label>
					<select name="parent" id="parent" class="form-control">
		    		</select>
				</div>
				<div class="form-group">
				    <label for="category">Category</label>
					<input type="text" name="category" id="category" class="form-control" value="<?=$category;?>">
				</div>
				<div class="form-group">
				    <input type="submit" name="submit" class="btn btn-success" value="<?= ((isset($_GET["edit"])) ? "Edit" : "Add"); ?> Category">
				</div>
		    </form>
		</div>
		
<Script>
    function get_sub_category(selected){
		if(typeof selected === "undefined"){
			var selected ="";
		}
		var parentID = $("#grandparent").val();
		jQuery.ajax({
			url : "/MyPhpFolder/ecommerceProject/admin/add1.php",
			type : "POST",
			data : {parentID : parentID, selected : selected},
			success : function(data){
				$("#parent").html(data);
			},
			error : function(){
				alert("Something went wrong with the sub category option!");
			}
		});			
	}
	$("select[name='grandparent']").change(function(){
		get_sub_category();
	});
      
    $("document").ready(function(){
		get_sub_category("<?=$parent;?>");
	});

</script>