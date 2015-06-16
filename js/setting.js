jQuery(document).ready(function(){
	jQuery('#mim_cover_width').on('keyup', function(){							
		    var value = jQuery('#mim_cover_width').val();
		    var regex = new RegExp(/^\+?[0-9(),.-]+$/);
		    if(value.match(regex)) {return true;}else{
				jQuery('#mim_cover_width').val('');
			}
		    return false;
	});
	jQuery('#mim_cover_height').on('keyup', function(){							
		    var value = jQuery('#mim_cover_height').val();
		    var regex = new RegExp(/^\+?[0-9(),.-]+$/);
		    if(value.match(regex)) {return true;}else{
				jQuery('#mim_cover_height').val('');
			}
		    return false;
	});
			
});