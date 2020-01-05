<?php
    require_once "../core/db.php";
	unset($_SESSION["SBuser"]);
	header("Location: login.php");
?>