jQuery(document).ready(function() {
		 
		 // Issue Image Prepare the variable that holds our custom media manager.
		 var loc_file;
		 var locationlabel = 0;
		 // Bind to our click event in order to open up the new media experience.
		 jQuery(document.body).on('click.mojoOpenMediaManager', '.mim_image_issue', function(e){ //mojo-open-media is the class of our form button
			 // Prevent the default action from occuring.
			 e.preventDefault();
			// Get our Parent element
			 locationlabel = jQuery(this).parent();
			 
			 // If the frame already exists, re-open it.
			 if ( loc_file ) {
			 loc_file.open();
			 return;
			 }
			 
			 loc_file = wp.media.frames.loc_file = wp.media({
						 title: "Add Issue Cover Image",
					     button: {
						  text: "Insert Issue Cover Image",
					     },
						 editing:    true,
						 className: 'media-frame loc_file',
						 frame: 'select', //Allow Select Only
						 multiple: false, //Disallow Mulitple selections
						 
			});
			
			 loc_file.on('select', function(){
			 // Grab our attachment selection and construct a JSON representation of the model.
			 var loc_media_attachment = loc_file.state().get('selection').first().toJSON();
			 if(loc_media_attachment.subtype == "pdf"){
					alert("Please Insert only image");
					return;
			 }
			 var thum_url=loc_media_attachment.sizes.thumbnail.url;
			 var thumb_id=loc_media_attachment.id; 
			// Send the attachment URL to our custom input field via jQuery.
			 loc_url=loc_media_attachment.url;
			 locurls=loc_url.substr( (loc_url.lastIndexOf('.') +1) );
			 if(locurls !='pdf' && locurls !='zip' && locurls !='rar')
			 {
					  jQuery('#mim_cover_img_show').css('display','block');
					  jQuery('#remove_image').css('display','inline-block');
					  jQuery('#mim_upload_image_issue').val(thumb_id);
					  jQuery('#mim_display_cover_image_issue').attr('src',thum_url );


			 }else{
				     alert('Please add only image');
		     }
			 
			 });
	 
			// Now that everything has been set, let's open up the frame.
			 loc_file.open();
		 });
		 
	jQuery('#remove_image').click(function() {
		jQuery("#mim_display_cover_image_issue").attr('src','');
		jQuery('#mim_upload_image_issue').attr('value','');	
		jQuery('#mim_cover_img_show').css('display','none');
		jQuery('#remove_image').css('display','none');
	});
	
	 jQuery(document).ajaxSuccess(function(event, xhr, settings) {
	 	
        if ( settings.data.indexOf("action=add-tag") !== -1 ) {
	        jQuery('#mim_cover_img_show').css('display','none');
			jQuery('#mim_upload_image_issue').attr('value','');
			jQuery('#remove_image').css('display','none');
			jQuery('input:checkbox').removeAttr('checked');
        }
    });	
    
    jQuery( "#sortable" ).sortable();
	jQuery( "#sortable" ).disableSelection();

	 // Issue Prepare the variable that holds our custom media manager.
	 var issue_file;
	 var coupleslabel = 0;
	 
	 // Bind to our click event in order to open up the new media experience.
	 jQuery(document.body).on('click.mojoOpenMediaManager', '.issuepdfform', function(e){ //mojo-open-media is the class of our form button
	 // Prevent the default action from occuring.
	 e.preventDefault();
	// Get our Parent element
	coupleslabel = jQuery(this).parent();
	 // If the frame already exists, re-open it.
	 if ( issue_file ) {
	 issue_file.open();
	 return;
	 }
	 issue_file = wp.media.frames.issue_file = wp.media({
	 
	//Create our media frame
	 title: "Issue PDF",
     button: {
	  text: "Insert Issue PDF",
     },
	 className: 'media-frame issue_file',
	 frame: 'select', //Allow Select Only
	 multiple: false, //Disallow Mulitple selections
	 library: {
	 type: '' //Only allow images type: 'image'
	 },
	 });
	 issue_file.on('select', function(){
	 // Grab our attachment selection and construct a JSON representation of the model.
	 var couples_media_attachment = issue_file.state().get('selection').first().toJSON();
	 
	// Send the attachment URL to our custom input field via jQuery.
	 cup_url=couples_media_attachment.url;
	 cupurls=cup_url.substr( (cup_url.lastIndexOf('.') +1) );
	 if(cupurls !='jpg' && cupurls !='gif' && cupurls !='png' && cupurls !='zip' && cupurls !='rar')
	 {
			coupleslabel.find('input[type="text"]').val(couples_media_attachment.url);
			jQuery('.issueremovepdfform').show();

	 }else{
		     alert('Please add only pdf files');
     }
	 
	 });
	 
	// Now that everything has been set, let's open up the frame.
	 issue_file.open();
	 });
	 jQuery('.issueremovepdfform').click(function() {
	 	jQuery('#mim_upload_file_pdf').val('');
		jQuery('.issueremovepdfform').hide();
	});
	 	
 });
