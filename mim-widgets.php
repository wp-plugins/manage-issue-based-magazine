<?php
/**
		* Registers MIM Widgets
		*
		* Function Name: register_mim_widgets.
		*
		**/

function register_mim_widgets() {
	
	register_widget( 'MIM_Issue_List_Widget' );
	register_widget( 'MIM_Issue_Browse_Widget' );
	register_widget( 'MIM_Current_Issue_Widget' );
	register_widget( 'MIM_Issue_Article_Listing_Widget' );
	register_widget( 'MIM_Current_Issue_Category_Widget' );
	register_widget( 'MIM_Issue_Feature_Post_Widget' );
	

}

add_action( 'widgets_init', 'register_mim_widgets' );

/**
*  Class:   MIM_Issue_List_Widget
*  Description: Displays list of published issues. When clicked on issue link, all the articles of the selected issues will be displayed.
*  
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
			array('description' => __( 'Displays list of published issues. When clicked on issue link, all the articles of the selected issues will be displayed.','mim-issue'))
		   );
	}
		
	 function form($instance) 
		{
		 	$defaults = array(
				'title'				=> '',
				'issues'	=> 	'all'
			);
			
			extract( wp_parse_args( (array) $instance , $defaults ) );
			
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
		else {
			_e ($before_title . 'Issue List' . $after_title);
		}
		$this->getIssuesListings($args,$instance);
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
    
	function getIssuesListings($args,$instance) 
	{ 
			
			//list terms in a given taxonomy
			$taxonomy = 'issues';
			
			$term_args=array(
			  'hide_empty' => false,
			  'orderby' => 'id',
			  'order' => 'DESC',
			);
			
			$terms = get_terms($taxonomy,$term_args);
			  _e('<ul>');
			 if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			   
			     foreach ( $terms as $term ) {
			     	$mim_term_id = $term->term_id;
			     	$mim_issue_date=get_metadata('taxonomy', $mim_term_id, 'mim_issue_publish_date', true) ;
			     	if( !empty ($mim_issue_date) && $mim_issue_date <=  date('Y-m-d') )
			     	{
			     	   echo '<li><a href="'.get_term_link( $term->slug,$taxonomy).'">' . $term->name. '</a></li>' ;
			     	}
					
			     }
			    
			 }
			 else
					{
						echo '<li><a href="#">No Issues Found.</a></li>' ;
					}
			 _e('</ul>'); 
		}    
	} 

/**
*  Class:   MIM_Issue_Browse_Widget
*  Description: Displays list of published issues. When clicked on issue link, user will be redirected to home page with content of selected issue.
*  
*/
class MIM_Issue_Browse_Widget extends WP_Widget
 {
 	/**
	 * Register widget with WordPress.
	 */
 	function __construct() {
		parent::__construct(
			'MIM_Issue_Browse_Widget', // Base ID
			__('MIM Issue Browse Widget','mim-issue'), // Name
			array('description' => __( 'Displays list of published issues. When clicked on issue link, user will be redirected to home page with content of selected issue.','mim-issue'))
		   );
	}
		
	 function form($instance) 
		{
		 	$defaults = array(
				'title'				=> '',
				'issues'	=> 	'all'
			);
			
			extract( wp_parse_args( (array) $instance , $defaults ) );
			
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
		else {
			_e ($before_title . 'Issue Browse' . $after_title);
		}
		$this->getIssuesBrowseListings($args,$instance);
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
    
	function getIssuesBrowseListings($args,$instance) 
	{ 
		
			//$posts_per_page=$instance['posts_per_page'];
			
			//list terms in a given taxonomy
			$taxonomy = 'issues';
			
			$term_args=array(
			  'hide_empty' => false,
			  'taxonomy'  => 'issues',
			  'orderby' => 'id',
			  'order' => 'DESC',
			);
			
			$terms = get_terms($taxonomy,$term_args);
			 _e('<ul>');
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			    
			     $i=0;
			     foreach ( $terms as $term ) {
			     	
			     	if( $i == 5)
			     	 break;
			     	 
			     	$mim_term_id = $term->term_id;
			     	$mim_issue_date=get_metadata('taxonomy', $mim_term_id, 'mim_issue_publish_date', true) ;
			     	if( !empty ($mim_issue_date) && $mim_issue_date <=  date('Y-m-d') )
			     	{
				     	$mim_edit_issue_pdf_file=get_metadata('taxonomy',$mim_term_id, 'mim_issue_pdf_file', true) ;
				     	$issue_listing_page_id = get_option('page_for_archives');
						$target = '';
						if($mim_edit_issue_pdf_file !='')
						{
							$issue_link = $mim_edit_issue_pdf_file;
							$target='target="_blank"';
							$val = " (PDF)";
						}
						else{
							$issue_link = site_url().'?issue='.$mim_term_id;		  
							$val = "";
						}
						if(!empty( $_SESSION['Current_Issue'] ) && $issue == $_SESSION['Current_Issue']){?>
	                  	<li class="active"><a href= "<?php echo $issue_link; ?>" <?php echo $target;?>><?php echo $term->name;?></a></li>
	                  	<?php }else{?>
						<li><a href= "<?php echo $issue_link; ?>" <?php echo $target;?>><?php echo $term->name.$val;?> </a></li>
	                    <?php }
	                    $i++;
	                  }  
			     }?>
			     <li><a href="<?php echo get_page_link( $issue_listing_page_id );?>"><strong><?php _e('Read All Issues ...','mim-issue');?></strong></a></li>
			     <?php
			   
			 }
			else
	        {
			
					echo '<li><a href="#">No Issues Found.</a></li>' ;
	        	
			} 
			  _e('</ul>');
		}    
	} 

	
