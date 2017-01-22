<!-- ADMIN USERS -->

<?php 
include('../bdd.php');
include('frame.php');
?>

<div class="adminframe">

<?php 
// IF EDIT USER
if (isset($_POST['edituserID']))
{	
	//Get user info	
	$usereditid = $_POST['edituserID'];
	$sqluseredit = mysqli_query($bdd, "SELECT * FROM hig_user WHERE userID = $usereditid");
	$fetchuseredit = mysqli_fetch_array($sqluseredit);
?>
	<h1>Edit User - <?php echo $fetchuseredit['firstname'] . ' ' . $fetchuseredit['lastname'];?></h1>
	<form action="users.php" method="Post">
	<input name="usereditid" value="<?php echo $fetchuseredit['userID']?>" hidden>
	<table style="width:70%" align="center">
	<tr><td width="100px" align="right">First Name</td><td><input name="usereditfirstname" required size="25" value="<?php echo $fetchuseredit['firstname'];?>"></td><td></td></tr>
	<tr><td align="right">Last Name</td><td><input name="usereditlastname" required size="25" value="<?php echo $fetchuseredit['lastname'];?>"></td><td></td></tr>
	<tr><td align="right">E-mail</td><td><input name="usereditemail" required size="25" value="<?php echo $fetchuseredit['email'];?>"></td><td></td></tr>
	<tr><td align="right">Language</td><td><select name="usereditlang"><option value="<?php echo $fetchuseredit['lang'];?>"><?php echo $fetchuseredit['lang'];?>
	<?php
if ($handle = opendir('../images/flags')) 
{
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
			$optiontrimmed = strtoupper(substr("<option>$entry", 0 , -4));
            echo $optiontrimmed;
        }
    }
    closedir($handle);
}
	?>
	</td><td></td></tr>
	<tr><td align="right">Role</td><td><select name="usereditrole" value="<?php echo $fetchuseredit['role'];?>"><option <?php if ($fetchuseredit['role'] == 'Translator') {echo 'selected';}?>>Translator<option <?php if ($fetchuseredit['role'] == 'Developer') {echo 'selected';}?>>Developer<option <?php if ($fetchuseredit['role'] == 'Inactive') {echo 'selected';}?>>Inactive</td><td>The role of the user will determine which query status will be displayed in the main interface. For example, a same query displayed as "Pending" for a Developer will be displayed as "Open" for a Translator.<br>Developers cannot post new queries.<br>Setting an account as "Inactive" will prevent the user from loging in or having any access to the website. Their queries and answers will not be deleted. To reactivate an account, simply set to it to "Developer" or "Translator".</td></tr>
	<tr><td align="right">Admin</td><td><input name="usereditadmin" type="checkbox" <?php if ($fetchuseredit['admin'] == 'TRUE'){echo " checked";};?>></td><td>Check this box if this user is an administrator. Administrators have access to the admin section. They can close and delete any query.</td></tr>
	</table>
	<div align="center"><button type="button" class="myButton" onclick="javascript:window.location='users.php';">Cancel</button><input style="margin-top:10px; margin-left:10px;" type="submit" class="myButton" value="Save"></div>
	</form>
<?php }

