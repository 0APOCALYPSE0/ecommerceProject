<!-- Main Navbar... -->
	<nav class="navbar navbar-default navbar-fixed-to">
	    <div class="container">
		<a href="index.php" class="navbar-brand">Shaunta's Boutique Admin</a>
		    <ul class="nav navbar-nav">
			<!-- menu items -->
				<li><a href="brand.php">Brands</a></li>
				<li><a href="categories.php">Categories</a></li>
				<li><a href="products.php">Products</a></li>
				<?php if(has_permission("admin")): ?>
				<li><a href="users.php">Users</a></li>
				<?php endif; ?>
				<li class="dropdown">
				    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?=$user_data["first"];?><span href="" class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="change_password.php">Change Password</a></li>
						<li><a href="logout.php">Log Out</a></li>
					</ul>
				</li>
				</li><!-- nav -->
			</ul><!-- nav -->
		</div><!--container-->
	</nav><!-- navbar -->