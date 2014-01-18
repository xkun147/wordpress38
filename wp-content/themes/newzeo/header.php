<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>

<meta charset="<?php bloginfo( 'charset' ); ?>" />

<!-- Use the .htaccess and remove these lines to avoid edge case issues. More info: h5bp.com/b/378 -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<!-- Mobile viewport optimized: j.mp/bplateviewport -->
<meta name="viewport" content="width=device-width,initial-scale=1">

<title>
	<?php 
	global $page, $paged;    
	wp_title( '|', true, 'right' ); 
	bloginfo( 'name' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
	echo " | $site_description";    
	if ( $paged >= 2 || $page >= 2 )
	echo ' | ' . sprintf( __( 'Page %s', 'newzeo' ), max( $paged, $page ) );?>
</title> 

<!-- Wordpress Pingback -->
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" /> 

<!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->
<!-- Favicon disabled by default, uncomment code below to enable favicon -->
<!-- <link rel="shortcut icon" href="<?php //echo get_template_directory_uri(); ?>/favicon.ico"> -->

<!-- Profile -->
<link rel="profile" href="http://gmpg.org/xfn/11" />

<!-- If IE version lower than IE 9 -->
<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/modernizr-2.0.6.min.js"></script>
<![endif]-->

<!-- Google font -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

<!-- CSS concatenated and minified via ant build script-->
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/1140.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/tipTip.css" />
<!--[if gte IE 7]> <link rel="stylesheet" media="all" href="<?php echo get_template_directory_uri(); ?>/css/ie.css" /> <![endif]-->
<!--[if lt IE 8]> <link rel="stylesheet" media="all" href="<?php echo get_template_directory_uri(); ?>/css/oldie.css" /> <![endif]-->

<!-- Dark color scheme option -->
<?php 
$colorscheme = 'yes'; 
$options = get_option('newzeo_theme_options'); 
$colorscheme = $options['colorschemeinput']; 
if ( $colorscheme == 'dark' ) : 
echo '<link rel="stylesheet" type="text/css" media="all" href="'.get_template_directory_uri().'/css/dark.css" />';
elseif ( $colorscheme != 'dark' ) :
echo ''; 
endif; ?>

<?php if ( is_singular() && get_option( 'thread_comments' ) )
	wp_enqueue_script( 'comment-reply' );
 ?>

<?php wp_head(); ?>

</head>

<body <?php body_class('newzeo'); ?> itemscope itemtype="http://schema.org/WebPage">

<div class="container">

		<div class="rowheader">
			<div class="twelvecol">
				<header>
					<nav id="topmenu" role="navigation">
						<?php //wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
						<span class="search-form"><?php get_search_form(); ?></span>
						
					</nav>
				</header>
			</div> 
			<!-- twelve col end -->
		</div> 
		<!-- row end -->

		<div class="row">
		
			<!-- <div class="sixcol">
				<header class="clearfix">
					<hgroup id="logo">
						<h1 id="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
								<?php bloginfo( 'name' ); ?>
							</a>
						</h1>
						<h2 id="site-description">
							<?php bloginfo( 'description' ); ?>
						</h2>
					</hgroup>
				</header>
			</div>  -->
			<!-- sixcol end -->
			
			<!-- <div class="sixcol last">
				<div id="searchbox">
				<?php get_search_form(); ?>
				</div> --> 
				<!-- searchbox end -->
			<!-- </div>  -->
			<!-- sixcol last end -->
			
			<?php if (get_header_image() != '') : ?>
			<div class="twelvecol headerimage">
				<a href="<?php echo get_settings('home');?>"><img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" />
				</a>
			</div>
			
			<?php endif; ?>
		</div> <!-- row end -->

