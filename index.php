<?php
/*
Plugin Name: Magazine Issue Manager
Plugin URI: http://mim.purplemadprojects.com/
Description: To manage issue based online publication content. Using this plugin site owner can publish cotent in issues using either WordPress categorized article features or in form of PDFs. Site owner can plan content in terms of periodic issues and users can browse content by selecting issues on website.
Version: 1.8
Text Domain: mim-issue
Author: PurpleMAD
Author URI: http://www.purplemad.ca/
*/

define( 'MIM_PLUGIN_URL', 			plugin_dir_url( __FILE__ ) );
define( 'MIM_PLUGIN_PATH',			plugin_dir_path( __FILE__ ) );
define( 'MIM_PLUGIN_BASENAME', 		plugin_basename( __FILE__ ) );

/**
* When plugin loaded then all files called.
*
* Function Name: mim_issue_plugin_load_function.
*
* 
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
		if( !empty ( $current_user->roles ) )
		{
			if($current_user->roles[0] != 'editor')
			{
				require_once( 'mim-user-field.php' );
			}
		}	
		require_once('mim-restriction-editors.php');
		require_once('mim-shortcode.php');
		require_once('mim-widgets.php');
		require_once('mim-functions.php');
	}
	
	$display_category = get_option('mim_issue_display_category');
	if ( $display_category == 1 ) {

		// Add a filter to the template include to determine if the page has our 
		// template assigned and return it's path
		add_filter(
			'template_include', 
			'view_project_template'
		);
	}	
	
	# Load the language files
	
	load_plugin_textdomain( 'mim-issue', false, plugin_basename( dirname( __FILE__ )  . '/languages/' ));
	
}

if ( ! function_exists( 'call_custom_taxonomy_template' ) ) : 
function call_custom_taxonomy_template( $template_path ){

    //Get template name
    $template = basename($template_path);
   

    if( 1 == preg_match('/^template-magazine-category-listing((-(\S*))?).php/',$template) )
         return true;
	elseif( 1 == preg_match('/^taxonomy-magazine_category((-(\S*))?).php/',$template) )
         return true;	 

    return false;
}
endif; // call_custom_taxonomy_template

if ( ! function_exists( 'view_project_template' ) ) : 
/**
 * Checks if the template is assigned to the page
 */
 function view_project_template( $template ) {

        global $post,$wp_query; 
        //check if the query is for that specific taxonomy page otherwise it goes to particular template page. 
        if( $wp_query->query_vars['taxonomy'] == 'issues' && !call_custom_taxonomy_template($template))
         $filename = 'template-magazine-category-listing.php';
        elseif( $wp_query->query_vars['taxonomy'] == 'magazine_category' && !call_custom_taxonomy_template($template))
         $filename = 'taxonomy-magazine_category.php';		 
        
        if( !empty($filename) ){
          $file = plugin_dir_path(__FILE__). $filename;
          
	      // Just to be safe, we check if the file exist first
	      if( file_exists( $file ) ) {
			   return $file;
	      } 
        }
        return $template;

} 
endif; // view_project_template

register_activation_hook( __FILE__, 'min_activate' );	
register_deactivation_hook( __FILE__,'min_deactivate' );
register_uninstall_hook( __FILE__, 'mim_uninstall'  );


/**
*  Activate Plugin : When activate plugin then tabel created and default value added in database.
*
* Function Name: min_activate
*
* 
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
		add_option('mim_cover_width','1366','', 'yes');
		add_option('mim_cover_height','375','', 'yes');
		add_option('mim_post_per_page_article','5','', 'yes');
		add_option('mim_search_behaviour','Yes','', 'yes');		
		add_option('mim_current_issue','-1','', 'yes');	
		add_option('mim_issue_menu_category');	
		add_option('mim_issue_display_category');	
		add_option('page_for_magazines','-1','', 'Select');	
		add_option('page_for_archives','-1','', 'Select');		
		add_option('page_for_issue_category','-1','', 'Select');		
		
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
* 
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
	delete_option('mim_issue_menu_category');
	delete_option('mim_issue_display_category');
	delete_option('page_for_magazines');
	delete_option('page_for_archives');
	delete_option('page_for_issue_category');
	delete_option('users_can_register');
	
    //unset session for current issue if plugin is deactive
	session_unset('Current_Issue');
}
	
/**
*  Uninstall Plugin : When uninstall plugin then tabel droped and default value deleted in database.
*
* Function Name: uninstall
*
* 
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
	delete_option('mim_issue_menu_category');
	delete_option('mim_issue_display_category');
	delete_option('page_for_magazines');
	delete_option('page_for_archives');
	delete_option('page_for_issue_category');
	delete_option('users_can_register');
	
    //unset session for current issue if plugin is uninstall
	session_unset('Current_Issue');
}

/**
* Taxonomymeta tabel assign to golbal.
*
* Function Name: wpdbfix
*
* 
*
**/

add_action( 'init', 'wpdbfix');
function wpdbfix() {
	global $wpdb;
	$wpdb->taxonomymeta = "{$wpdb->prefix}taxonomymeta";
	
	$mim_issue_menu_category=get_option('mim_issue_menu_category');	
	$mim_issue_menu_category_selected = !empty($mim_issue_menu_category) ? $mim_issue_menu_category : '0';
	if($mim_issue_menu_category_selected!='0')
	{
		$theme_locations = get_nav_menu_locations();
		if($theme_locations[primary]!=0)
		{	
			add_filter('wp_nav_menu_items','add_issue_categories_items', 10, 2);		
		}
		else
		{	
			add_filter( 'wp_page_menu_args', 'primary_menu_items');	

		}
	}
	
	
	
}
function add_issue_categories_items( $nav, $args )
{
	if( $args->theme_location == 'primary' )
	{
		
		$mim_current_issue_id=get_option('mim_current_issue');
		if(!empty($mim_current_issue_id))
		{
			$mim_category_id=get_metadata('taxonomy', $mim_current_issue_id, 'mim_issue_menu_category', true) ;
			$taxonomy='magazine_category';
		
			if(!empty($mim_category_id)) 
							{
								
								foreach($mim_category_id as $mim_cat_name => $mim_cat_value ) 
								{		
									$cat_name=get_term_by('id',$mim_cat_value,'magazine_category',ARRAY_A) ;	
									if(($cat_name['name'])!="")
									{
										$nav .='<li class="page_item page-item-'.$mim_category_id.'"><a href="'.get_term_link($cat_name['slug'],$taxonomy).'">'.$cat_name['name'].'</a></li>';
									}
								}	
							}		
		
		}
	}
return $nav;
}
function primary_menu_items( $args ) {
	$args['show_home'] = true;
	return $args;
}

if ( ! function_exists( 'mim_plugin_rate_us' ) ) : 
function mim_plugin_rate_us( $footer_text ) {
	global $typenow;

	if ( $typenow == 'magazine' ) {
		$rate_text = sprintf( __( 'Thank you for using Manage Issue Based Magazine (Multi-language) ! Please <a href="%1$s" target="_blank">rate us</a> on <a href="%1$s" target="_blank">WordPress.org</a> and <a href="%2$s" target="_blank">Like us</a> on <a href="%2$s" target="_blank">Facebook</a> to stay with us.', 'mim-issue' ),
			'https://wordpress.org/support/view/plugin-reviews/manage-issue-based-magazine',
			'https://www.facebook.com/PurpleMADcanada'
		);

		return str_replace( '</span>', '', $footer_text ) . ' | ' . $rate_text . '</span>';
	} else {
		return $footer_text;
	}
}
endif; // mim_plugin_rate_us
?>