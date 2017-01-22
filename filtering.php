<?php 
$currentuserid = $fetchcurrentuser['userID'];

// If option reset
if (isset($_POST['reset']))
{
	include_once('resetfilters.php');
}
	
// Definition of SESSION PerPage
if (isset($_POST['perpage']))
{
	$_SESSION['perpage'] = $_POST['perpage'];
}
else
{
	if (!isset($_SESSION['perpage']))
	{
		// PerPage default value
		$_SESSION['perpage'] = 10;
	}
}

function filterprocess($postname, $sessionname, $columnname)
{
	// Definition of SESSION filtre in question
	// If POST filtre in question
	if (isset($_POST[$postname]))
	{
		$_SESSION[$sessionname] = $_POST[$postname];		
		// If it's ALL
		if ($_SESSION[$sessionname] == "ALL")
		{
			$filtretoapplySQL = 'hig_query.' . $columnname . ' IS NOT nULL';
		}
		// else we set the filter in question
		else {$filtretoapplySQL = 'hig_query.' . $columnname . ' = \"' . $_SESSION[$sessionname] . '\"';}

	}
	// If SESSION filter in question is not defined
	if (!isset($_SESSION[$sessionname]))
	{
		// We set it to ALL
		$_SESSION[$sessionname] = "ALL";
		$filtretoapplySQL = 'hig_query.' . $columnname . ' IS NOT nULL';
	}
	// if no post
	else
	{
		// if it's ALL
		if ($_SESSION[$sessionname] == "ALL")
		{
			$filtretoapplySQL = 'hig_query.' . $columnname . ' IS NOT nULL';
		}
		// else we set the filter in question
		else {$filtretoapplySQL = 'hig_query.' . $columnname . ' = \'' . $_SESSION[$sessionname] . '\'';}
	}
	return $filtretoapplySQL;
}

// If reset button clicked
if (isset($_POST["resetfilters"]) AND $_POST["resetfilters"] != NULL)
{
	include_once('resetfilters.php');
}
else
// Else we process all the filters
{
	$filtregameSQL = filterprocess("game", "game", "game");
	$filtrereporterSQL = filterprocess("reporter", "reporter", "reporter");
	$filtreprioritySQL = filterprocess("priority", "priority", "priority");
	$filtrestatusSQL = filterprocess("status", "status", "status");
}

// If Search
if (isset($_POST['search']))
{
	include_once('resetfilters.php');
	$textsearch = mysqli_real_escape_string($bdd, $_POST['search']);
	$search = "AND (hig_query.question LIKE '%$textsearch%' OR hig_query.stringids LIKE '%$textsearch%' OR hig_query.sourcetext LIKE '%$textsearch%')";
}
else
{
	$search = "";
}


// if Search by QID
if (isset($_GET['QID']) AND is_numeric($_GET['QID']))
{
	include_once('resetfilters.php');
	$textsearchID = htmlspecialchars($_GET['QID']);
	$searchID = "AND hig_query.ID = $textsearchID";
}
else
{
	$searchID = ""; 
}


// If an answer is posted
if (isset($_POST['answertext']))
{
	$answertext = nl2br(htmlentities($_POST['answertext'], ENT_QUOTES, 'UTF-8'));
	$formanswerqueryid = $_POST['formanswerqueryid'];
	
	$sqlanswer = "INSERT INTO hig_answer (answer, queryid, reporteranswer) 
	VALUES ('$answertext', $formanswerqueryid, $currentuserid)"; 
	
	mysqli_query ($bdd, $sqlanswer) or die ('Erreur SQL !'.$sqlanswer.'<br />'.mysqli_error($bdd));
	
	if ($_SESSION['role'] == 'Translator')
	{
		$sqlanswerstatus = "UPDATE hig_query
							SET status = 'answeredbytrans'
							WHERE ID = $formanswerqueryid;"; 
		
		mysqli_query ($bdd, $sqlanswerstatus) or die ('Erreur SQL !'.$sqlanswerstatus.'<br />'.mysqli_error($bdd));
	}
	
	else if ($_SESSION['role'] == 'Developer')
	{
		$sqlanswerstatus = "UPDATE hig_query
							SET status = 'answeredbydev'
							WHERE ID = $formanswerqueryid;"; 
		
		mysqli_query ($bdd, $sqlanswerstatus) or die ('Erreur SQL !'.$sqlanswerstatus.'<br />'.mysqli_error($bdd));
	}
}

