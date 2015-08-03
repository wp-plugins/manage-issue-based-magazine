<?php session_start(); ?>
<?php
/**
 * IssueMag theme Taxonomy Magazine categories
 * @package   IssueMag
 * @copyright Copyright (C) 2015  PurpleMad
 * @author    PurpleMad
 * @since     V1.0
 * @link      http://www.purplemad.ca/
 */

get_header(); 
global $wp_query;
$ArticlePerPageCount = get_option('mim_post_per_page_article');

if( isset( $_SESSION['Current_Issue'] ) && !empty( $_SESSION['Current_Issue'] ) )
{
	$CatID = $wp_query->get_queried_object_id();
	$CatData =  get_term_by('id', $CatID, 'magazine_category');
	
}
$ArticleArgs = array( 
				'post_type' => 'magazine',
				'posts_per_page' => ( !empty( $ArticlePerPageCount ) ) ? $ArticlePerPageCount : '8' ,
				'paged' => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
				'post_status' => 'publish',
				'tax_query' => array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'issues',
						'field'    => 'id',
						'terms'    => $_SESSION['Current_Issue'],
					),
					array(
						'taxonomy' => 'magazine_category',
						'field'    => 'id',
						'terms'    => $CatID,
						'include_children' => 'false',
					),
					),
					'order' => 'DESC',
					'orderby'=>'date',
				);
				
$loop = new WP_Query($ArticleArgs);
?>
<div id="main-content" class="main-content">
  <div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">
    <div class="entry-content">
	<div class="all-magazine-div">
	<?php if ( $loop->have_posts() ) 
	{
		while ( $loop->have_posts() ) 
		{
			$loop->the_post();	
			if (has_post_thumbnail( $post->ID ) ) 
			{
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' ); 
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
						<?php if( !empty( $featured_image_url ) ){ ?>
							<img alt="<?php echo the_permalink(); ?>" src="<?php  echo $featured_image_url; ?>">
						<?php } else { ?>
							<img alt="<?php echo the_permalink(); ?>" src="<?php  echo MIM_PLUGIN_URL . '/images/default.jpg'; ?>">
						<?php } ?>
						<br/>
						<?php _e('Posted On '.get_the_date()); ?>
						<br/>
						<?Php if(!empty($descr)) 
						{
							if($descr_count>=100) 
							{ 
							_e($descr);
							?>
							<br/>
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
  echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';	
?>
<?php
get_sidebar( 'content' );
get_sidebar();
?>
<?php get_footer(); ?>