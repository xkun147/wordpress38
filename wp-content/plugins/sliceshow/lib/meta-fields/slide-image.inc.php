<div class="slide-image-wrap"><?php $image = plugins_url('/sliceshow/images/placeholder-image.png', SliceShow_PLUGIN_URI );?>

<span class="custom_default_image" style="display:none"><?php echo $image; ?></span>
<?php
if ($slide) { $image = wp_get_attachment_image_src($slide, 'thumbnail');	$image = $image[0]; }				
?>
<input name="slide[<?php echo $gk;?>][<?php echo $sk;?>]" id="<?php echo $sk;?>" type="hidden" class="custom_upload_image <?php echo $field['class']; ?><?php echo $req; ?>" value="<?php echo $slide; ?>" />

			<img src="<?php echo $image; ?>" class="custom_preview_image" alt="" /><br />

				<input class="custom_upload_image_button button" type="button" value="Choose Image" />

				<small>&nbsp;<a href="#" class="custom_clear_image_button">Remove Image</a></small>

				<br clear="all" />
</div><!-- /slide-image-wrap -->