<!-- Script toggleclass -->
<script>
jQuery(document).ready(function($) 
{
$( "#<?php echo $fetchqueries['ID'] ?>" ).click(function() {
$( "#<?php echo $fetchqueries['ID'] ?>" ).toggleClass( "articlegrand", 300 );
$( "#conteneur<?php echo $fetchqueries['ID'] ?>" ).toggleClass( "conteneuropen", 300 );
});
});

jQuery(document).ready(function($) 
{
$( "#answerfooter<?php echo $fetchqueries['ID'] ?>" ).click(function() {
$( "#<?php echo $fetchqueries['ID'] ?>" ).toggleClass( "articlegrand", 300 );
$( "#conteneur<?php echo $fetchqueries['ID'] ?>" ).toggleClass( "conteneuropen", 300 );
});
});
</script>
  
<?php
// If an answer is posted, we expand the query
if (isset($_POST['answertext']))
{
?>	
<script>
window.onload = function() {
	$( "#<?php echo $_POST['formanswerqueryid']; ?>" ).toggleClass( "articlegrand", 0 );
	$( "#conteneur<?php echo $_POST['formanswerqueryid']; ?>" ).toggleClass( "conteneuropen", 0 );
};
</script> 
<?php } 

// If an answer is permanently deleted, we expand the query
if (isset($_POST['deletepermanswerqueryID']))
{
?>	
<script>
window.onload = function() {
	$( "#<?php echo $_POST['deletepermanswerqueryID']; ?>" ).toggleClass( "articlegrand", 0 );
	$( "#conteneur<?php echo $_POST['deletepermanswerqueryID']; ?>" ).toggleClass( "conteneuropen", 0 );
};
</script> 
<?php } ?>

<script>
$(document).ready(function() {
$("#answerformbutton<?php echo $fetchqueries['ID']?>").click(function() {
	// SCRIPT À EXÉCUTER QUAND FORMULAIRE ENVOYÉ (Réponse à query)
});
});
</script>