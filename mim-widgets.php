<?php
/**
		* Registers MIM Widgets
		*
		* Function Name: register_mim_widgets.
		*
		* @created by {Ruma Patel} and {18-11-2014}
		*
		**/

function register_mim_widgets() {
	
	
	
	register_widget( 'MIM_Issue_List_Widget' );
	register_widget( 'MIM_Current_Issue_Widget' );
	register_widget( 'MIM_Issue_Article_Listing_Widget' );
	register_widget( 'MIM_Current_Issue_Category_Widget' );
	

}
add_action( 'widgets_init', 'register_mim_widgets' );

/**
*  Class:   MIM_Issue_List_Widget
*  Description: Creates Widget for All Issues Listing.
*  @created by {Ruma Patel} and {18-11-2014}
*/
 class MIM_Issue_List_Widget extends WP_Widget
 {
 	/**
	 * Register widget with WordPress.
	 */
 	function __construct() {
	parent::__construct(
		'MIM_Issue_List_Widget', // Base ID
		__('MIM Issue List Widget','mim-issue'), // Name
		array('description' => __( 'Displays your All listings for Issues & Outputs the Issue title.','mim-issue'))
	   );
		}
		
	 function form($instance) 
		 {
			if( $instance) {
				$title = esc_attr($instance['title']);
			} else {
				$title = '';
			}
			?>
				<p>
				<label for="<?php _e ($this->get_field_id('title')); ?>"><?php _e('Title', 'mim-issue'); ?></label>
				<input  id="<?php _e ($this->get_field_id('title')); ?>" name="<?php _e ($this->get_field_name('title')); ?>" type="text" value="<?php _e ($title); ?>" />
				</p>
				 
			<?php
		}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
		function widget($args, $instance) { 
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
		_e ($before_widget);
		if ( $title ) {
			_e ($before_title . $title . $after_title);
		}
		$this->getIssuesListings();
		_e ($after_widget);
	}
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
		}
    
	function getIssuesListings() 
	{ 
			
			//list terms in a given taxonomy
			$taxonomy = 'issues';
			$term_args=array(
			  'hide_empty' => false,
			  'orderby' => 'date',
			  'order' => 'DESC'
			);
			$terms = get_terms($taxonomy,$term_args);
			 if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			     _e('<ul>');
			     foreach ( $terms as $term ) {
			     	$issueList='<a href="'.get_term_link( $term->slug,$taxonomy).'">'; 
			     	$issueList.='<li>' . $term->name . '</li></a>';
			        echo $issueList;
			        
			     }
			     _e('</ul>');
			 }
			else
	        {
				_e( 'No Issues Found.', 'mim-issue' );
			} 
		}    
	} 
