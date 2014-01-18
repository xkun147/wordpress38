/* from: http://wp.tutsplus.com/tutorials/reusable-custom-meta-boxes-part-3-extra-fields/
Modified by Randy Dustin
*/

//enable validation of hidden fields
jQuery.validator.setDefaults({ ignore: [] });

jQuery(function(jQuery) {
	
	
	jQuery('#media-items').bind('DOMNodeInserted',function(){
		jQuery('input[value="Insert into Post"]').each(function(){
				jQuery(this).attr('value','Use This Image');
		});
	});
	
	jQuery('.custom_upload_image_button').click(function() {
		formfield = jQuery(this).siblings('.custom_upload_image');
		preview = jQuery(this).siblings('.custom_preview_image');
		post_id = jQuery('input#post_ID').val();
		tb_show('', 'media-upload.php?post_id='+post_id+'&TB_iframe=true');
		window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
			classes = jQuery('img', html).attr('class');
			id = classes.replace(/(.*?)wp-image-/, '');
			formfield.val(id);
			preview.attr('src', imgurl);
			tb_remove();
		}
		return false;
	});
	
	jQuery('.custom_clear_image_button').click(function() {
		var defaultImage = jQuery(this).parent().siblings('.custom_default_image').text();
		jQuery(this).parent().siblings('.custom_upload_image').val('');
		jQuery(this).parent().siblings('.custom_preview_image').attr('src', defaultImage);
		return false;
	});
	
	jQuery('body').append(jQuery('#sliceshow-box_seed'));
	jQuery('.repeatable-add').click(function() {
		box = jQuery('#sliceshow-box_seed').clone(true);
		boxes = getMaxBoxID() + 1;
		
		box.attr('id', function(index, bid) {
			return bid.replace(/seed/, function(fullMatch, n) {
				return boxes;
			});
		});
		
		jQuery('input', box).each(function(i){
			if(jQuery(this).attr('type') != 'button') {
				jQuery(this).attr('name', function(index, name) {
					return name.replace(/seed/, function (fullMatch, n) {  
						return boxes; 
					});
				});
				jQuery(this).attr('id', function(index, id) {
					return id.replace(/seed/, function(fullMatch, n) {
						return boxes;
					});
				});
				if(jQuery(this).attr('type') == 'hidden') {
					jQuery(this).siblings('img').attr('src',jQuery(this).siblings('.custom_default_image').text());
				}//type = hidden
				if(jQuery(this).hasClass('slideorder')){
					var v = jQuery(this).val();
					v = Number(v)+1;
					jQuery(this).val(v);
				} else {
					jQuery(this).val('');
				}//hasclass slideorder
				
				var tr = jQuery(this).parents('tr');
				jQuery('th label',tr).attr('for', function (index, f) {
					return f.replace(/seed/, function(fullMatch, n) {
						return boxes;
					});
				});
			}//attr(type)
		});//input, box
		jQuery('.repeatable-remove', box).removeClass('initial-slide');
		if(boxes > 1) {
			box.insertAfter(jQuery('#slide-wrapper .sliceshow-group').last());
		} else {
			jQuery('#slide-wrapper').append(box);
		}
		return false;
	});
	
	jQuery('.repeatable-remove').click(function(){
		jQuery(this).parent().remove();
		return false;
	});
		
	jQuery('#slide-wrapper').sortable({
		opacity: 0.6,
		revert: true,
		cursor: 'move',
		handle: '.sort',
		stop: function (event, ui) {
			jQuery('.sliceshow-group').each(function(i) {
				jQuery('input.slideorder',this).val(i);
			});
		}
	});
	
	//required meta fields
	jQuery('#post').validate({
		invalidHandler: function() {
			jQuery('#publish').removeClass('button-primary-disabled');
			jQuery('#ajax-loading').css('visibility', 'hidden');
		}
	});
	
	jQuery('input.cycle2-settings-slide-size').change(function(e){
		slideSizeShowHide();
	});
	
	slideSizeShowHide();
});

function slideSizeShowHide() {
	//show-hide slideshow width/height based on fixed or flexible
	var ck = jQuery('input[type=radio].cycle2-settings-slide-size:checked').attr('id')
	var ind = ck.lastIndexOf("-");
	var type = ck.substring(ind + 1);
	if(type == 'fixed') {
		jQuery('tr.cycle2-settings-image-width').show();
		jQuery('tr.cycle2-settings-image-height').show();
		
		jQuery('tr.cycle2-settings-max-width').hide();
		jQuery('tr.cycle2-settings-max-height').hide();
	} else {
		jQuery('tr.cycle2-settings-image-width').hide();
		jQuery('tr.cycle2-settings-image-height').hide();
		
		jQuery('tr.cycle2-settings-max-width').show();
		jQuery('tr.cycle2-settings-max-height').show();
	}
}

function getMaxBoxID() {
	maxID = 0;
	jQuery('#slide-wrapper .sliceshow-group').each(function(i){
		id = jQuery(this).attr('id');
		l = id.indexOf("_");
		id = parseInt(id.substring((l+1)));
		
		if( isNaN(id) ) {
			id = 0;
		}
		if( id > maxID ) {
			maxID = id;
		}
	});
	
	return maxID;
}