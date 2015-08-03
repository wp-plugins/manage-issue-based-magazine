<?php
	/**
		* css and js loaded.
		*
		* 
		*
	**/
	if( isset($_REQUEST['taxonomy']) && $_REQUEST['taxonomy']== 'issues')
	{
		function mim_issues_load_custom_wp_admin_style() {	
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_media();	
			wp_enqueue_script( 'jquery-ui-datepicker' );	 
			wp_enqueue_style( 'date-css', MIM_PLUGIN_URL . 'css/jquery-ui.css' );
			wp_enqueue_script( 'issue-js', MIM_PLUGIN_URL . 'js/issue.js' );
		}
		add_action( 'admin_enqueue_scripts', 'mim_issues_load_custom_wp_admin_style' );	
	}
	
	/**
		* Added custom field in magzine category taxonomy.
		*
		* Function Name: mim_magazine_category_add_form_fields.
		*
		* 
		*
	**/	

	if ( !function_exists( 'mim_magazine_category_add_form_fields' ) )  {
		function mim_magazine_category_add_form_fields() {		 
			wp_enqueue_media();
			wp_enqueue_script( 'cat-upload-image', MIM_PLUGIN_URL . 'js/cat-media-upload.js' );	
			wp_enqueue_style( 'issue-form', MIM_PLUGIN_URL . 'css/issue.css' );
			?>
			<div class="form-field">
				<?php $width=get_option('mim_cover_width');
				$height=get_option('mim_cover_height'); ?>
				<label for="mim_cover_image"><?php _e( 'Cover Image','mim-issue' ); ?></label>
				<div id="mim_magazine_cover_img_show" class="cover_img">
					<img src="" name="mim_display_cover_image_magazine" id="mim_display_cover_image_magazine"/>
				</div>
				<input id="mim_upload_image_magazine" type="hidden" size="36" name="mim_upload_image_magazine" value="" />
				<input id="mim_upload_image_button_magazine" type="button" value="<?php _e('Upload','mim-issue');?>" class="mim_image_magazine button button-primary"/>
				<input id="remove_magazine_image" type="button" value="<?php _e('Remove Image','mim-issue');?>" class="mim_remove_magazine button button-primary" style="display:none;"><br/>
				<p><?php _e('Cover image size is dynamic or static? If static, change it to get dynamic value from plugin settings.','mim-issue')?><br/><?php _e('You must upload','mim-issue');?> <?php _e($width.'*'.$height ,'mim-issue');  _e(' size of image.','mim-issue');?></p>
			</div> 
			<?php $mim_nonce = wp_create_nonce( 'mim-category-nonce' ); ?>
			<input type="hidden" name="category_wpnonce" value="<?php _e($mim_nonce,'mim-issue');?>">
		<?php	
		}
		add_action( 'magazine_category_add_form_fields', 'mim_magazine_category_add_form_fields' );
	 }	
	 
	 /**
		* Edited custom field in magazine category taxonomy.
		*
		* Function Name: mim_magazine_category_edit_form_fields.
		*
		* 
		*
	**/
	
   if ( !function_exists( 'mim_magazine_category_edit_form_fields' ) )  {
		function mim_magazine_category_edit_form_fields($tag) {
			
			wp_enqueue_media();
			wp_enqueue_script( 'cat-upload-image', MIM_PLUGIN_URL . 'js/cat-media-upload.js' );	
			wp_enqueue_style( 'issue-form-edit', MIM_PLUGIN_URL . 'css/issue-edit.css' );
			$mim_term_id = $tag->term_id;
            $mim_coverimage=get_metadata('taxonomy', $mim_term_id, 'mim_category_cover_image', true) ;	
            $mim_coverimage_path=wp_get_attachment_image_src($mim_coverimage,'thumbnail'); 	
			$mim_cover_style= empty($mim_coverimage) ? 'display:none' : '';
			if(!empty($mim_coverimage))
			{
				$mim_image_path=$mim_coverimage_path[0];
			} else {
				$mim_image_path='';
			}	
            ?>
			<tr class="form-field">
				<?php $width=get_option('mim_cover_width');
				$height=get_option('mim_cover_height'); ?>
				<th valign="top" scope="row"><?php _e( 'Cover Image', 'mim-issue'  ); ?></th>
				<td>
					 <div id="mim_magazine_cover_img_show" class="cover_img_edit" style="<?php _e($mim_cover_style,'mim-issue');?>">
						 <img src="<?php _e(esc_url($mim_image_path),'mim-issue');?>" name="mim_display_cover_image_magazine" id="mim_display_cover_image_magazine"/>
					 </div>
					 <input id="mim_upload_image_magazine" type="hidden" size="36" name="mim_upload_image_magazine" value="<?php _e($mim_coverimage,'mim-issue');?>" />
					 <input id="mim_upload_image_button_magazine" type="button" value="<?php _e('Upload','mim-issue');?>" class="mim_image_magazine button button-primary"/>
					 <input id="remove_magazine_image" type="button" value="<?php _e('Remove Image','mim-issue');?>" class="mim_remove_magazine button button-primary" style="<?php _e($mim_cover_style,'mim-issue');?>"><br/> 
					 <span class="description"><?php _e('Cover image size is dynamic or static? If static, change it to get dynamic value from plugin settings.<br/>You must upload','mim-issue');?><?php _e($width.'*'.$height,'mim-issue');?> <?php _e('size of image.','mim-issue');?></span>
				</td>
			</tr>
		<?php $mim_nonce = wp_create_nonce( 'mim-category-nonce' ); ?>
			<input type="hidden" name="category_wpnonce" value="<?php _e($mim_nonce,'mim-issue');?>">			
		<?php	
		}
		add_action( 'magazine_category_edit_form_fields','mim_magazine_category_edit_form_fields');
	}
	
		/**
		* Save custom field data for magazine category.
		*
		* Function Name: mim_save_magazine_category_custom_meta.
		*
		* 
		*
	**/
	if ( !function_exists( 'mim_save_magazine_category_custom_meta' ) )  {
		function mim_save_magazine_category_custom_meta( $term_id ) {	  	
			$mim_nonces = $_REQUEST['category_wpnonce'];	
			if(! wp_verify_nonce( $mim_nonces, 'mim-category-nonce' ))
				return;		
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				$tax_name = $_POST['taxonomy'];
				$tax_obj  = get_taxonomy($tax_name);
			} else {
				$tax_name = get_current_screen()->taxonomy;
				$tax_obj  = get_taxonomy($tax_name);
			}				
			if ( !current_user_can( $tax_obj->cap->edit_terms ) )
				return $term_id;	

			$mim_upload_image_magazine=$_REQUEST['mim_upload_image_magazine'];

			if ( $_REQUEST['taxonomy'] == 'magazine_category' ) {		
				update_metadata('taxonomy', $term_id, 'mim_category_cover_image', $mim_upload_image_magazine); 	
			}
		}  	
		add_action( 'edited_magazine_category', 'mim_save_magazine_category_custom_meta', 10, 2 );  
		add_action( 'create_magazine_category', 'mim_save_magazine_category_custom_meta', 10, 2 );
	}
	
	/**
		* Added custom field in issue taxonomy.
		*
		* Function Name: mim_issues_taxonomy_add_form_fields.
		*
		* 
		*
	**/	
	if ( !function_exists( 'mim_issues_taxonomy_add_form_fields' ) )  {
		function mim_issues_taxonomy_add_form_fields() {		 
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'upload-image', MIM_PLUGIN_URL . 'js/media-upload.js' );	
			wp_enqueue_style( 'issue-form', MIM_PLUGIN_URL . 'css/issue.css' );
			?>
			<div class="form-field">
				<label for="mim_issue_menu"><?php _e( 'Select&nbsp;&nbsp;Issue Category', 'mim-issue' ); ?></label>
				<ul class="mim-ulcategory" id='sortable'>
					<?php 				
					$args = array(
					'descendants_and_self'  => 0,
					'selected_cats'         => false,
					'popular_cats'          => false,
					'walker'                => null,
					'taxonomy'              => 'magazine_category',
					'checked_ontop'         => true
					); 
					wp_terms_checklist( '', $args ); 
					?>			
				</ul>
				<p>
					<?php _e('Selected categories will be displayed in published issue. <br> To add magazine categories,', 'mim-issue');?>  <a href="<?php echo admin_url('edit-tags.php?taxonomy=magazine_category&post_type=magazine'); ?>"><?php _e('Click here.','mim-issue');?></a>
				</p>
			</div>
			<div class="form-field">
				<?php $width=get_option('mim_cover_width');
				$height=get_option('mim_cover_height'); ?>
				<label for="mim_cover_image"><?php _e( 'Cover Image','mim-issue' ); ?></label>
				<div id="mim_cover_img_show" class="cover_img">
					<img src="" name="mim_display_cover_image_issue" id="mim_display_cover_image_issue"/>
				</div>
				<input id="mim_upload_image_issue" type="hidden" size="36" name="mim_upload_image_issue" value="" />
				<input id="mim_upload_image_button_issue" type="button" value="<?php _e('Upload','mim-issue');?>" class="mim_image_issue button button-primary"/>
				<input id="remove_image" type="button" value="<?php _e('Remove Image','mim-issue');?>" class="mim_remove_issue button button-primary" style="display:none;"><br/>
				<p><?php _e('Cover image size is dynamic or static? If static, change it to get dynamic value from plugin settings.','mim-issue')?><br/><?php _e('You must upload','mim-issue');?> <?php _e($width.'*'.$height ,'mim-issue');  _e(' size of image.','mim-issue');?></p>
			</div> 
			<div class="form-field">
				<label for="mim_issue_pdf"><?php _e( 'Issue PDF' ,'mim-issue'); ?></label>
				<input id="mim_upload_file_pdf" type="text" size="36" name="mim_upload_file_pdf" value="" />
				<input id="mim_upload_file_button" type="button" value="<?php _e('Add File','mim-issue');?>" class="issuepdfform button button-primary"/>
				<input id="mim_upload_remove_file" type="button" value="<?php _e('Remove File','mim-issue');?>" class="issueremovepdfform button button-primary" style="display:none;"/><br/>
				<p><?php _e('Upload PDF for issue if you would like to manage issues as PDF.','mim-issue');?></p>
			</div> 
			<div class="form-field">		
				<label for="mim_issue_menu"><?php _e( 'Issue Publish Date', 'mim-issue' ); ?></label>
				<input type="text" name="mim_issue_publish_date" id="mim_issue_publish_date"  value=""/><br/>
				<p><?php _e('Issue Publish Date (Format: YYYY-MM-DD)','mim-issue');?></p>
			</div>
			<?php $mim_nonce = wp_create_nonce( 'mim-issue-nonce' ); ?>
			<input type="hidden" name="isuue_wpnonce" value="<?php _e($mim_nonce,'mim-issue');?>">
		<?php	
		}
		add_action( 'issues_add_form_fields', 'mim_issues_taxonomy_add_form_fields' );
	}
  
   	/**
		* Edited custom field in issue taxonomy.
		*
		* Function Name: mim_issues_taxonomy_edit_form_fields.
		*
		* 
		*
	**/
	
   if ( !function_exists( 'mim_issues_taxonomy_edit_form_fields' ) )  {
		function mim_issues_taxonomy_edit_form_fields($tag) {
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'upload-image', MIM_PLUGIN_URL . 'js/media-upload.js' );
			wp_enqueue_style( 'issue-form-edit', MIM_PLUGIN_URL . 'css/issue-edit.css' );
			$mim_term_id = $tag->term_id;
			$mim_cateroty=get_metadata('taxonomy', $mim_term_id, 'mim_issue_menu_category', true) ;	
			$mim_coverimage=get_metadata('taxonomy', $mim_term_id, 'mim_issue_cover_image', true) ;	
			$mim_master_category=get_metadata('taxonomy', $mim_term_id, 'mim_issue_master_category', true) ;
			$mim_issue_date=get_metadata('taxonomy', $mim_term_id, 'mim_issue_publish_date', true) ;	
			$mim_edit_issue_pdf_file=get_metadata('taxonomy', $mim_term_id, 'mim_issue_pdf_file', true) ;	
			$mim_coverimage_path=wp_get_attachment_image_src($mim_coverimage,'thumbnail'); 	
			$mim_cover_style= empty($mim_coverimage) ? 'display:none' : '';
			if(!empty($mim_coverimage))
			{
				$mim_image_path=$mim_coverimage_path[0];
			} else {
				$mim_image_path='';
			}	
			?>
			<tr class="form-field">
				<th valign="top" scope="row"><?php _e( 'Select Master Category','mim-issue' ); ?></th>
				<td>
					<?php
						$mim_master_category_selected = !empty($mim_master_category)? $mim_master_category : '-1';
						$mim_master_category_selected;
						$mim_selected='selected='.$mim_master_category_selected;
						if(!empty($mim_cateroty)):
							$str_sel_cat=implode(',',$mim_cateroty);
						else:
							$str_sel_cat='';
						endif;
						wp_dropdown_categories('show_option_none=Select Master category&depth=0&hierarchical=1&orderby=name&echo=1&taxonomy=magazine_category&hide_empty=0&'.$mim_selected.'&include='.$str_sel_cat.'&parent=0');
					?>
					<br/> <span class="description"><?php _e('Select master category here.','mim-issue');?></span>
				</td>
		   </tr>
			<tr class="form-field">
				<th valign="top" scope="row"><?php _e( 'Select&nbsp;&nbsp;Issue Category', 'mim-issue' ); ?></th>
				<td>
					<ul class="mim-ulcategory" id='sortable'>
						<?php 
						if(!empty($mim_cateroty)) {
							foreach($mim_cateroty as $mim_cat_name => $mim_cat_value ) {		
								$cat_name=get_term_by( 'id', $mim_cat_value, 'magazine_category',ARRAY_A) ;				
								if(!empty($cat_name['name'])):
								?>
									<li class="popular-category" id="magazine_category-<?php _e($mim_cat_value,'mim-issue');?>"><label class="selectit"><input type="checkbox" id="in-magazine_category-<?php _e($mim_cat_value,'mim-issue');?>" name="tax_input[magazine_category][]" checked="checked" value="<?php _e($mim_cat_value,'mim-issue');?>"> <?php _e($cat_name['name'],'mim-issue');?></label></li>
								<?php
								endif;
							}	
						}					
						$categories_list = get_categories(array('hide_empty'=> 0,'exclude'=> $mim_cateroty,'taxonomy' => 'magazine_category','parent'=> 0));					
						foreach($categories_list as $mim_cat_name => $mim_cat_value ) {	
							if(!empty($mim_cat_value->name)):					
							?>
								<li class="popular-category" id="magazine_category-<?php _e($mim_cat_value->term_id,'mim-issue');?>"><label class="selectit"><input type="checkbox" id="in-magazine_category-<?php _e($mim_cat_value->term_id,'mim-issue');?>" name="tax_input[magazine_category][]"  value="<?php _e($mim_cat_value->term_id,'mim-issue');?>"> <?php _e($mim_cat_value->name,'mim-issue');?></label></li>
							<?php
							endif;	
						}					
						?>			
					</ul>
					<span class="description"><?php _e('Selected categories will be displayed in published issue. To add magazine categories, ','mim-issue')?> <a href="<?php echo admin_url('edit-tags.php?taxonomy=magazine_category&post_type=magazine','mim-issue'); ?>"><?php _e('Click here.','mim-issue');?></a><br><strong><?php _e('If you checked issue catgeory then you must click on update button then all checked categories will be reflected in select master category.','mim-issue');?></strong></span>
				</td>
			</tr>
			<tr class="form-field">
				<?php $width=get_option('mim_cover_width');
				$height=get_option('mim_cover_height'); ?>
				<th valign="top" scope="row"><?php _e( 'Cover Image', 'mim-issue'  ); ?></th>
				<td>
					 <div id="mim_cover_img_show" class="cover_img_edit" style="<?php _e($mim_cover_style,'mim-issue');?>">
						 <img src="<?php _e(esc_url($mim_image_path),'mim-issue');?>" name="mim_display_cover_image_issue" id="mim_display_cover_image_issue"/>
					 </div>
					 <input id="mim_upload_image_issue" type="hidden" size="36" name="mim_upload_image_issue" value="<?php _e($mim_coverimage,'mim-issue');?>" />
					 <input id="mim_upload_image_button_issue" type="button" value="<?php _e('Upload','mim-issue');?>" class="mim_image_issue button button-primary"/>
					 <input id="remove_image" type="button" value="<?php _e('Remove Image','mim-issue');?>" class="mim_remove_issue button button-primary" style="<?php _e($mim_cover_style,'mim-issue');?>"><br/> 
					 <span class="description"><?php _e('Cover image size is dynamic or static? If static, change it to get dynamic value from plugin settings.<br/>You must upload','mim-issue');?><?php _e($width.'*'.$height,'mim-issue');?> <?php _e('size of image.','mim-issue');?></span>
				</td>
			</tr>	  
			<tr class="form-field">	   		
				<th valign="top" scope="row"><?php _e( 'Issue PDF','mim-issue' ); ?></th>
				<td>
					<?php 
					if($mim_edit_issue_pdf_file == '') {
						$style='display:none;';
					} else {
						$style='';
					}
					?>
				  <input id="mim_upload_file_pdf" type="text" size="36" name="mim_upload_file_pdf" value="<?php _e($mim_edit_issue_pdf_file,'mim-issue');?>" />
				  <input id="mim_upload_file_button" type="button" value="<?php _e('Add File','mim-issue');?>" class="issuepdfform button button-primary"/>
				  <input id="mim_upload_remove_file" type="button" value="<?php _e('Remove File','mim-issue');?>" class="issueremovepdfform button button-primary" style="<?php _e($style,'mim-issue');?>"/>
				  <br/><span class="description"><?php _e('Upload PDF for issue if you would like to manage issues as PDF.','mim-issue');?></span>
				</td>		
			</tr>	
			<tr class="form-field">	   		
				<th valign="top" scope="row"><?php _e( 'Issue Publish Date', 'mim-issue' ); ?></th>
				<td>
					<input type="text" name="mim_issue_publish_date" id="mim_issue_publish_date"  value="<?php _e($mim_issue_date,'mim-issue');?>"/><br/>			
					<span class="description"><?php _e('Issue Publish Date (Format: YYYY-MM-DD)','mim-issue');?></span>
				</td>
			</tr>	
			<?php $mim_nonce = wp_create_nonce( 'mim-issue-nonce' ); ?>
			<input type="hidden" name="isuue_wpnonce" value="<?php _e($mim_nonce,'mim-issue');?>">			
		<?php	
		}
		add_action( 'issues_edit_form_fields','mim_issues_taxonomy_edit_form_fields');
	}

   	/**
		* Save custom field data for issue taxonomy.
		*
		* Function Name: mim_save_issue_taxonomy_custom_meta.
		*
		* 
		*
	**/
	if ( !function_exists( 'mim_save_issue_taxonomy_custom_meta' ) )  {
		function mim_save_issue_taxonomy_custom_meta( $term_id ) {	  	
			$mim_nonces = $_REQUEST['isuue_wpnonce'];	
			if(! wp_verify_nonce( $mim_nonces, 'mim-issue-nonce' ))
				return;		
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				$tax_name = $_POST['taxonomy'];
				$tax_obj  = get_taxonomy($tax_name);
			} else {
				$tax_name = get_current_screen()->taxonomy;
				$tax_obj  = get_taxonomy($tax_name);
			}				
			if ( !current_user_can( $tax_obj->cap->edit_terms ) )
				return $term_id;	
			$mim_issue_menu_category=$_REQUEST['tax_input']['magazine_category'];	
			$mim_issue_display_category=$_REQUEST['tax_input']['magazine_category'];
			$mim_upload_image_issue=$_REQUEST['mim_upload_image_issue'];
			$mim_issue_pdf_file=$_REQUEST['mim_upload_file_pdf'];
			if(!empty($_REQUEST['mim_issue_publish_date'])) {
				$mim_issue_publish_date=$_REQUEST['mim_issue_publish_date'];
			} else {
				$mim_issue_publish_date=date('Y-m-d');
			}
			if (  $_REQUEST['taxonomy'] == 'issues' ) {		
				update_metadata('taxonomy', $term_id, 'mim_issue_menu_category', $mim_issue_menu_category); 
				update_metadata('taxonomy', $term_id, 'mim_issue_display_category', $mim_issue_display_category); 
				update_metadata('taxonomy', $term_id, 'mim_issue_cover_image', $mim_upload_image_issue); 
				update_metadata('taxonomy', $term_id, 'mim_issue_pdf_file', $mim_issue_pdf_file); 
				update_metadata('taxonomy', $term_id, 'mim_issue_publish_date',$mim_issue_publish_date ); 	
				if($_REQUEST['action']== 'editedtag'){
					$mim_master_cat=$_REQUEST['cat'];
					update_metadata('taxonomy', $term_id, 'mim_issue_master_category',$mim_master_cat ); 	
				}	
			}
		}  	
		add_action( 'edited_issues', 'mim_save_issue_taxonomy_custom_meta', 10, 2 );  
		add_action( 'create_issues', 'mim_save_issue_taxonomy_custom_meta', 10, 2 );
	}
  
   	/**
		*Remove parent issue dropdown for issue taxonomy.
		*
		* Function Name: mim_issue_remove_parent_category.
		*
		* 
		*
	**/	
	if ( !function_exists( 'mim_issue_remove_parent_category' ) ) {
		function mim_issue_remove_parent_category() {                                                        
			if ( 'issues' != $_GET['taxonomy'] )
				return;
			$parent = 'parent()';
			if ( isset( $_GET['action'] ) )
				$parent = 'parent().parent()';

			?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {     
						$('label[for=parent]').<?php echo $parent; ?>.remove();       
					});
				</script>
			<?php
		}	  	  	  
		add_action( 'admin_head-edit-tags.php', 'mim_issue_remove_parent_category' );
	}
   
   	/**
		* Issue Filtering for magazine.
		*
		* Function Name: mim_issue_filter_list.
		*
		* 
		*
	**/
	 if ( !function_exists( 'mim_issue_filter_list' ) )  { 	
		   add_action( 'restrict_manage_posts', 'mim_issue_filter_list' );	   
		   function mim_issue_filter_list() {
				$screen = get_current_screen();
				global $wp_query;
				if ( $screen->post_type == 'magazine' ) {
					wp_dropdown_categories(array(
						'show_option_all' => __('Show All Issues','mim-issue'),
						'taxonomy' => 'issues',
						'name' => 'issues',
						'orderby' => 'name',
						'selected' => ( isset( $wp_query->query['issues'] ) ? $wp_query->query['issues'] : '' ),
						'hierarchical' => false,
						'show_count' => false,
						'hide_empty' => false,
					));
				}
			}
		}
	
	/**
		* Magazine Category Filtering for magazine.
		*
		* Function Name: mim_magazine_filter_list.
		*
		* 
		*
	**/
	if ( !function_exists( 'mim_magazine_filter_list' ) )  {
		add_action( 'restrict_manage_posts', 'mim_magazine_filter_list' );	   
		function mim_magazine_filter_list() {
			$screen = get_current_screen();
			global $wp_query;
			if ( $screen->post_type == 'magazine' ) {
				wp_dropdown_categories(array(
					'show_option_all' => __('Show All Magazine Category','mim-issue'),
					'taxonomy' => 'magazine_category',
					'name' => 'magazine_category',
					'orderby' => 'name',
					'selected' => ( isset( $wp_query->query['magazine_category'] ) ? $wp_query->query['magazine_category'] : '' ),
					'hierarchical' => false,         
					'show_count' => false,
					'hide_empty' => false,
				));
			}
		}
	}
	
	/**
		*  Issues filtering result for magazine.
		*
		* Function Name: mim_issue_perform_filtering.
		*
		* 
		*
	**/
	if ( !function_exists( 'mim_issue_perform_filtering' ) )  {		
		add_filter( 'parse_query','mim_issue_perform_filtering' );
		function mim_issue_perform_filtering( $query ) {
			$qv = &$query->query_vars;
			if( !empty ( $qv ) )
			{
				if ( !empty ( $qv['issues'] ) && is_numeric( $qv['issues'] ) ) {
				$term = get_term_by( 'id', $qv['issues'], 'issues' );
				$qv['issues'] = $term->slug;
			  }
			}  
		}
	 }
  
   /**
		*  Magazine Category filtering result for magazine.
		*
		* Function Name: mim_magazine_category_perform_filtering.
		*
		* 
		*
	**/
	if ( !function_exists( 'mim_magazine_category_perform_filtering' ) )  {
		add_filter( 'parse_query','mim_magazine_category_perform_filtering' );	
		function mim_magazine_category_perform_filtering( $query ) {
			$qv = &$query->query_vars;	
			if( !empty ( $qv ) )
			{	
				if ( !empty ( $qv['magazine_category'] ) && is_numeric( $qv['magazine_category'] ) ) {
					$term = get_term_by( 'id', $qv['magazine_category'], 'magazine_category' );
					$qv['magazine_category'] = $term->slug;
				}
			}	
		}
	}
	
	/**
		*  Custom column added in Magazine Category.
		*
		* Function Name: mim_magazine_category_custom_columns.
		*
		* 
		*
	**/
	if ( !function_exists( 'mim_magazine_category_custom_columns' ) )  {
		add_filter("manage_edit-magazine_category_columns", 'mim_magazine_category_custom_columns');	 
		function mim_magazine_category_custom_columns($theme_columns) {
		    $new_columns = array(
				'cb' => '<input type="checkbox" />',
				'cover_image' =>__('Cover Image','mim-issue'),
		        'name' => __('Name','mim-issue'), 
		        'slug' => __('Slug','mim-issue'), 
		        'posts' => __('No of Articles','mim-issue')
		        );
		    return $new_columns;
		}
	}
	
	/**
		*  Custom column added in Magazine Category.
		*
		* Function Name: mim_magazine_category_manage_columns.
		*
		* 
		*
	**/
	
	if ( !function_exists( 'mim_magazine_category_manage_columns' ) )  {	
		add_filter("manage_magazine_category_custom_column", 'mim_magazine_category_manage_columns', 10, 3);	 
		function mim_magazine_category_manage_columns($out, $column_name, $mim_term_id) {   
		    switch ($column_name) {
		        case 'cover_image':
		           	$mim_coverimage=get_metadata('taxonomy', $mim_term_id, 'mim_category_cover_image', true) ;
					$mim_coverimage_path=wp_get_attachment_image_src($mim_coverimage,'thumbnail'); 
					if(empty($mim_coverimage))
						$imgurl=MIM_PLUGIN_URL . '/images/default.jpg';
						
					else
						$imgurl=$mim_coverimage_path[0];
					$out .='<img src="'.esc_url($imgurl).'" />';			
		            break;
		        default:
		            break;
		    }
		    return $out;   
		}
	}

	/**
		*  Custom column added in Issue taxonomy.
		*
		* Function Name: mim_issue_custom_columns.
		*
		* 
		*
	**/
	if ( !function_exists( 'mim_issue_custom_columns' ) )  {
		add_filter("manage_edit-issues_columns", 'mim_issue_custom_columns');	 
		function mim_issue_custom_columns($theme_columns) {
		    $new_columns = array(
		        'cb' => '<input type="checkbox" />',
				'cover_image' =>__('Cover Image','mim-issue'),
		        'name' => __('Name','mim-issue'), 
				'issue_menu' => __('Issue Menu','mim-issue'),
				'issue_date' => __('Issue Date','mim-issue'),       
		        'posts' => __('No of Issue','mim-issue')
		        );
		    return $new_columns;
		}
	}
    
	/**
		*  Custom column added in Issue taxonomy.
		*
		* Function Name: mim_issue_manage_columns.
		*
		* 
		*
	**/
	
	if ( !function_exists( 'mim_issue_manage_columns' ) )  {	
		add_filter("manage_issues_custom_column", 'mim_issue_manage_columns', 10, 3);	 
		function mim_issue_manage_columns($out, $column_name, $mim_term_id) {   
		    switch ($column_name) {
		        case 'cover_image':
		           	$mim_coverimage=get_metadata('taxonomy', $mim_term_id, 'mim_issue_cover_image', true) ;
					$mim_coverimage_path=wp_get_attachment_image_src($mim_coverimage,'thumbnail'); 
					if(empty($mim_coverimage))
						$imgurl=MIM_PLUGIN_URL . '/images/default.jpg';
						
					else
						$imgurl=$mim_coverimage_path[0];
					$out .='<img src="'.esc_url($imgurl).'" />';			
		            break;
		 		case 'issue_menu':
					$mim_cateroty=get_metadata('taxonomy', $mim_term_id, 'mim_issue_menu_category', true) ;
					if(!empty($mim_cateroty)) {
						$outs = '';
						foreach($mim_cateroty as $mim_cat_name => $mim_cat_value ) {
							
							$cat_name=get_term_by( 'id', $mim_cat_value, 'magazine_category',ARRAY_A) ;
							if(!empty($cat_name))
							 $outs .= $cat_name['name'].',';
						}	
					}
					else{
						$outs = '  -----  ';
					}
					$out =rtrim($outs,',');
					break;
				case 'issue_date':
		            $mim_issue_date=get_metadata('taxonomy', $mim_term_id, 'mim_issue_publish_date', true) ;
					if(!empty($mim_issue_date)) {
						$out .= date('F j, Y',strtotime($mim_issue_date));		
					} else {
						$out .= ' ----- ';		
					}	
		            break;	
		        default:
		            break;
		    }
		    return $out;   
		}
	}
	
	/**
		*  Custom column added in magazine.
		*
		* Function Name: mim_add_new_magazine_columns.
		*
		* 
		*
	**/
	if ( !function_exists( 'mim_add_new_magazine_columns' ) )  {	
		add_filter('manage_edit-magazine_columns', 'mim_add_new_magazine_columns');
		function mim_add_new_magazine_columns($columns) {  
			$new_columns = array();
		    $columns_1 = array_slice( $columns, 0, 1 );
			$columns_2 = array_slice( $columns, 1 );
		    $new_columns = $columns_1 + array( 'images' => __('Featured Image','mim-issue') ) + $columns_2;
		    return $new_columns;
		}
	}
	
	/**
		*  Custom column added in magazine.
		*
		* Function Name: mim_manage_magazine_columns.
		*
		* 
		*
	**/	
	if ( !function_exists( 'mim_manage_magazine_columns' ) )  {
		add_action('manage_magazine_posts_custom_column', 'mim_manage_magazine_columns', 10, 2);	 
		function mim_manage_magazine_columns($column_name, $postid) {
		    global $wpdb;
		    switch ($column_name) {
		    case 'images':
		        echo get_the_post_thumbnail($postid,'thumbnail');
		        break;
		    default:
		        break;
		    } 
		}   
	}
	
	/**
		* When delete issue then custom issue taxonomy field deleted.
		*
		* Function Name: mim_delete_issue_taxonomy_custom_meta.
		*
		* 
		*
	**/
	
	if ( !function_exists( 'mim_delete_issue_taxonomy_custom_meta' ) )  {		
		add_action( 'delete_issues', 'mim_delete_issue_taxonomy_custom_meta', 10, 2 );
		function mim_delete_issue_taxonomy_custom_meta($term_id){
			delete_metadata('taxonomy', $term_id, 'mim_issue_menu_category'); 
			delete_metadata('taxonomy', $term_id, 'mim_issue_cover_image');
			delete_metadata('taxonomy', $term_id, 'mim_issue_pdf_file');
			delete_metadata('taxonomy', $term_id, 'mim_issue_publish_date');
			delete_metadata('taxonomy', $term_id, 'mim_issue_master_category');	
		}
	}
	
	/**
		* When delete issue then mim_issue_menu_category custom issue taxonomy field updated .
		*
		* Function Name: mim_delete_magazine_category_taxonomy_custom_meta.
		*
		* 
		*
	**/
	
	if ( !function_exists( 'mim_delete_magazine_category_taxonomy_custom_meta' ) )  {		
		add_action( 'delete_magazine_category', 'mim_delete_magazine_category_taxonomy_custom_meta', 10, 2 );		
		function mim_delete_magazine_category_taxonomy_custom_meta($term_id){			
			$args = array(
			    'orderby'       => 'name', 
			    'order'         => 'ASC',
			    'hide_empty'    => false, 
	  		); 
			$all_issue_term=get_terms( 'issues',$args );
			$arr=array();
			foreach($all_issue_term as $k=>$v) {
				$mim_cateroty=get_metadata('taxonomy', $v->term_id, 'mim_issue_menu_category', true) ;	
				$mim_cateroty_master=get_metadata('taxonomy', $v->term_id, 'mim_issue_master_category', true) ;	
				if(!empty($mim_cateroty)) {				
					if(($key = array_search($term_id,$mim_cateroty)) !== false) {
	    				unset($mim_cateroty[$key]);
						update_metadata('taxonomy', $v->term_id, 'mim_issue_menu_category', $mim_cateroty); 
					}           
				}
				if($mim_cateroty_master == $term_id){
					update_metadata('taxonomy', $v->term_id, 'mim_issue_master_category', '-1'); 
				}	
			}
		}
	}
?>