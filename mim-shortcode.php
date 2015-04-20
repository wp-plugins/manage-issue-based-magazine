<?php
/**
* Short code for current issue
*
* Function Name: mim_get_current_issue.
*
* 
*
**/
/* S: Short code for current issue -- USE <?php echo do_shortcode('[MIM_Current_Issue]'); ?>*/
add_shortcode( 'MIM_Current_Issue', 'mim_get_current_issue' );
function mim_get_current_issue($atts,$content="")
{
	$mim_current_issue__id=get_option('mim_current_issue');
	
	return $mim_current_issue__id;
}
// E: Short code for current issue

/**
* Short code for return magazine catgeory ids base on issue for menu.
*
* Function Name: mim_get_current_issue_menu.
*
* 
*
**/
/* S: Short code for return magazine catgeory ids base on issue for menu -- USE <?php echo do_shortcode('[MIM_Issue_Menu]'); ?> */
add_shortcode( 'MIM_Issue_Menu', 'mim_get_current_issue_menu' );
function mim_get_current_issue_menu( $atts ) {
	
	extract( shortcode_atts( array(
		'issue_id' => '',
	), $atts ) );
	
	$issue_id = sanitize_text_field( $atts['issue_id'] );		

	if($atts['issue_id'] == ''){
		$issue_id = get_option('mim_current_issue');
	}


	$mim_category_menu = get_metadata('taxonomy', $issue_id, 'mim_issue_menu_category', true) ;
	if(!empty($mim_category_menu)){
		$cat_ids = implode($mim_category_menu,',');
		return $cat_ids;	
	}else
	{
		return '';
	}
}
// E:  Short code for return magazine catgeory ids base on issue for menu

/**
* Short code for return master issue ids.
*
* Function Name: mim_get_current_master_issue.
*
* 
*
**/
/*  S: Short code for return master issue ids -- USE <?php echo do_shortcode('[MIM_Master_Issue]'); ?> */
add_shortcode( 'MIM_Master_Issue', 'mim_get_current_master_issue' );
function mim_get_current_master_issue( $atts ) {
	
	extract( shortcode_atts( array(
		'issue_id' => '',
	), $atts ) );
	
	$issue_id = sanitize_text_field( $atts['issue_id'] );		
	
	if($issue_id == ''){
		$issue_id = get_option('mim_current_issue');
	}
	
	$mim_master_category = get_metadata('taxonomy', $issue_id, 'mim_issue_master_category', true) ;
	return $mim_master_category;	
}
// E: Short code for return master issue ids


/**
* Shortcode for Menu for Issue with simple design with horizontal.
*
* Function Name: mim_issue_menu_with_design_h.
*
* 
*
**/
/* S: Shortcode for Menu for Issue with simple design with horizontal--- USE <?php echo do_shortcode('[MIM_Issue_Menu_With_Horizontal issue_id=""]'); ?> */
function mim_issue_menu_with_design_h( $atts ) {
	
	extract( shortcode_atts( array(
		'issue_id' => '',
	), $atts ) );
	
	$issue_id = sanitize_text_field( $atts['issue_id'] );		
	$html = '';
	
	$html = '<div><style>.menu-issue-horizontal { display:inline-block; margin-right: 15px; }</style>';
	if($issue_id == ''){
		$issue_id = get_option('mim_current_issue');
	}
	$mim_category_menu = get_metadata('taxonomy', $issue_id, 'mim_issue_menu_category', true) ;
	
	if(!empty($mim_category_menu))
	{	
		$html .= '<ul class="menu-ul-horizontal">';		
		foreach($mim_category_menu as $mim_cat_name => $mim_cat_value )
		{
			$cat = get_term_by( 'term_id', $mim_cat_value, 'magazine_category' );		
			$html .= '<li class="menu-issue-horizontal" id="category-'.$mim_cat_value.'"><a href="'.get_term_link( $cat->term_id, 'magazine_category' ).'">'.$cat->name.'</a></li>';		
		}
		$html .= '</ul>';	
		
	}
	$html .= '</div>';
return $html;
}
add_shortcode( 'MIM_Issue_Menu_With_Horizontal', 'mim_issue_menu_with_design_h' );
/* E: Shortcode for Menu for Issue with simple design with horizontal */


