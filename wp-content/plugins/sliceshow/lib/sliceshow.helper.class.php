<?php
/* Helper Class */
Class SliceShow_Helper {
	
	
	public function __construct() {
	
	} //construct
	
	/**
	 * query to get all active slideshows
	 * @return array ids for all active slideshows
	 */
	public function sliceshow_get_published_slideshows() {
		$args = array(
			'post_type' => 'sliceshow',
			'fields' => 'ids',
			'posts_per_page' => -1
		);
		
		$ids = new WP_Query( $args );
		return $ids->posts;
	} //sliceshow_get_published_slideshows
	
	/**
	 * derive name of slideshow from id
	 * @param  integer $id slideshow id
	 * @return string      slideshow name
	 */
	public function sliceshow_get_post_name( $id = 0 ) {
		global $wpdb;
		//make sure it is an integer
		$id = (int) $id;
		$sql = 'SELECT post_name FROM '.$wpdb->posts.' WHERE id = %d';
		$post_name = $wpdb->get_row( $wpdb->prepare( $sql, $id ) );
		return $post_name->post_name;
	} //sliceshow_get_post_name
	
	/**
	 * fetch slide info from post meta table
	 * @param  integer $post_ID 
	 * @return array          this has all slides in it
	 */
	public function sliceshow_get_slides( $post_ID ) {
		return get_post_meta( $post_ID, 'slides', true );
	} //sliceshow_get_slides
	
	/**
	 * gets the first image saved in the slideshow
	 * @param  integer $post_ID 
	 * @return string          url for image
	 */
	public function sliceshow_get_first_image( $post_ID ) {
		$slides = SliceShow_Helper::sliceshow_get_slides( $post_ID );
		if( $slides ) {
			$first_slide = array_shift( $slides );
			$img = wp_get_attachment_image_src( $first_slide['slideimage_0'], 'thumbnail' );
			return $img[0];
		}//if slides
	} //sliceshow_get_first_image
	
	/**
	 * generates the shortcode text for display purposes
	 * @param  integer $id slideshow id
	 * @return string     
	 */
	public function sliceshow_shortcode_shortcut( $id ) {
		return '[sliceshow id="'.$id.'"]';
	}
	
	/**
	 * create a hash using the slideshow post name
	 * @param  integer $id slideshow id
	 * @return string     hash
	 */
	public function generateHash( $id ) {
		return SliceShow_Helper::sliceshow_get_post_name( $id ).'_'.mt_rand(10, 300);
	} //generateHash
	
} //SliceShow_Helper
