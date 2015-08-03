jQuery(document).ready(function($){
	
     // Issue Image Prepare the variable that holds our custom media manager.
		 var loc_file;
		 var locationlabel = 0;
		 // Bind to our click event in order to open up the new media experience.
		 jQuery(document.body).on('click.mojoOpenMediaManager', '.mim_image_magazine', function(e){ //mojo-open-media is the class of our form button
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
					 title: "Add Magazine Cover Image",
				     button: {
					  text: "Insert Magazine Cover Image",
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
				  jQuery('#mim_magazine_cover_img_show').css('display','block');
				  jQuery('#remove_magazine_image').css('display','inline-block');
				  jQuery('#mim_upload_image_magazine').val(thumb_id);
				  jQuery('#mim_display_cover_image_magazine').attr('src',thum_url );


		 }else{
			     alert('Please add only image');
	     }
		 
		 });
 
		// Now that everything has been set, let's open up the frame.
		 loc_file.open();
	 });
	 
	 jQuery('#remove_magazine_image').click(function() {
		jQuery("#mim_display_cover_image_magazine").attr('src','');
		jQuery('#mim_upload_image_magazine').attr('value','');	
		jQuery('#mim_magazine_cover_img_show').css('display','none');
		jQuery('#remove_magazine_image').css('display','none');
	});

   jQuery(document).ajaxSuccess(function(event, xhr, settings) {
 	
      if ( settings.data.indexOf("action=add-tag") !== -1 ) {
        jQuery('#mim_magazine_cover_img_show').css('display','none');
		jQuery('#mim_upload_image_magazine').attr('value','');
		jQuery('#remove_magazine_image').css('display','none');
		jQuery('input:checkbox').removeAttr('checked');
     }
  });
});  	