/**
*  Class:   MIM_Current_Issue_Widget
*  Description: Creates Widget For Current Issue.
*  @created by {Ruma Patel} and {18-11-2014}
*/
class MIM_Current_Issue_Widget extends WP_Widget
 {
 	/**
	 * Register widget with WordPress.
	 */
 	function __construct() {
	parent::__construct(
		'MIM_Current_Issue_Widget', // Base ID
		__('MIM Current Issue Widget','mim-issue'), // Name
		array('description' => __( 'Displays Current Issue. Outputs the Issue title and cover Image.','mim-issue'))
	   );
		}
		
	 function form($instance) 
		 {
		 	$defaults = array(
			'display_issue_name'	=> 'on',
			'display_issue_cover'	=> 'on'
		);
		
		extract( wp_parse_args( (array) $instance, $defaults ) );
		
			if( $instance) {
				$title = esc_attr($instance['title']);
			} else {
				$title = '';
			}
			?>
				<p>
				<label for="<?php _e ($this->get_field_id('title')); ?>"><?php _e('Title', 'mim-issue'); ?></label>
				<input  id="<?php _e ($this->get_field_id('title')); ?>" name="<?php _e ($this->get_field_name('title')); ?>" type="text" value="<?php _e ($title); ?>" />
				</p>
				<p>
			    <label for="<?php _e ($this->get_field_id('display_issue_name')); ?>"><?php _e( 'Display Issue Title?', 'mim-issue' ); ?></label>
		        <input class="checkbox" id="<?php _e($this->get_field_id('display_issue_name')); ?>" name="<?php _e($this->get_field_name('display_issue_name')); ?>" type="checkbox" value="on" <?php checked( 'on' == $display_issue_name ) ?> />
			   </p>
            
				<p>
		   		 <label for="<?php _e($this->get_field_id('display_issue_cover')); ?>"><?php _e( 'Display Issue Cover Image?', 'mim-issue' ); ?></label>
	        <input class="checkbox" id="<?php _e($this->get_field_id('display_issue_cover')); ?>" name="<?php _e($this->get_field_name('display_issue_cover')); ?>" type="checkbox" value="on" <?php checked( 'on' == $display_issue_cover ) ?> />
	   			</p>
									 
			<?php
		}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
		function widget($args, $instance) { 
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
		
		_e ($before_widget);
		if ( $title ) {
			_e ($before_title . $title . $after_title);
		}
		$this->getCurrentIssue($instance);
		_e ($after_widget);
	}
	function getCurrentIssue($instance) 
	{ 		$display_issue_name=$instance['display_issue_name'];
			$display_issue_cover=$instance['display_issue_cover'];
			$mim_current_issue_id=get_option('mim_current_issue');
			//print_r($mim_current_issue_id);
			//$current_term_id=(string)$mim_current_issue_id;
			$current_issue= get_term_by('id',$mim_current_issue_id, 'issues', 'ARRAY_A');
			$mim_coverimage=get_metadata('taxonomy',$mim_current_issue_id, 'mim_issue_cover_image', true) ;
			$mim_coverimage_path=wp_get_attachment_image_src($mim_coverimage,'thumbnail'); 
			$mim_cover_style= empty($mim_coverimage) ? 'display:none' : '';
			if(!empty($mim_coverimage))
			{
				$mim_image_path=$mim_coverimage_path[0];
			} else {
				$mim_image_path='';
			}	
			
			if($mim_current_issue_id!='-1')
			{
				_e('<div>');
		
					if ( 'on' == $display_issue_name )
					{
						_e('<a href="'.get_term_link( $current_issue[slug], 'issues' ).'"><h4>'.$current_issue[name].'</h4></a>');
					
					}	
					if ( 'on' == $display_issue_cover ) {
					
						if(empty($mim_coverimage))
						{
							$imgurl=MIM_PLUGIN_URL . '/images/default.jpg';
						}
											
						else
						{
							$imgurl=$mim_coverimage_path[0];
									        
						}
						_e('<a href="'.get_term_link( $current_issue[slug], 'issues' ).'">');
						_e('<img src="'.esc_url($imgurl).'" />');		
						_e('</a>');	
					}
					_e('</div>');
			}
			else
	        {
				_e( 'No Issue is selected as Current.', 'mim-issue' );
			} 
	} 
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['display_issue_name'] 	= ( 'on' == $new_instance['display_issue_name'] ) ? 'on' : 'off';
		$instance['display_issue_cover'] 	= ( 'on' == $new_instance['display_issue_cover'] ) ? 'on' : 'off';
		return $instance;
		}
    
		

}
/**
*  Class:   MIM_Issue_Article_Listing_Widget
*  Description: Creates Widget For All Magazines Listing  For Selected Current Issue.
*  @created by {Ruma Patel} and {19-11-2014}
*/
class MIM_Issue_Article_Listing_Widget extends WP_Widget
 {
 	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
			
			$widget_ops = array('classname' => 'MIM_Issue_Article_Listing_Widget', 'description' => __('Displays all articles For Selected Current Issue. Outputs the Article title and Featured Image.','mim-issue'));
			$control_ops = array('width' => 400, 'height' => 350);
			parent::__construct('MIM_Issue_Article_Listing_Widget', __('MIM Issue Article Listing Widget','mim-issue'), $widget_ops, $control_ops);
		}
			
	 function form($instance) 
		 {
		
			$defaults = array(
				'title'				=> '',
				'posts_per_page'	=> '',
				'magazine_category'	=> 	'all'
			);
			
			extract( wp_parse_args( (array) $instance, $defaults ) );
			$posts_per_page=$instance['posts_per_page'];
			$magazine_category=$instance['magazine_category'];
			$cat_taxonomy = 'magazine_category';
			$cat_term_args=array(
			  'hide_empty' => false,
			  'orderby' => 'date',
			  'order' => 'DESC'
			);
			$categories = get_terms($cat_taxonomy,$cat_term_args);
			if( $instance) {
				$title = esc_attr($instance['title']);
			} else {
				$title = '';
			}
			?>
				<p>
				<label for="<?php _e ($this->get_field_id('title')); ?>"><?php _e('Title', 'mim-issue'); ?></label>
				<input class="widefat" id="<?php _e ($this->get_field_id('title')); ?>" name="<?php _e ($this->get_field_name('title')); ?>" type="text" value="<?php _e ($title); ?>" />
				</p>
				<p>
	        	<label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e( 'Number of Magazines to Show:', 'mim-issue' ); ?></label>
	            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" type="text" value="<?php echo esc_attr( strip_tags( $posts_per_page ) ); ?>" />
                <small>Leave it Blank To Display All Magazines</small>
	        	</p>
	        	<p>
	        	<label for="<?php echo $this->get_field_id('magazine_category'); ?>"><?php _e( 'Select Category to Display:', 'mim-issue' ); ?></label><br />
                <select class="widefat" id="<?php echo $this->get_field_id('magazine_category'); ?>" name="<?php echo $this->get_field_name('magazine_category'); ?>">
				<option value="all" <?php selected( 'all', $magazine_category ); ?>><?php _e( 'All Categories', 'mim-issue' ); ?></option>
				<?php foreach ( $categories as $cat ) { ?>
					<option value="<?php echo $cat->slug; ?>" <?php selected( $cat->slug, $magazine_category ); ?>><?php echo $cat->name; ?></option>
                <?php } ?>
                </select>
	        	</p>
							 
			<?php
		}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
		function widget($args, $instance) { 
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
		
		_e ($before_widget);
		if ( $title ) {
			_e ($before_title . $title . $after_title);
		}
		$this->getMagazineListing($args,$instance);
		_e ($after_widget);
	}
	function getMagazineListing($args,$instance) 
	{ 		
			$posts_per_page=$instance['posts_per_page'];
			$magazine_category=$instance['magazine_category'];	
			$magazine_category_array = get_term_by( 'slug', $magazine_category, 'magazine_category' ); 
			$magazine_category_id=$magazine_category_array->term_id;
			$mim_current_issue_id=get_option('mim_current_issue');
			$current_issue= get_term_by('id',$mim_current_issue_id, 'issues', 'ARRAY_A');
			if ( !empty( $magazine_category ) && 'all' != $magazine_category )
			{
				$args = array(
				'posts_per_page'    => empty( $posts_per_page ) ? -1 : $posts_per_page,
				'post_type' => 'magazine',
				'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'issues',
					'field' => 'id',
					'terms' => $mim_current_issue_id
				),
				array(
					'taxonomy' => 'magazine_category',
					'field' => 'id',
					'terms' => $magazine_category_id,
					'operator' => 'IN'
						)
					)
				); 
			}
			else
			{
				$args = array(
				'posts_per_page'    => empty( $posts_per_page ) ? -1 : $posts_per_page,
				'post_type' => 'magazine',
				'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'issues',
					'field' => 'id',
					'terms' => $mim_current_issue_id
				),
				array(
					'taxonomy' => 'magazine_category',
					'field'    => 'slug' ,
					'terms'		=>$magazine_category,
					'operator' => 'NOIN'
					)
					)
				); 
			}
			$the_query = new WP_Query( $args );
			
			if($mim_current_issue_id!='-1')
			{
				 _e('<div>');
				if ( $the_query->have_posts() )
				 {
				 	while ( $the_query->have_posts() )
				 	{
						$the_query->the_post();	
					 
					 if (has_post_thumbnail( $post->ID ) )
					 {
					 	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); 
					 	$featured_image_url=$image[0];
					 } 						
   					else
   					{
						$featured_image_url	= MIM_PLUGIN_URL . '/images/default.jpg';

					}
				    _e('<h4>');
				    			if(get_the_title()!="")
	                                {
										?>	
		                                <a href="<?php the_permalink(); ?>"><?php echo _e(get_the_title()); ?></a>
		                                <?php
									}
									else
									{
										?>	<a href="<?php the_permalink(); ?>"><?php echo _e('(no-title)'); ?></a>
	                                	<?php
									}
	
					_e('</h4>'); ?>
					<a href="<?php the_permalink(); ?>">
					<?php _e('<img src="'.esc_url($featured_image_url).'" height="100" width="100" />');?></a>
					<?php					
				}
						
				// Reset Query
				_e('</div>');
			wp_reset_postdata();	
			}
			else
	        {
				_e( 'No Magazines Found.', 'mim-issue' );
			} 	
			
				 	
		}
		else
	    {
			_e( 'No Magazines Found.', 'mim-issue' );
		}  
			
			
	} 
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['posts_per_page'] 	= $new_instance['posts_per_page'];
		$instance['magazine_category'] 	= $new_instance['magazine_category'];
		return $instance;
		}
    
		

}
/**
*  Class:   MIM_Current_Issue_Category_Widget
*  Description: Creates Widget For All Magazine Categories Listing For Selected Current Issue.
*  @created by {Ruma Patel} and {22-11-2014}
*/
class MIM_Current_Issue_Category_Widget extends WP_Widget
{
 	/**
	 * Register widget with WordPress.
	 */
 	function __construct() {
	parent::__construct(
		'MIM_Current_Issue_Category_Widget', // Base ID
		__('MIM Current Issue Category Widget','mim-issue'), // Name
		array('description' => __( 'Displays all Magazine Categories For Current Issue. Outputs the magazine category title.','mim-issue'))
	   );
		}
	function form($instance) 
		 {
			if( $instance) {
				$title = esc_attr($instance['title']);
			} else {
				$title = '';
			}
			?>
				<p>
				<label for="<?php _e ($this->get_field_id('title')); ?>"><?php _e('Title', 'MIM_Issue_List_Widget'); ?></label>
				<input  id="<?php _e ($this->get_field_id('title')); ?>" name="<?php _e ($this->get_field_name('title')); ?>" type="text" value="<?php _e ($title); ?>" />
				</p>
				 
			<?php
		}	
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
		function widget($args, $instance) { 
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
		
		_e ($before_widget);
		if ( $title ) {
			_e ($before_title . $title . $after_title);
		}
		$this->getCurrentIssueCateggory($instance);
		_e ($after_widget);
	}
	function getCurrentIssueCateggory($instance) 
	{ 
						$mim_current_issue_id=get_option('mim_current_issue');
						if(!empty($mim_current_issue_id))
						{
								$mim_category_id=get_metadata('taxonomy', $mim_current_issue_id, 'mim_issue_menu_category', true) ;
								$taxonomy='magazine_category';
								if(!empty($mim_category_id)) 
								{
									_e('<ul>');
									foreach($mim_category_id as $mim_cat_name => $mim_cat_value ) 
									{		
										$cat_name=get_term_by('id',$mim_cat_value,'magazine_category',ARRAY_A) ;	
										if(($cat_name['name'])!="")
										{
											echo '<a href="'.get_term_link($cat_name['slug'],$taxonomy).'"><li>'.$cat_name['name'].'</li></a>';
										}
									}	
									_e('</ul>');
								}
								else
						        {
									_e( 'No Current Issue Categories are found.', 'mim-issue' );
								} 		
							
						}
						else
						{
							_e( 'No Current Issue Categories are found.', 'mim-issue' );
						} 
					
	} 
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
		}
    
		

}


