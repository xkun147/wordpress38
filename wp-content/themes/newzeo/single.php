<?php get_header(); ?>
	
	<div class="row">
		<div class="twelvecol">
			<?php if (function_exists('breadcrumbs')) breadcrumbs(); ?>
		</div> <!-- twelvecol end -->
	</div> <!-- row end -->
	
	<div class="row">
		
		<div class="ninecol clearfix" id="main">
			<?php get_template_part('sidebar','left'); ?>
				<div class="rightcontent">
					<div id="content" role="main">
					<?php while ( have_posts() ) : the_post(); ?>
					
						<!-- Call content-single.php -->
						<?php get_template_part( 'content', 'single' ); ?>	
						
						<?php comments_template( '', true ); ?>
								
					<?php endwhile; // end of the loop. ?>
					</div>
				</div> <!-- content end -->
		</div> <!-- eightcol end -->
		
		<div class="threecol last" id="sidebar">
			<?php get_sidebar(); ?>
		</div> <!-- fourcol end -->
				
	</div> <!-- row end -->

<?php get_footer(); ?>