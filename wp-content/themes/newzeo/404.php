<?php get_header(); ?>
	
	<div class="row">
	
		<div class="twelvecol" id="main" role="main">
		
			<article id="post-0" class="post error404 not-found">
				<header class="entry-header">
					<div class="bigfont">Oops 404</div>
					<h1 class="entry-title"><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'newzeo' ); ?></h1>
				</header>

				<div class="entry-content">
					<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.', 'newzeo' ); ?></p>

					<?php get_search_form(); ?>

					<?php the_widget( 'WP_Widget_Recent_Posts', array( 'number' => 10 ), array( 'widget_id' => '404' ) ); ?>

				</div><!-- .entry-content -->
			</article><!-- #post-0 -->
				
		</div> <!-- four-o-four end -->
		
	</div> <!-- row end -->

<?php get_footer(); ?>