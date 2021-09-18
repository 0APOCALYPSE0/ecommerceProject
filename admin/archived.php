<?php
  require_once $_SERVER["DOCUMENT_ROOT"]."/ecommerceProject/core/db.php";
	include "includes/header.php";
	include "includes/navigation.php";
	if(!is_logged_in()){
		login_error_redirect();
	}
?>
  <?php
    $sql = "select * from products where deleted = 1;";
    $result = mysqli_query($conn, $sql);
    //delete code...
    if(isset($_GET["id"])){
      $id = (int)$_GET["id"];
      $id = sanitize($id);
      $deleteSql = "update products set deleted = 0 where id = '$id';";
      $deleteResult = mysqli_query($conn, $deleteSql);
      header("Location: archived.php");
    }
  ?>
    <h2 class="text-center">Archived Products</h2>
    <div class="clearfix"></div><hr>
    <table class="table table-bordered table-condensed table-stripped">
      <thead>
        <th>Restore</th>
        <th>Product</th>
        <th>Price</th>
        <th>Category</th>
        <th>Sold</th>
      </thead>
      <tbody>
      <?php while($data = mysqli_fetch_assoc($result)):
        $childID = $data["categories"];
        $catSql = "select * from categories where id ='$childID';";
        $catResult = mysqli_query($conn, $catSql);
        $subcat = mysqli_fetch_assoc($catResult);
        $parentID = $subcat["parent"];
        $pSql = "select * from categories where id = '$parentID';";
        $pResult = mysqli_query($conn, $pSql);
        $cat = mysqli_fetch_assoc($pResult);
        $category = $cat["category"].' - '.$subcat["category"];
        ?>
          <tr>
            <td><a href="archived.php?id=<?= $data["id"];?>" class="btn btn-xs btn-secondary"><span class="glyphicon glyphicon-refresh"></span></a></td>
            <td><?= $data["title"];?></td>
            <td><?= money($data["price"]);?></td>
            <td><?= $category;?></td>
            <td><?=$data["deleted"];?></td>
          </tr>
      <?php endwhile; ?>
      </tbody>
    </table>

  <?php
      include "includes/footer.php";
  ?>