/**
*  Shortcode for Menu for Issue with simple design with vertical.
*
* Function Name: mim_issue_menu_with_design_v.
*
* 
*
**/
/* S: Shortcode for Menu for Issue with simple design with vertical  --- USE <?php echo do_shortcode('[MIM_Issue_Menu_With_Vertical issue_id=""]'); ?> */
function mim_issue_menu_with_design_v( $atts ) {
	
	extract( shortcode_atts( array(
		'issue_id' => '',
	), $atts ) );
	
	$issue_id = sanitize_text_field( $atts['issue_id'] );			
	$html = '';
	
	$html = '<div><style>.menu-issue-vertical { display:block;clear: both; }</style>';
	if($issue_id == ''){
		$issue_id = get_option('mim_current_issue');
	}
	$mim_category_menu = get_metadata('taxonomy', $issue_id, 'mim_issue_menu_category', true) ;
	
	if(!empty($mim_category_menu))
	{	
		$html .= '<ul class="menu-ul-vertical">';
		foreach($mim_category_menu as $mim_cat_name => $mim_cat_value )
		{
			$cat = get_term_by( 'term_id', $mim_cat_value, 'magazine_category' );
			$html .= '<li class="menu-issue-vertical" id="category-'.$mim_cat_value.'"><a href="'.get_term_link( $cat->term_id, 'magazine_category' ).'">'.$cat->name.'</a></li>';	
		}	
		$html .= '</ul>';
	}
	$html .= '</div>';
return $html;
}
add_shortcode( 'MIM_Issue_Menu_With_Vertical', 'mim_issue_menu_with_design_v' );
/* E: Shortcode for Menu for Issue with simple design with vertical */


/**
*  Shortcode for All Issue with simple design.
*
* Function Name: mim_all_issue_call_with_simple_design.
*
* 
*
**/
/* S: Shortcode for All Issue with simple design --- USE <?php echo do_shortcode('[MIM_All_Issues_Simple]'); ?> */
function mim_all_issue_call_with_simple_design( $atts ) {
		
	$html = '';
	
	$html = '<div><style>.all-issues-horizontal { display:inline-block;float:left; margin-right: 15px; }</style>';
	$args = array(
		'child_of'                 => 0,
		'orderby'                  => 'name',
		'order'                    => 'ASC',
		'hide_empty'               => 0,
		'echo'             		   => 1,
		'taxonomy'                 => 'issues'
	); 
	$issues = get_categories( $args );
//print_r($issues);
	
	if(!empty($issues))
	{
		$html .= '<ul class="all-issues-horizontal">';		
		foreach($issues as $issues_list )
		{
		 $cat = get_term_by( 'id', $issues_list->term_id, 'issues' );
	     $issue_link= get_term_link( $cat->term_id,'issues');
		 $html .= '<li class="all-issues-horizontal" id="category-'.$issues_list->term_id.'"><a href="'.$issue_link.'">'.$issues_list->name.'</a></li>';
			
		}	
		$html .= '</ul>';
	}
	$html .= '</div>';
return $html;
}
add_shortcode( 'MIM_All_Issues_Simple', 'mim_all_issue_call_with_simple_design' );
/* E: Shortcode for All Issue with simple design   */

/**
*  Shortcode for all issue with return ids.
*
* Function Name: mim_list_all_issue_id_call.
*
* 
*
**/
/* S: Shortcode for all issue with return ids --- USE <?php echo do_shortcode('[MIM_All_Issues]'); ?> */
function mim_list_all_issue_id_call( $atts ) {
	
	$args = array(
		'orderby' 				   => 'id',
		'order'                    => 'DESC',
		'hide_empty'               => 0,
		'echo'             		   => 1,
		'taxonomy'                 => 'issues'
	); 
	$issues = get_categories($args);
	$issue_id_array = array();	
	$current_year = date("Y");
	$current_month = date("m");
	if(!empty($issues)):
	
		foreach($issues as $issues_list ):
			$mim_issue_date = get_metadata('taxonomy', $issues_list->term_id, 'mim_issue_publish_date', true);
			$mim_issue_year = date('Y',strtotime($mim_issue_date));
			$mim_issue_month = date('m',strtotime($mim_issue_date));
			
			if($mim_issue_year <= $current_year):			
				if($mim_issue_month <= $current_month || $mim_issue_year < $current_year):
					$issue_id_array[strtotime($mim_issue_date)] = $issues_list->term_id;
				endif;	
			endif;
		endforeach;	
		
		krsort($issue_id_array); // for issue date filtering manage
		$issue_ids = implode($issue_id_array,',');
		
		return $issue_ids;
	else:
		return 'No Issues Available';
	endif;

}
add_shortcode( 'MIM_All_Issues', 'mim_list_all_issue_id_call' );
/* E: Shortcode for all issue with return ids  */

