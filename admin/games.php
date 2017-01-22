<?php 	
include('../bdd.php');
include('frame.php');
?>

<div class="adminframe">
<?php 
// IF EDIT GAME
if (isset($_POST['editgameID']))
{
// get game info
$editgameid = $_POST['editgameID'];
$sqlgameedit = mysqli_query($bdd, "SELECT * FROM hig_games WHERE gameID = $editgameid");
$fetchgameedit = mysqli_fetch_array($sqlgameedit);
?>
<h1>Edit game - <?php echo $fetchgameedit['nameshort']; ?></h1>
<form action="games.php" method="Post">
<table style="width:50%" align="center">
<input name="editgameid" value="<?php echo $fetchgameedit['gameID'];?>" hidden>
<tr><td width="100px" align="right">Short name</td><td><input maxlength="4" name="editgamenameshort" size="4" value="<?php echo $fetchgameedit['nameshort']; ?>" required></td><td>4 letters max</td></tr>
<tr><td align="right">Full name</td><td><input name="editgamenamelong" value="<?php echo $fetchgameedit['namelong']; ?>" size="25" required></td><td></td></tr>
<tr><td align="right">Status</td><td><select name="editgamestatus" value="<?php echo $fetchgameedit['game_status'];?>"><option <?php if ($fetchgameedit['game_status'] == 'Active') {echo 'selected';}?>>Active<option <?php if ($fetchgameedit['game_status'] == 'Inactive') {echo 'selected';}?>>Inactive</td><td>Queries from an "Inactive" game will not be accessible to anyone, but they will NOT be deleted.</td></tr>
</table>
<div align="center"><button type="button" class="myButton" onclick="javascript:window.location='games.php';">Cancel</button><input style="margin-top:10px; margin-left:10px;" type="submit" class="myButton" value="Save"></div>
</form>
<?php }
	
// IF NEW GAME
else if (isset($_GET['action']) && $_GET['action'] == 'newgame')
{?>
<h1>Add a new game</h1>
<form action="games.php" method="Post">
<table style="width:40%" align="center">
<tr><td width="100px" align="right">Short name</td><td><input maxlength="4" name="newgamenameshort" size="4" required></td><td>4 letters max</td></tr>
<tr><td align="right">Full name</td><td><input name="newgamenamelong" size="25" required></td><td></td></tr>
</table>
<div align="center"><button type="button" class="myButton" onclick="javascript:window.location='games.php';">Cancel</button><input style="margin-top:10px; margin-left:10px;" type="submit" class="myButton" value="Save"></div>
</form>
	
<?php }
else
{
// IF POST NEW GAME
if (isset($_POST['newgamenameshort']))
{
	$postnewgamenameshort = strtoupper(nl2br(htmlentities($_POST['newgamenameshort'], ENT_QUOTES, 'UTF-8')));
	$postnewgamenamelong = nl2br(htmlentities($_POST['newgamenamelong'], ENT_QUOTES, 'UTF-8'));
	
	$sqlnewgame = "INSERT INTO hig_games (nameshort, namelong, game_status)
						VALUES ('$postnewgamenameshort', '$postnewgamenamelong', 'Active')"; 		
	mysqli_query ($bdd, $sqlnewgame) or die ('Erreur SQL !'.$sqlnewgame.'<br />'.mysqli_error($bdd));
}

// IF POST EDIT GAME
if (isset($_POST['editgamestatus']))
{
	$posteditgamenameshort = strtoupper(nl2br(htmlentities($_POST['editgamenameshort'], ENT_QUOTES, 'UTF-8')));
	$posteditgamenamelong = nl2br(htmlentities($_POST['editgamenamelong'], ENT_QUOTES, 'UTF-8'));
	$posteditgamestatus = $_POST['editgamestatus'];
	$posteditgameid = $_POST['editgameid'];
	
	$sqleditgame = "UPDATE hig_games
						SET nameshort = '$posteditgamenameshort', namelong = '$posteditgamenamelong', game_status = '$posteditgamestatus'
						WHERE gameID = '$posteditgameid'"; 		
	mysqli_query ($bdd, $sqleditgame) or die ('Erreur SQL !'.$sqleditgame.'<br />'.mysqli_error($bdd));
}
?>		

<!-- LIST OF GAMES -->	
<h1>List of games<?php if (isset($_GET['filtergameactive']) && $_GET['filtergameactive'] == 'Inactive'){echo ' - Inactive';}else{echo ' - Active';}?></h1>
<?php if (isset($_GET['filtergameactive']) && $_GET['filtergameactive'] == 'Inactive')
	  {echo '<a href="games.php">Active games</a>';}
		else {?>
		<a href="games.php?filtergameactive=Inactive">Inactive games</a><?php } ?>
		<br><br>

<table style="width:80%" align="center">
<tr>
	<th><a href ="games.php?action=newgame"><img title="Add a new game" src="../images/add.png" style="margin-top:5px;"></a></th>
	<th>Short Name</th>
	<th>Name</th>
	<th>Open/Pending queries</th>
	<th>Closed queries</th>
	<th>Deleted queries</th>
 </tr>
<?php 
$filtergame = 'WHERE game_status = \'Active\'';
if (isset($_GET['filtergameactive']) && $_GET['filtergameactive'] == 'Inactive')
{$filtergame = 'WHERE game_status = \'Inactive\'';}
$sqllistgames = mysqli_query($bdd, "SELECT * FROM hig_games $filtergame ORDER BY gameID");
while ($fetchallgames = mysqli_fetch_array($sqllistgames))
{?>	<tr><td width="20px;">	

	<!-- EDIT GAME ICON -->
	<form name="editgame" action="games.php" method="post">
	<input name="editgameID" value="<?php echo $fetchallgames['gameID'];?>" hidden>
	<div class="buttonedithidden" style="display:inline-block; float:left;"><input type="submit" name="" value="" title="Edit game - <?php echo $fetchallgames['nameshort']; ?>"></div></form>	
	</td>
	<td width="80px" align="center"style="<?php if ($fetchallgames['game_status'] == "Inactive"){echo 'background-color:#DDDDDD;';}?>"><?php echo $fetchallgames['nameshort'];?> </td>
	<td> <?php echo $fetchallgames['namelong'];
	$gameid = $fetchallgames['gameID'];		?> </td>
	
	<!-- STATS -->
	<?php	$sqlcountgamesopen = mysqli_query($bdd, "SELECT ID FROM hig_query WHERE game = $gameid AND (status = 'answeredbydev' OR status = 'answeredbytrans')");
	$countopen = mysqli_num_rows($sqlcountgamesopen);?>
	<td align="center"><?php echo $countopen; ?></td>
	
	<?php	$sqlcountgamesclosed = mysqli_query($bdd, "SELECT ID FROM hig_query WHERE game = $gameid AND status = 'closed'");
	$countclosed = mysqli_num_rows($sqlcountgamesclosed);?>
	<td align="center"><?php echo $countclosed; ?></td>
	
	<?php	
	$sqlcountgamesdeleted = mysqli_query($bdd, "SELECT ID FROM hig_query WHERE game = $gameid AND status = 'deleted'");
	$countdeleted = mysqli_num_rows($sqlcountgamesdeleted);?>
	<td align="center" style="background-color:#eeeeee"><?php echo $countdeleted; ?></td>
	
	</tr>
	
<?php } ?>
</table>
<?php } ?>

</div>	

</div>	

</body>
	
</html>