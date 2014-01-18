<?php 
require_once ( get_stylesheet_directory() . '/include/theme-options.php' );
require_once ( get_stylesheet_directory() . '/include/breadcrumbs.php' );
require_once ( get_stylesheet_directory() . '/include/widget.php' );

add_theme_support( 'admin-bar' );
add_theme_support( 'automatic-feed-links' );
add_editor_style('css/editor-style.css');

register_nav_menus( array( 'primary' => 'Primary Navigation' ) );

if ( ! isset( $content_width ) ) $content_width = 600;

/* 	=============================================== */
/*	Admin CSS
/* 	=============================================== */
function admin_register_head() {
    $siteurl = get_option('siteurl');
    $url = $siteurl . '/wp-content/themes/' . basename(dirname(__FILE__)) . '/css/admin.css';
    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}
add_action('admin_head', 'admin_register_head');

/* 	=============================================== */
/*	Function post_thumbnails
/* 	=============================================== */

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
}

if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'sidebar-thumb', 60, 60, true ); //(cropped)
	add_image_size( 'related-thumb', 80, 80, true ); //(cropped)
}

/* 	=============================================== */
/*	Function to display custom header image
/* 	=============================================== */

define('NO_HEADER_TEXT', true );
define('HEADER_TEXTCOLOR', '');
define('HEADER_IMAGE', '%s/images/rumput.jpg'); // %s is the template dir uri
define('HEADER_IMAGE_WIDTH', 1130); // use width and height appropriate for your theme
define('HEADER_IMAGE_HEIGHT', 200);

function header_style() {
    ?><style type="text/css">
        #header {
            background: url(<?php header_image(); ?>);
        }
    </style><?php
}

// gets included in the admin header
function admin_header_style() {
    ?><style type="text/css">
        #headimg {
            width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
            height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
            background: no-repeat;
        }
    </style><?php
}

add_custom_image_header('header_style', 'admin_header_style');


/* 	=============================================== */
/* Adds Custom Widget
/* 	=============================================== */

// register Custom widget
add_action( 'widgets_init', create_function( '', 'register_widget( "multicol_widget" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "social_widget" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "recentpost_widget" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "randompost_widget" );' ) );

