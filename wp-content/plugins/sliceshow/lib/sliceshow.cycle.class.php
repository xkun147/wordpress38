<?php
/* Cycle Output class - this class currently isn't used */

Class SliceShow_Cycle {
	/**
	 * the slideshow's id
	 * @var integer
	 */
	private $show_id;
	
	public function __construct( $show_id ) {
		$this->set_show_id( $show_id );
	} //__construct
	
	/**
	 * setter for show_id
	 * @param integer $show_id 
	 */
	private function set_show_id( $show_id = 0 ) {
		$show_id = (int)$show_id;
		
		if( $show_id != 0 ) {
			$this->show_id = $show_id;
		}
	} //set_show_id
	
	/**
	 * getter for show_id
	 * @return integer 
	 */
	private function get_show_id() {
		return $this->show_id;
	} //get_show_id
	
	//load document.ready code and cycle init for each slideshow
	/**
	 * creates the jquery code to run the slideshow
	 * @return string the javascript code that runs the slider
	 */
	public function sliceshow_load_cycle_js() {
		$cycle_out = '';
		
		if( $this->get_show_id() ) {
			$ids = array( $this->get_show_id() );
		} else {
			//get ids of slideshows
			$ids = SliceShow_Helper::sliceshow_get_published_slideshows();
		}
		
		if( $ids ) {
			$cycle_out .= '<script type="text/javascript">'.PHP_EOL;
			$cycle_out .= '/* <![CDATA[ */'.PHP_EOL;
			$cycle_out .= 'jQuery(window).load(function(){'.PHP_EOL;
			//loop through ids, get settings, and create cycle init for slideshow
			foreach( $ids as $id ) {
				$speed = get_post_meta( $id, 'slide-settings-speed', true );
				$speed = ($speed == 0) ? 300 : $speed;
				$fx = get_post_meta( $id, 'slide-settings-transition', true );
				$arrows = get_post_meta( $id, 'slide-settings-show-arrows', true );
				$nav = get_post_meta( $id, 'slide-settings-show-nav', true );
				$hover_pause = get_post_meta( $id, 'slide-settings-hover-pause', true );
				
				$cycle_out .= 'jQuery("#'.SliceShow_Helper::sliceshow_get_post_name( $id ).'").cycle({'.PHP_EOL;
				if( $arrows == 'yes' ) {
					$cycle_out .= 'next: \'#'.SliceShow_Helper::sliceshow_get_post_name( $id ).'-next\','.PHP_EOL;
					$cycle_out .= 'prev: \'#'.SliceShow_Helper::sliceshow_get_post_name( $id ).'-prev\','.PHP_EOL;
				}//arrows = yes
				
				if( $nav == 'yes' ) {
					$cycle_out .= 'pager: \'#'.SliceShow_Helper::sliceshow_get_post_name( $id ).'-nav\','.PHP_EOL;
				}//nav = yes
				
				if( $hover_pause == 'yes' ) {
					$cycle_out .= 'pause: true,'.PHP_EOL;
				}//arrows = yes
				
				$cycle_out .= 'speed: '.$speed.','.PHP_EOL;
				$cycle_out .= 'fx: \''.$fx.'\''.PHP_EOL;
				
				$cycle_out .= '});'.PHP_EOL;
			}//foreach ids as id
			
			$cycle_out .= '});'.PHP_EOL;
			$cycle_out .= '/* ]]> */'.PHP_EOL;
			$cycle_out .= '</script>'.PHP_EOL.PHP_EOL;
		}//if ids
		
		print $cycle_out;
	}//sliceshow_load_cycle_js
	
} //SliceShow_Cycle