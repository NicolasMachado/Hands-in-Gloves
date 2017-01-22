<!-- New query dialog -->
<div class="hiddendialog">

<!-- DIALOG NEW QUERY -->
<div id="newqueryid" style="background-color:#d6e0f5; text-align:center;">
<form name="newqueryform" action="index.php" method="post">
<div style="text-align: left; text-align:center;">

<!-- Priority select NEWQUERY -->	
<div style="display:inline-block; vertical-align:top; height: 180px; width: 150px;">

<div id="prioselect" class="" style="width:80px; text-align: left;">
	<h2>PRIORITY</h2>
	<input id="newquerypriohigh" type="radio" name="newquerypriority" value="1"> <label for ="newquerypriohigh">1 - High</label>
	<br>
	<input id="newqueryprionormal" type="radio" name="newquerypriority" value="2" checked> <label for ="newqueryprionormal">2 - Normal</label>
	<br>
	<input id="newquerypriolow" type="radio" name="newquerypriority" value="3"> <label for ="newquerypriolow">3 - Low</label>
</div>

<!-- Game select NEWQUERY -->	
<!-- The list is not displayed if content <= 1 (user only have acces to one game or 0) -->
<?php $sqlgamescurrentuser = mysqli_query($bdd, "SELECT * FROM hig_games WHERE hig_games.gameID IN (SELECT ga_gameid FROM hig_gameaccess WHERE hig_gameaccess.ga_userID = $currentuserid) AND game_status != 'Inactive' ORDER BY nameshort ASC");	
$rowCount = mysqli_num_rows($sqlgamescurrentuser);
if ($rowCount > 1)
{?>	
	<div id="newquerygameselect" class="" style="text-align: left; width:100px; padding-top:10px;" >
	<h2 style="margin-bottom:6px;">GAME:</h2>
	<select name="newquerygame">
	<?php 
		while ($fetchgamescurrentuser = mysqli_fetch_array($sqlgamescurrentuser))
			{
				echo '<option'; if ($_SESSION['game'] == $fetchgamescurrentuser['gameID']){echo ' selected="selected"';} echo ' value="' . $fetchgamescurrentuser['gameID'] . '">' . $fetchgamescurrentuser['nameshort'] . '</option>';
			}
	echo '</select> </div>'; 
} 
else
{	
	// auto select game if user only has access to 1 game
	?><select name="newquerygame" hidden><?php
		while ($fetchgamescurrentuser = mysqli_fetch_array($sqlgamescurrentuser))
			{
				echo '<option'; if ($_SESSION['game'] == $fetchgamescurrentuser['gameID']){echo ' selected="selected"';} echo ' value="' . $fetchgamescurrentuser['gameID'] . '">' . $fetchgamescurrentuser['nameshort'] . '</option>';
			}
	echo '</select>'; 	
}
	?>
</div>

<div style="display:inline-block; height: 180px;">	

	<h2>SOURCE TEXT:</h2>
	<textarea name="newquerysourcetext" rows="2" cols="50" placeholder="Source text" style="width:300px;height:50px;"></textarea>
	<h2  style="margin-top:15px;">STRING ID:</h2>
	<textarea name="newquerystringid" rows="2" cols="50" placeholder="String ID (mutiple entries possible)" style="width:300px;height:50px;"></textarea>
	
</div>

<div  style="width:500px; text-align:center; display:inline-block;">
	<h2>QUERY:</h2>
	<textarea name="newqueryquery" rows="4" cols="70" placeholder="Enter your query..." required style="width:450px;height:70px;"></textarea>
</div>

</div>

<div style="text-align: center; padding-top:5px;">
	<input id="newqueryconfirm" type="submit" class="myButton" value="SUBMIT" align="center">
</div>

</form>

</div>

</div>