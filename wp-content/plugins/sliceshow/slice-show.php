<?php
/*
Plugin Name: SliceShow
Plugin URI: http://slicenpress.com/plugins/sliceshow/
Description: Slideshow plugin
Version: 1.1
Author: Slice n Press - Randy Dustin, Tyler Jones
Author URI: http://slicenpress.com
License: GPL2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
?>
<?php
if( !defined( 'SliceShow_PLUGIN_URI' ) ) {
	define( 'SliceShow_PLUGIN_URI', dirname(__FILE__)  );
}

if( !defined( 'SliceShow_ACTIVE_SLIDER' ) ) {
	define( 'SliceShow_ACTIVE_SLIDER', 'cycle2' );
}

//include necessary classes
require_once('lib/sliceshow.public.class.php');
require_once('lib/sliceshow.helper.class.php');
require_once('lib/sliceshow.admin.class.php');
require_once('lib/sliceshow.meta-box.class.php');
require_once('lib/sliceshow.'.SliceShow_ACTIVE_SLIDER.'.class.php');

//register post type
$sliceshow_public = new SliceShow_Public(); //get public class instance
$sliceshow_admin = SliceShow_Admin::getInstance(); //get admin class instance
$sliceshow_settings_box = new SliceShow_MetaBox( $sliceshow_public->getSettingsBoxArgs(), $sliceshow_public->getSettingsBoxFields() ); //add settings meta box
$sliceshow_slide_box = new SliceShow_MetaBox( $sliceshow_public->getSlideBoxArgs(), $sliceshow_public->getSlideBoxFields() ); //add settings meta box

//enqueue public scripts for site
if( !is_admin() ) {
	add_action( 'wp_enqueue_scripts', array($sliceshow_public, 'addScripts') );
	add_action( 'wp_enqueue_scripts', array($sliceshow_public, 'addStyles') );
}

// enqueue scripts and styles, but only if is_admin
if(is_admin()) {
	add_action( 'admin_print_scripts', array($sliceshow_admin, 'admin_scripts') );
	
	add_action( 'admin_print_styles', array($sliceshow_admin, 'admin_styles') );
	
	add_action( 'admin_init', array($sliceshow_admin, 'sliceshow_edit_add_only_scripts') );
	
	add_filter( 'get_media_item_args', array( $sliceshow_admin, 'force_send') ); // force the insert into post button to show in media page (http://wordpress.org/support/topic/insert-into-post-button-missing-for-some-picture)
}

add_filter( 'manage_sliceshow_posts_columns', array($sliceshow_admin, 'sliceshow_columns_head') );
add_action( 'manage_sliceshow_posts_custom_column', array($sliceshow_admin, 'sliceshow_columns_content'), 10, 2 );

function sliceshow_slideshow( $id = 0 ) {
	$id = (int) $id;
	if( $id > 0 ) {
		echo do_shortcode( '[sliceshow id="'.$id.'"]' );
	}
} //sliceshow_slideshow

