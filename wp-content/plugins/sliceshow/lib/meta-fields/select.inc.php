
<th><label for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label></th>
<td><select name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="<?php echo $field['class']; ?><?php echo $req; ?>">
<?php foreach ($field['options'] as $option) :?>
		<?php $sel = ($meta == $option['value']) ? 'selected="selected"' : '';?>
		<option  <?php echo $sel;?> value="<?php echo $option['value']; ?>"><?php echo $option['label']; ?></option>

	<?php endforeach; //field['options']?>
	</select><br /><span class="description"><?php echo $field['desc']; ?></span>
<br /><span class="description"><?php echo $field['desc']; ?></span></td>