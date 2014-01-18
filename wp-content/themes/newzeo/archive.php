<?php get_header(); ?>
	
	<div class="row">
		<div class="twelvecol">
			<?php if (function_exists('breadcrumbs')) breadcrumbs(); ?>
		</div> <!-- twelvecol end -->
	</div> <!-- row end -->
	
	<div class="row">
		<div class="eightcol" id="main">
		
				<div id="content" role="main">
					
					<?php if ( have_posts() ) : the_post(); ?>
					
						<!-- If author page -->
						<?php if (is_author()) : ?>
							<div id="author-info" class="clearfix">
								<div id="author-avatar">
									<?php echo get_avatar( get_the_author_meta( 'user_email' ), 80 ); ?>
								</div><!-- #author-avatar -->
								<div id="author-description">
									<h2 >About <?php echo get_the_author(); ?></h2>
								<?php the_author_meta( 'description' ); ?>
								<span> | </span>
								<span>Add my circles on Google+ : </span>
								<span itemprop="author">
									<a href="<?php the_author_meta( 'user_url' ); ?>?rel=author" rel="me">
									<?php echo get_the_author(); ?></a>
								</span>
								</div><!-- author-description end -->
							</div><!-- entry-author-info end -->
						<?php endif; ?>
						
						<?php rewind_posts(); ?>
						
						<!-- Call content.php -->
						<?php while ( have_posts() ) : the_post(); ?>				
							<?php get_template_part( 'content' ); ?>
						<?php endwhile; // end of the loop. ?>
					
					<?php else : ?>
					
						<!-- If no post --> 
						<article id="post-0" class="post no-results not-found">
							<header class="entry-header">
								<h1 class="entry-title">Nothing Found</h1>
							</header><!-- .entry-header -->
							<div class="entry-content">
								<p>Sorry, we can't find post you request. Please try search for a related post.</p>
								<?php get_search_form(); ?>
							</div><!-- .entry-content -->
						</article><!-- #post-0 -->

					<?php endif; ?>
					
					<nav class="paging">
						<?php if(function_exists('kriesi_pagination')) : kriesi_pagination(); else:  ?>
						<?php /* Bottom post navigation */ ?>
						<?php global $wp_query; $total_pages = $wp_query->max_num_pages; if ( $total_pages > 1 ) { ?>
							<div id="nav-below" class="navigation">
								<div class="nav-previous"><?php next_posts_link(__( '<span class="meta-nav">&laquo;</span> Older posts', 'newzeo' )) ?></div>
								<div class="nav-next"><?php previous_posts_link(__( 'Newer posts <span class="meta-nav">&raquo;</span>', 'newzeo' )) ?></div>
							</div><!-- #nav-below -->
						<?php } endif; ?>
					</nav> <!-- paging end -->

				</div> <!-- content end -->
				
		</div> <!-- eightcol end -->
		
		<div class="fourcol last" id="sidebar">
			<?php get_sidebar(); ?>
		</div> <!-- fourcol end -->
	</div> <!-- row end -->

<?php get_footer(); ?>