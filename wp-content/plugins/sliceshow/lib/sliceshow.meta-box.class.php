<?php
/* Meta Box Class  - creates the meta fields for the slideshows  & settings */

Class SliceShow_Metabox {
	
	/**
	 * array of arguments for the meta box creation
	 * @var array
	 */
	private $box_args = array();
	
	/**
	 * the fields to be created
	 * @var array
	 */
	private $fields = array();
	
	/**
	 * value added to the beginning of a field name/id
	 * @var string
	 */
	private $prefix = '';
	
	/**
	 * value added to the end of a field name/id
	 * @var string
	 */
	private $suffix = '';
	
	public function __construct( $box_args, $fields ) {
		$this->setBoxValues( $box_args );
		$this->setFields( $fields );
		
		add_action( 'add_meta_boxes', array(&$this, 'addBox') );
		add_action( 'save_post', array(&$this, 'save_settings_meta') );
		add_action( 'save_post', array(&$this, 'save_slides_meta') );
	} //__construct
	
	/**
	 * setter for box_args
	 * @param array $box_args 
	 */
	public function setBoxValues( $box_args ) {
		$this->box_args = (array)$box_args;
	} //setBoxValues
	
	/**
	 * setter for fields
	 * @param array $fields 
	 */
	public function setFields( $fields ) {
		$this->fields = (array) $fields;
	} //setFields
	
	/**
	 * create the meta box
	 */
	public function addBox() {
		add_meta_box(
		$this->box_args['id'], // $id
		$this->box_args['title'], // $title
		array( &$this, 'addFields' ), // $callback
		$this->box_args['page'], // $page
		$this->box_args['context'], // $context
		$this->box_args['priority']); // $priority
	} //addBox
	
	/**
	 * checks id in box_args and calls function for either the slides or settings meta box
	 */
	public function addFields() {
		switch( $this->box_args['id'] ) {
			case 'slide_meta_box':
				$this->addSlides();
			break;
			case 'slide_settings_meta_box':
				$this->addSettings();
			break;
			default:
		} //switch
	} //addFields
	
	/**
	 * creates the settings box output
	 */
	private function addSettings() {
		global $post;
		if( !empty( $this->fields ) ) {
						
			//for backwards compatibility with (v1) we need to check for empty cycle2 setting and non-empty flexslider setting
			//if true we only have flexslider settings in the post meta table so we need to get those in our foreach below
			$use_flexslider_settings = (get_post_meta( $post->ID, 'flexslider-settings-slide-size', true ) && !get_post_meta( $post->ID, 'cycle2-settings-slide-size', true ));

			echo  '<input type="hidden" name="'.$this->box_args['id'].'_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
			
			echo  '<table class="form-table">'.PHP_EOL;

			echo '<tr class="shortcode-wrap"><th>Shortcode</th><td><span class="shortcode">'.SliceShow_Helper::sliceshow_shortcode_shortcut( $post->ID ).'</span></td></tr><tr><td colspan="2"><span class="sliceshow-meta-note">Copy and paste the shortcode into your page or post to embed this slideshow.</span></td></tr>'.PHP_EOL;
			
			foreach( $this->fields as $field ) {
				$meta_path = 'meta-fields/';
				if( isset($field['tpl-path'] ) ){
					$meta_path = $field['tpl-path'];
				}
				if($use_flexslider_settings) {
					$field_id = str_replace("cycle2", "flexslider", $field['id']);
				} else {
					$field_id = $field['id'];
				}
				$meta = get_post_meta( $post->ID, $field_id, true );

				$meta = $meta ? $meta : $field['default']; 
		
				$req = isset($field['required']) ? ' required '.substr($sk, 0, strpos($sk, '_')).'"' : '';
				
				echo  '<tr class="'.$field['id'].'">'.PHP_EOL;
				
				include $meta_path.$field['type'].'.inc.php';
				
				echo  '</tr>'.PHP_EOL;
				
				
			} //foreach fields as field
			echo '</table>'.PHP_EOL; // end table
		} //!empty fields
	} //addSettings
	
	/**
	 * creates the slides box output
	 */
	private function addSlides() {
		global $post;
		
		$prefix = 'slide';
		$suffix = '_0';
		$group = array();
		
		$seed_box = array(
			'sliceshow-box_seed' =>
				array(
					$prefix.'image'.$suffix => '',
					$prefix.'title'.$suffix => '', 
					$prefix.'url'.$suffix => '',
					$prefix.'order'.$suffix => 0
				)
		);
		
		if( !empty( $this->fields ) ) {		
			$saved_data_fields = get_post_meta($post->ID, 'slides', true);
			if($saved_data_fields)
				$group = $saved_data_fields;
				
			echo '<div id="slides">';
			echo '<div id="slide-wrapper">';
			$group = array_merge($group, $seed_box);

			foreach ($group as $gk=>$g) {
			echo '<div id="'.$gk.'" class="sliceshow-group"><span class="sort hndle">+++</span>';
		
			// Begin loop
			foreach ($g as $sk=>$slide) {
				$field = $this->fields[substr($sk, 0, strpos($sk, '_'))];
				$meta_path = 'meta-fields/';
				if( isset($field['tpl-path']) ){
					$meta_path = $field['tpl-path'];
				}
				$req = isset($field['required']) ? ' required '.substr($sk, 0, strpos($sk, '_')).'' : '';
					
					include $meta_path.$field['type'].'.inc.php';
					
					
				} //foreach fields as field
				echo '<a class="repeatable-remove" href="#">Delete Slide</a>';
				echo '</div>';//end .sliceshow-group
			}//end group as g
			echo '</div>';//end slide-wrapper
			echo '<div class="repeatable-add-wrap"><a class="repeatable-add" href="#">Add New Slide</a></div>';//add repeatable button
			echo '</div>';//end slides
		} //!empty fields
	} //addSlides
	
	/**
	 * saves settings
	 * @param  integer $post_id 
	 */
	public function save_settings_meta($post_id) {
		global $settings_meta_fields;
		
		$_POST += array("{$this->box_args['id']}_nonce" => '');
		// verify nonce
	
		if (!wp_verify_nonce($_POST[$this->box_args['id'].'_nonce'], basename(__FILE__))) 
	
			return $post_id;
	
		// check autosave
	
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
	
			return $post_id;
	
		// check permissions
	
		if ('page' == $_POST['post_type']) {
	
			if (!current_user_can('edit_page', $post_id))
	
				return $post_id;
	
			} elseif (!current_user_can('edit_post', $post_id)) {
	
				return $post_id;
	
		}
	
		// loop through fields and save the data
		//$slide_dimens = array();
		foreach ($this->fields as $field) {
	
			if($field['type'] == 'tax_select') continue;
	
			$old = get_post_meta($post_id, $field['id'], true);
	
			$new = $_POST[$field['id']];
		
			if ($new && $new != $old) {
	
				update_post_meta($post_id, $field['id'], $new);
	
			} elseif ('' == $new && $old) {
	
				delete_post_meta($post_id, $field['id'], $old);
	
			}
	
		} // end foreach
	
	} //save_settings_meta
	
	/**
	 * saves slide values into meta fields
	 * @param  integer $post_id 
	 */
	public function save_slides_meta( $post_id ) {
		// verify nonce
		if (!wp_verify_nonce($_POST[$this->box_args['id'].'_nonce'], basename(__FILE__)))
			return $post_id;
		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;
		// check permissions
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id))
				return $post_id;
			} elseif (!current_user_can('edit_post', $post_id)) {
				return $post_id;
		}
		
		
		update_post_meta($post_id, 'slides', $_POST['slide']); //save slides in serialized format
	} //save_slides_meta
	
} //SliceShow_Metabox