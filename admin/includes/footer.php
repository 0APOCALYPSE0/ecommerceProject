</div><!-- row -->
	</div><br><br><!--container-fluid-->

	<!-- footer -->
	<footer class="col-md-12 text-center" id="footer">
	    &copy; Copyright 2018 Shaunta's Boutique
	</footer><!-- footer -->

	<script>
	    function updateSizes(){
			var stringSizes = "";
			for(var i=1; i<=12; i++){
				if($("#size"+i).val() != ""){
					stringSizes += $("#size"+i).val()+":"+$("#qty"+i).val()+":"+$("#threshold"+i).val()+",";
				}
			}
			$("#sizes").val(stringSizes);
		}

	    function get_sub_category(selected){
				if(typeof selected === "undefined"){
					var selected ="";
				}
		    var parentID = $("#category").val();
        jQuery.ajax({
				url : "/ecommerceProject/admin/parsers/sub_category.php",
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
		// $("select[name='category']").change(function(){
		// 	get_sub_category();
		// });
		$("select[name='category']").change(function () {
			get_sub_category();
		});
	</script>

</body>
</html>