// IF EDIT GAME ACCESS
else if (isset($_POST['editgauserID']))
{
	$usereditgaID = $_POST['editgauserID'];
	
	//Get user
	$sqlusereditga = mysqli_query($bdd, "SELECT * FROM hig_user WHERE userID = $usereditgaID");
	$fetchusereditga = mysqli_fetch_array($sqlusereditga);
	
	//Get all games
	$sqlusereditgagames = mysqli_query($bdd, "SELECT * FROM hig_games");
	?>	
	<h1>Games access - <?php echo $fetchusereditga['firstname'] . ' ' . $fetchusereditga['lastname']; ?></h1>
	<form action="users.php" method="Post">
	<input name="gameaccesschangeuser" value="<?php echo $fetchusereditga['userID'];?>" hidden>
	<table style="width:50%" align="center">		
		  <tr>
			<th>Game</th>
			<th>Name</th>
			<th>Access</th>
		  </tr>
	<?php while ($fetchallgameseditga = mysqli_fetch_array($sqlusereditgagames)) 
	{?>
		<?php
		$gameideditga = $fetchallgameseditga['gameID'];
		if ($fetchallgameseditga['game_status'] == 'Inactive')
		{echo '<tr><td><h7>' . $fetchallgameseditga['nameshort'] . '</h7></td><td><h7>' . $fetchallgameseditga['namelong'] . '</h7></td><td>' . '<INPUT type="checkbox" name="gameid' . $gameideditga . '"' ;}
		else
		{echo '<tr><td>' . $fetchallgameseditga['nameshort'] . '</td><td>' . $fetchallgameseditga['namelong'] . '</td><td>' . '<INPUT type="checkbox" name="gameid' . $gameideditga . '"' ;}
		
		// searching if game access exist for this specific user/game
		$sqlusereditga = mysqli_query($bdd, "SELECT * FROM hig_gameaccess WHERE ga_userid = $usereditgaID AND ga_gameid = $gameideditga");
		if (mysqli_fetch_array($sqlusereditga))
		{
			echo ' value="1" checked>';
		}
		else
		{
			echo ' value="0" >';
		}
		echo '</td></tr>';
	}
	echo '</table><div align="center"><button type="button" class="myButton" onclick="javascript:window.location=\'users.php\';">Cancel</button><input style="margin-top:10px; margin-left:10px;" type="submit" class="myButton" value="Save"></div></form>';
}

// IF NEW USER
else if (isset($_GET['action']) && $_GET['action'] == "newuser")
{?>
	<h1>Add a new user</h1>
	<form action="users.php" method="Post">
	<table style="width:70%" align="center">
	<tr><td width="100px" align="right">First Name</td><td><input name="newuserfirstname" size="25" required></td><td></td></tr>
	<tr><td align="right">Last Name</td><td><input name="newuserlastname" size="25" required></td><td></td></tr>
	<tr><td align="right">E-mail</td><td><input name="newuseremail" size="25" required></td><td></td></tr>
	
	
	<tr><td align="right">Language</td><td><select name="newuserlang"><option>
	<?php
if ($handle = opendir('../images/flags')) 
{
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
			$optiontrimmed = strtoupper(substr("<option>$entry", 0 , -4));
            echo $optiontrimmed;
        }
    }
    closedir($handle);
}
	?>
	</td><td></td></tr>
	
	
	<tr><td align="right">Role</td><td><select name="newuserrole"><option>Translator<option>Developer<option>Inactive</td><td>The role of the user will determine which query status will be displayed in the main interface. For example, a same query displayed as "Pending" for a Developer will be displayed as "Open" for a Translator.<br>Developers cannot post new queries.<br>Setting an account as "Inactive" will prevent the user from loging in or having any access to the website. Their queries and answers will not be deleted. To reactivate an account, simply set to it to "Developer" or "Translator".</td></tr>
	<tr><td align="right">Admin</td><td><input name="newuseradmin" type="checkbox"></td><td>Check this box if this user is an administrator. Administrators have access to the admin section. They can close and delete any query.</td></tr>
	</table>
	<div align="center"><button type="button" class="myButton" onclick="javascript:window.location='users.php';">Cancel</button><input style="margin-top:10px; margin-left:10px;" type="submit" class="myButton" value="Save"></div>
	</form>
<?php }

