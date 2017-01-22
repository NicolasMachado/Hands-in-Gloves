<!-- Query principale -->
<?php	
	$per_page = $_SESSION['perpage']; 	
 
	// If admin, show deleted queries
	if	($_SESSION['admin'] == 'TRUE')
	{
		$showdeleted = "";
	}
	else
	{
		$showdeleted = "AND hig_query.status != 'deleted'";
	}
	
	// MAIN QUERY + JOINS
	$sql = "
	SELECT *
	FROM hig_query

	LEFT JOIN hig_user
    ON hig_user.userID = hig_query.reporter

	LEFT JOIN hig_games
    ON hig_games.gameID = hig_query.game
	
	WHERE $filtrereporterSQL 
	AND $filtregameSQL 
	AND $filtreprioritySQL 
	AND $filtrestatusSQL 
	$showdeleted
	AND game_status != 'Inactive'
	AND hig_query.game IN (SELECT ga_gameid FROM hig_gameaccess WHERE hig_gameaccess.ga_userID = $currentuserid)
	$search
	$searchID
	";
	
	// Getting number of rows and calculating number of pages
	$rsd = mysqli_query($bdd, $sql);
	$count = mysqli_num_rows($rsd);
	$nombrepages = ceil($count/$per_page);
	
	// Getting current page number from url GET
	// If it's a number, it's processed, otherwise, default to 1
	$current = 1;
	if (isset($_GET['p']) && is_numeric($_GET['p'])) 
	{
		$page = intval($_GET['p']);
		if ($page >= 1 && $page <= $nombrepages) 
		{
			// normal case
			$current=$page;
		} 
		else if ($page < 1) 
		{
			// if page number is inferior to 1, current becomes 1
			$current=1;
		} 
		else 
		{
			// if page number is superior to total page number, we affect the number of the last page to current page
			$current = $nombrepages;
		}
	}
	
	// $start is the base value of LIMIT in the SQL (depends on current page)
	$start = ($current * $per_page - $per_page);
	
	// Finalization of sql query
	$queries = "
		$sql 
		ORDER BY date DESC
		LIMIT $start, $per_page
		";
?>