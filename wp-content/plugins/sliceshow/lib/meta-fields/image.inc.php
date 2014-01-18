<th><label for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label></th>
<td><?php $image = get_template_directory_uri().'/images/placeholder-image.png';?>

<span class="custom_default_image" style="display:none"><?php echo $image; ?></span>
<?php
if ($meta) { $image = wp_get_attachment_image_src($meta, 'medium');	$image = $image[0]; }				
?>
<input name="<?php echo $field['id']; ?>" type="hidden" class="custom_upload_image <?php echo $field['class']; ?><?php echo $req; ?>" value="<?php echo $meta; ?>" />

			<img src="<?php echo $image; ?>" class="custom_preview_image" alt="" /><br />

				<input class="custom_upload_image_button button" type="button" value="Choose Image" />

				<small>&nbsp;<a href="#" class="custom_clear_image_button">Remove Image</a></small>

				<br clear="all" /><span class="description"><?php echo $field['desc']; ?></span></td>