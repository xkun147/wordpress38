<?php
/* cycle2 Output class */

Class SliceShow_Cycle2 {
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
	
	//load document.ready code and cycle2 init for each slideshow
	/**
	 * creates the jquery code to run the slideshow
	 * @return string the javascript code that runs the slider
	 */
	public function sliceshow_load_cycle2_js() {
		$cycle2_out = '';
		
		if( $this->get_show_id() ) {
			$ids = array( $this->get_show_id() );
		} else {
			//get ids of slideshows
			$ids = SliceShow_Helper::sliceshow_get_published_slideshows();
		}
		
		if( $ids ) {
			$cycle2_out .= '<script type="text/javascript">'.PHP_EOL;
			$cycle2_out .= '/* <![CDATA[ */'.PHP_EOL;
			$cycle2_out .= 'jQuery(window).load(function(){'.PHP_EOL;
			//loop through ids, get settings, and create cycle2 init for slideshow
			foreach( $ids as $id ) {
				$slideshow_speed = (int) get_post_meta( $id, 'cycle2-settings-slideshow-speed', true );
				$slideshow_speed = ($slideshow_speed == 0) ? 7000 : $slideshow_speed;
				$animation_speed = (int ) get_post_meta( $id, 'cycle2-settings-animation-speed', true );
				$animation_speed = ($animation_speed == 0) ? 600 : $animation_speed;
				
				$direction = get_post_meta( $id, 'cycle2-settings-direction', true );
				$direction = 'horizontal'; //override until we figure out vertical issue;
				$animation = get_post_meta( $id, 'cycle2-settings-animation', true );
				$arrows = get_post_meta( $id, 'cycle2-settings-show-arrows', true );
				$nav = get_post_meta( $id, 'cycle2-settings-show-nav', true );
				
				$cycle2_out .= 'jQuery("#'.SliceShow_Helper::sliceshow_get_post_name( $id ).'").cycle2({'.PHP_EOL;
				if( $nav == 'yes' ) {
					$cycle2_out .= 'controlNav: true';
				} else {
					$cycle2_out .= 'controlNav: false';
				}//arrows = yes
				
				if( $arrows == 'yes' ) {
					$cycle2_out .= ','.PHP_EOL.'directionNav: true';
				} else {
					$cycle2_out .= ','.PHP_EOL.'directionNav: false';
				}//nav = yes
				
				$cycle2_out .= ','.PHP_EOL.'slideshowSpeed: '.$slideshow_speed;
				$cycle2_out .= ','.PHP_EOL.'animationSpeed: '.$animation_speed;
				
				$cycle2_out .= ','.PHP_EOL.'animation: "'.$animation.'"';
				$cycle2_out .= ','.PHP_EOL.'pauseOnHover: true'; // pause on hover
				$cycle2_out .= ','.PHP_EOL.'direction: "'.$direction.'"'.PHP_EOL;
				$cycle2_out .= '});'.PHP_EOL;
			}//foreach ids as id
			
			$cycle2_out .= '});'.PHP_EOL;
			$cycle2_out .= '/* ]]> */'.PHP_EOL;
			$cycle2_out .= '</script>'.PHP_EOL.PHP_EOL;
		}//if ids
		//print $cycle2_out;
	}//sliceshow_load_cycle2_js
	
} //SliceShow_cycle2