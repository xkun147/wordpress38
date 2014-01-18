<?php
/* flexslider Output class */

Class SliceShow_Flexslider {
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
	
	//load document.ready code and flexslider init for each slideshow
	/**
	 * creates the jquery code to run the slideshow
	 * @return string the javascript code that runs the slider
	 */
	public function sliceshow_load_flexslider_js() {
		$flexslider_out = '';
		
		if( $this->get_show_id() ) {
			$ids = array( $this->get_show_id() );
		} else {
			//get ids of slideshows
			$ids = SliceShow_Helper::sliceshow_get_published_slideshows();
		}
		
		if( $ids ) {
			$flexslider_out .= '<script type="text/javascript">'.PHP_EOL;
			$flexslider_out .= '/* <![CDATA[ */'.PHP_EOL;
			$flexslider_out .= 'jQuery(window).load(function(){'.PHP_EOL;
			//loop through ids, get settings, and create flexslider init for slideshow
			foreach( $ids as $id ) {
				$slideshow_speed = (int) get_post_meta( $id, 'flexslider-settings-slideshow-speed', true );
				$slideshow_speed = ($slideshow_speed == 0) ? 7000 : $slideshow_speed;
				$animation_speed = (int ) get_post_meta( $id, 'flexslider-settings-animation-speed', true );
				$animation_speed = ($animation_speed == 0) ? 600 : $animation_speed;
				
				$direction = get_post_meta( $id, 'flexslider-settings-direction', true );
				$direction = 'horizontal'; //override until we figure out vertical issue;
				$animation = get_post_meta( $id, 'flexslider-settings-animation', true );
				$arrows = get_post_meta( $id, 'flexslider-settings-show-arrows', true );
				$nav = get_post_meta( $id, 'flexslider-settings-show-nav', true );
				
				$flexslider_out .= 'jQuery("#'.SliceShow_Helper::sliceshow_get_post_name( $id ).'").flexslider({'.PHP_EOL;
				if( $nav == 'yes' ) {
					$flexslider_out .= 'controlNav: true';
				} else {
					$flexslider_out .= 'controlNav: false';
				}//arrows = yes
				
				if( $arrows == 'yes' ) {
					$flexslider_out .= ','.PHP_EOL.'directionNav: true';
				} else {
					$flexslider_out .= ','.PHP_EOL.'directionNav: false';
				}//nav = yes
				
				$flexslider_out .= ','.PHP_EOL.'slideshowSpeed: '.$slideshow_speed;
				$flexslider_out .= ','.PHP_EOL.'animationSpeed: '.$animation_speed;
				
				$flexslider_out .= ','.PHP_EOL.'animation: "'.$animation.'"';
				$flexslider_out .= ','.PHP_EOL.'pauseOnHover: true'; // pause on hover
				$flexslider_out .= ','.PHP_EOL.'direction: "'.$direction.'"'.PHP_EOL;
				$flexslider_out .= '});'.PHP_EOL;
			}//foreach ids as id
			
			$flexslider_out .= '});'.PHP_EOL;
			$flexslider_out .= '/* ]]> */'.PHP_EOL;
			$flexslider_out .= '</script>'.PHP_EOL.PHP_EOL;
		}//if ids
		print $flexslider_out;
	}//sliceshow_load_flexslider_js
	
} //SliceShow_flexslider