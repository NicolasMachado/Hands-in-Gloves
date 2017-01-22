<?php 	 
include('bdd.php');
include('filtering.php'); 
include('queryprincipale.php'); 
include_once('paginate.php');
?>

<!DOCTYPE html>

<html>
    <head>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="style.css">
		<link rel="stylesheet" href="jquery-ui.css">
		<script src="js/jquery-1.11.3.js"></script>
		<script src="js/jquery-ui.js"></script> 
		<title>Hands in Gloves</title>
		<?php include('scriptuidialog.php');?>
    </head>
	
    <body>
	<?php include('formdialogs.php'); ?>
	
	<!-- Main frame -->
	<div id="mainframe">
	<a href="index.php"><div align="center"><img src="images/logomain.png"></div></a>
	
	<header>		 
		<!-- <div style="float:left;"><img title="Sign out" src="images/signout.png"></div> -->
		
		<!-- If user is admin, display link to admin section -->
		<div style="float: right; margin-top: 48px; margin-left:10px;">
			<?php if ($_SESSION['admin'] == 'TRUE'){echo '<a title="Administration" href="admin/"><img src="images/tools.png"></a>';}?>
		</div>
		
		<!-- Search form -->
		<div style="float: right; margin-top: 47px;">
			<form action="index.php" method="Post" title="Search in query, in source text or in string ID">
			<input id = "testsearchfield" type="text" placeholder="Search" class="searchfield" name="search" size="40" value="<?php if (isset($_POST['search'])) {echo $_POST['search'];} ?>">
			</form>
		</div>
	</header>
	
	<!-- Left section - filters -->
	<div id="menufilter">
	<?php include('formfilter.html'); ?>
	</div>
	
	<!-- Section Article + Aside -->
	<?php	
		$res = @mysqli_query($bdd, $queries);
		
		// If there is at least on query
		if ($count > 0)
		{
	?>

	 <section id="sectionarticle">		
	 <?php
				// Main loop
				while ($fetchqueries = mysqli_fetch_array($res))
				{
					include('scriptaccordeon.php');?>
					
					<!-- Conteneur question + réponses -->
					<div id="conteneur<?php echo $fetchqueries['ID']?>" class="conteneur">
			
						<!-- Header de l'article -->
						<div id="<?php echo $fetchqueries['ID'] ?>" class='article'>	
							<div id ="HQtext<?php echo $fetchqueries['ID'] ?>" class="headerquery">
							
								<!-- Image header query -->
								<div class="divimgheaderquery" style="background-image:url(images/iconssmall/<?php echo $fetchqueries['nameshort'];?>.png);"  title="<?php echo $fetchqueries['namelong'];?>">
									<div class="texticonheader"><?php echo $fetchqueries['nameshort'];?></div>
								</div>
								<div class="blocpriority"
								<?php
									if ($fetchqueries['priority'] == '1')
									{echo ' style="color:#ff9999; background-color:#f3f3f3;" title="High Priority">P1';}
									else if ($fetchqueries['priority'] == '2')
									{echo ' style="color:#ffc266; background-color:#f3f3f3;" title="Normal Priority">P2';}
									else if ($fetchqueries['priority'] == '3')
									{echo ' style="color:#aaaaaa; background-color:#f3f3f3;" title="Low Priority">P3';}
								?>
								</div>
								<?php echo $fetchqueries['question']; ?>
							</div>
						</div>
					
							<!-- ASIDE -->
							<aside style="background-color : 
							<?php if ($_SESSION['role'] == 'Translator') 
							{switch ($fetchqueries['status']){case 'answeredbydev': echo '#ffcccc'; break; case 'answeredbytrans': echo '#ffe5b3'; break; case 'closed': echo '#ececec'; break; case 'deleted': echo '#bbbbbb'; break;}} 
							else if ($_SESSION['role'] == 'Developer') 
							{switch ($fetchqueries['status']){case 'answeredbydev': echo '#ffe5b3'; break; case 'answeredbytrans': echo '#ffcccc'; break; case 'closed': echo '#ececec'; break; case 'deleted': echo '#bbbbbb'; break;}} 
							?>">						
								<div style="float:left; margin:5px 5px 5px 5px;">
									<?php echo 'QID: ' . $fetchqueries['ID'] . '<br>' . date('d M Y', strtotime($fetchqueries['date']));
									$queryid = $fetchqueries['ID'];
									$result = mysqli_query($bdd, "SELECT * FROM hig_answer WHERE queryid = '$queryid'" );
									$num_rows = mysqli_num_rows($result);
									echo '<br>';
										
									// Display simple status text if non admin
									if ($_SESSION['admin'] == 'FALSE')
									{
										if ($fetchqueries['status'] == 'closed'){$statusquery = "Closed";}
										else if ($fetchqueries['status'] == 'deleted'){$statusquery = "Deleted";}
										else if ($fetchqueries['status'] == 'answeredbytrans'){if ($_SESSION['role'] == 'Translator') {$statusquery = "Pending";} else if ($_SESSION['role'] == 'Developer') {$statusquery = "Open";}}
										else if ($fetchqueries['status'] == 'answeredbydev'){if ($_SESSION['role'] == 'Translator') {$statusquery = "Open";} else if ($_SESSION['role'] == 'Developer') {$statusquery = "Pending";}}
										echo $statusquery . '<br>';
									}
									
									// Display status drop down list if user is admin
									else
									{ ?>
										<form name="changequerystatusform" action="index.php<?php if ($current > 1){echo '?p=' . $current;} if ($searchID != ""){echo '?&amp;QID=' . $textsearchID;}?>" method="post" onsubmit="return confirm('Are you sure you want to change the status of the query # <?php echo $fetchqueries['ID']; ?>?');">
											<input name="changequerystatusID" value="<?php echo $fetchqueries['ID']?>" hidden>
											<select name="changequerystatus" onchange="this.form.submit();">
											<?php
											if ($_SESSION['role'] == 'Translator')
											{
												if ($fetchqueries['status'] == 'answeredbydev'){echo '<option selected="selected" value="answeredbydev">Open</option>';} else {echo '<option value="answeredbydev">Open</option> ';}
												if ($fetchqueries['status'] == 'answeredbytrans'){echo '<option selected="selected" value="answeredbytrans">Pending</option> ';} else {echo '<option value="answeredbytrans" >Pending</option> ';}
											} 
											else if ($_SESSION['role'] == 'Developer')
											{
												if ($fetchqueries['status'] == 'answeredbydev'){echo '<option selected="selected" value="answeredbydev">Pending</option> ';} else {echo '<option value="answeredbydev">Pending</option> ';}
												if ($fetchqueries['status'] == 'answeredbytrans'){echo '<option selected="selected" value="answeredbytrans">Open</option> ';} else {echo '<option value="answeredbytrans">Open</option> ';}
											} 
											?>
											<option <?php if ($fetchqueries['status'] == 'closed'){echo 'selected="selected" ';} ?>value="closed">Closed</option>
											<option <?php if ($fetchqueries['status'] == 'deleted'){echo 'selected="selected" ';} ?>value="deleted">Deleted</option>
											</select>
										</form>
									<?php }
									
									echo 'Replies: ' .$num_rows; ?>						
								</div>
								
								<div style="float:right; margin:5px 5px 5px 5px;">
								
								<!-- CLOSE ICON -->
								<?php if ($fetchqueries['status'] != 'closed' && $_SESSION['role'] == 'Translator' && $_SESSION['admin'] == 'FALSE'){?>
								<form name="closequeryform" action="index.php<?php if ($current > 1){echo '?p=' . $current;} if ($searchID != ""){echo '?&amp;QID=' . $textsearchID;}?>#<?php echo $fetchqueries['ID']?>" method="post" onsubmit="return confirm('Are you sure you want to CLOSE the query # <?php echo $fetchqueries['ID']; ?>?');">
								<input name="closequeryID" value="<?php echo $fetchqueries['ID']?>" hidden>
								<div class="buttonclosehidden"><input type="submit" name="" value="" title="Close query"></div></form>
								<?php } else {echo "<br>";} ?>
								<br>

								<!-- PERM DELETE ICON -->
								<?php if ($_SESSION['admin'] == 'TRUE'){?>
								<form name="deletepermqueryform" action="index.php<?php if ($current > 1){echo '?p=' . $current;} if ($searchID != ""){echo '?&amp;QID=' . $textsearchID;}?>" method="post" onsubmit="return confirm('Are you sure you want to PERMANENTLY DELETE the query #<?php echo $fetchqueries['ID']; ?> and all its answers?\n\nWARNING: Once permanently deleted, the query will not be recoverable.\n\n');">
								<input name="deletepermqueryID" value="<?php echo $fetchqueries['ID']?>" hidden>
								<div class="buttondeletepermhidden"><input type="submit" name="" value="" title="Permanently delete query"></div></form>
								<?php } else {echo "<br>";} ?>

								<!-- DELETE ICON -->
								<?php if ($fetchqueries['status'] != 'deleted'){
								if ($fetchqueries['reporter'] == $currentuserid){?>
								<form name="deletequeryform" action="index.php<?php if ($current > 1){echo '?p=' . $current;} if ($searchID != ""){echo '?&amp;QID=' . $textsearchID;}?>#<?php echo $fetchqueries['ID']?>" method="post" onsubmit="return confirm('Are you sure you want to DELETE the query # <?php echo $fetchqueries['ID']; ?>?');">
								<input name="deletequeryID" value="<?php echo $fetchqueries['ID'];?>" hidden>
								<div class="buttondeletehidden"><input type="submit" name="" value="" title="Delete query" style="margin-top:2px;"></div></form>
								<?php }} ?>
								
								</div>
								
							</aside><br>
							
							<!-- Bloc source text -->
							<div class='querysourcetext'>	
								<p align="right">
								<?php if($fetchqueries['firstname'] && $fetchqueries['lastname']) {echo '<b>' . $fetchqueries['firstname'] . ' ' . $fetchqueries['lastname'] . '</b>&nbsp;&nbsp;<img src="images/flags/' . $fetchqueries['lang'] . '.png" height="10px" width="10px" onerror="this.src=\'images/df.png\'">&nbsp;&nbsp;(' . date('d M Y - H:i', strtotime($fetchqueries['date'])) . ')';} else{echo '<b>Unknown user</b> (' . date('d M Y - H:i', strtotime($fetchqueries['date'])) . ')';} ?>
								</p>							
								<div style="padding-bottom:10px;">
									<h3>Source text:</h3><p id="source<?php echo $fetchqueries['ID'];?>" class="queryinfo"><?php echo $fetchqueries['sourcetext'] ?></p>
									<h3>String ID:</h3><p class="queryinfo"><?php echo $fetchqueries['stringids'] ?></p>
								</div>
							</div><br>
								
							<!-- ANSWER LOOP -->
							<?php 
								$queryid = $fetchqueries['ID'];
								$answers = mysqli_query($bdd, "SELECT * FROM hig_answer WHERE queryid = '$queryid' ORDER BY dateanswer ASC");
								while ($fetchanswers = mysqli_fetch_array($answers))
								{ 
									?>
									
									<!-- Answer blocs -->
									<article class="articleexpand">							
									<?php // Get reporter
									$reporter = $fetchanswers['reporteranswer'];
									$useranswer = @mysqli_query($bdd, "SELECT * FROM hig_user WHERE hig_user.userID = $reporter");
									$fetchuseranswer = mysqli_fetch_array($useranswer);?>
									
									<!-- Display text answer -->
									<p><?php echo $fetchanswers['answer'];?></p>
									
									<!-- PERM DELETE ICON -->
									<div style="display:inline-block;float:right;padding:5px;" align="right">
										<?php if ($_SESSION['admin'] == 'TRUE'){?>
										<form name="deletepermansweryform" action="index.php<?php if ($current > 1){echo '?p=' . $current;} if ($searchID != ""){echo '?&amp;QID=' . $textsearchID;}?>#<?php echo $fetchqueries['ID']?>" method="post" onsubmit="return confirm('Are you sure you want to PERMANENTLY DELETE this answer?\n\nWARNING: Once permanently deleted, the answer will not be recoverable.\n\n');">
										<input name="deletepermanswerID" value="<?php echo $fetchanswers['answerID'];?>" hidden>
										<input name="deletepermanswerqueryID" value="<?php echo $fetchqueries['ID'];?>" hidden>
										<div class="buttondeletepermhidden"><input type="submit" name="" value="" title="Permanently delete answer"></div></form>
										<?php } ?>
									</div>
									
									<div style="display:inline-block;float:right;padding:5px;color: #555555;" align = 'right'>
										<?php if($fetchuseranswer['firstname'] && $fetchuseranswer['lastname']) {echo '<b>' . $fetchuseranswer['firstname'] . ' ' .  $fetchuseranswer['lastname'] . '</b>&nbsp;&nbsp;<img src="images/flags/' . $fetchuseranswer['lang'] . '.png" height="10px" width="10px" onerror="this.src=\'images/df.png\'">&nbsp;&nbsp;(' . date("d M Y - H:i", strtotime($fetchanswers["dateanswer"])) . ')';} else{echo '<b>Unknown user</b> (' . date('d M Y - H:i', strtotime($fetchanswers['dateanswer'])) . ')';} ?>
									</div>
									
									</article>
								<?php } ?>
							
							<!-- Afficher formulaire réponse -->
							<article class="articleexpand" style="background-color:#DDDDDD; width: 649px;">
								<div class="divformanswer" style="width: 629px;">
									<form id="answerformid<?php echo $fetchqueries['ID']?>" name="answerform<?php echo $fetchqueries['ID']?>" method="post" action="index.php<?php if ($current > 1){echo '?p=' . $current;} if ($searchID != ""){echo '?&amp;QID=' . $textsearchID;}?>#<?php echo $fetchqueries['ID']?>">
										<textarea name="answertext" rows="3" cols="60" style="height: 50px; width:620px;" placeholder="Enter your reply..." required></textarea><br> 
										<input class="button" name="formanswerqueryid" value="<?php echo $fetchqueries['ID']?>" hidden>
										<input class="button" name="formanswerreporter" value="<?php echo $_SESSION['userID']?>" hidden>
										<input id="answerformbutton<?php echo $fetchqueries['ID']?>" type="submit" value="SUBMIT" class="myButton" style="margin-top: 2px; margin-bottom: 5px;">
									</form>
								</div>
							</article>
							
							<!-- Footer de bloc answer -->
							<div id="answerfooter<?php echo $fetchqueries['ID']?>" class="answerfooter">
								<img src="images/arrow-top.png" align="center" width="20px" height="10px" style="padding-top: 5px;">
							</div>
							
							</div>
				<?php }
				mysqli_free_result($res);
				mysqli_free_result($answers);
			}
				// If no query
				else {
				echo mysqli_error($bdd);
				// Message Aucun résultat
				echo "<div id= \"sectionarticle\"><div class=\"Noresult\"> <h2>" . 'NO QUERY FOUND<br>' . mysqli_error($bdd) . "</h2></div></div>" ;
				}
				?>				
	</section>
	
	<!-- Footer -->
	<footer>
		<div id="conteneurpagination" align="center">
		<?php echo paginate('index.php', '?p=', $nombrepages, $current); ?>
		</div>
	</footer>
				
	</div>	
	
    </body>

</html>
