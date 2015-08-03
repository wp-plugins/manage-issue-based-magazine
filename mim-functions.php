<?php
/**
 * Default content filter, sets Page for Articles to default shortcode content if no content exists for page
 *
 *
 * @return string new content.
 **/
if ( !function_exists( 'mim_issue_content_filter' ) ) {
	
	function mim_issue_content_filter( $content ) {
		global $post;
		
		$page_for_magazines_id=get_option('page_for_magazines');
		$page_for_archives_id=get_option('page_for_archives');
	
		
		if ( ( $post->ID == $page_for_magazines_id ) && empty( $content ) ) {
			include('includes/magazine-listing-template.php');
		} else if( ( $post->ID == $page_for_archives_id ) && empty( $content ) ) {			
			include('includes/issue-listing-template.php');		
		} 
		
		return $content;		
	}
	add_filter( 'the_content', 'mim_issue_content_filter',5);		
}

add_action( 'admin_enqueue_scripts','enqueue_mim_admin_styles_scripts' );

function enqueue_mim_admin_styles_scripts() {
	
	wp_enqueue_style( 'issue-form-edit', MIM_PLUGIN_URL . 'css/issue-edit.css' ); 
}
?>