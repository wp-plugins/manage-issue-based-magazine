<?php
/**
* User Status change only administartor.
*
* Function Name: mim_custom_user_profile_fields.
*
* 
*
**/
function mim_custom_user_profile_fields( $user ) {
	
	if($user->roles[0]!='administrator'){
?>
	<h3><?php _e('User Status Updating', 'mim-issue'); ?></h3>
	
	<table class="form-table">
		<tr>
			<th><label for="user_status"><?php _e('User Status', 'mim-issue'); ?></label></th>
			<td>
				<?php $user_status_arr=array('approved'=>'Approved','pending'=>'Pending');
				 	  $curr_status=esc_attr(get_the_author_meta('mim_user_status', $user->ID) ); 
				?>
				
				<select name="mim_user_status">
					<?php foreach($user_status_arr as $key=>$value){?>
						<option value="<?php _e($key);?>" <?php selected($curr_status,$key,true); ?>><?php _e($value);?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
	</table>
<?php
	} 
}

/**
* Save User Status change only administartor.
*
* Function Name: mim_save_custom_user_profile_fields.
*
* 
*
**/
function mim_save_custom_user_profile_fields( $user_id ) {
	
	if ( !current_user_can( 'edit_user', $user_id ) )
		return FALSE;
	
	update_usermeta( $user_id, 'mim_user_status', $_POST['mim_user_status'] );
}

add_action( 'show_user_profile', 'mim_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'mim_custom_user_profile_fields' );

add_action( 'personal_options_update', 'mim_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'mim_save_custom_user_profile_fields' );

/**
* When user register then change default user status.
*
* Function Name: add_user_status.
*
* 
*
**/

add_action( 'user_register', 'add_user_status' );
function add_user_status( $user_id ) {
		$mim_curr_sel_new_editor_val=get_option('mim_new_editor_status');	
		if($mim_curr_sel_new_editor_val == 'approved')
		{
			$status = 'approved';
		}else
		{
			 $status = 'pending';
		}
       
        if ( isset( $_REQUEST['action'] ) && 'createuser' == $_REQUEST['action'] ) {
            $status = 'approved';
        }
        update_user_meta( $user_id, 'mim_user_status', $status );
 }
 
 
add_filter( 'manage_users_columns',  'mim_add_column'  );
add_filter( 'manage_users_custom_column',  'mim_status_column', 10, 3 );

/**
* Added custom column in user list.
*
* Function Name: mim_add_column.
*
* 
*
**/

function mim_add_column( $columns ) {
	    
	    $display_columns['display_name'] = __('Display Name', 'mim-issue');
        $the_columns['mim_user_status'] = __( 'Status', 'mim-issue' );
        $the_columns['articles'] = __('No Of Articles', 'mim-issue');
        
        $columns = array_slice( $columns, 0, 3, true ) + $display_columns + array_slice( $columns, 3, NULL, true );
        
        $newcol = array_slice( $columns, 0, -1 );
        $newcol = array_merge( $newcol, $the_columns );
        $columns = array_merge( $newcol, array_slice( $columns, 1 ) );

        return $columns;
}
	
/**
*  Added custom column in user list.
*
* Function Name: mim_status_column.
*
* 
*
**/
function mim_status_column( $val, $column_name, $user_id ) {
	
    switch ( $column_name ) {
        case 'mim_user_status' :
            return get_the_author_meta('mim_user_status', $user_id);
            break;
        case 'articles' :
		    $count= count_user_posts( $user_id , 'magazine' );
		    return '<a class="edit" href="edit.php?post_type=magazine&author='.$user_id.'">'.$count.'</a>';
		    break;
        case 'display_name' :
		    return get_the_author_meta( 'display_name', $user_id );  

        default:
    }

    return $val;
}	

/**
*  Added user restriction by user status.
*
* Function Name: mim_authenticate_user.
*
* 
*
**/

add_filter( 'wp_authenticate_user',	'mim_authenticate_user'  );

function mim_authenticate_user( $userdata ) {
	
        $status = get_the_author_meta('mim_user_status', $userdata->ID);
		
        if ( empty( $status ) ) {
            return $userdata;
        }
		
		$message = false;
        switch ( $status ) {
            case 'pending':
                $pending_message = __( '<strong>ERROR</strong>: Your account is still pending approval.', 'mim-issue' );
                $message = new WP_Error( 'pending_approval', $pending_message );
                break;
            case 'approved':
                $message = $userdata;
                break;
        }

        return $message;
    }

/**
*  Send mail when user registration.
*
* Function Name: mim_request_admin_approval_email.
*
* 
*
**/

add_action( 'register_post', 'mim_request_admin_approval_email' , 10, 3 );

function mim_request_admin_approval_email( $user_login, $user_email, $errors ) {
        if ( $errors->get_error_code() ) {
            return $errors;
        }
		
		$message  = __( 'USERNAME (USEREMAIL) has requested a username at SITENAME', 'new-user-approve' ) . "\n\n";
        $message .= "SITEURL\n\n";
        $message .= __( 'To approve or deny this user access to SITENAME go to', 'new-user-approve' ) . "\n\n";
        $message .= "ADMINURL\n\n";
        $admin_url = admin_url(); 
        $blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
   
        $message = str_replace( 'USERNAME', $user_login, $message );
        $message = str_replace( 'USEREMAIL', $user_email, $message );
        $message = str_replace( 'SITENAME', $blogname, $message );
        $message = str_replace( 'SITEURL', get_option( 'siteurl' ), $message );
        $message = str_replace( 'ADMINURL', $admin_url, $message );

       

        $subject = sprintf( __( '[%s] User Approval', 'new-user-approve' ), $blogname );
        

        $to = get_option( 'admin_email' );
		
		 $admin_email = get_option( 'admin_email' );
        if ( empty( $admin_email ) )
            $admin_email = 'support@' . $_SERVER['SERVER_NAME'];

        $from_name = get_option( 'blogname' );

        $headers = array(
            "From: \"{$from_name}\" <{$admin_email}>\n",
            "Content-Type: text/plain; charset=\"" . get_option( 'blog_charset' ) . "\"\n",
        );
		
        // send the mail
        wp_mail( $to, $subject, $message, $headers );
}	
?>