<?php 
// Database connection
$db_username = 'root';
$db_password = '';
$db_name = 'handsingloves';
$db_host = 'localhost';

$bdd = mysqli_connect($db_host, $db_username, $db_password, $db_name)or die('could not connect to database');


//Get current user
$sqlcurrentuser = mysqli_query($bdd, "SELECT * FROM hig_user WHERE userid =1");
$fetchcurrentuser = mysqli_fetch_array($sqlcurrentuser);

// if user is not logged in, redirect him to login page
if(!$fetchcurrentuser)
{
	header('Location: /handsingloves/login.php'); 
}

if(!isset($_SESSION)) 
{ 
	session_cache_limiter ('private_no_expire, must_revalidate');
	session_start();

	// transfer db user data to session (after connection)
	$_SESSION['firstname'] = $fetchcurrentuser['firstname'];
	$_SESSION['lastname'] = $fetchcurrentuser['lastname'];
	$_SESSION['email'] = $fetchcurrentuser['email'];
	$_SESSION['userID'] = $fetchcurrentuser['userID'];
	$_SESSION['admin'] = $fetchcurrentuser['admin'];
	$_SESSION['role'] = $fetchcurrentuser['role'];
}

// kick user out if user is inactive
if ($_SESSION['role'] == 'Inactive')
{
	header('Location: /handsingloves/accessdenied.php'); 
} 
?>