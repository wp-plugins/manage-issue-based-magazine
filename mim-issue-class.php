<?php
if ( ! class_exists( 'MIM_Issue' ) ) {
	
	class MIM_Issue{
		
		

		/**
		* Default Constructor called.
		*
		* Function Name: __construct.
		*
		* 
		*
		**/
		function __construct(){
			add_action( 'admin_menu', array( $this, 'mim_issue_setting_admin_menu' ) );
			add_action('init',  array( $this,'mim_init'));
		}
		
		/**
		* Added submenu setting page in menu of magazines.
		*
		* Function Name: mim_issue_setting_admin_menu.
		*
		* 
		*
		**/
		
		function mim_issue_setting_admin_menu() {
							
			add_submenu_page( 'edit.php?post_type=magazine', __( 'MIM Issue Settings', 'mim-issue' ), __( 'MIM Issue Settings', 'mim-issue' ), 'manage_options', 'issue-setting', array( $this, 'mim_issue_settings_page' ) );
			add_submenu_page( 'edit.php?post_type=magazine', __( 'MIM Plugin Help', 'mim-issue' ), __( 'MIM Plugin Help', 'mim-issue' ), 'manage_options', 'help', array( $this, 'mim_issue_help_page' ) );
		
		}
		/**
		* Added Cover image size.
		*
		* Function Name: mim_init.
		*
		* 
		*
		**/
		
		function mim_init()
		{
			$width = get_option('mim_cover_width');
			$height = get_option('mim_cover_height');
			$f_width = !empty($width) ? $width : '840';
			$f_height = !empty($height) ? $height : '480';
 			add_image_size('mim-issue-cover-image',$f_width, $f_height, true);
		}
		/**
		* Help page of issue plugin.
		*
		* Function Name: mim_issue_help_page.
		*
		* 
		*
		**/
		
		function mim_issue_help_page()
		{
			?>
            <div class="wrap">
              <div style="width:70%;" class="postbox-container">
                <div class="metabox-holder">
                  <div class="meta-box-sortables ui-sortable">
                    <h2 style="margin-bottom: 10px;"><?php echo _e('MIM Plugin Help', 'mim-issue');?></h2>
                    <div id="modules" class="postbox">
                      <div class="handlediv" title="Click to toggle"><br>
                      </div>
                      <h3 class="hndle"><span><?php echo _e('User Guide (PDF)', 'mim-issue');?></span></h3>
                      <div class="inside"> <a class="button-primary" href="http://mim.purplemadprojects.com/User-Guide-for-Manage-Issue-Based-Magazine-Plugin.pdf" target="_blank"><?php echo _e('Download User Guide', 'mim-issue');?></a><br>
                        <small><?php echo _e('for Manage Issue Based Magazine Plugin', 'mim-issue');?></small>
                        <p><br><?php echo _e('OR', 'mim-issue');?><br></p>
                        <p><?php echo _e('For more information visit website : ', 'mim-issue');?><a href="http://www.purplemad.ca" target="_blank">www.purplemad.ca</a></p>
                        
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
		<?php }
		
		/**
		* Setting page of issue plugin.
		*
		* Function Name: mim_issue_settings_page.
		*
		* 
		*
		**/
		
		function mim_issue_settings_page() {
			wp_enqueue_style( 'isuue-css', MIM_PLUGIN_URL . 'css/admin-issue.css' );
			wp_enqueue_script( 'settings', MIM_PLUGIN_URL . 'js/setting.js' );
			if(isset($_REQUEST['update_issuem_settings']))
			{ 
				if ( !isset($_POST['mim_issue_nonce']) || !wp_verify_nonce($_POST['mim_issue_nonce'],'mim_issue_general_setting') )
				{
				    _e('Sorry, your nonce did not verify.', 'mim-issue');
				   exit;
				}
				else
				{
					
				  $mim_full_article_display= !empty($_REQUEST['mim_full_article_display']) ? $_REQUEST['mim_full_article_display'] : 'No';
				  update_option('mim_full_article_display',$mim_full_article_display);
					
				  $mim_allow_user_post_article= !empty($_REQUEST['mim_allow_user_post_article']) ? $_REQUEST['mim_allow_user_post_article'] : 'No';
				  update_option('mim_allow_user_post_article',$mim_allow_user_post_article);
				  
				  $mim_default_post_article_status= !empty($_REQUEST['mim_default_post_article_status']) ? $_REQUEST['mim_default_post_article_status'] : 'draft';
				  update_option('mim_default_post_article_status',$mim_default_post_article_status);
				  
				  $mim_editor_register_check= ($_REQUEST['users_can_register'])!='' ? $_REQUEST['users_can_register'] : '1';
				  update_option('users_can_register',$mim_editor_register_check);
				  
				  $mim_new_editor_status= !empty($_REQUEST['mim_new_editor_status']) ? $_REQUEST['mim_new_editor_status'] : 'Yes';
				  update_option('mim_new_editor_status',$mim_new_editor_status);
				  
				  $mim_cover_width= !empty($_REQUEST['mim_cover_width']) ? $_REQUEST['mim_cover_width'] : '300';
				  update_option('mim_cover_width',$mim_cover_width);
				  
				  $mim_cover_height= !empty($_REQUEST['mim_cover_height']) ? $_REQUEST['mim_cover_height'] : '300';
				  update_option('mim_cover_height',$mim_cover_height);
				  
				  $mim_post_per_page_article= !empty($_REQUEST['mim_post_per_page_article']) ? $_REQUEST['mim_post_per_page_article'] : '5';
				  update_option('mim_post_per_page_article',$mim_post_per_page_article);
				  update_option('posts_per_page',$mim_post_per_page_article);
				  
				  
				  $mim_search_behaviour= !empty($_REQUEST['mim_search_behaviour']) ? $_REQUEST['mim_search_behaviour'] : 'Yes';
				  update_option('mim_search_behaviour',$mim_search_behaviour);
				  
				  $mim_current_issue= !empty($_REQUEST['cat']) ? $_REQUEST['cat'] : 'Select Current Issue';
				  update_option('mim_current_issue',$mim_current_issue);
				   
				  $mim_issue_menu_category= !empty($_REQUEST['mim_issue_menu_category']) ;
				  update_option('mim_issue_menu_category',$mim_issue_menu_category);
				
				  $mim_issue_display_category= !empty($_REQUEST['mim_issue_display_category']) ;
				  update_option('mim_issue_display_category',$mim_issue_display_category);
				 
				  $page_for_magazines= !empty($_REQUEST['page_for_magazines']) ? $_REQUEST['page_for_magazines'] : 'Select';
				  update_option('page_for_magazines',$page_for_magazines);
				  
				  $page_for_archives= !empty($_REQUEST['page_for_archives']) ? $_REQUEST['page_for_archives'] : 'Select';
				  update_option('page_for_archives',$page_for_archives);
				  
				 
				 		
				 ?>
				 <script>
					jQuery(document).ready(function(){
						jQuery('body,html').animate({
									scrollTop: 0
							},1000);				
						jQuery( "#message" ).fadeIn( 1000, function() {
							jQuery( "#message" ).fadeOut( 2500 );
						});
						return false;
					 });			  
				</script>
				 <?php
				}
			}
			$mim_arr_yesno=array('Yes'=>'Yes','No'=>'No');
			$mim_arr_yesno_number=array('1'=>'Yes','0'=>'No');
			?>
			
			<div class=wrap>
            	<div style="width:70%;" class="postbox-container">
            		<div class="metabox-holder">	
            			<div class="meta-box-sortables ui-sortable">
            				 <form id="issue-setting" method="post" action="" enctype="multipart/form-data" >
            
                   			 <h2 style='margin-bottom: 10px;' ><?php _e( 'Magazine Issue General Settings', 'mim-issue' ); ?></h2>
							 <div id="message" class="updated" style="display:none;">
								<p><?php _e('Submitted Successfully', 'mim-issue' );?></p>
							 </div>
                    		 
                  			  <div id="modules" class="postbox">
                    
                        		<div class="handlediv" title="Click to toggle"><br /></div>
                        
                       			 <h3 class="hndle"><span><?php _e( 'General Settings', 'mim-issue' ); ?></span></h3>
                        
                       				 <div class="inside">
                        
				                        <table  id="mim-issue-table">
										<tbody>
				                            <tr>
				                                <th rowspan="1">  <?php _e( 'Page for Magazines', 'mim-issue' ); ?>
				                                <?php _e( '<i><p style="font-weight: normal;">(Displays Magazine Listing On Selected Page,If selected page is empty)</p></i>', 'mim-issue' ); ?>	
				                                </th>
				                                <td>
												<?php
												$page_for_magazines=get_option('page_for_magazines');
												$page_for_magazines_selected = !empty($page_for_magazines)? $page_for_magazines : '-1';
												echo wp_dropdown_pages( array( 'name' => 'page_for_magazines', 'echo' => 0, 'show_option_none' => __( '&mdash; Select &mdash;' ), 'option_none_value' => '0','selected'=>$page_for_magazines_selected));	?>								
												</td>
				                            </tr>
				                            <tr>
				                                <th rowspan="1">  <?php _e( 'Page for Issues Archives', 'mim-issue' ); ?>
				                                <?php _e( '<i><p style="font-weight: normal;">(Displays Issue Listing On Selected Page,If selected page is empty)</p></i>', 'mim-issue' ); ?>	
				                                </th>
				                                <td>
												<?php
												$page_for_archives=get_option('page_for_archives');
												$page_for_archives_selected = !empty($page_for_archives)? $page_for_archives : '-1';
												//print_r($page_for_archives_selected);
												echo wp_dropdown_pages( array( 'name' => 'page_for_archives', 'echo' => 0, 'show_option_none' => __( '&mdash; Select &mdash;' ), 'option_none_value' => '0','selected'=>$page_for_archives_selected));	?>								
												</td>
				                            </tr>
				                           	
				                           	<tr>
				                                <th rowspan="1"> <?php _e( 'Display Issue Category', 'mim-issue' ); ?>
				                                <?php _e( '<i><p style="font-weight: normal;">(Issue Category if checked displays list of Current Issue Categories. )</p></i>', 'mim-issue' ); ?>
												</th>
				                                <td>
				                                <?php
				                                  $mim_issue_display_category=get_option('mim_issue_display_category');
				                                  $mim_issue_display_category_selected = !empty($mim_issue_display_category) ? $mim_issue_display_category : '0';
				                                ?>
				                                <input type="checkbox" id="mim_issue_display_category" name="mim_issue_display_category" <?php checked( $mim_issue_display_category_selected); ?>"/>Display Current Issue Categories<br/>
				                                
				                                </td>
				                           </tr>
				                         
											<tr>
				                                <th rowspan="1"> <?php _e( 'Display full article on category page', 'mim-issue' ); ?>
													<?php _e( '<i><p style="font-weight: normal;">(If this option is set to \'Yes\', when single article is there in category and you want to display full article on category page)</p></i>', 'mim-issue' ); ?>
												</th>
				                                <td>
												<select name="mim_full_article_display">
													<?php
														$mim_curr_sel_article_val=get_option('mim_full_article_display');
														foreach($mim_arr_yesno as $mim_k=>$mim_v){?>
																<option value="<?php _e($mim_k,'mim-issue');?>" <?php selected( $mim_curr_sel_article_val,$mim_v ,$echo = true);?>><?php _e($mim_v,'mim-issue');?></option>										
														<?php }
													?>
												</select>
												</td>												
				                            </tr>																					
				                        	<tr>
				                                <th rowspan="1"> <?php _e( 'Allow users to post articles in past issue', 'mim-issue' ); ?>
													<?php _e( '<i><p style="font-weight: normal;">(If this option is set to \'Yes\', logged in user would be able to post articles in past issues)</p></i>', 'mim-issue'); ?>
												</th>
				                                <td>
												<select name="mim_allow_user_post_article">
													<?php
														$mim_curr_sel_val=get_option('mim_allow_user_post_article');
														foreach($mim_arr_yesno as $mim_k=>$mim_v){?>
																<option value="<?php _e($mim_k,'mim-issue');?>" <?php selected( $mim_curr_sel_val,$mim_v ,$echo = true);?>><?php _e( $mim_v,'mim-issue');?></option>										
														<?php }
													?>
												</select>
												</td>
				                            </tr>
											
											<tr>
				                                <th rowspan="1"> <?php _e( 'Default article status for editors', 'mim-issue' ); ?>
													<?php _e( '<i><p style="font-weight: normal;">(Default article status when editors add new articles. This feature is useful for moderation of posted articles)</p></i>', 'mim-issue' ); ?>
												</th>
				                                <td>
												<select name="mim_default_post_article_status">
													<?php
														$mim_curr_default_val=get_option('mim_default_post_article_status');
														$mim_staus=array('approved'=>__('Approved'),'draft'=>__('Pending'));
														foreach($mim_staus as $mim_k=>$mim_v){?>
																<option value="<?php _e($mim_k,'mim-issue');?>" <?php selected( $mim_curr_default_val,$mim_k ,$echo = true);?>><?php _e($mim_v,'mim-issue');?></option>										
														<?php }
													?>
												</select>
												</td>
				                            </tr>
											
											<tr>
				                                <th rowspan="1"> <?php _e( 'Editors can register from website', 'mim-issue' ); ?>
												<?php _e( '<i><p style="font-weight: normal;">(If this option is set to \'Yes\', link will be displayed on website to register)</p></i>', 'mim-issue' ); ?>
													
												</th>
				                                <td>
												<select name="users_can_register">
													<?php
														$mim_curr_sel_editor_val=get_option('users_can_register');									
														foreach($mim_arr_yesno_number as $mim_k=>$mim_v){?>
																<option value="<?php _e($mim_k,'mim-issue');?>" <?php selected( $mim_curr_sel_editor_val,$mim_k ,$echo = true);?>><?php _e($mim_v,'mim-issue');?></option>										
														<?php }
													?>
												</select>
												</td>
				                            </tr>
											
											<tr>
				                                <th rowspan="1"> <?php _e( 'New editor status', 'mim-issue' ); ?>
												<?php _e( '<i><p style="font-weight: normal;">(Default status for users when they get registered from website)</p></i>', 'mim-issue' ); ?>
													
												</th>
				                                <td>
												<select name="mim_new_editor_status">
													<?php
														$mim_curr_sel_new_editor_val=get_option('mim_new_editor_status');		
														$mim_new_editor_status=array('approved'=>__('Approved', 'mim-issue'),'nonapproved'=>__('Non-Approved', 'mim-issue'));			
														foreach($mim_new_editor_status as $mim_k=>$mim_v){?>
																<option value="<?php _e($mim_k,'mim-issue');?>" <?php selected( $mim_curr_sel_new_editor_val,$mim_k ,$echo = true);?>><?php _e($mim_v,'mim-issue');?></option>										
														<?php }
													?>
												</select>
												</td>
				                            </tr>
											
											<tr>
				                                <th rowspan="1"> <?php _e( 'Cover image dimensions', 'mim-issue' ); ?>
												<?php _e( '<i><p style="font-weight: normal;">(Best size for your cover image. Thumbnail will be created with specified dimentions)</p></i>', 'mim-issue' ); ?>
													
												</th>
				                                <td>
													<?php
														$mim_check_width=get_option('mim_cover_width');
														$mim_check_height=get_option('mim_cover_height');
														$mim_cover_width=!empty($mim_check_width) ? $mim_check_width : '1366';
														$mim_cover_height=!empty($mim_check_height) ? $mim_check_height : '375';
													?>
													<?php _e('Width  :','mim-issue');?> <input type="text" maxlength="4" id="mim_cover_width" name="mim_cover_width" value="<?php _e($mim_cover_width,'mim-issue');?>"/><?php _e('px','mim-issue');?><br/>
													<?php _e('Height :','mim-issue');?> <input type="text" maxlength="3" id="mim_cover_height" name="mim_cover_height" value="<?php  _e($mim_cover_height,'mim-issue');?>"/><?php _e('px','mim-issue');?>
												</td>
				                            </tr>
											
											<tr>
				                                <th rowspan="1"> <?php _e( 'Number of articles displayed per page', 'mim-issue' ); ?>
												<?php _e( '<i><p style="font-weight: normal;">(Specified number of articles will be listed on each page on article listing page)</p></i>', 'mim-issue' ); ?>
													
												</th>
												<td>
												<?php $mim_post_per_page_article_val=get_option('mim_post_per_page_article');?>
												 <input type="number" name="mim_post_per_page_article" step="1" min="5" id="mim_post_per_page_article" value="<?php echo $mim_post_per_page_article_val;?>" class="small-text"><?php _e('Articles','mim-issue'); ?>	
												</td>
				                            </tr>
											
											<tr>
				                                <th rowspan="1"> <?php _e( 'Search behaviour (Within current issue or site wide)', 'mim-issue' ); ?>
												<?php _e( '<i><p style="font-weight: normal;">(If set to \'Within current issue\', search will be perfomed only on current selected issue. If set to \'Site wide\', search will be perfomed on all issues)</p></i>', 'mim-issue' ); ?>
													
												</th>
				                                <td>
													<?php $mim_search_behaviour_val=get_option('mim_search_behaviour');	?>
															<input type="radio" name="mim_search_behaviour" value="<?php _e('Yes','mim-issue');?>" <?php checked( $mim_search_behaviour_val, 'Yes', $echo ='true' );?>> <?php _e('Within current issue','mim-issue'); ?><br/>
															<input type="radio" name="mim_search_behaviour" value="<?php _e('No','mim-issue');?>" <?php checked( $mim_search_behaviour_val, 'No', $echo ='true' );?>> <?php _e('Site wide','mim-issue'); ?>
												</td>
				                            </tr>
				                            <tr>
				                            
				                                <td colspan="2"> 
				                               <code>
				                               /* change the search behaviour (Please add this code in to the theme function.php file) */ <br/>
add_filter( 'pre_get_posts', 'modified_pre_get_posts' ); <br/>
function modified_pre_get_posts( $query ) { <br/>
  global $wpdb,$wp_query;<br/>
  $cat_n=get_query_var('magazine_category');<br/>
  $idObj = get_term_by( 'slug', $cat_n, 'magazine_category' ); <br/><br/>
 
	$id = $idObj->term_id;<br/><br/>
  
  if(!isset($_SESSION))<br/>
  	session_start();<br/><br/>
  	
	
  if ( $query->is_search() && get_option('mim_search_behaviour')== 'Yes') {<br/>  
	$taxonomy_query =	array(<br/>
							array(<br/>
									'taxonomy' => 'issues',<br/>
									'field' => 'id',<br/>
									'terms' => $_SESSION['Current_Issue']<br/>
								)<br/>
					); <br/>
     $query->set('post_type','magazine');<br/>
	 $query->set('relation','AND');<br/>
	 $query->set('tax_query', $taxonomy_query);<br/>
	 <br/>
  } <br/>
  return $query; <br/>
}<br/>   </code>
													
												</td>
				                            </tr>
																					
											<tr>
				                                <th rowspan="1"> <?php _e( 'Current Issue', 'mim-issue' ); ?>
												<?php _e( '<i><p style="font-weight: normal;">(Issue set as default issue when website is loaded)</p></i>', 'mim-issue' ); ?>
												</th>
				                                <td>
												<?php
													$mim_current_issue=get_option('mim_current_issue');
													//print_r($mim_current_issue);
													$mim_current_issue_selected = !empty($mim_current_issue)? $mim_current_issue : '-1';
													$mim_current_selected='selected='.$mim_current_issue_selected;
													wp_dropdown_categories('show_option_none='.__("Select Current Issue", 'mim-issue').'&orderby=name&echo=1&taxonomy=issues&hide_empty=0&'.$mim_current_selected);?>													
												</td>
				                            </tr>
				                            <tr>
				                                <th rowspan="1"> <?php _e( 'Issue Category', 'mim-issue' ); ?>
				                                <?php _e( '<i><p style="font-weight: normal;">(Issue Category if checked displays all Current Issue Categories in Primary Menu)</p></i>', 'mim-issue' ); ?>
												</th>
				                                <td>
				                                <?php
				                                  $mim_issue_menu_category=get_option('mim_issue_menu_category');
				                                  $mim_issue_menu_category_selected = !empty($mim_issue_menu_category) ? $mim_issue_menu_category : '0';
				                                ?>
				                                <input type="checkbox" id="mim_issue_menu_category" name="mim_issue_menu_category" <?php checked( $mim_issue_menu_category_selected); ?>"/>Display Current Issue Menu Categories<br/>
				                                
				                                </td>
				                           </tr>
				                         			
										</tbody>	
				                        </table>
                        				<?php wp_nonce_field( 'mim_issue_general_setting', 'mim_issue_nonce' ); ?>
				                        <p class="submit">
				                         <input id="mim-submit" class="button-primary" type="submit" name="update_issuem_settings" value="<?php _e( 'Save Settings', 'mim-issue' ) ?>" />
				                        </p>
									</div>
                        
                   			  </div>
                    		  </form>                
            			</div>
          		    </div>
           	    </div>
			</div>
			<?php
			
		}
	}	
}	
?>