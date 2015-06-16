<?php
	$mim_current_issue_id=get_option('mim_current_issue');
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
	$args = array(
				'post_type' => 'magazine',
				'posts_per_page' => get_option('posts_per_page'), 
				'orderby' => 'date',
				'order' => 'DESC',
				'paged' => $paged,
				'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'issues',
					'field' => 'id',
					'terms' => $mim_current_issue_id
				)
				)); 			
	$loop = new WP_Query($args);
	?><div class="all-magazine-div">
	<?php if ( $loop->have_posts() ) 
	{
		while ( $loop->have_posts() ) 
		{
			$loop->the_post();	
			if (has_post_thumbnail( $post->ID ) ) 
			{
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); 
				$featured_image_url=$image[0];
			} 
			else 
			{
				$featured_image_url	= MIM_PLUGIN_URL . '/images/default.jpg';
			}
			$descr = substr( $post->post_content,0,100); 
			$descr_count=strlen($post->post_content); ?>
			<div class="magazine-align-relative">
				<div class="magazine-columns">
					<div class="mim-image-list" style="background-image: url(<?php echo $featured_image_url; ?>);background-repeat: no-repeat; background-size: 150px 150px;"></div>
					<div class="magazine-subfont">
						<h5><a href="<?php echo the_permalink(); ?>"><?php echo get_the_title(); ?></a></h5>
						<?php _e('Posted On '.get_the_date()); ?>
						<br/>
						<?Php if(!empty($descr)) 
						{
							if($descr_count>=100) 
							{ 
							_e($descr);
							?>
								<a href="<?php echo _e(the_permalink()); ?>"><?php echo _e('Read More...'); ?></a>
							<?php
							}
						} ?>	
					</div>			
				</div>
			</div>
		<?php 
		}
	echo '</div>';
	$big = 999999999; 
	echo '<div class="mim-pagination-div">';
	echo paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'type'         => 'plain',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $loop->max_num_pages));
		wp_reset_postdata();
	}
	echo '</div>';
?>