<?php
/* Admin Class - singleton */

Class SliceShow_Admin {
	
	private static $sliceshow_instance;
	
	private function __construct() {
	
	} //__construct
	
	public static function getInstance() {
		
		if( !self::$sliceshow_instance ) {
			self::$sliceshow_instance = new SliceShow_Admin();
		}
		
		return self::$sliceshow_instance;
	} //getInstance
	
	/**
	 * enqueues media scripts
	 */
	public function admin_scripts() {		
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_script( 'validate', plugins_url( '/thirdparty/jquery.validate.min.js', dirname(__FILE__) ), array('jquery') );
	} //admin_scripts

	/**
	 * enqueues media styles
	 */
	public function admin_styles() {
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_style( 'slice-show-admin-css', plugins_url('/css/slice-show-admin.css', dirname(__FILE__)) );
	} //admin_styles
	
	//only load this on edit and new page for slideshow admin - based from: http://wordpress.stackexchange.com/questions/1058/loading-external-scripts-in-admin-but-only-for-a-specific-post-type (see update #2 of accepted answer)
	public function sliceshow_edit_add_only_scripts() {
		global $pagenow, $typenow;
		if (empty($typenow) && !empty($_GET['post'])) {
			$post = get_post($_GET['post']);
			$typenow = $post->post_type;
		}
		
		if ($typenow == 'sliceshow') {
			if ($pagenow == 'post-new.php' || $pagenow == 'post.php') {
				wp_enqueue_script( 'custom-js', plugins_url( '/js/custom-js.js', dirname(__FILE__) ), array('jquery', 'validate') );
				if( class_exists( 'mf_post' ) ) {
					wp_dequeue_script( 'mf_field_base' );
				}
				
				do_action( 'sliceshow_enqueue_script' );
			}
		}
		if ($pagenow == 'edit.php' || $pagenow == 'post-new.php' || $pagenow == 'post.php') {
			wp_enqueue_script( 'select-shortcode', plugins_url( '/js/select-shortcode.js', dirname(__FILE__) ), array('jquery') );
			
			if( class_exists( 'mf_post' ) ) {
				wp_dequeue_script( 'mf_field_base' );
			}
		} //pagenow == edit.php
	} //sliceshow_edit_add_only_scripts
	
	/**
	 * modifies columns for admin page
	 * @param  array $defaults column names 
	 * @return array           column names 
	 */
	public function sliceshow_columns_head( $defaults ) {
		unset($defaults['date']);
		unset($defaults['title']);
		$defaults['slide_image'] = '';
		$defaults = array_reverse($defaults);
		return $defaults;
	} //sliceshow_columns_head
	
	/**
	 * adds image from slideshow to admin page output
	 * @param  string $column_name name of column
	 * @param  int $post_ID     id of post 
	 */
	public function sliceshow_columns_content( $column_name, $post_ID ) {
		if ($column_name == 'slide_image' ) {
			$img = SliceShow_Helper::sliceshow_get_first_image($post_ID);
			if ($img) {
				echo '<img src="'.$img . '" />';
			}
		}//if slide_image
		
		echo '<div class="slideshow-actions">'.PHP_EOL;
		echo '<h3 class="slideshow-title"><a href="'.get_edit_post_link( $post_ID ).'">'.get_the_title( $post_ID ).'</a></h3>'.PHP_EOL;
		echo '<p class="shortcode-wrap">shortcode: <span class="shortcode">'.SliceShow_Helper::sliceshow_shortcode_shortcut( $post_ID ).'</span></p>'.PHP_EOL;
		echo '<p><a href="'.get_edit_post_link( $post_ID ).'" class="slideshow-edit-btn button">Edit</a>'.PHP_EOL;
		
		echo '</div><!-- /slideshow-actions -->'.PHP_EOL;
	} //sliceshow_columns_content
	
	/**
	 * used to make sure insert into post button shows
	 * @param  array $args 
	 * @return array       
	 */
	public function force_send( $args ) {
		$args['send'] = true;
		return $args;
	}
	
} //SliceShow_Admin