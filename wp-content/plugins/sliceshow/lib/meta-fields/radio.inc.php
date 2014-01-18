
<th><label for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label></th>
<td>
<?php foreach ($field['options'] as $option) :?>
		<?php $sel = ($meta == $option['value']) ? 'checked="checked"' : '';?>
		<input type="radio"  name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>-<?php echo $option['value'];?>" class="<?php echo $field['class']; ?>" <?php echo $sel;?> value="<?php echo $option['value']; ?>"/> <label for="<?php echo $field['id']; ?>-<?php echo $option['value'];?>"><?php echo $option['label']; ?></label>

	<?php endforeach; //field['options']?>
<br /><span class="description"><?php echo $field['desc']; ?></span></td>