/* 	=============================================== */
/*	Function to register sidebar 
/*	=============================================== */
function newzeo_widgets_init() {

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'newzeo' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget clearfix %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Left Sidebar', 'newzeo' ),
		'id' => 'sidebar-2',
		'description' => __( 'Sidebar left, just show on homepage', 'newzeo' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
}

add_action( 'widgets_init', 'newzeo_widgets_init' );


/* 	=============================================== */
/*	Function to show custom comments & pingback
/*	=============================================== */
function newzeo_comments($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
    $GLOBALS['comment_depth'] = $depth;
  ?>
    <li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
        <div class="comment-author vcard"><?php commenter_link() ?></div>
        <div class="comment-meta">
			<?php printf(__('Posted <span itemprop="commentTime">%1$s</span> at %2$s <span class="meta-sep">|</span> <a class="tooltip" href="%3$s" title="Permalink to this comment">Permalink</a>', 'your-theme'),
                    get_comment_date(),
                    get_comment_time(),
                    '#comment-' . get_comment_ID() );
                    edit_comment_link(__('Edit', 'newzeo'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
  <?php if ($comment->comment_approved == '0') _e("\t\t\t\t\t<span class='unapproved'>Your comment is awaiting moderation.</span>\n", 'newzeo') ?>
          <div class="comment-content" itemprop="commentText">
            <?php comment_text() ?>
        </div>
        <?php // echo the comment reply link
            if($args['type'] == 'all' || get_comment_type() == 'comment') :
                comment_reply_link(array_merge($args, array(
                    'reply_text' => __('Reply <span>&darr;</span>','newzeo'),
                    'login_text' => __('Log in to reply.','newzeo'),
                    'depth' => $depth,
                    'before' => '<div class="comment-reply-link">',
                    'after' => '</div>'
                )));
            endif;
        ?>
<?php } // end custom_comments

// Custom callback to list pings
function custom_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
        ?>
            <li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
                <div class="comment-author"><?php printf(__('By %1$s on %2$s at %3$s', 'your-theme'),
                        get_comment_author_link(),
                        get_comment_date(),
                        get_comment_time() );
                        edit_comment_link(__('Edit', 'your-theme'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
    <?php if ($comment->comment_approved == '0') _e('\t\t\t\t\t<span class="unapproved">Your trackback is awaiting moderation.</span>\n', 'your-theme') ?>
            <div class="comment-content">
                <?php comment_text() ?>
            </div>
<?php } // end custom_pings

// Produces an avatar image with the hCard-compliant photo class
function commenter_link() {
    $commenter = get_comment_author_link();
    if ( ereg( '<a[^>]* class=[^>]+>', $commenter ) ) {
        $commenter = ereg_replace( '(<a[^>]* class=[\'"]?)', '\\1url ' , $commenter );
    } else {
        $commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );
    }
    $avatar_email = get_comment_author_email();
    $avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $avatar_email, 50 ) );
    echo ' <div class="comment-avatar">' . $avatar . ' </div><div class="fn n" itemprop="creator">' . $commenter . '</div>';
} // end commenter_link

/* 	=============================================== */
/*	Function to show attachment thumbnail
/*	=============================================== */
function attachmentgallery($size = 'thumbnail', $limit = '0', $offset = '0') {

	global $post;

	$images = get_children( array('post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );

	if ($images) {

		$num_of_images = count($images);

		if ($offset > 0) : $start = $offset--; else : $start = 0; endif;
		if ($limit > 0) : $stop = $limit+$start; else : $stop = $num_of_images; endif;

		$i = 0;
		foreach ($images as $image) {
			if ($start <= $i and $i < $stop) {
			$img_title = $image->post_title;   /*title.*/
			$img_description = $image->post_content; /*description.*/
			$img_caption = $image->post_excerpt; /*caption.*/
			$img_size = wp_get_attachment_image_src( $image->ID, 'thumbnail' ); /* image size */
			$img_url = wp_get_attachment_url($image->ID); /*url of the full size image.*/
			$post_title = get_the_title($image->post_parent);
			$post_url = get_permalink($post->ID); /*url of the post.*/
			$attachment_url = get_attachment_link($image->ID); /*url of the atttachment.*/
			$preview_array = image_downsize( $image->ID, 'thumbnail' ); /*thumbnail or medium or large image to use for preview.*/
			$img_preview = $preview_array[0];

			/* 	This is where you'd create your custom image/link/whatever tag using the variables above.
			This is an example of a basic image tag using this method. */?>
			
			<a href="<?php echo $attachment_url; ?>">
			<img class="thumbgallery" src="<?php echo $img_preview; ?>" alt="<?php echo $img_title; ?>" title="<?php echo $img_title; ?>" />
			</a>
			
			<?php
			/* ============ End custom image tag. Do not edit below here. ========== */

			}
			$i++;
		}

	}
}

/* 	=============================================== */
/*	Function to multiple column category
/*	=============================================== */
function newzeo_multicol_cat() {
	$catArray = explode("</li>",wp_list_categories('title_li=&echo=0&depth=1'));
	$catCount = count($catArray) - 1;
	$catColumns = round($catCount / 2);
	$twoColumns = round($catColumns + $catColumns);   
	$catLeft = ''; $catRight = '';	
	
	for ($i=0;$i<$catCount;$i++) {
					
	if ($i<$catColumns){
		$catLeft = $catLeft.''.$catArray[$i].'</li>';
	}
	elseif ($i>=$catColumns){
		$catRight = $catRight.''.$catArray[$i].'</li>';
	}
	}; 

	echo '<ul class="catleft">'.$catLeft.'</ul>';
	echo '<ul class="catright">'.$catRight.'</ul>';			
}

/* 	=============================================== */
/*	Function to display spesific char in homepage
/* 	=============================================== */

function sidebartrimword() {
$temp_arr_content = explode(" ",substr(strip_tags(get_the_content()),0,130)); 
$temp_arr_content[count($temp_arr_content)-1] = ""; 
$display_arr_content = implode(" ",$temp_arr_content); 
echo $display_arr_content; 
if(strlen(strip_tags(get_the_content())) > 130) echo "[...]"; 
}

function metadesctrimword() {
$temp_arr_content = explode(" ",substr(strip_tags(get_the_content()),0,200)); 
$temp_arr_content[count($temp_arr_content)-1] = ""; 
$display_arr_content = implode(" ",$temp_arr_content); 
echo $display_arr_content; 
if(strlen(strip_tags(get_the_content())) > 200) echo "..."; 
}

/* 	=============================================== */
/*	Function to show recent post with thumbnail
/*	=============================================== */
function display_recent_posts( $rpsize /* show xx post */ ) {
	// Query arguments
	$recent_args = array(
		'posts_per_page' => $rpsize,
		'ignore_sticky_posts'=>1,
		'orderby' => 'id',
		'order' => 'DESC'
		
	);
 
	// The query
	$recent_posts = new WP_Query( $recent_args );
 
	// The loop
	while ( $recent_posts->have_posts() ) : $recent_posts->the_post();
		echo '<li class="clearfix">';
		echo '<h2><a class="tooltip" href="' . get_permalink( get_the_ID() ) . '" title="'.get_the_title().'">' . get_the_title() . '</a></h2>';
		the_post_thumbnail('sidebar-thumb',array('class' => 'alignleft'));
		echo sidebartrimword();
		echo '</li>';
	endwhile;
 
	// Reset post data
	wp_reset_postdata();
}

/* 	=============================================== */
/*	Function to show related post with thumbnail
/*	=============================================== */

function display_related_posts( $relsize /* show xx post */ ) {

$categories = get_the_category();
if ($categories) {
	$category_ids = array();
	foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;

	$args=array(
		'category__in' => $category_ids,
		/*'post__not_in' => array($post->ID), */
		'posts_per_page'=> $relsize,  
		'ignore_sticky_posts'=>1,
		'orderby' => 'rand'
	);
	$related_post = new wp_query($args);
	if( $related_post->have_posts() ) {
		echo '<h3>You might also like these post : </h3><ul>';
		while ($related_post->have_posts()) {
			$related_post->the_post();
			echo '<li class="clearfix">';
			the_post_thumbnail('related-thumb',array('class' => 'alignleft'));
			echo '<a class="tooltip" href="'.get_permalink( get_the_ID()).'" title="'.get_the_title().'" >'.get_the_title().'</a></li>';
		}
		echo '</ul>';
		}
	}
	
	// Reset post data
	wp_reset_postdata();
}

/* 	=============================================== */
/*	Function to show random posts
/*	=============================================== */
function display_random_posts ($rndsize /*show xx post */) {
	$random_args = array(
		'posts_per_page' => $rndsize,
		'ignore_sticky_posts'=>1,
		'orderby' => 'rand'
	);
					 
	// The query
	$random_posts = new WP_Query( $random_args );
					 
	// The loop
	while ( $random_posts->have_posts() ) : $random_posts->the_post();
		echo '<li>';
		echo '<h2><a class="tooltip" href="'.get_permalink( get_the_ID() ).'" title="'.get_the_title().'">'.get_the_title().'</a></h2>';
		echo '</li>';
	endwhile;
					 
	// Reset post data
	wp_reset_postdata();
}

/* 	=============================================== */
/*	Function to show social button & social icon sidebar
/*	=============================================== */
function social_button() {
?> 
<ul>
	<!-- Facebook share -->
	<li><div class="fb-like" data-href="<?php echo get_permalink(); ?>" data-send="false" data-layout="button_count" data-width="50" data-show-faces="false" data-font="lucida grande"></div></li>
	<!-- Twitter share -->
	<li><a href="<?php echo 'https://twitter.com/share'; ?>" class="twitter-share-button" data-count="horizontal" data-url="<?php echo get_permalink(); ?>" data-text="<?php the_title(); ?>" data-via="<?php $options = get_option('newzeo_theme_options'); echo $options['twitid']; ?>">Tweet</a></li>
	<!-- G+ Share -->
	<li><div class="g-plusone" data-size="medium" data-annotation="bubble" data-href="<?php echo get_permalink(); ?>"></div></li>
</ul>
<?php 
}

function social_sidebar() {
	echo '<ul class="clearfix">';
	echo '<li class="socialshare-1">';
	echo '<a class="tooltip" href="'. $options = get_option('newzeo_theme_options');
	echo $options['rss'].'" title="Subscribe to Zeospot.com" target="_blank">RSS</a></li>';
	echo '<li class="socialshare-2"><a class="tooltip" href="'.$options = get_option('newzeo_theme_options');
	echo 'http://www.twitter.com/'.$options['twitid'].'" title="Follow Zeospot Twitter" target="_blank">Twitter</a></li>';
	echo '<li class="socialshare-3"><a class="tooltip" href="'.$options = get_option('newzeo_theme_options');
	echo $options['facebook'].'" title="Like Zeospot.com Facebook fans page" target="_blank">FB</a></li></ul>';
}

/* 	=============================================== */
/*	Function to show pagination 
/*	=============================================== */

function kriesi_pagination($pages = '', $range = 1)
{  
     $showitems = ($range * 2)+1;  

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
         echo "<div class='pagination'><span>Page: </span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
         echo "</div>\n";
     }
}

function register_my_menus() {     
	register_nav_menus(array( 'header-menu' => __( 'Header Menu' ) ) );
}
add_action( 'init', 'register_my_menus' );


//Insert FlexSlider plugin
function flexslider2() {
    wp_enqueue_script( 'flexslider', get_stylesheet_directory_uri() . '/flexslider/jquery.flexslider-min.js', array('jquery') );
    wp_enqueue_script( 'flexslider-option', get_stylesheet_directory_uri() . '/flexslider/flexslider-option.js', array('jquery') );
    wp_enqueue_style( 'flexslider-css', get_stylesheet_directory_uri() . '/flexslider/flexslider.css');
}
add_action( 'wp_enqueue_scripts', 'flexslider2' );


?>