jQuery(document).ready(function(){
	
	
		jQuery("#idviptype").change(
			function(){

        var cid=jQuery("#idviptype").val();
        jQuery.ajax({
        	url:pgvajax.ajaxurl,
        	type:'post',
        	data:{
            	action:'payline_get_price',
            	cid:cid,
        	},
        	success:function(resp){
				alert("DSGD");
				switch(jQuery("#idviptype").val()) {
    				case "30":
						jQuery(".payline-price").val(resp);
						jQuery(".payline_des").val("عضویت ویژه یک ماهه");
        				break;
    				case "90":
						jQuery(".payline-price").val(resp);
						jQuery(".payline_des").val("عضویت ویژه سه ماهه");
						break;
    				case "180":
						jQuery(".payline-price").val(resp);
						jQuery(".payline_des").val("عضویت ویژه شش ماهه");
						break;					
    				case "360":
						jQuery(".payline-price").val(resp);
						jQuery(".payline_des").val("عضویت ویژه دوازده ماهه");
						break;					
    				default:		
				}
        	}
    	});
   				
				
				
				

						});
							function GetVipType(type){
	   	switch(type) {
    	case "30":		
        	return "عضویت ویژه یک ماهه";
        	break;
    	case "90":
        	return "عضویت ویژه سه ماهه";
			break;
    	case "180":
        	return "عضویت ویژه شش ماهه";
			break;					
    	case "360":
        	return "عضویت ویژه دوازده ماهه";
			break;					
    	default:		
	}
	
}
	
	
	
	////////////
	jQuery("#id_bTypeOfAds").change(
		function(){
			var AdsType=jQuery( "#id_bTypeOfAds option:selected" ).val();
			switch(AdsType) {
    			case "Image":
        			jQuery("#id_lbImageAddress,#id_bImageAddress,#upload_image_button,#upload_image_text").addClass("show").removeClass("hide");
        			jQuery("#id_lbTitle,#id_bTitle,#id_lbMessage,#id_bMessage").addClass("hide").removeClass("show");
        			break;
    			case "Text":
        			jQuery("#id_lbImageAddress,#id_bImageAddress,#upload_image_button,#upload_image_text").addClass("hide").removeClass("show");
					jQuery("#id_lbTitle,#id_bTitle,#id_lbMessage,#id_bMessage").addClass("show").removeClass("hide");

					break;
    			default:		
			}
		})
///////////////////////////////////////////
    jQuery('.delete_ads').on('click',function(){
        if(!confirm("برای حذف این تبلیغ اطمینان دارید؟")){return false;}
        var loader=jQuery('#pga_loader');
        var el=jQuery(this);
        var cid=el.data('id');
        loader.fadeIn(300);
        jQuery.ajax({
        	url:pgaajax.ajaxurl,
        	type:'post',
        	data:{
            	action:'pga_delete_ads',
            	cid:cid,
        	},
        	success:function(resp){
           		loader.fadeOut(500);
           		alert(resp);
        	}
    	});
        return false;
    });
///////////////////////////////
    jQuery('.archive_ads').on('click',function(){
        if(!confirm("برای ارسال به بایگانی اطمینان دارید؟")){return false;}
        var loader=jQuery('#pga_loader');
        var el=jQuery(this);
        var cid=el.data('id');
        loader.fadeIn(300);
        jQuery.ajax({
        	url:pgaajax.ajaxurl,
        	type:'post',
        	data:{
            	action:'pga_archive_ads',
            	cid:cid,
        	},
        	success:function(resp){
           		loader.fadeOut(500);
           		alert(resp);
        	}
    	});
        return false;
    });
///////////////////////////////	
    jQuery('#select_all').on('change',function(){
    jQuery('input[type="checkbox"]').prop('checked',this.checked);
	});
//////////////////////////////////////
 jQuery('.atst').on('click',function(){
        var el=jQuery(this);
		window.open(el.attr("data-url"));
        jQuery.ajax({
        	url:pgaajax.ajaxurl,
        	type:'post',
        	data:{
            	action:'pga_UpdateACredit_ads',
				TContract:el.attr("data-typeofco"),
				AID:el.attr("data-id"),
        	},
        	
    	});
        return false;
    });
////////////////////////////////////////////
// Uploading files
	var file_frame;
	jQuery('#upload_image_button').live('click', function( event ){
		event.preventDefault();
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false // Set to true to allow multiple files to be selected
		}); 
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			attachment = file_frame.state().get('selection').first().toJSON();
			jQuery('#id_bImageAddress').val(attachment.url);
				// Do something with attachment.id and/or attachment.url here
		});
		// Finally, open the modal
		file_frame.open();
	});
///////////////////////////////////////////////////
				jQuery('#id_bCountOfClick').prop('disabled', true).removeClass("pga_enable");
				jQuery('#id_bCountOfDays').prop('disabled', true).removeClass("pga_enable");
				jQuery('#id_bCountOfShow').prop('disabled', false).addClass("pga_enable");
	jQuery("#id_mbCountOfShow").change(
		function(){
			if (document.getElementById('id_mbCountOfShow').checked) {
				jQuery('#id_bCountOfClick').prop('disabled', true).removeClass("pga_enable");
				jQuery('#id_bCountOfDays').prop('disabled', true).removeClass("pga_enable");
				jQuery('#id_bCountOfShow').prop('disabled', false).addClass("pga_enable");
			}
		})
////////
	jQuery("#id_mbCountOfClick").change(
		function(){
			if (document.getElementById('id_mbCountOfClick').checked) {
				jQuery('#id_bCountOfClick').prop('disabled', false).addClass("pga_enable");
				jQuery('#id_bCountOfDays').prop('disabled', true).removeClass("pga_enable");
				jQuery('#id_bCountOfShow').prop('disabled', true).removeClass("pga_enable");
			}
		})
/////////
	jQuery("#id_mbCountOfDays").change(
		function(){
			if (document.getElementById('id_mbCountOfDays').checked) {
				jQuery('#id_bCountOfClick').prop('disabled', true).removeClass("pga_enable");
				jQuery('#id_bCountOfDays').prop('disabled', false).addClass("pga_enable");
				jQuery('#id_bCountOfShow').prop('disabled', true).removeClass("pga_enable");
			}
		})
////////////////////////////////////////

///////////////////////////////////////////////////
});