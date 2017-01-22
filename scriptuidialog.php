<!-- Script ui dialog -->
<script>
/* Dialog New Query */  
  jQuery(document).ready(function($) {
	  
    $( "#newqueryid" ).dialog({
		autoOpen: false,  
		closeOnEscape: true,
		draggable: false,
		width: 530,
		height: 370,
		modal: true,
		title: "ADD A NEW QUERY",
		show: {
			effect: "fade",
			duration: 300
		},
		hide: {
			effect: "fade",
			duration: 300
		}
    });
 
    $( "#newquery" ).click(function() {
      $( "#newqueryid" ).dialog( "open" );
    });
	
  });
  </script>