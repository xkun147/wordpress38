					
		<article id="post-id" class="post-class clearfix" itemscope itemtype="http://schema.org/Article">
			<header class="entry-header">
				<div class="entry-meta">
				
					<a class="tooltip" rel="bookmark" title="<?php echo get_the_time(); ?>" href="<?php echo get_permalink(); ?>">
						<time class="entry-date" pubdate="" itemprop="dateCreated" datetime="<?php echo get_the_date( 'c' ) ?>"><?php echo get_the_date(); ?></time>
					</a> 
					
					<h1 class="entry-title" itemprop="name"><?php the_title(); ?></h1> 
					
					<span class="sep"> by </span>
					<span class="author vcard">
						<a class="url tooltip fn" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="View all posts by <?php echo get_the_author(); ?>"><span itemprop="author"><?php echo get_the_author(); ?></span>
						</a>
					</span> 
						
				</div> <!-- entry-meta end -->
			</header> <!-- header end -->	

			<div class="entry-content" itemprop="description">
				<?php the_content(); ?>
				<div class="clearfix"></div>
				<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'newzeo' ) . '</span>', 'after' => '</div>' ) ); ?>	
				
				<?php 
				$displayrelatedpost = 'yes'; 
				$options = get_option('newzeo_theme_options'); 
				$displayrelatedpost = $options['relatedinput']; 
				if ( $displayrelatedpost == 'yes' ) : 
					echo '<div class="related-post clearfix">';
					display_related_posts(4);
					echo '</div>';
				else: endif; ?>

			</div> <!-- entry-content end -->
			
			<footer class="entry-meta">
			
				<div class="socialshareboxsingle clearfix">
					Share this post, let the world know: <?php social_button();?>
				</div>
				
				<span class="cat-links">
					<span>Posted in</span>
					<?php echo get_the_category_list(', '); ?>
				</span>
				
				<span class="sep"> | </span>
				
				<span class="tag-links">
					<span>Tagged</span>
					<?php echo get_the_tag_list('',', ',''); ?>
				</span>
				
			</footer> <!-- footer end -->
			
			<?php edit_post_link('Edit', '<span class="edit-link">', '</span>'); ?>
			
			<?php if ( get_the_author_meta( 'description' ) && ( ! function_exists( 'is_multi_author' ) || is_multi_author() ) ) : 
			/* If a user has filled out their description and this is a multi-author blog, show a bio on their entries */ ?>	
			<div id="author-info">
			
				<div id="author-avatar">
					<?php echo get_avatar( get_the_author_meta( 'user_email' ), 80 ); ?>
				</div>
				
				<div id="author-description">
					<h2 >About <?php echo get_the_author(); ?></h2>
					<?php the_author_meta( 'description' ); ?>
					<span> | </span>
					<span>Add my circles on Google+ : </span>
					<span itemprop="author"><a href="<?php the_author_meta( 'user_url' ); ?>?rel=author" rel="me"><?php echo get_the_author(); ?></a></span>
					<div id="author-link">
						<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
						View all posts by <?php echo get_the_author(); ?><span class="meta-nav">&rarr;</span>
						</a>
					</div>
				</div><!-- #author-description -->
				
				</div><!-- #entry-author-info -->
				
				<?php endif; ?>
				
		</article> <!-- article end -->	
				
