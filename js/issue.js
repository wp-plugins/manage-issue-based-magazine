jQuery(document).ready(function(){
		jQuery( "#mim_issue_publish_date").datepicker({dateFormat: 'yy-mm-dd'});				
});
jQuery( "#sortable ul.children" ).each( function() {
    jQuery(this).remove();
});