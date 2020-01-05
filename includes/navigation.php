<?php
    $sql = "select * from categories where parent=0;";
	$result = mysqli_query($conn, $sql);
?>

<!-- Main Navbar... -->
	<nav class="navbar navbar-default navbar-fixed-top">
	    <div class="container-fluid">
		    <div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand" href="index.php">Shaunta's Boutique</a>
			</div>
		<!--<a href="index.php" class="navbar-brand">Shaunta's Boutique</a>-->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
				<?php while($data = mysqli_fetch_assoc($result)): ?>
				<?php
					$parent_id = $data["id"];
					$sql1 = "select * from categories where parent = $parent_id;";
					$result1 = mysqli_query($conn, $sql1);
				?>
				<!-- menu items -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $data["category"]; ?><span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
						<?php while($data1 = mysqli_fetch_assoc($result1)): ?>
							<li><a href="category.php?cat=<?=$data1["id"];?>"><?php echo $data1["category"]; ?></a></li>
						<?php endwhile; ?>	
						</ul><!-- dropdown-menu -->
					</li><!-- dropdown -->
					<?php endwhile; ?>
					<li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> My Cart</a></li>
				</ul><!-- nav -->
			</div><!--collapse-->	
		</div><!--container-fluid-->
	</nav><!-- navbar -->