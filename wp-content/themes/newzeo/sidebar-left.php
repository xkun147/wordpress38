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
	<div class="leftcontent" role="complementary">

		<?php if ( ! dynamic_sidebar( 'sidebar-2' ) ) : ?>
				
			<?php the_widget('WP_Widget_Pages', array( 'title' => 'Page'),$sidebar_args ); ?> 
			<?php the_widget('WP_Widget_Archives',  array( 'title' => 'Archives', 'count' => 1),$sidebar_args); ?> 

		<?php endif; // end sidebar widget area ?>
			
	</div> <!-- leftcontent end -->