<?php
$mim_current_issue_id=get_option('mim_current_issue');

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
	$taxonomy = 'issues';
	$per_page = get_option('posts_per_page');
	$offset      = $per_page * ( $paged - 1) ;
	$term_args = array(
						'orderby' => 'date',
						'order' => 'DESC',
						'post_type'=>'magazine',
						'hide_empty' => false,
						'number' => $per_page,
						'offset' => $offset
					);
	
	$terms = get_terms($taxonomy,$term_args);
	$no_terms =wp_count_terms($taxonomy,array('hide_empty' => false));
	?><div class="all-magazine-div">
	<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$term_id=$term->term_id;
			$mim_coverimage=get_metadata('taxonomy', $term_id, 'mim_issue_cover_image', true) ;	
			$mim_coverimage=get_metadata('taxonomy',$term_id, 'mim_issue_cover_image', true) ;
			$mim_coverimage_path=wp_get_attachment_image_src($mim_coverimage,'thumbnail'); 
			$mim_cover_style= empty($mim_coverimage) ? 'display:none' : '';
			$mim_issue_date=get_metadata('taxonomy', $term_id, 'mim_issue_publish_date', true) ;
			$mim_issue_publish_date= date('F j, Y',strtotime($mim_issue_date));		
			$mim_descpr=get_metadata('taxonomy', $term_id, 'tag-description', true) ;	
			//$description = $term->description;
			if(!empty($mim_coverimage)) {
				$mim_image_path=$mim_coverimage_path[0];
			} else {
				$mim_image_path='';
			}
			if(empty($mim_coverimage)) {
				$imgurl=MIM_PLUGIN_URL . '/images/default.jpg';
			} else {
				$imgurl=$mim_coverimage_path[0];			        
			}
			$full_descr=$term->description;
			$descr = substr( $full_descr,0,100); 
			$descr_count=strlen($full_descr); 			
			$issue_link = get_term_link( $term );			
			?>
			<div class="magazine-align-relative">
				<div class="magazine-columns">
					<div class="mim-image-list" style="background-image: url(<?php echo $imgurl; ?>);background-repeat: no-repeat; background-size: 150px 150px;"></div>
					<div class="magazine-subfont">
						<h5>
							<a href="<?php echo $issue_link; ?>"><?php echo $term->name; ?></a>	
						</h5>
						<?php _e('Posted On '. $mim_issue_publish_date); ?>
						<br/>
						<?php if(!empty($descr)) {
							_e($descr);
							if($descr_count>=100) { 
							?>
								<a href="<?php echo get_term_link( $term ); ?>"><?php echo _e('Read More...'); ?></a>
							<?php }
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
		'current' =>max( 1, get_query_var('paged') ),
		'total' => ceil( $no_terms / $per_page )));
		echo '</div>';		
	} else {
		_e( 'No Issues Found.', 'mim-issue' );
	}
?>