else
{
	// IF POST NEW USER
	if (isset($_POST['newuserrole']))
	{
		$postnewuserfirstname = nl2br(htmlentities($_POST['newuserfirstname'], ENT_QUOTES, 'UTF-8'));
		$postnewuserlastname = nl2br(htmlentities($_POST['newuserlastname'], ENT_QUOTES, 'UTF-8'));
		$postnewuseremail = nl2br(htmlentities($_POST['newuseremail'], ENT_QUOTES, 'UTF-8'));
		$postnewuserlang = strtoupper(nl2br(htmlentities($_POST['newuserlang'], ENT_QUOTES, 'UTF-8')));
		$postnewuserrole = nl2br(htmlentities($_POST['newuserrole'], ENT_QUOTES, 'UTF-8'));
		if (isset($_POST['newuseradmin']))
		{$postnewuseradmin = 'TRUE';}
		else
		{$postnewuseradmin = 'FALSE';}
	
			$sqlnewuser = 	"INSERT INTO hig_user (firstname, lastname, lang, email, role, admin)
							VALUES ('$postnewuserfirstname', '$postnewuserlastname', '$postnewuserlang', '$postnewuseremail', '$postnewuserrole', '$postnewuseradmin')"; 		
			mysqli_query ($bdd, $sqlnewuser) or die ('Erreur SQL !'.$sqlnewuser.'<br />'.mysqli_error($bdd));
	}

	// IF POST EDIT USER
	if (isset($_POST['usereditid']))
	{
		$postusereditid = $_POST['usereditid'];
		$postusereditfirstname = nl2br(htmlentities($_POST['usereditfirstname'], ENT_QUOTES, 'UTF-8'));
		$postusereditlastname = nl2br(htmlentities($_POST['usereditlastname'], ENT_QUOTES, 'UTF-8'));
		$postusereditemail = nl2br(htmlentities($_POST['usereditemail'], ENT_QUOTES, 'UTF-8'));
		$postusereditlang = strtoupper(nl2br(htmlentities($_POST['usereditlang'], ENT_QUOTES, 'UTF-8')));
		$postusereditrole = nl2br(htmlentities($_POST['usereditrole'], ENT_QUOTES, 'UTF-8'));
		if (isset($_POST['usereditadmin']))
		{$postusereditadmin = 'TRUE';}
		else
		{$postusereditadmin = 'FALSE';}
			$sqledituser = "UPDATE hig_user
								SET firstname = '$postusereditfirstname', lastname = '$postusereditlastname', email = '$postusereditemail', lang = '$postusereditlang', role = '$postusereditrole', admin = '$postusereditadmin'
								WHERE userID = $postusereditid;"; 		
			mysqli_query ($bdd, $sqledituser) or die ('Erreur SQL !'.$sqledituser.'<br />'.mysqli_error($bdd));
	}
	
	//IF POST FORM EDIT GAME ACCESS	-> SQL
	if (isset($_POST['gameaccesschangeuser']))
	{	
	//Get all games
	$idusergaedit = $_POST['gameaccesschangeuser'];
	$sqlusereditgagames = mysqli_query($bdd, "SELECT * FROM hig_games ORDER BY nameshort");
	while ($fetchallgameseditga = mysqli_fetch_array($sqlusereditgagames))
	{		
		$gameideditga = $fetchallgameseditga['gameID'];
		$postgameid ='$_POST[\'gameid' . $gameideditga . '\']';
		// search entry in db, returns false if doesn't exist
		$sqlcheckaccess = mysqli_query($bdd, "SELECT * FROM hig_gameaccess WHERE ga_userid = $idusergaedit AND ga_gameid = $gameideditga"); 
		
		// if game was unchecked and is now checked
		if (isset($_POST['gameid' . $gameideditga]) && $_POST['gameid' . $gameideditga] == 0 && !mysqli_fetch_array($sqlcheckaccess))
		{
			$sqladdaccess = "INSERT INTO hig_gameaccess (ga_userid, ga_gameid) VALUES ('$idusergaedit', '$gameideditga')"; 	
			mysqli_query ($bdd, $sqladdaccess) or die ('Erreur SQL ! '.$sqladdaccess.'<br />'.mysqli_error($bdd));			
		}
		// if unchecked
		else if (!isset($_POST['gameid' . $gameideditga]))
		{
			// if the game was checked but is now unchecked (watch directly in db)
			if (mysqli_fetch_array($sqlcheckaccess))
			{
			$sqldeleteaccess = "DELETE FROM hig_gameaccess WHERE ga_userid = $idusergaedit AND ga_gameid = $gameideditga"; 	
			mysqli_query ($bdd, $sqldeleteaccess) or die ('Erreur SQL ! '.$sqldeleteaccess.'<br />'.mysqli_error($bdd));					
			}
		}
	}		
	}
	?>	
	<!-- LIST OF USERS -->
		<h1>List of users - <?php if (isset($_GET['filteractive']) && $_GET['filteractive'] == 'Inactive') {echo 'Inactive';} else {echo 'Active';}?></h1>
		<?php if (isset($_GET['filteractive']) && $_GET['filteractive'] == 'Inactive')
		{echo '<a href="users.php">Active users</a>';}
		else {?>
			<a href="users.php?filteractive=Inactive">Inactive users</a><?php } ?>
			<br><br>
	<table style="width:100%">		
		  <tr>
			<th><a href ="users.php?action=newuser"><img title="Add a new user" src="../images/add.png" style="margin-top:5px;"></img></th>
			<th>Name</th>
			<th>Lang</th>
			<th>E-mail</th>
			<th>Games access</th>
			<th>Role</th>
			<th>Admin</th>
		  </tr>	
	<?php
		$rolesql = "role = 'Translator' or role = 'Developer'";
		if (isset($_GET['filteractive']) && $_GET['filteractive'] == 'Inactive')
		{$rolesql = "role = 'Inactive'";}
		$sqlusers = "SELECT * FROM hig_user WHERE $rolesql ORDER BY userID ASC;";
		$allusers = @mysqli_query($bdd, $sqlusers) or die ('Erreur SQL !'.$sqlusers.'<br />'.mysqli_error($bdd));;
		while ($fetchusers = mysqli_fetch_array($allusers)) 
		{?> <tr>

		<td width="20px;">	
		
		<!-- EDIT USER ICON -->
		<form name="edituser" action="users.php" method="post">
		<input name="edituserID" value="<?php echo $fetchusers['userID'];?>" hidden>
		<div class="buttonedithidden" style="display:inline-block; float:left;"><input type="submit" name="" value="" title="Edit account - <?php echo $fetchusers['firstname'] . ' ' . $fetchusers['lastname']; ?>"></div></form>	
		</td>
		
		<td width="180px" style="<?php if ($fetchusers['role'] == "Inactive"){echo 'background-color:#DDDDDD;';} if ($fetchusers['admin'] == "TRUE"){echo ' font-weight: bold; ';}?>"><?php echo $fetchusers['firstname'] . '&nbsp;' . $fetchusers['lastname']; ?></td>
		<td width="40px" align="center"><img src="../images/flags/<?php echo $fetchusers['lang']; ?>.png"></td>
		<td width="180px"><?php echo $fetchusers['email']; ?></td>
		<td width="350px">
		<?php 
		$adminuserid = $fetchusers['userID'];
		$sqlgetgameaccess =  "SELECT * FROM hig_gameaccess LEFT JOIN hig_games ON hig_games.gameID = hig_gameaccess.ga_gameid WHERE $adminuserid = ga_userid ORDER BY gameaccessid ASC" ; 
		$userga = @mysqli_query($bdd, $sqlgetgameaccess);
		$str = "";
		while ($fetchuserga = mysqli_fetch_array($userga)) 
		{
			if ($fetchuserga['game_status'] == 'Inactive')
				{$str .= '<h7>' . $fetchuserga['nameshort'] . '</h7> - ';}
			else
				{$str .= $fetchuserga['nameshort'] . ' - ';}
		}	
		echo rtrim($str," - ");?>	
		
		<!-- EDIT GAME ACCESS ICON -->
		<div style="display:inline-block; float:right;" title="Edit games access - <?php echo $fetchusers['firstname'] . ' ' . $fetchusers['lastname']; ?>">
		<form name="editgauser" action="users.php" method="post">
		<input name="editgauserID" value="<?php echo $fetchusers['userID'];?>" hidden>
		<div class="buttonedithidden"><input type="submit" name="" value=""></div></form></div>		
		</td>
		
		<td width="80px" align="center"><?php echo $fetchusers['role']; ?></td>
		<td align="center"><?php if ($fetchusers['admin'] == "TRUE") {echo "YES";} else {echo "NO";} ?></td>
		</tr>	
<?php } ?>	
	</table>
<?php } ?>

</div>
		
</div>	

</body>

</html>