/**
*  Class:   MIM_Current_Issue_Widget
*  Description: Creates Widget For Current Issue.
*  
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
		else {
			_e ($before_title . 'Current Issue' . $after_title);
		}
		$this->getCurrentIssue($instance);
		_e ($after_widget);
	}
	function getCurrentIssue($instance) 
	{ 		
			$display_issue_name=$instance['display_issue_name'];
			$display_issue_cover=$instance['display_issue_cover'];
			$mim_current_issue_id=get_option('mim_current_issue');
			
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
			
			_e('<div class="post-list">');
			_e('<ul>');
			if($mim_current_issue_id!='-1')
			{
				
				_e('<li>');
				
					?>
					
						<h5>
						<a href="<?php echo get_term_link( $current_issue[slug], 'issues' ); ?>">
							<?php	if ( 'on' == $display_issue_cover ) {
							
								if(empty($mim_coverimage))
								{
									$imgurl=MIM_PLUGIN_URL . '/images/default.jpg';
								}
													
								else
								{
									$imgurl=$mim_coverimage_path[0];
											        
								}
								
									_e('<img alt="" src="'.esc_url($imgurl).'" />');		
									
							}
								if ( 'on' == $display_issue_name )
								{ 
								 	echo $current_issue[name];
								}	
								 ?>
						</a>	
						</h5>
					
					<?php 
					
					_e('</li>');
					
			}
			
			else
	        {
				
				echo '<li><a href="#">No Issue is selected as Current.</a></li>' ;
	        	
			} 
			_e('</ul>');
			_e('</div>');
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
*  
*/
class MIM_Issue_Article_Listing_Widget extends WP_Widget
 {
 	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
			
			$widget_ops = array('classname' => 'MIM_Issue_Article_Listing_Widget', 'description' => __('Displays all articles for selected Current Issue. Outputs the Article title and Featured Image.','mim-issue'));
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
			  'orderby' => 'id',
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
		else {
			_e ($before_title . 'Issue Article Listing' . $after_title);
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
			
			//echo '<pre>';
			//print_r($the_query);
			 _e('<div class="post-list">');
				 	_e('<ul>');
			if($mim_current_issue_id!='-1')
			{
				
				if ( $the_query->have_posts() )
				 {
				 	while ( $the_query->have_posts() )
				 	{
						$the_query->the_post();	
					 
					 if (has_post_thumbnail( $post->ID ) )
					 {
					 	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' ); 
					 	$featured_image_url=$image[0];
					 } 						
   					else
   					{
						$featured_image_url	= MIM_PLUGIN_URL . '/images/default.jpg';

					}?>
					
					<li>
					<img src="<?php echo $featured_image_url; ?>"/>
					
					
				    <h5>
				    		<?php	if(get_the_title()!="")
	                                {
										?>	
		                                <a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a>
		                                <?php
									}
									else
									{
										?>	<a href="<?php the_permalink(); ?>"><?php _e('(no-title)','mim-issue'); ?></a>
	                                	<?php
									}
							 ?>
					</h5>
					
					</li>
					
					
					<?php					
				}
						
				// Reset Query
					
			wp_reset_postdata();	
			}
			else
	        {
				echo '<li><a href="#">No Magazines Found.</a></li>' ;
			} 	
			
				 	
		}
		else
	    {
	    	echo '<li><a href="#">No Magazines Found.</a></li>' ;
		}  
		
		_e('</ul>');		
			
		_e('</div>');	
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
*  
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
		else {
			_e ($before_title . 'Current Issue Category' . $after_title);
		}
		$this->getCurrentIssueCateggory($instance);
		_e ($after_widget);
	}
	function getCurrentIssueCateggory($instance) 
	{ 
						$mim_current_issue_id=get_option('mim_current_issue');
						_e('<ul>');
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
											echo '<li><a href="'.get_term_link($cat_name['slug'],$taxonomy).'">' .$cat_name['name']. '</a></li>';
										}
									}	
									
								}
								else
						        {
									echo '<li><a href="#">No Current Issue Categories are found.</a></li>' ;
						        	
								} 		
							
						}
						else
						{
							echo '<li><a href="#">No Current Issue Categories are found.</a></li>' ;
						} 
					_e('</ul>');
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

