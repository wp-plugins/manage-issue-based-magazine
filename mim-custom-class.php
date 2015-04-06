<?php
if ( ! class_exists( 'MIM_Custom' ) ) {
	
	class MIM_Custom{
		
		/**
		* Default Constructor called.
		*
		* Function Name: __construct.
		*
		* 
		*
		**/
		function __construct(){
	
			 add_action( 'init', array( $this, 'mim_load_post_type_taxonomy' ) );
		  
            // Adds meta box
            add_action( 'add_meta_boxes', array( &$this, 'issue_init_add_metaboxes' ) );
            
            //Save meta-box value
            add_action('save_post', array( &$this, 'save_issue_details' )); 
		}
	 
		/**
		* Post type and taxonomy loaded.
		*
		* Function Name: mim_load_post_type_taxonomy.
		*
		* 
		*
		**/
			
			
		function mim_load_post_type_taxonomy(){
			
		
			// Load Magazine Post Type	
  			$labels_magazine = array(
							    'name'               =>  __( 'Magazine' , 'mim-issue' ),
							    'singular_name'      =>  __( 'Magazine', 'mim-issue' ), 
							    'add_new'            =>  __( 'Add New', 'mim-issue' ),
							    'add_new_item'       =>  __( 'Add New Magazine', 'mim-issue' ),
							    'edit_item'          =>  __( 'Edit Magazine', 'mim-issue' ),
							    'new_item'           =>  __( 'New Magazine', 'mim-issue' ),
							    'all_items'          =>  __( 'All Magazines', 'mim-issue' ),
							    'view_item'          =>  __( 'View Magazine', 'mim-issue' ),
							    'search_items'       =>  __( 'Search Magazines', 'mim-issue' ),
							    'not_found'          =>  __( 'No magazines  found', 'mim-issue' ),
							    'not_found_in_trash' =>  __( 'No magazines  found in Trash', 'mim-issue' ),
							    'parent_item_colon'  => '',
							    'menu_name'          =>  __( 'Magazines', 'mim-issue' ),
							  );

			 $args_magazine = array(
							    'labels'             => $labels_magazine,
							    'public'             => true,
							    'publicly_queryable' => true,
							    'show_ui'            => true,
							    'show_in_menu'       => true,
								'show_in_nav_menus'  => true,
								'show_in_admin_bar'  => true,
								'query_var'          => true,
							    'rewrite'            => array( 'slug' => 'magazine' ),
								'exclude_fromsearch' => false,
							    'capability_type'    => 'post' ,
							    'has_archive'        => true,
							    'hierarchical'       => false,
							    'menu_position'      => 6,								
							    'supports'           => array( 'title', 'author', 'editor', 'custom-fields', 'revisions', 'thumbnail', 'excerpt','trackbacks', 'comments', 'page-attributes', 'post-formats' ),
								'menu_icon'			 => MIM_PLUGIN_URL . 'images/issuem-16x16.png'
							  );

	 		register_post_type( 'magazine', $args_magazine );
			
			// Load Taxonomy
			$labels_issues = array(
								'name'                       => __( 'Issues', 'mim-issue' ),
								'singular_name'              => __( 'Issue', 'mim-issue' ),
								'search_items'               => __( 'Search Issues' , 'mim-issue' ),
								'popular_items'              => __( 'Popular Issues' , 'mim-issue' ),
								'all_items'                  => __( 'All Issues' , 'mim-issue' ),
								'parent_item'                => null,
								'parent_item_colon'          => null,
								'edit_item'                  => __( 'Edit Issue' , 'mim-issue' ),
								'update_item'                => __( 'Update Issue' , 'mim-issue' ),
								'add_new_item'               => __( 'Add New Issue' , 'mim-issue' ),
								'new_item_name'              => __( 'New Issue Name' , 'mim-issue' ),
								'separate_items_with_commas' => __( 'Separate issues with commas' , 'mim-issue' ),
								'add_or_remove_items'        => __( 'Add or remove issues', 'mim-issue' ),
								'choose_from_most_used'      => __( 'Choose from the most used issues', 'mim-issue' ),
								'not_found'                  => __( 'No issues found.' , 'mim-issue' ),
								'menu_name'                  => __( 'Issues', 'mim-issue' ),
							);
			
			
			
			$args_issues = array(
								'hierarchical'          => true,
								'labels'                => $labels_issues,
								'show_ui'               => true,
								'show_admin_column'     => true,
								'show_in_nav_menus'     => true,
								'update_count_callback' => '_update_post_term_count',
								'query_var'             => true,
								'sort'                  => true,
								'rewrite'               => array( 'slug' => 'issue' ),								
								);

		   register_taxonomy( 'issues', array('magazine'), $args_issues );
		   
		   // Load Taxonomy
			$labels_magazine_category = array(
								'name'                       => __( 'Magazine Category', 'mim-issue' ),
								'singular_name'              => __( 'Magazine Categorys', 'mim-issue' ),
								'search_items'               => __( 'Search Magazine Category' , 'mim-issue' ),
								'popular_items'              => __( 'Popular Magazine Category' , 'mim-issue' ),
								'all_items'                  => __( 'All Magazine Category' , 'mim-issue' ),
								'parent_item'                => null,
								'parent_item_colon'          => null,
								'edit_item'                  => __( 'Edit Magazine Category' , 'mim-issue' ),
								'update_item'                => __( 'Update Magazine Category' , 'mim-issue' ),
								'add_new_item'               => __( 'Add New Magazine Category' , 'mim-issue' ),
								'new_item_name'              => __( 'New Magazine Category Name' , 'mim-issue' ),
								'separate_items_with_commas' => __( 'Separate Magazine Category with commas' , 'mim-issue' ),
								'add_or_remove_items'        => __( 'Add or remove Magazine Category', 'mim-issue' ),
								'choose_from_most_used'      => __( 'Choose from the most used Magazine Category', 'mim-issue' ),
								'not_found'                  => __( 'No Magazine Category found.' , 'mim-issue' ),
								'menu_name'                  => __( 'Magazine Category', 'mim-issue' ),
							);

			$args_magazine_category = array(
								'hierarchical'          => true,
								'labels'                => $labels_magazine_category,
								'show_ui'               => true,
								'show_admin_column'     => true,
								'show_in_nav_menus'     => true,
								'sort'                  => true,
								'update_count_callback' => '_update_post_term_count',
								'query_var'             => true,
								'rewrite'               => array( 'slug' => 'magazine-category','with_front' => false ),						
							);

		   register_taxonomy( 'magazine_category', array('magazine'), $args_magazine_category );
		   register_taxonomy_for_object_type( 'issues', 'magazine' );
		   register_taxonomy_for_object_type( 'magazine_category', 'magazine' );
		   
		  

	  }
	  
	  	/* Adding metabox for feature post */
	  
	  	function issue_init_add_metaboxes()  {
      	
      		add_meta_box("featurepost_meta", "Featured Post", array( &$this, 'add_issue_feature_metaboxes' ), "magazine", "side", "low");
           
       	}
        
        
        function add_issue_feature_metaboxes() {
        	global $post;
        	 
        	$prfx_stored_meta = get_post_meta( $post->ID );  ?>
        	
				<label for="featured-checkbox">
		            <input type="checkbox" name="featured-checkbox" id="featured-checkbox" value="yes" <?php if ( isset ( $prfx_stored_meta['featured-checkbox'] ) ) checked( $prfx_stored_meta['featured-checkbox'][0], 'yes' ); ?> />
		            <?php _e( 'Featured Post', 'mim-issue' )?>
		        </label>
     
		<?php }  
		
		/* Function to save meta box value */
		
		function save_issue_details($post_id) {      
		
			global $post;
			
			if( isset( $_POST[ 'featured-checkbox' ] ) ) {
    			update_post_meta( $post_id, 'featured-checkbox', 'yes' );
     		}
     		else {
    			update_post_meta( $post_id, 'featured-checkbox', 'no' );
     		}
		}
	}
}