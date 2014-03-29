<?php
/*
Plugin Name: Magazine Issue Manager
Plugin URI: http://mim.purplemadprojects.com/
Description: To manage issue based online publication content. Using this plugin site owner can publish cotent in issues using either WordPress categorized article features or in form of PDFs. Site owner can plan content in terms of periodic issues and users can browse content by selecting issues on website.
Version: 1.0
Text Domain: issuemanager
Author: PurpleMAD
Author URI: http://www.purplemad.ca/
*/

define( 'MIM_PLUGIN_URL', 			plugin_dir_url( __FILE__ ) );
define( 'MIM_PLUGIN_PATH',			plugin_dir_path( __FILE__ ) );
define( 'MIM_PLUGIN_BASENAME', 		plugin_basename( __FILE__ ) );
define( 'MIM_PLUGIN_REL_DIR', 		dirname( PLUGIN_BASENAME ) );

/**
* When plugin loaded then all files called.
*
* Function Name: mim_issue_plugin_load_function.
*
* @created by {Nilesh Mokani} and {01-12-2013}
*
**/

add_action( 'plugins_loaded','mim_issue_plugin_load_function' );
function mim_issue_plugin_load_function(){
	require_once( 'mim-issue-class.php' );	
	
	if (class_exists( 'MIM_Issue' ) ) {		
		$mim_issue=new MIM_Issue();
		require_once( 'mim-custom-class.php' );
		$mim_custom=new MIM_Custom();
				
		require_once( 'mim-posttype-taxonomy.php' );
		$current_user = wp_get_current_user();
		if($current_user->roles[0] != 'editor')
		{
			require_once( 'mim-user-field.php' );
		}
		require_once('mim-restriction-editors.php');
		require_once('mim-shortcode.php');

	}
	# Load the language files
	load_plugin_textdomain( 'mim-issue', false, MIM_PLUGIN_REL_DIR . '/languages/' );	
	
}

register_activation_hook( __FILE__, 'min_activate' );	
register_deactivation_hook( __FILE__,'min_deactivate' );
register_uninstall_hook( __FILE__, 'mim_uninstall'  );

/**
*  Activate Plugin : When activate plugin then tabel created and default value added in database.
*
* Function Name: min_activate
*
* @created by {Nilesh Mokani} and {01-12-2013}
*
**/

function min_activate(){			
	global $wpdb,$wp_query;
	$charset_collate = '';	
	if ( ! empty($wpdb->charset) )
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	if ( ! empty($wpdb->collate) )
		$charset_collate .= " COLLATE $wpdb->collate";
	
	
	$tables = $wpdb->get_results("show tables like '{$wpdb->prefix}taxonomymeta'");
	if (!count($tables)):
		$wpdb->query("CREATE TABLE {$wpdb->prefix}taxonomymeta (
			meta_id bigint(20) unsigned NOT NULL auto_increment,
			taxonomy_id bigint(20) unsigned NOT NULL default '0',
			meta_key varchar(255) default NULL,
			meta_value longtext,
			PRIMARY KEY	(meta_id),
			KEY taxonomy_id (taxonomy_id),
			KEY meta_key (meta_key)
		) $charset_collate;");
	endif;	
	
		add_option('mim_full_article_display','No','', 'yes');
		add_option('mim_allow_user_post_article','No','', 'yes');
		add_option('mim_default_post_article_status','draft','', 'yes');
		update_option('users_can_register','1');
		update_option('default_role','editor');
		update_option('posts_per_page','5');
		add_option('mim_new_editor_status','Yes','', 'yes');
		add_option('mim_cover_width','850','', 'yes');
		add_option('mim_cover_height','450','', 'yes');
		add_option('mim_post_per_page_article','5','', 'yes');
		add_option('mim_search_behaviour','Yes','', 'yes');		
		add_option('mim_current_issue','-1','', 'yes');		
		
		require_once( 'mim-custom-class.php' );
		$mim_custom=new MIM_Custom();
		$mim_custom->mim_load_post_type_taxonomy();
		$issue_term = wp_insert_term(
			  __('Default Issue','mim-issue'), // the term 
			  'issues', // the taxonomy
			  array(
			    'description'=> __('This is Default Issue.','mim-issue'),
			    'slug' => __('default-issue','mim-issue'),
			    'parent'=> ''
			  )
			);
			
			
			$magazine_term = wp_insert_term(
			  __('Default Magazine Category','mim-issue'), // the term 
			  'magazine_category', // the taxonomy
			  array(
			    'description'=>  __('This is Default Magazine Category.','mim-issue'),
			    'slug' => __('default-magazine','mim-issue'),
			    'parent'=> ''
			  )
			);
	
}

/**
*  Deactivate Plugin : When deactivate plugin then default value deleted in database.
*
* Function Name: min_deactivate
*
* @created by {Nilesh Mokani} and {01-12-2013}
*
**/
	 
function min_deactivate(){
	delete_option('mim_full_article_display');
	delete_option('mim_allow_user_post_article');
	delete_option('mim_default_post_article_status');
	delete_option('mim_new_editor_status');
	delete_option('mim_cover_width');
	delete_option('mim_cover_height');
	delete_option('mim_post_per_page_article');
	delete_option('mim_search_behaviour');
	delete_option('mim_current_issue');
}
	
/**
*  Uninstall Plugin : When uninstall plugin then tabel droped and default value deleted in database.
*
* Function Name: uninstall
*
* @created by {Nilesh Mokani} and {01-12-2013}
*
**/
		
function mim_uninstall(){
	global $wpdb;
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}taxonomymeta");
	
	delete_option('mim_full_article_display');
	delete_option('mim_allow_user_post_article');
	delete_option('mim_default_post_article_status');
	delete_option('mim_new_editor_status');
	delete_option('mim_cover_width');
	delete_option('mim_cover_height');
	delete_option('mim_post_per_page_article');
	delete_option('mim_search_behaviour');
	delete_option('mim_issue_menu_type');
	delete_option('mim_current_issue');
}

/**
* Taxonomymeta tabel assign to golbal.
*
* Function Name: wpdbfix
*
* @created by {Nilesh Mokani} and {01-12-2013}
*
**/

add_action( 'init', 'wpdbfix');
function wpdbfix() {
	global $wpdb;
	$wpdb->taxonomymeta = "{$wpdb->prefix}taxonomymeta";
}
?>