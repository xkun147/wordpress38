<?php
/* Public Class */

Class SliceShow_Public {

	/**
	 * width value for slide
	 * @var integer
	 */
	private $slide_width;
	
	/**
	 * height value for slide
	 * @var integer
	 */
	private $slide_height;
	
	/**
	 * size of slide (i.e. flexible or fixed)
	 * @var string
	 */
	private $slide_size;

	public function __construct() {
		add_action( 'init', array($this, 'initPT') ); //start the post type
		add_action( 'after_setup_theme', array($this, 'setImageSizes') ); //add image sizes
		add_shortcode( 'sliceshow', array($this, 'sliceshow_shortcode') ); //activate shortcode
		
		add_filter('get_sample_permalink_html', array($this, 'removePermalink'), '',4); //remove view slideshow link
		add_action( 'admin_bar_menu', array($this, 'remove_viewslideshow'), 999 ); //remove view slideshow link from admin toobar
	
		

	} //__construct
	
	/**
	 * initialize slideshow post type
	 */
	public function initPT() {
		
		$labels = array(
			'name' => _x('SliceShow Slideshows', 'post type general name'),
			'singular_name' => _x('SliceShow Slideshow', 'post type singular name'),
			'add_new' => _x('Add New', 'sliceshow'),
			'add_new_item' => __('Add New Slideshow'),
			'edit_item' => __('Edit Slideshow'),
			'new_item' => __('New Slideshow'),
			'all_items' => __('All Slideshows'),
			'view_item' => __('View Slideshow'),
			'search_items' => __('Search Slideshows'),
			'not_found' =>  __('No slideshows found'),
			'not_found_in_trash' => __('No slideshows found in Trash'), 
			'parent_item_colon' => '',
			'menu_name' => __('SliceShow')
		  );
		  
		  $pt_args = array(
			'labels' => $labels,
			'public' => true,
			'exclude_from_search' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'show_in_menu' => true,
			'capability_type' => 'page',
			'supports' => array(
				'title',
				'post-thumbnails'
			)
		);
		
		register_post_type( 'sliceshow', $pt_args );		
	} //initPT
	
	/**
	 * removes the "view post" link in the slideshow admin area
	 * @return string the string that has been manipulated
	 */
	/* based off of this: http://wordpress.stackexchange.com/questions/31627/removing-edit-permalink-view-custom-post-type-areas */
	public function removePermalink($return, $id, $new_title, $new_slug) {
		global $typenow;
		if($typenow == 'sliceshow') {
			$ret2 = preg_replace('/<span id="edit-slug-buttons">.*<\/span>|<span id=\'view-post-btn\'>.*<\/span>/i', '', $return);
		} else {
			$ret2 = $return;
		}

		return $ret2;
	}
	
	/**
	 * removes the view slideshow link from the admin bar
	 * @param  object $wp_admin_bar 
	 */
	public function remove_viewslideshow( $wp_admin_bar ) {		
		global $typenow;
		if( $typenow == 'sliceshow' ) {
			$wp_admin_bar->remove_node( 'view' );
		}
	} //admin_bar_render
	
	/**
	 * sets up several sizes we want to use for the images as they are uploaded.
	 */
	public function setImageSizes() {
		
		if( function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'post-thumbnails' );
		}
		if ( function_exists( 'add_image_size' ) ) { 
			add_image_size( 'sliceshow-400', 400, 9999 ); //400 pixels wide (and unlimited height)
			add_image_size( 'sliceshow-800', 800, 9999 );
			add_image_size( 'sliceshow-1000', 1000, 9999 );
		}
	}//setImageSizes
	
	/**
	 * creates the shortcode for displaying a slideshow
	 * @param  array $atts 
	 * @return string 	html formatted slideshow       
	 */
	public function sliceshow_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'id' => 0
			), $atts
		) );
		
		//if no id was provided return nothing
		if( (int)$id == 0 )
			return '';
		
		return self::sliceshow_output_slides( $id );	
	} //sliceshow_shortcode
	
	/**
	 * function that outputs the slideshow html
	 * @param  integer $id slideshow id
	 * @return string      htmlfor slideshow based on active slideshow constant
	 */
	public function sliceshow_output_slides( $id = 0 ) {
		$output = '';
		//if no id was provided return nothing
		if( (int)$id == 0 )
			return '';
			
		//if the id is for a slideshow that hasn't been published yet return an html comment to that effect
		if( !in_array( $id, SliceShow_Helper::sliceshow_get_published_slideshows() ) )
			return '<!-- this slideshow ('.$id.') has not been published yet or has been deleted -->';
		
		$slides = SliceShow_Helper::sliceshow_get_slides( $id );
		
		if( $slides ) {
		
			$slider_class = "SliceShow_".ucfirst(SliceShow_ACTIVE_SLIDER);
			$slider_output = SliceShow_ACTIVE_SLIDER."Output";
			//setup the slider script
			${'cyc_'.SliceShow_Helper::sliceshow_get_post_name( $id )} = new $slider_class( $id );
			
			add_action('wp_footer', array(${'cyc_'.SliceShow_Helper::sliceshow_get_post_name( $id )}, 'sliceshow_load_'.SliceShow_ACTIVE_SLIDER.'_js') );
			
			return $this->$slider_output($slides, $id);
			
		}//if slides
	}//sliceshow_output_slides

	/**
	 * outputs slides formatted to use flexslider
	 * @param  array   $slides 
	 * @param  integer $id     
	 * @return string          html for slideshow
	 */
	public function flexsliderOutput( $slides = array(), $id = 0 ) {
		$output = '';
		//get slideshow image settings
		$this->slide_size = get_post_meta( $id, 'flexslider-settings-slide-size', true );
		if( $this->slide_size == 'flexible' ) {
			$this->slide_width = (int) get_post_meta( $id, 'flexslider-settings-max-width', true );
			$this->slide_height = (int) get_post_meta( $id, 'flexslider-settings-max-height', true );
			
			if( $this->slide_width == 0 )
				$this->slide_width = 1000;
				
			if( $this->slide_height == 0 )
				$this->slide_height = 750;
			
			$wrap_inline_styles = 'style="max-height: '.$this->slide_height.'px; max-width: '.$this->slide_width.'px;"';
			
			$li_inline_styles = 'style="max-height: '.$this->slide_height.'px;"';
		} else {
			$this->slide_width = (int) get_post_meta( $id, 'flexslider-settings-image-width', true );
			
			$this->slide_height = (int) get_post_meta( $id, 'flexslider-settings-image-height', true );
			
			if( $this->slide_width == 0 )
				$this->slide_width = 1000;
				
			if( $this->slide_height == 0 )
				$this->slide_height = 750;
			
			$wrap_inline_styles = 'style="height: '.$this->slide_height.'px; width: '.$this->slide_width.'px;"';
			
			$li_inline_styles = 'style="height: '.$this->slide_height.'px;"';
		}//slide_size == flexible
		
		//determine which size image to use 
	
		switch( $this->slide_width ) {
			case $this->slide_width <= 400:
				$img_size = 'sliceshow-400';
			break;
			case $this->slide_width <= 800:
				$img_size = 'sliceshow-800';
			break;
			case $this->slide_width <= 1000:
				$img_size = 'sliceshow-1000';
			break;
			default:
				$img_size = 'full';
		}
		
		$nav = get_post_meta( $id, 'flexslider-settings-show-nav', true );
		
		$container_classes_array = array( 
			'sliceshow-slideshow-container'
		);
		$container_style_class = implode( ' ', apply_filters( 'sliceshow_container_class', $container_classes_array, $id ) );
		
		$sliceshow_slideshow_classes_array = array( 
			'sliceshow-slideshow'
		);
		
		if( $nav == 'no' ) {
			$sliceshow_slideshow_classes_array[] = 'pager-off';
		}
		$sliceshow_slideshow_style_class = implode( ' ', apply_filters( 'sliceshow_slideshow_class', $sliceshow_slideshow_classes_array ) );
		
		$nav_out = '';
		$output .= '<div class="'.$container_style_class.'">'.PHP_EOL;
		$output .= '<div id="'.SliceShow_Helper::sliceshow_get_post_name( $id ).'" class="'.$sliceshow_slideshow_style_class.'" '.$wrap_inline_styles.'><ul class="slides">'.PHP_EOL;
		
		foreach( $slides as $k=>$slide ) {
			$img = wp_get_attachment_image_src( $slide['slideimage_0'], $img_size );
			$output .= '<li '.$li_inline_styles.'>'.PHP_EOL;
			//title and url
			if( $slide['slidetitle_0'] != '' && $slide['slideurl_0'] != '' ) {
				$output .= '<p class="sliceshow-caption"><a href="'.$slide['slideurl_0'].'">'.$slide['slidetitle_0'].'</a></p>'.PHP_EOL;
				$output .= '<img src="'.$img[0].'" alt="'.$slide['slidetitle_0'].'">'.PHP_EOL;
			} elseif( $slide['slidetitle_0'] == '' && $slide['slideurl_0'] != '' ) {
				$output .= '<a href="'.$slide['slideurl_0'].'"><img src="'.$img[0].'" alt="'.$slide['slidetitle_0'].'"></a>'.PHP_EOL;
			} elseif( $slide['slidetitle_0'] != '' && $slide['slideurl_0'] == '' ) {
				$output .= '<p class="sliceshow-caption">'.$slide['slidetitle_0'].'</p>';
				$output .= '<img src="'.$img[0].'" alt="'.$slide['slidetitle_0'].'">'.PHP_EOL;
			} else {
				$output .= '<img src="'.$img[0].'" alt="'.$slide['slidetitle_0'].'">'.PHP_EOL;
			}//if slide title && slideurl
			$output .= '</li><!-- /sliceshow-slideshow-item -->'.PHP_EOL;
		}//foreach slides as slide
		$output .= '</ul></div><!-- /sliceshow-slideshow -->';
		$output .= '</div><!-- /sliceshow-slideshow-container -->';
		
		return $output;
	}
	
	/**
	 * outputs slides formatted to use cycle2 (default)
	 * @param  array   $slides 
	 * @param  integer $id     
	 * @return string          html for slideshow
	 */
	public function cycle2Output( $slides = array(), $id = 0 ) {
		$output = '';
		//get slideshow image settings
		$this->slide_size = get_post_meta( $id, 'cycle2-settings-slide-size', true );
		//check for empty string because that means we have a slideshow that still has flexslider settings and needs conversion
		if('' == $this->slide_size) {
			$this->slide_size = get_post_meta( $id, 'flexslider-settings-slide-size', true );
			if( $this->slide_size == 'flexible' ) {
				$this->slide_width = (int) get_post_meta( $id, 'flexslider-settings-max-width', true );
				$this->slide_height = (int) get_post_meta( $id, 'flexslider-settings-max-height', true );
				
				if( $this->slide_width == 0 )
					$this->slide_width = 1000;
					
				if( $this->slide_height == 0 )
					$this->slide_height = 750;
				
				$wrap_inline_styles = 'style="max-height: '.$this->slide_height.'px; max-width: '.$this->slide_width.'px;"';
				
				$li_inline_styles = 'style="max-height: '.$this->slide_height.'px;"';
				
				$container_inline_styles = 'style="max-width: '.$this->slide_width.'px;"';
			} else {
				$this->slide_width = (int) get_post_meta( $id, 'flexslider-settings-image-width', true );
				
				$this->slide_height = (int) get_post_meta( $id, 'flexslider-settings-image-height', true );
				
				if( $this->slide_width == 0 )
					$this->slide_width = 1000;
					
				if( $this->slide_height == 0 )
					$this->slide_height = 750;
				
				$wrap_inline_styles = 'style="height: '.$this->slide_height.'px; width: '.$this->slide_width.'px;"';
				
				$li_inline_styles = 'style="height: '.$this->slide_height.'px;"';
				
				$container_inline_styles = 'style="width: '.$this->slide_width.'px;"';
			}//slide_size == flexible


		} else {
			if( $this->slide_size == 'flexible' ) {
				$this->slide_width = (int) get_post_meta( $id, 'cycle2-settings-max-width', true );
				$this->slide_height = (int) get_post_meta( $id, 'cycle2-settings-max-height', true );
				
				if( $this->slide_width == 0 )
					$this->slide_width = 1000;
					
				if( $this->slide_height == 0 )
					$this->slide_height = 750;
				
				$wrap_inline_styles = 'style="max-height: '.$this->slide_height.'px; max-width: '.$this->slide_width.'px;"';
				
				$li_inline_styles = 'style="max-height: '.$this->slide_height.'px;"';
				
				$container_inline_styles = 'style="max-width: '.$this->slide_width.'px;"';
			} elseif( $this->slide_size == 'fixed' ) {
				$this->slide_width = (int) get_post_meta( $id, 'cycle2-settings-image-width', true );
				
				$this->slide_height = (int) get_post_meta( $id, 'cycle2-settings-image-height', true );
				
				if( $this->slide_width == 0 )
					$this->slide_width = 1000;
					
				if( $this->slide_height == 0 )
					$this->slide_height = 750;
				
				$wrap_inline_styles = 'style="height: '.$this->slide_height.'px; width: '.$this->slide_width.'px;"';
				
				$li_inline_styles = 'style="height: '.$this->slide_height.'px;"';
				
				$container_inline_styles = 'style="width: '.$this->slide_width.'px;"';
			}//slide_size == flexible
		}
		
		//determine which size image to use 
	
		switch( $this->slide_width ) {
			case $this->slide_width <= 400:
				$img_size = 'sliceshow-400';
			break;
			case $this->slide_width <= 800:
				$img_size = 'sliceshow-800';
			break;
			case $this->slide_width <= 1000:
				$img_size = 'sliceshow-1000';
			break;
			default:
				$img_size = 'full';
		}
		
		$timeout = get_post_meta( $id, 'cycle2-settings-slideshow-speed', true );
		$timeout = ('' == $timeout) ? get_post_meta( $id, 'flexslider-settings-slideshow-speed', true ) : $timeout; //gets flexslider settings if cycle2 setting aren't found (backwards compatibility for slide already created when flexslider was default)
		$timeout = ((int)$timeout == 0) ? 7000 : $timeout;
		$speed = get_post_meta( $id, 'cycle2-settings-animation-speed', true );
		$speed = ('' == $speed) ? get_post_meta( $id, 'flexslider-settings-animation-speed', true ) : $speed; //gets flexslider settings if cycle2 setting aren't found (backwards compatibility for slide already created when flexslider was default)
		$speed = ($speed == 0) ? 600 : $speed;
		$nav = get_post_meta( $id, 'cycle2-settings-show-nav', true );
		$nav = ('' == $nav) ? get_post_meta( $id, 'flexslider-settings-show-nav', true) : $nav;//gets flexslider settings if cycle2 setting aren't found (backwards compatibility for slide already created when flexslider was default)
		$animation = get_post_meta( $id, 'cycle2-settings-animation', true );
		$animation = ('' == $animation) ? get_post_meta( $id, 'flexslider-settings-animation', true ) : $animation;//gets flexslider settings if cycle2 setting aren't found (backwards compatibility for slide already created when flexslider was default)
		$animation = ('slide' == $animation) ? 'scrollHorz' : $animation; //change name if it is flexslider slide
		$arrows = get_post_meta( $id, 'cycle2-settings-show-arrows', true );
		$arrows = ('' == $arrows) ? get_post_meta( $id, 'flexslider-settings-show-arrows', true ) : $arrows;//gets flexslider settings if cycle2 setting aren't found (backwards compatibility for slide already created when flexslider was default)
		if( $nav == 'yes' ) {
			$nav_out = 'data-cycle-pager="#'.SliceShow_Helper::sliceshow_get_post_name( $id ).'-nav"';
		} else {
			$nav_out = '';
		}//arrows = yes
		
		if( $arrows == 'yes' ) {
			$arrows_out = 'data-cycle-next="#'.SliceShow_Helper::sliceshow_get_post_name( $id ).'-next" data-cycle-prev="#'.SliceShow_Helper::sliceshow_get_post_name( $id ).'-prev"';
		} else {
			$arrows_out = '';
		}//nav = yes


		$container_classes_array = array( 
			'sliceshow-slideshow-container'
		);
		$container_style_class = implode( ' ', apply_filters( 'sliceshow_container_class', $container_classes_array, $id ) );
		
		$sliceshow_slideshow_classes_array = array( 
			'sliceshow-slideshow'
		);
		
		if( $nav == 'no' ) {
			$sliceshow_slideshow_classes_array[] = 'pager-off';
		}
		$sliceshow_slideshow_style_class = implode( ' ', apply_filters( 'sliceshow_slideshow_class', $sliceshow_slideshow_classes_array ) );
		
		$output .= '<div class="'.$container_style_class.'" '.$container_inline_styles.'>'.PHP_EOL;
		$output .= '<div id="'.SliceShow_Helper::sliceshow_get_post_name( $id ).'" class="'.$sliceshow_slideshow_style_class.'" '.$wrap_inline_styles.'>'.PHP_EOL;
		
		// Output opening arrow div
		if( $arrows == 'yes' ) {
			$output .= '<div class="sliceshow-arrow-wrap">'.PHP_EOL;
			$output .= '<a class="sliceshow-arrow sliceshow-arrow-prev" id="'.SliceShow_Helper::sliceshow_get_post_name( $id ).'-prev">Prev</a>'.PHP_EOL;
			$output .= '<a class="sliceshow-arrow sliceshow-arrow-next" id="'.SliceShow_Helper::sliceshow_get_post_name( $id ).'-next">Next</a>'.PHP_EOL;
		}// End opening arrow div
		
		$output .= '<ul class="slides cycle-slideshow" data-cycle-slides="> li" data-cycle-pause-on-hover="true" data-cycle-log="false" data-cycle-timeout="'.$timeout.'" data-cycle-speed="'.$speed.'" data-cycle-fx="'.$animation.'" '.$arrows_out.' '.$nav_out.' data-cycle-swipe="true">'.PHP_EOL;
		
		foreach( $slides as $k=>$slide ) {
			$img = wp_get_attachment_image_src( $slide['slideimage_0'], $img_size );
			$output .= '<li '.$li_inline_styles.'>'.PHP_EOL;
			//title and url
			if( $slide['slidetitle_0'] != '' && $slide['slideurl_0'] != '' ) {
				$output .= '<p class="sliceshow-caption"><a href="'.$slide['slideurl_0'].'">'.$slide['slidetitle_0'].'</a></p>'.PHP_EOL;
				$output .= '<img src="'.$img[0].'" alt="'.$slide['slidetitle_0'].'">'.PHP_EOL;
			} elseif( $slide['slidetitle_0'] == '' && $slide['slideurl_0'] != '' ) {
				//$output .= '<a href="'.$slide['slideurl_0'].'"><img src="'.$img[0].'" alt="'.$slide['slidetitle_0'].'"></a>'.PHP_EOL;
				$output .= '<a href="'.$slide['slideurl_0'].'"><img src="'.$img[0].'" alt="'.$slide['slidetitle_0'].'"></a>';
			} elseif( $slide['slidetitle_0'] != '' && $slide['slideurl_0'] == '' ) {
				$output .= '<p class="sliceshow-caption">'.$slide['slidetitle_0'].'</p>';
				$output .= '<img src="'.$img[0].'" alt="'.$slide['slidetitle_0'].'">'.PHP_EOL;
			} else {
				$output .= '<img src="'.$img[0].'" alt="'.$slide['slidetitle_0'].'">'.PHP_EOL;
			}//if slide title && slideurl
			$output .= '</li><!-- /sliceshow-slideshow-item -->'.PHP_EOL;
		}//foreach slides as slide
		$output .= '</ul></div><!-- /sliceshow-slideshow -->';

		// Output closing arrow div
		if( $arrows == 'yes' ) {
			$output .= '</div><!-- /arrow-wrap -->'.PHP_EOL;
		}// if arrows
		
		if( $nav == 'yes' ) {
			$output .= '<div id="'.SliceShow_Helper::sliceshow_get_post_name( $id ).'-nav" class="pager-wrap"></div><!-- /pager-wrap -->'.PHP_EOL;
		}// if nav
		
		$output .= '</div><!-- /sliceshow-slideshow-container -->';
		
		return $output;
	}

	/**
	 * outputs slides for use with cycle plugin (currently incomplete)
	 * @param  array   $slides 
	 * @param  integer $id     
	 * @return string          html for slideshow
	 */
	public function cycleOutput( $slides = array(), $id = 0 ) {
		//get slideshow image settings
		$slide_width = get_post_meta( $id, 'slide-settings-image-width', true );
		$slide_height = get_post_meta( $id, 'slide-settings-image-height', true );
		$arrows = get_post_meta( $id, 'slide-settings-show-arrows', true );
		$nav = get_post_meta( $id, 'slide-settings-show-nav', true );
		$nav_out = '';
		$output .= '<div id="'.SliceShow_Helper::sliceshow_get_post_name( $id ).'" class="sliceshow-slideshow">'.PHP_EOL;
		
		foreach( $slides as $k=>$slide ) {
			$img = wp_get_attachment_image_src( $slide['slideimage_0'], array( $slide_width, $slide_height ) );
			$output .= '<div class="sliceshow-slideshow-item">'.PHP_EOL;
			//title and url
			if( $slide['slidetitle_0'] != '' && $slide['slideurl_0'] != '' ) {
				$output .= '<h3><a href='.$slide['slideurl_0'].'>'.$slide['slidetitle_0'].'</a></h3>'.PHP_EOL;
				$output .= '<img src="'.$img[0].'" />'.PHP_EOL;
			} elseif( $slide['slidetitle_0'] == '' && $slide['slideurl_0'] != '' ) {
				$output .= '<a href='.$slide['slideurl_0'].'><img src="'.$img[0].'" /></a>'.PHP_EOL;
			} elseif( $slide['slidetitle_0'] != '' && $slide['slideurl'] == '' ) {
				$output .= '<h3>'.$slide['slidetitle_0'].'</h3>';
				$output .= '<img src="'.$img[0].'" />'.PHP_EOL;
			} else {
				$output .= '<img src="'.$img[0].'" />'.PHP_EOL;
			}//if slide title && slideurl
			$output .= '</div><!-- /sliceshow-slideshow-item -->'.PHP_EOL;
		}//foreach slides as slide
		$output .= '</div><!-- /sliceshow-slideshow -->';
		
		if( $arrows == 'yes' ) {
			$output .= '<div class="arrow-wrap">'.PHP_EOL;
			$output .= '<a id="'.SliceShow_Helper::sliceshow_get_post_name( $id ).'-prev">Prev</a>'.PHP_EOL;
			$output .= '<a id="'.SliceShow_Helper::sliceshow_get_post_name( $id ).'-next">Next</a>'.PHP_EOL;
			$output .= '</div><!-- /arrow-wrap -->'.PHP_EOL;
		}// if arrows
		
		if( $nav == 'yes' ) {
			$output .= '<div id="'.SliceShow_Helper::sliceshow_get_post_name( $id ).'-nav" class="pager-wrap"></div><!-- /pager-wrap -->'.PHP_EOL;
		}// if nav
		
		return $output;
	}//cycleOutput
	
	public function addScripts() {
		wp_enqueue_script( 'jquery.'.SliceShow_ACTIVE_SLIDER.'.all', plugins_url( '/thirdparty/jquery.'.SliceShow_ACTIVE_SLIDER.'.js', dirname(__FILE__) ), array('jquery') );
	} //addScripts
	
	public function addStyles() {
		wp_enqueue_style( 'sliceshow', plugins_url( '/css/slice-show.css', dirname(__FILE__) ) );
		if( SliceShow_ACTIVE_SLIDER == 'flexslider' ) {
			wp_enqueue_style( 'flexslider', plugins_url( '/thirdparty/flexslider.css', dirname(__FILE__) ) );
		}
	} //addStyles
	
	public function getSettingsBoxFields() {
		$fieldFunc = SliceShow_ACTIVE_SLIDER.'SettingsFields';
		$settings_meta_fields = $this->$fieldFunc();
		
		return $settings_meta_fields;
	} //getSettingsBoxFields
	
	
	public function flexsliderSettingsFields() {
		$prefix = 'flexslider-settings-';
		$suffix = '';
		
		$settings_meta_fields = array(
			$prefix.'slide-size' =>
			array(
				'label'=> 'Slide Size',
				'desc'	=> '',
				'id'	=> $prefix.'slide-size'.$suffix,
				'class' => $prefix.'slide-size',
				'type'	=> 'radio',
				'options' => array(
					'flexible' => array( 
						'label' => 'Flexible',
						'value' => 'flexible'
					),
					'fixed' => array( 
						'label' => 'Fixed',
						'value' => 'fixed'
					)
				),
				'default' => 'flexible'
			),
			$prefix.'max-width' =>
			array(
				'label'=> 'Max Width',
				'desc'	=> '',
				'id'	=> $prefix.'max-width'.$suffix,
				'class' => $prefix.'max-width',
				'type'	=> 'text',
				'default' => 1000
			),
			$prefix.'max-height' =>
			array(
				'label'=> 'Max Height',
				'desc'	=> '',
				'id'	=> $prefix.'max-height'.$suffix,
				'class' => $prefix.'max-height',
				'type'	=> 'text',
				'default' => 750
			),
			$prefix.'image-width' =>
			array(
				'label'=> 'Image Width',
				'desc'	=> '',
				'id'	=> $prefix.'image-width'.$suffix,
				'class' => $prefix.'image-width',
				'type'	=> 'text',
				'default' => 1000
			),
			$prefix.'image-height' =>
			array(
				'label'=> 'Image Height',
				'desc'	=> '',
				'id'	=> $prefix.'image-height'.$suffix,
				'class' => $prefix.'image-height',
				'type'	=> 'text',
				'default' => 750
			),
			$prefix.'slideshow-speed' =>
			array(
				'label'=> 'Slideshow Speed (milliseconds)',
				'desc'	=> '',
				'id'	=> $prefix.'slideshow-speed'.$suffix,
				'class' => $prefix.'slideshow-speed',
				'type'	=> 'text',
				'default' => '7000'
			),
			$prefix.'animation-speed' =>
			array(
				'label'=> 'Animation Speed (milliseconds)',
				'desc'	=> '',
				'id'	=> $prefix.'animation-speed'.$suffix,
				'class' => $prefix.'animation-speed',
				'type'	=> 'text',
				'default' => '600'
			),
			$prefix.'animation' =>
			array(
				'label'=> 'Animation',
				'desc'	=> '',
				'id'	=> $prefix.'animation'.$suffix,
				'class' => $prefix.'animation',
				'type'	=> 'select',
				'options' => array(
					'slide' => array( 
						'label' => 'slide',
						'value' => 'slide'
						),
					'fade' => array( 
						'label' => 'fade',
						'value' => 'fade'
						)
					
				),
				'default' => ''
			),
			$prefix.'show-arrows' =>
			array(
				'label'=> 'Show Arrows',
				'desc'	=> '',
				'id'	=> $prefix.'show-arrows'.$suffix,
				'class' => $prefix.'show-arrows',
				'type'	=> 'select',
				'options' => array(
					'yes' => array( 
						'label' => 'Yes',
						'value' => 'yes'
					),
					'no' => array( 
						'label' => 'No',
						'value' => 'no'
					)
				),
				'default' => ''
			),
			$prefix.'show-nav' =>
			array(
				'label'=> 'Show Slide Navigation',
				'desc'	=> '',
				'id'	=> $prefix.'show-nav'.$suffix,
				'class' => $prefix.'show-nav',
				'type'	=> 'select',
				'options' => array(
					'yes' => array( 
						'label' => 'Yes',
						'value' => 'yes'
					),
					'no' => array( 
						'label' => 'No',
						'value' => 'no'
					)
				),
				'default' => ''
			)
		);
		
		return apply_filters( 'sliceshow_settings_fields', $settings_meta_fields );
	} //flexsliderSettingsFields

	public function cycle2SettingsFields() {
		$prefix = 'cycle2-settings-';
		$suffix = '';
		
		$settings_meta_fields = array(
			$prefix.'slide-size' =>
			array(
				'label'=> 'Slide Size',
				'desc'	=> '',
				'id'	=> $prefix.'slide-size'.$suffix,
				'class' => $prefix.'slide-size',
				'type'	=> 'radio',
				'options' => array(
					'flexible' => array( 
						'label' => 'Flexible',
						'value' => 'flexible'
					),
					'fixed' => array( 
						'label' => 'Fixed',
						'value' => 'fixed'
					)
				),
				'default' => 'flexible'
			),
			$prefix.'max-width' =>
			array(
				'label'=> 'Max Width',
				'desc'	=> '',
				'id'	=> $prefix.'max-width'.$suffix,
				'class' => $prefix.'max-width',
				'type'	=> 'text',
				'default' => 1000
			),
			$prefix.'max-height' =>
			array(
				'label'=> 'Max Height',
				'desc'	=> '',
				'id'	=> $prefix.'max-height'.$suffix,
				'class' => $prefix.'max-height',
				'type'	=> 'text',
				'default' => 750
			),
			$prefix.'image-width' =>
			array(
				'label'=> 'Image Width',
				'desc'	=> '',
				'id'	=> $prefix.'image-width'.$suffix,
				'class' => $prefix.'image-width',
				'type'	=> 'text',
				'default' => 1000
			),
			$prefix.'image-height' =>
			array(
				'label'=> 'Image Height',
				'desc'	=> '',
				'id'	=> $prefix.'image-height'.$suffix,
				'class' => $prefix.'image-height',
				'type'	=> 'text',
				'default' => 750
			),
			$prefix.'slideshow-speed' =>
			array(
				'label'=> 'Slideshow Speed (milliseconds)',
				'desc'	=> '',
				'id'	=> $prefix.'slideshow-speed'.$suffix,
				'class' => $prefix.'slideshow-speed',
				'type'	=> 'text',
				'default' => '7000'
			),
			$prefix.'animation-speed' =>
			array(
				'label'=> 'Animation Speed (milliseconds)',
				'desc'	=> '',
				'id'	=> $prefix.'animation-speed'.$suffix,
				'class' => $prefix.'animation-speed',
				'type'	=> 'text',
				'default' => '600'
			),
			$prefix.'animation' =>
			array(
				'label'=> 'Animation',
				'desc'	=> '',
				'id'	=> $prefix.'animation'.$suffix,
				'class' => $prefix.'animation',
				'type'	=> 'select',
				'options' => array(
					'scrollHorz' => array( 
						'label' => 'Scroll Horizontal',
						'value' => 'scrollHorz'
						),
					'fade' => array( 
						'label' => 'Fade',
						'value' => 'fade'
						),
					'fadeout' => array( 
						'label' => 'Fade Out',
						'value' => 'fadeout'
						),
					'none' => array( 
						'label' => 'None',
						'value' => 'none'
						)
					
				),
				'default' => ''
			),
			$prefix.'show-arrows' =>
			array(
				'label'=> 'Show Arrows',
				'desc'	=> '',
				'id'	=> $prefix.'show-arrows'.$suffix,
				'class' => $prefix.'show-arrows',
				'type'	=> 'select',
				'options' => array(
					'yes' => array( 
						'label' => 'Yes',
						'value' => 'yes'
					),
					'no' => array( 
						'label' => 'No',
						'value' => 'no'
					)
				),
				'default' => ''
			),
			$prefix.'show-nav' =>
			array(
				'label'=> 'Show Slide Navigation',
				'desc'	=> '',
				'id'	=> $prefix.'show-nav'.$suffix,
				'class' => $prefix.'show-nav',
				'type'	=> 'select',
				'options' => array(
					'yes' => array( 
						'label' => 'Yes',
						'value' => 'yes'
					),
					'no' => array( 
						'label' => 'No',
						'value' => 'no'
					)
				),
				'default' => ''
			)
		);
		
		return apply_filters( 'sliceshow_settings_fields', $settings_meta_fields );
	} //cycle2SettingsFields
	
	/**
	 * the nitty gritty details used to set up the settings meta fields for slideshow using cycle plugin (currently incomplete)
	 * @return [type] [description]
	 */
	public function cycleSettingsFields() {
		$prefix = 'slide-settings-';
		$suffix = '';
		
		$settings_meta_fields = array(
			$prefix.'image-width' =>
			array(
				'label'=> 'Image Width',
				'desc'	=> '',
				'id'	=> $prefix.'image-width'.$suffix,
				'class' => $prefix.'image-width',
				'type'	=> 'text',
				'default' => 623,
				'required' => 'required'
			),
			$prefix.'image-height' =>
			array(
				'label'=> 'Image Height',
				'desc'	=> '',
				'id'	=> $prefix.'image-height'.$suffix,
				'class' => $prefix.'image-height',
				'type'	=> 'text',
				'default' => 437,
				'required' => 'required'
			),
			$prefix.'speed' =>
			array(
				'label'=> 'Slideshow Speed',
				'desc'	=> '',
				'id'	=> $prefix.'speed'.$suffix,
				'class' => $prefix.'speed',
				'type'	=> 'text',
				'default' => ''
			),
			$prefix.'transition' =>
			array(
				'label'=> 'Transition Type',
				'desc'	=> '',
				'id'	=> $prefix.'transition'.$suffix,
				'class' => $prefix.'transition',
				'type'	=> 'select',
				'options' => array(
					'fade' => array( 
						'label' => 'fade',
						'value' => 'fade'
						),
					'none' => array( 
						'label' => 'none',
						'value' => 'none'
						),
					'blindX' => array( 
						'label' => 'blindX',
						'value' => 'blindX'
						),
					'blindY' => array( 
						'label' => 'blindY',
						'value' => 'blindY'
						),
					'blindZ' => array( 
						'label' => 'blindZ',
						'value' => 'blindZ'
						),
					'cover' => array( 
						'label' => 'cover',
						'value' => 'cover'
						),
					'curtainX' => array( 
						'label' => 'curtainX',
						'value' => 'curtainX'
						),
					'curtainY' => array( 
						'label' => 'curtainY',
						'value' => 'curtainY'
						),
					'fadeZoom' => array( 
						'label' => 'fadeZoom',
						'value' => 'fadeZoom'
						),
					'growX' => array( 
						'label' => 'growX',
						'value' => 'growX'
						),
					'scrollUp' => array( 
						'label' => 'scrollUp',
						'value' => 'scrollUp'
						),
					'scrollDown' => array( 
						'label' => 'scrollDown',
						'value' => 'scrollDown'
						),
					'scrollLeft' => array( 
						'label' => 'scrollLeft',
						'value' => 'scrollLeft'
						),
					'scrollRight' => array( 
						'label' => 'scrollRight',
						'value' => 'scrollRight'
						),
					'scrollHorz' => array( 
						'label' => 'scrollHorz',
						'value' => 'scrollHorz'
						),
					'scrollVert' => array( 
						'label' => 'scrollVert',
						'value' => 'scrollVert'
						),
					'shuffle' => array( 
						'label' => 'shuffle',
						'value' => 'shuffle'
						),
					'slideX' => array( 
						'label' => 'slideX',
						'value' => 'slideX'
						),
					'slideY' => array( 
						'label' => 'slideY',
						'value' => 'slideY'
						),
					'toss' => array( 
						'label' => 'toss',
						'value' => 'toss'
						),
					'turnUp' => array( 
						'label' => 'turnUp',
						'value' => 'turnUp'
						),
					'turnDown' => array( 
						'label' => 'turnDown',
						'value' => 'turnDown'
						),
					'turnLeft' => array( 
						'label' => 'turnLeft',
						'value' => 'turnLeft'
						),
					'turnRight' => array( 
						'label' => 'turnRight',
						'value' => 'turnRight'
						),
					'uncover' => array( 
						'label' => 'uncover',
						'value' => 'uncover'
						),
					'wipe' => array( 
						'label' => 'wipe',
						'value' => 'wipe'
						),
					'zoom' => array( 
						'label' => 'zoom',
						'value' => 'zoom'
						)
				),
				'default' => ''
			),
			$prefix.'show-arrows' =>
			array(
				'label'=> 'Show Arrows',
				'desc'	=> '',
				'id'	=> $prefix.'show-arrows'.$suffix,
				'class' => $prefix.'show-arrows',
				'type'	=> 'select',
				'options' => array(
					'yes' => array( 
						'label' => 'Yes',
						'value' => 'yes'
					),
					'no' => array( 
						'label' => 'No',
						'value' => 'no'
					)
				),
				'default' => ''
			),
			$prefix.'show-nav' =>
			array(
				'label'=> 'Show Slide Navigation',
				'desc'	=> '',
				'id'	=> $prefix.'show-nav'.$suffix,
				'class' => $prefix.'show-nav',
				'type'	=> 'select',
				'options' => array(
					'yes' => array( 
						'label' => 'Yes',
						'value' => 'yes'
					),
					'no' => array( 
						'label' => 'No',
						'value' => 'no'
					)
				),
				'default' => ''
			),
			$prefix.'hover-pause' =>
			array(
				'label'=> 'Pause on Hover',
				'desc'	=> '',
				'id'	=> $prefix.'hover-pause'.$suffix,
				'class' => $prefix.'hover-pause',
				'type'	=> 'select',
				'options' => array(
					'yes' => array( 
						'label' => 'Yes',
						'value' => 'yes'
					),
					'no' => array( 
						'label' => 'No',
						'value' => 'no'
					)
				),
				'default' => ''
			)
		);
		
		return $settings_meta_fields;
	} //cycleSettingsFields
	
	public function getSettingsBoxArgs() {
		
		
		$box_args = array(
			'id' => 'slide_settings_meta_box',
			'title' => 'Settings',
			'callback' => '',
			'page' => 'sliceshow',
			'context' => 'side',
			'priority' => 'default'
		);
		
		return $box_args;		
	} //getsettingsBoxArgs
	
	/**
	 * the default args for the slide meta box
	 * @return array 
	 */
	public function getslideBoxArgs() {
		
		$box_args = array(
			'id' => 'slide_meta_box',
			'title' => 'Slides',
			'callback' => '',
			'page' => 'sliceshow',
			'context' => 'normal',
			'priority' => 'high'
		);
		
		return $box_args;		
	} //getslideBoxArgs
	
	/**
	 * the default args for the slide fields
	 * @return array 
	 */
	function getSlideBoxFields() {
		
		$prefix = 'slide';
		$suffix = '_0';
		
		// Field Array
		$custom_meta_fields = array(
			$prefix.'title' =>
			array(
				'label'=> 'Slide Title',
				'desc'	=> 'Title',
				'id'	=> $prefix.'title'.$suffix,
				'class' => $prefix.'title',
				'type'	=> $prefix.'-text-title'
			),
			$prefix.'url' =>
			array(
				'label'=> 'Slide Url',
				'desc'	=> 'URL',
				'id'	=> $prefix.'url'.$suffix,
				'class' => $prefix.'url',
				'type'	=> $prefix.'-text-url'
			),
			$prefix.'image' =>
			array(
				'name'	=> 'Image',
				'desc'	=> 'Slide Image',
				'id'	=> $prefix.'image'.$suffix,
				'class' => $prefix.'image',
				'type'	=> $prefix.'-image',
				'required' => 'required'
			),
			$prefix.'order' =>
			array(
				'label'=> 'Slide Order',
				'desc'	=> 'Order of slide. Lowest number is first',
				'id'	=> $prefix.'order'.$suffix,
				'class' => $prefix.'order',
				'type'	=> $prefix.'-hidden'
			),
			$prefix.'repeatable' =>
			array(
				'label' => 'Repeatable',
				'desc' => '',
				'id' => $prefix.'repeatable'.$suffix,
				'class' => $prefix.'repeatable',
				'type' => $prefix.'-repeatable'
			)
		);
		
		return $custom_meta_fields;
	} //getSlideBoxFields

}//SliceShow_Public