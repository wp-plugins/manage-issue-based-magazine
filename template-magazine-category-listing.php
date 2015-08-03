<?php
/**
*  Template Name: Magazine Category Listing
 * IssueMag theme template used for displaying the Magzine category listing for current issue
 * @package   IssueMag
 * @copyright Copyright (C) 2015  PurpleMad
 * @author    PurpleMad
 * @since     V1.0
 * @link      http://www.purplemad.ca/
 */
 
session_start();
get_header();
global $wp_query;
$CurrentIssueID = $wp_query->get_queried_object_id(); 
$_SESSION['Current_Issue']  = $CurrentIssueID; 

if( isset( $_SESSION['Current_Issue'] ) && !empty( $_SESSION['Current_Issue'] ) )
{
   $CurrentIssueID = $_SESSION['Current_Issue'];
   
   //$CurrentIssueTerm = do_shortcode('[MIM_Issue_Menu issue_id="'.$CurrentIssueID.'"]');
   $mim_category = get_metadata('taxonomy', $CurrentIssueID, 'mim_issue_menu_category', true) ;

	if(!empty($mim_category)){
		$CurrentIssueTerm = implode($mim_category,',');
		
	 }
	 
    $IssueTerms = explode(",",$CurrentIssueTerm);
 
  
   // get children of category
   foreach($IssueTerms as $IssueTermID){
  	 $IssueTermChildIds = get_term_children( $IssueTermID, 'magazine_category' );
  	 foreach($IssueTermChildIds as $IssueTermChildId) {
  	   if( !in_array( $IssueTermChildId,$IssueTerms ) ){
  	 	  array_push($IssueTerms,$IssueTermChildId);
       }
     }    
   }
}
?>
<div id="main-content" class="main-content">
  <div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">
    <div class="entry-content">
	<ul>
	  <?php
	  $i=1;
	  if (!empty($CurrentIssueTerm)) {
	  	
			
			  foreach ($IssueTerms as $IssueTermID){
				
				$MagazineCatData = get_term_by('id', (int)$IssueTermID, 'magazine_category');	
												
				$MagazineCatLink = get_term_link($MagazineCatData->slug,$MagazineCatData->taxonomy);
				$MagazineDescription = term_description( $IssueTermID, 'magazine_category' );
				$descr = substr( $full_descr,0,100); 
				$descr_count=strlen($full_descr); 
				$MagazineCoverimageData = get_metadata('taxonomy', $IssueTermID, 'mim_category_cover_image', true) ;
				$MagazineCoverimagePath = wp_get_attachment_image_src($MagazineCoverimageData,'thumbnail');
				$MagazineCoverimageURL = $MagazineCoverimagePath[0];
				
				if (!empty ($MagazineCatData)) { ?>
			   
			   
				  <li>
			      	<a href="<?php echo $MagazineCatLink;?>"><?php echo $MagazineCatData->name;?></a>
			      </li>
			         
			      <?php if( !empty( $MagazineCoverimageURL ) ){ ?>
						<img alt="<?php echo $MagazineCatData->name;?>" src="<?php  echo $MagazineCoverimageURL; ?>">
				  <?php } else { ?>
							<img alt="<?php echo $MagazineCatData->name;?>" src="<?php  echo MIM_PLUGIN_URL . '/images/default.jpg'; ?>">
				  <?php } ?>
				 
			      <?php 
			      if ( !empty($MagazineDescription) ) {
				  	 if( strlen($MagazineDescription) > 100 ) {
				  		echo substr( $MagazineDescription,0,100);
				  		echo '<br/>';
				  		echo '<a href="'.$MagazineCatLink.'">Read More...</a>';
					  }
					 else {
					  	echo $MagazineDescription;
					 }
				 }
				 
			  } 	
			} 
		} 
	  
	  else {
	  	echo '<li>No Current Issue Categories are found.</li>';
	  } ?>
	  	 
	</ul>
</div>   
</div>   
</div>   
</div>  
<?php
get_sidebar( 'content' );
get_sidebar();
?>     
<?php get_footer();?>    