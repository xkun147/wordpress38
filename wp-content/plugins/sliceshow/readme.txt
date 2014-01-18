=== SliceShow ===
Contributors: tylerjones
Donate link: 
Tags: slideshow, image rotator, image slider, javascript rotator, javascript slider, jquery rotator, responsive, responsive image slider, responsive slider, responsive slideshow, rotator, slider
Requires at least: 3.4.2
Tested up to: 3.5.1
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple, beautiful, responsive slideshows for WordPress. Upload images, add links & titles, & rearrange slides. Embed with a shortcode.

== Description ==

SliceShow plugin for WordPress allows you to very quickly and easily create beautiful slide shows on your site. You can add titles and links that will overlay the images in your slideshow. You can create as many slideshows as you'd like and easily embed those into your posts and pages using the `[sliceshow id="1"]` shortcode or directly in your templates using the php function `<?php if( function_exists( 'sliceshow_slideshow' ) ) { sliceshow_slideshow( 1 ); } ?>`

Slideshows are responsive as well, so they will scale from desktop to tablet to mobile sizes, automatically. Alternately you can define a custom size for your slideshow using "fixed" pizel sizes, which is great for those times when you need a slideshow to always be a specific size.

Uploaded images are also automatically resized via php to optimize the loading times of your slideshow. No need to worry about your clients uploading 4000 pixel wide images and slowing down their site!

**Note: this is the free version of SliceShow. If you'd like more features and access to support, please consider purchasing [SliceShow Pro](http://plugins.slicenpress.com/sliceshow/)**

### Features

* Responsive sizing - works great with responsive and standard themes
* Create unlimited slideshows
* Drag & drop reordering of slides
* Automatic image resizing
* Set the speed, transition, and size of each slideshow individually
* Easily embed your slideshows with a shortcode & php template function
* Looks great on mobile devices & tablets
* Swipe to change slides on tablets and mobile devices
* Solid core - built with a proper class style codebase for security and extensibility

**You can learn more about SliceShow at [Slice n Press](http://plugins.slicenpress.com/sliceshow/ "SlicenPress")**


== Installation ==

To install the SliceShow plugin:

1. Upload `sliceshow` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place the SliceShow shortcode in your pages or posts like `[sliceshow id="1"]` replacing the id value with the id of your slideshow. To find the shortcode, just click on SliceShow - All Slideshows in the WordPress admin menu. The shortcode for each slideshow is listed on this page.
1. Alternately you can embed the slideshow via your templates using the php function `<?php if( function_exists( 'sliceshow_slideshow' ) ) { sliceshow_slideshow( 1 ); } ?>`. Replace the "1" with the id value of your slideshow.

== Frequently Asked Questions ==

= How do I file a bug report or get support? =

If you're using the free version of SliceShow, you can post on the WordPress.org support forum, which we check periodically. Users of the Pro version of SliceShow have access to support at [SliceShow Pro Support](http://plugins.slicenpress.com/support/)

= How do I add a slideshow to my posts or pages? =

Place the SliceShow shortcode in your pages or posts like `[sliceshow id="1"]` replacing the id value with the id of your slideshow. To find the shortcode, just click on SliceShow - All Slideshows in the WordPress admin menu. The shortcode for each slideshow is listed on this page.

= How do I add a slideshow to my theme's template? =

You can embed the slideshow via your templates using the php function `<?php if( function_exists( 'sliceshow_slideshow' ) ) { sliceshow_slideshow( 1 ); } ?>`. Replace the "1" with the id value of your slideshow.

== Screenshots ==

1. Slideshow overview page
2. SliceShow in action
3. SliceShow mobile size - images auto scale to fit
4. Add a new slideshow
5. Drag and drop reordering of slides
6. Settings panel - NOTE: Style Chooser is only available in the Pro version

== Changelog ==

= 1.1 =
* Added shortcode to sidebar of edit screen
* Switched js code from flexslider to cycle2. NOTE: If you've applied custom css you may need to update your class names for the new cycle2 code.

= 1.0 =
* Initial Release