class MIM_Issue_Feature_Post_Widget extends WP_Widget
{
	/* ---------------------------------------------------------------------------
	 * Constructor
	 * --------------------------------------------------------------------------- */
	function MIM_Issue_Feature_Post_Widget() {
			$widget_ops = array( 'classname' => 'widget_mim_issue_featured_recent_posts', 'description' => __( 'Displays the most featured posts on your site.', 'mim-issue' ) );
			$this->WP_Widget( 'widget_mim_issue_featured_recent_posts', __( 'Issue Featured Posts', 'mim-issue' ), $widget_ops );
			$this->alt_option_name = 'widget_mim_issue_featured_recent_posts';
		}
	
	
	/* ---------------------------------------------------------------------------
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 * --------------------------------------------------------------------------- */
	function form( $instance ) {
	
		
		$title = isset( $instance['title']) ? esc_attr( $instance['title'] ) : '';
		$count = isset( $instance['count'] ) ? absint( $instance['count'] ) : '';
		

		?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'mim-issue' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php _e( 'Number of posts:', 'mim-issue' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>"/>
			</p>
			
		<?php 
	}

	/* ---------------------------------------------------------------------------
	 * Deals with the settings when they are saved by the admin.
	 * --------------------------------------------------------------------------- */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['count'] = (int) $new_instance['count'];
		
		return $instance;
	}

	
	/* ---------------------------------------------------------------------------
	 * Outputs the HTML for this widget.
	 * --------------------------------------------------------------------------- */
	function widget( $args, $instance ) {

		if ( ! isset( $args['widget_id'] ) ) $args['widget_id'] = null;
		extract( $args, EXTR_SKIP );

		echo $before_widget;
		
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);
		
		$featured_artcile_args = array( 
								'post_type' => 'magazine',
								'posts_per_page' => $instance['count'] ? intval($instance['count']) : -1,
								'no_found_rows' => true, 
								'meta_key' => 'featured-checkbox',
								'meta_value' => 'yes',
								'post_status' => 'publish',
					            'ignore_sticky_posts' => true,
					            'tax_query' => array(
									array(
										'taxonomy' => 'issues',
										'field'    => 'id',
										'terms'    => ( ! empty( $_SESSION['Current_Issue'] ) ) ? $_SESSION['Current_Issue'] : '',
										
									),
								),
								'order' => 'DESC',
								'orderby' => 'date'
				);	
		
		
		$r = new WP_Query( apply_filters( 'widget_posts_args', $featured_artcile_args ) );
		if( $title ) {
			$output = $before_title . $title . $after_title;
		}
			 
		else {
			$output = $before_title . 'Feature Post' . $after_title;
		}
		$output .= '<ul>';
		if ($r->have_posts()){
				

				while ( $r->have_posts() ){
					$r->the_post();
					
						$output .= '<li><a href="'. get_permalink() .'">'. get_the_title() .'</a></li>';
				}
				wp_reset_postdata();
				
		}
		else
	        {
				$output .= '<li><a href="#">No Feature Post found.</a></li>' ;
	        	
			} 
		$output .= '</ul>'."\n";
		echo $output;
		
		echo $after_widget;
	}

}