// If a new query is posted
if (isset($_POST['newqueryquery']))
{
	$formnewquerygame = $_POST['newquerygame'];
	$formnewqueryquery = nl2br(htmlentities($_POST['newqueryquery'], ENT_QUOTES, 'UTF-8'));
	$formnewquerystringids = nl2br(htmlentities($_POST['newquerystringid'], ENT_QUOTES, 'UTF-8'));
	$formnewquerysourcetext = nl2br(htmlentities($_POST['newquerysourcetext'], ENT_QUOTES, 'UTF-8'));
	$formnewquerypriority = $_POST['newquerypriority'];
	
	$sqlnewquery = "INSERT INTO hig_query (game, question, status, stringids, sourcetext, priority, reporter) 
	VALUES ('$formnewquerygame', '$formnewqueryquery', 'answeredbytrans' , '$formnewquerystringids', '$formnewquerysourcetext', '$formnewquerypriority', '$currentuserid')"; 
	
	mysqli_query ($bdd, $sqlnewquery) or die ('Erreur SQL !'.$sqlnewquery.'<br />'.mysqli_error($bdd));
	include_once('resetfilters.php');
}

// If a query is deleted
if (isset($_POST['deletequeryID']))
{
	$formdeletequerygameID = $_POST['deletequeryID'];
	$sqldeletequery = "UPDATE hig_query SET status = 'deleted' WHERE ID = '$formdeletequerygameID'; " ; 
	mysqli_query ($bdd, $sqldeletequery) or die ('Erreur SQL !'.$sqldeletequery.'<br />'.mysqli_error($bdd));
}

// If an answer is permanently deleted
if (isset($_POST['deletepermanswerID']))
{
	$formdeleteanswergameID = $_POST['deletepermanswerID'];
	$sqldeleteanswer = "DELETE FROM hig_answer WHERE answerID = '$formdeleteanswergameID'; " ; 
	mysqli_query ($bdd, $sqldeleteanswer) or die ('Erreur SQL !'.$sqldeleteanswer.'<br />'.mysqli_error($bdd));
}

// If a query is closed
if (isset($_POST['closequeryID']))
{
	$formdeletequerygameID = $_POST['closequeryID'];
	$sqlclosequery = "UPDATE hig_query SET status = 'closed' WHERE ID = '$formdeletequerygameID'; " ; 
	mysqli_query ($bdd, $sqlclosequery) or die ('Erreur SQL !'.$sqlclosequery.'<br />'.mysqli_error($bdd));
}

// If a query is permanently deleted
if (isset($_POST['deletepermqueryID']))
{
	$formdeletepermquerygameID = $_POST['deletepermqueryID'];
	$sqldeletepermquery = "DELETE hig_query, hig_answer FROM hig_query LEFT JOIN hig_answer ON hig_query.ID = hig_answer.queryid WHERE hig_query.ID = '$formdeletepermquerygameID'; " ; 
	mysqli_query ($bdd, $sqldeletepermquery) or die ('Erreur SQL !'.$sqldeletepermquery.'<br />'.mysqli_error($bdd));
}

// If a query status is changed by an admin (drop down list)
if (isset($_POST['changequerystatus']))
{
	$newquerystatus = $_POST['changequerystatus'];
	$newquerystatusID = $_POST['changequerystatusID'];
	$sqlchangestatusquery = "UPDATE hig_query SET status = '$newquerystatus' WHERE ID = '$newquerystatusID'; " ; 
	mysqli_query ($bdd, $sqlchangestatusquery) or die ('Erreur SQL !'.$sqlchangestatusquery.'<br />'.mysqli_error($bdd));
}
?>