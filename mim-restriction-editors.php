<?php
/**
* Editor role restriction. 
* 
*
**/
if( !empty ( $current_user->roles ) ){

if( $current_user->roles[0] == 'editor')
{
		global $wp_roles;
		
		$wp_roles->remove_cap( 'editor', 'edit_others_posts' );
		$wp_roles->remove_cap( 'editor', 'delete_others_posts' );
		$wp_roles->remove_cap( 'editor', 'delete_others_pages' );		
		$wp_roles->remove_cap( 'editor', 'edit_others_pages' );
		$wp_roles->remove_cap( 'editor', 'manage_categories' );	
		
		$mim_editor_status=get_option('mim_default_post_article_status');
		$status= !empty($mim_editor_status) ? $mim_editor_status : 'draft';
		if(isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'magazine')
		{
			if($status == 'draft'){
				$wp_roles->remove_cap( 'editor', 'publish_posts' );
			}else
			{
				$wp_roles->add_cap( 'editor', 'publish_posts' );
			}
		}
		$mim_allow_user_past_issue=get_option('mim_allow_user_post_article');
		$mim_past_issue= !empty($mim_allow_user_past_issue) ? $mim_allow_user_past_issue : 'No';
		
		if($mim_past_issue == 'No')
		{
			add_action( 'admin_menu', 'mim_remove_issue_box' );
			
		}else
		{
			add_action( 'admin_menu', 'mim_add_issue_box' );
			
		}
		
		/**
		* Removed issue metabox.
		*
		* Function Name: mim_remove_issue_box.
		*
		* 
		*
	  **/
	
		function mim_remove_issue_box()
		{
			remove_meta_box( 'issuesdiv' , 'magazine' , 'normal' ); 
			
			add_meta_box( 'issuesdiv' ,'Issues','mim_issue_categories_meta_box', 'magazine' , 'side' ); 
		}
		
		/**
		* Added issue metabox.
		*
		* Function Name: mim_add_issue_box.
		*
		* 
		*
	  **/
		
		function mim_add_issue_box()
		{
			add_meta_box( 'issuesdiv' , 'magazine' , 'normal' ); 			
		}
		
		/**
		* List of issue base on restriction.
		*
		* Function Name: mim_issue_categories_meta_box.
		*
		* 
		*
	  **/
	  if(!function_exists('mim_issue_categories_meta_box'))
	  {
		function mim_issue_categories_meta_box( $post, $box ) {
    		$taxonomy = 'issues';  
	  		$tax = get_taxonomy($taxonomy);  
		    $terms = get_terms($taxonomy,array('hide_empty' => 0));  			
  			$name = 'tax_input[' . $taxonomy . '][]';  		    
		    $popular = get_terms( $taxonomy, array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 10, 'hierarchical' => false ) );  
		    $postterms = get_the_terms( $post->ID,$taxonomy );
			if(is_array($postterms)){
				foreach($postterms as $k=>$v){
					$terms_ids[]=$v->term_id;
				}
			}	
			$current_year=date("Y");
			$current_month= date("m");
									
    ?>    
			    <div id="taxonomy-<?php _e($taxonomy); ?>" class="categorydiv">  
			  
			        <!-- Display tabs-->  
			        <ul id="<?php _e($taxonomy); ?>-tabs" class="category-tabs">  
			            <li class="tabs"><a href="#<?php _e($taxonomy); ?>-all" tabindex="3"><?php _e($tax->labels->all_items); ?></a></li>  
			            <li class="hide-if-no-js"><a href="#<?php _e($taxonomy); ?>-pop" tabindex="3"><?php _e( 'Most Used' ,'mim-issue'); ?></a></li>  
			        </ul>  
			  
			        <!-- Display taxonomy terms -->  
			        <div id="<?php _e($taxonomy); ?>-all" class="tabs-panel">  
						<input type="hidden" value="0" name="tax_input[<?php _e($taxonomy);?>][]">
			            <ul id="<?php _e($taxonomy); ?>checklist" class="categorychecklist form-no-clear" data-wp-lists="list:<?php _e($taxonomy);?>" >  
			                <?php   
							
							foreach($terms as $term){  
								
								$mim_issue_publish_date=get_metadata('taxonomy', $term->term_id, 'mim_issue_publish_date', true) ;
								$mim_issue_year = date('Y',strtotime($mim_issue_publish_date));
								$mim_issue_month = date('m',strtotime($mim_issue_publish_date));
								if($mim_issue_year == $current_year)
								{
									if($mim_issue_month == $current_month)
									{
					                    $id = $taxonomy.'-'.$term->term_id;  
										$in_ids = 'in-'.$id;  
										if(is_array($postterms)){
											if(in_array($term->term_id,$terms_ids)){
												$check="checked='checked'";
											}
											else{
												$check='';
											}
										}else
										{
											$check='';
										}
					                    _e("<li id='".$id."'><label class='selectit'>");  
					                    _e("<input type='checkbox' id='{$in_ids}' name='{$name}' ".$check." value='$term->term_id' />$term->name<br />");  
					                    _e("</label></li>");  
									}						
								}
			                }?>  
			           </ul>  
			        </div>  
			  
			        <!-- Display popular taxonomy terms -->  
			        <div id="<?php _e($taxonomy); ?>-pop" class="tabs-panel" style="display: none;">  
			            <ul id="<?php _e($taxonomy); ?>checklist-pop" class="categorychecklist form-no-clear" >  
			                <?php   foreach($popular as $term){ 
								$mim_issue_publish_date=get_metadata('taxonomy', $term->term_id, 'mim_issue_publish_date', true) ;
								$mim_issue_year = date('Y',strtotime($mim_issue_publish_date));
								$mim_issue_month = date('m',strtotime($mim_issue_publish_date));
								if($mim_issue_year == $current_year)
								{
									if($mim_issue_month == $current_month)
									{ 
					                    $id = 'popular-'.$taxonomy.'-'.$term->term_id; 
										
										$in_ids = 'in-'.$id;   
										if(is_array($postterms)){
											if(in_array($term->term_id,$terms_ids)){
												$check="checked='checked'";
											}
											else{
												$check='';
											}
										}else
										{
											$check='';
										}
					                    _e("<li id='{$id}'><label class='selectit'>");  
					                    _e("<input type='checkbox' id='{$in_ids}' ".$check." value='$term->term_id' />$term->name<br />");  
					                    _e("</label></li>");
									}
								}  
			                }?>  
			           </ul>  
			       </div>  
			  
			    </div>  
		<?php
	   }	
	
	 }

	 /**
		* Quick edit base on restriction.
		*
		* Function Name: mim_add_to_bulk_quick_edit_custom_box.
		*
		* 
		*
	  **/
	  
	  if(!function_exists('mim_add_to_bulk_quick_edit_custom_box'))
	 {
		add_action( 'quick_edit_custom_box', 'mim_add_to_bulk_quick_edit_custom_box', 10, 2 );

		function mim_add_to_bulk_quick_edit_custom_box( $column_name, $post_type ) {
		 switch( $column_name ) {
		            case 'taxonomy-issues':
						
							$taxonomy = 'issues';  
			  				$tax = get_taxonomy($taxonomy);  
						    $terms = get_terms($taxonomy,array('hide_empty' => 0));  			
		  				    $current_year=date("Y");
							$current_month= date("m");
		              		$term_id=array();
							foreach($terms as $term){  
								
								$mim_issue_publish_date=get_metadata('taxonomy', $term->term_id, 'mim_issue_publish_date', true) ;
								$mim_issue_year = date('Y',strtotime($mim_issue_publish_date));
								$mim_issue_month = date('m',strtotime($mim_issue_publish_date));
								
								if(get_option('mim_allow_user_post_article') == 'No')
								{
									if($mim_issue_year == $current_year)
									{
										if($mim_issue_month == $current_month)
										{}else{?>
											 <script type="text/javascript">
							   					 jQuery(document).ready(function() {
							        				jQuery('.issues-checklist #issues-<?php echo $term->term_id;?>').remove();
							   					 });
						   					 </script>
										<?php }													
									}else
									{?>
									<script type="text/javascript">
							   					 jQuery(document).ready(function() {
							        				jQuery('.issues-checklist #issues-<?php echo $term->term_id;?>').remove();
							   					 });
						   			</script>
									<?php	
									}
								}					
			                }
						    break;

		  	 }
		  }
		 }
 }
}
?>