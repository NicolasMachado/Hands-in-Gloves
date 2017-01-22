<?php 	
include('../bdd.php');

// kick user out if user if not admin
if ($_SESSION['admin'] == 'FALSE')
{
	header('Location: /handsingloves/accessdenied.php'); 
} 
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="../style.css">
	<title>Hands in Gloves - Admin</title>
</head>

<body>

<!-- Main frame -->
<div id="mainframeadmin">

<div align="center">
	<a href="../index.php"><img src="../images/logomain.png"></a>
</div>	

<!-- Header TOP -->
<header>
</header>

<nav>
	<a style="display:inline-block; float:right;" href="../index.php">
	<div class="boutonnav">
	Back to site
	</div>
	</a>
	<a style="display:inline-block;" href="index.php">
	<div class="boutonnav">
	Admin
	</div>
	</a>
	<a style="display:inline-block" href="users.php">
	<div class="boutonnav">
	Users
	</div>
	</a>
	<a style="display:inline-block" href="games.php">
	<div class="boutonnav">
	Games
	</div>
	</a>
</nav>