/**
*  Shortcode for return  magazine article ids base on select issue and magazine category .
*
* Function Name: mim_magazine_articles_ids_with_issue_magazine.
*
* 
*
**/
/* S: Shortcode for return  magazine article ids base on select issue and magazine category  --- USE <?php echo do_shortcode('[MIM_Magazine_Artices_Ids issue_id="" category_id=""]'); ?> */
function mim_magazine_articles_ids_with_issue_magazine( $atts ) {
	extract( shortcode_atts( array(
		'issue_id' => '',
		'category_id' => '',
	), $atts ) );
	
	$mim_issue_id = sanitize_text_field( $atts['issue_id'] );		
	$mim_cat_id = sanitize_text_field( $atts['category_id'] );			

	$args = array(
		'post_type' => 'magazine',
		'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'issues',
			'field' => 'id',
			'terms' => $mim_issue_id
		),
		array(
			'taxonomy' => 'magazine_category',
			'field' => 'id',
			'terms' => $mim_cat_id,
			'operator' => 'IN'
		)
	)
	); 
	$query = new WP_Query($args);
	$post_array = array();
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$post_array[] = $query->post->ID;
		}
	} else {
		return 'No Posts Found !!!';
	}
	$post_ids = implode($post_array,',');
	return $post_ids;
}
add_shortcode( 'MIM_Magazine_Artices_Ids', 'mim_magazine_articles_ids_with_issue_magazine' );
/* E: Shortcode for return  magazine article ids base on select issue and magazine category*/

/**
*  Shortcode for magazine article base on select issue and magazine category .
*
* Function Name: mim_magazine_article_list.
*
* 
*
**/
/* S: Shortcode for magazine article base on select issue and magazine category --- USE <?php echo do_shortcode('[MIM_Magazine_Article issue_id="" category_id="" no_of_posts=""]'); ?> */
function mim_magazine_article_list( $atts ) {
	extract( shortcode_atts( array(
		'issue_id' => '',
		'category_id' => '',
		'no_of_posts' => '',
	), $atts ) );
	
	$mim_issue_id = sanitize_text_field( $atts['issue_id'] );		
	$mim_cat_id = sanitize_text_field( $atts['category_id'] );
	$mim_no_of_posts = sanitize_text_field( $atts['no_of_posts'] );	
	if($mim_no_of_posts == ''){
		$mim_no_of_posts = get_option('mim_post_per_page_article');
	}			
			
	$html = '';
	
	$args = array(
		'post_type' => 'magazine',
		'post_status' => 'publish',
		'posts_per_page' => $mim_no_of_posts,
		'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'issues',
			'field' => 'id',
			'terms' => $mim_issue_id
		),
		array(
			'taxonomy' => 'magazine_category',
			'field' => 'id',
			'terms' => $mim_cat_id,
			'operator' => 'IN'
		)
	)
	); 
	$query = new WP_Query($args);
	if ( $query->have_posts() ) {
		$html = '<div><ul class="mim-post-titles-ul">';
		while ( $query->have_posts() ) {
			$query->the_post();
			$html .= '<li id="'.$query->post->ID.'" class="mim-posts-titles-li"><a title="'.get_the_title().'" href="'.get_permalink().'">'.get_the_title().'</a></li>';
		}
		$html .= '</ul></div>';
	} else {
		return 'No Posts Found !!!';
	}

	return $html;
}
add_shortcode( 'MIM_Magazine_Article', 'mim_magazine_article_list' );
/* E: Shortcode for magazine article base on select issue and magazine category  */

add_filter( 'pre_get_posts', 'mim_pre_get_posts' ); 
function mim_pre_get_posts( $query ) { 
  global $wpdb,$wp_query;

  if ( $query->is_search() && get_option('mim_search_behaviour')== 'Yes') 
  {  
	$taxonomy_query =	array(
							array(
									'taxonomy' => 'issues',
									'field' => 'id',
									'terms' => get_option('mim_current_issue')
								)
					); 
     $query->set('post_type','magazine');
	 $query->set('relation','AND');
	 $query->set('tax_query', $taxonomy_query);
  } 
  return $query; 
}
?>