<?php get_header(); ?>
	
	<div class="row">
		<div class="ninecol clearfix" id="main">

		<?php get_template_part('sidebar','left'); ?>
		
			<div class="rightcontent">
				<?php do_action('slideshow_deploy', '67'); ?>
				<div id="content" role="main">
					
					<?php if ( have_posts() ) : ?>
					
						<!-- Call content.php -->
						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'content', '' ); ?>
						<?php endwhile; ?>
					
					<?php else : ?>
					
						<article id="post-0" class="post no-results not-found">
							<header class="entry-header">
								<h1 class="entry-title">Nothing Found</h1>
							</header>
							<div class="entry-content">
								<p>Sorry, we can't find post you request. Please try search for a related post.</p>
								<?php get_search_form(); ?>
							</div>
						</article>

					<?php endif; ?>
			
					<nav class="paging">
						<?php if(function_exists('kriesi_pagination')) : kriesi_pagination(); else:  ?>
						<?php global $wp_query; $total_pages = $wp_query->max_num_pages; if ( $total_pages > 1 ) { ?>
							<div id="nav-below" class="navigation">
								<div class="nav-previous"><?php next_posts_link(__( '<span class="meta-nav">&laquo;</span> Older posts', 'newzeo' )) ?></div>
								<div class="nav-next"><?php previous_posts_link(__( 'Newer posts <span class="meta-nav">&raquo;</span>', 'newzeo' )) ?></div>
							</div>
						<?php } endif; ?>
					</nav>
						
				</div> <!-- content end -->
				
			</div> <!-- rightcontent end -->
			
		</div> <!-- ninecol end -->
		
		<div class="threecol last" id="sidebar">
			<?php get_sidebar(); ?>
		</div> <!-- threecol end -->
		
	</div> <!-- row end -->
	
<?php get_footer(); ?>