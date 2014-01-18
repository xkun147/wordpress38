	<?php 
		// Sidebar arguments, should be same as in functions.php
		$sidebar_args = array(
		'before_widget' => '<aside class="widget clearfix">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	);
	?>
	
		<!-- If theme widget empty, show widget below -->
		<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
				
				<!-- Social default widget -->
				<aside id="social-widget" class="widget clearfix">
					<h3 class="widget-title">Subscribe to zeospot</h3>
					<ul class="clearfix">
						<li class="socialshare-1">
							<a class="tooltip" href="<?php $options = get_option('newzeo_theme_options'); echo $options['rss']; ?>" title="Subscribe to Zeospot.com" target="_blank">RSS</a>
						</li>
						<li class="socialshare-2"><a class="tooltip" href="<?php $options = get_option('newzeo_theme_options'); echo 'http://www.twitter.com/'.$options['twitid']; ?>" title="Follow Zeospot Twitter" target="_blank">Twitter</a></li>
						<li class="socialshare-3"><a class="tooltip" href="<?php $options = get_option('newzeo_theme_options'); echo $options['facebook']; ?>" title="Like Zeospot.com Facebook fans page" target="_blank">FB</a></li>
					</ul>
				</aside>
				
				<!-- Multi column category default widget -->
				<aside id="multiple-category" class="widget clearfix">
					<h3 class="widget-title">Category</h3>
						<?php newzeo_multicol_cat(); ?>
				</aside>
				
				<!-- Meta default widget -->
				<?php the_widget('WP_Widget_Meta', array( 'title' => 'Meta'),$sidebar_args); ?> 
				
				<!-- Show only on single -->
				<?php if (!is_home()): ?>
				
				<!-- Recent post default widget -->
				<aside id="recent-post" class="widget">
					<h3 class="widget-title">Recent home design article</h3>
						<ul><?php display_recent_posts(5); ?></ul>
				</aside>
				
				<!-- Random post default widget -->
				<aside class="widget" id="random-post">
					<h3 class="widget-title">Random Post </h3>
						<ul><?php display_random_posts(5); ?></ul>
				</aside>
				<?php endif; ?>
						
		<?php endif; // end sidebar widget area ?>
				