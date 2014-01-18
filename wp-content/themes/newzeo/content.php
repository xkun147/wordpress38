
	<!-- If in search, category, tag, archive page -->
	<?php if(!is_home()) : if ( is_search() || is_category() || is_tag() || is_archive() ) : ?>
		
		<article id="post-<?php the_ID(); ?>" class="clearfix" itemscope itemtype="http://schema.org/Article"> 	
			<header class="entry-header">
				<div class="entry-meta clearfix">
					
					<div class="datebox" >
						<a class="tooltip" rel="bookmark" title="<?php echo get_the_time(); ?>" href="<?php echo get_permalink(); ?>">
							<time class="entry-date updated" pubdate="" itemprop="dateCreated" datetime="<?php echo get_the_date( 'c' ) ?>"><?php echo get_the_date(); ?></time>
						</a>
					</div>
					
					<div class="authorbox">
						<span class="sep"> by </span>
						<span class="author vcard " >
							<a class="url tooltip fn" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="View all posts by <?php echo get_the_author(); ?>" rel="author">
								<span itemprop="author"><?php echo get_the_author(); ?></span>
							</a>
						</span>
					</div> 
					
					<div class="commentcountbox clearfix">
						<?php comments_popup_link( 'No comments yet', '1 comment', '% comments', 'comments-link', 'Comments are off for this post'); ?>
					</div> 
					
					<div class="socialsharebox">
						<?php social_button(); ?>
					</div> 
					
				</div> <!-- entry-meta end -->	
				
			</header> <!-- header end -->
			
			<div class="archive-content">
			
				<h1 class="entry-title" >
					<a class="tooltip" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark" itemprop="name"><?php the_title(); ?></a>
				</h1>
				
				<div class="entry-content" itemprop="description">
					<?php the_content('Continue reading <span class="meta-nav">&rarr;</span>'); ?>
					<?php wp_link_pages( array('before' => '<div class="page-link"> <span> Pages: </span>', 'after' => '</div>')); ?>
				</div> 
				
				<?php if (!is_search()) : ?>
					<footer class="entry-meta">
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
				<?php endif; ?>
				
			</div> <!-- archive-content end -->
		</article> <!-- article end -->
		
		<?php endif; endif; ?>
		
	<!-- If in home page -->
	<?php if ( is_home()) : ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
			<header class="entry-header">
			
				<div class="entry-meta" >
					<a class="tooltip" rel="bookmark" title="<?php echo get_the_time(); ?>" href="<?php echo get_permalink(); ?>">
						<time class="entry-date updated" pubdate="" itemprop="dateCreated" datetime="<?php echo get_the_date( 'c' ) ?>"><?php echo get_the_date(); ?></time>
					</a>
				</div>
				
				<h1 class="entry-title">
					<a class="tooltip" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark" itemprop="name"><?php the_title(); ?></a>
				</h1>
				
				<div class="entry-meta">
					<span class="sep"> by </span>
					<span class="author vcard">
						<a class="url tooltip fn" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="View all posts by <?php echo get_the_author(); ?>" rel="author">
							<span itemprop="author"><?php echo get_the_author(); ?></span>
						</a>
					</span>
				</div> <!-- entry-meta end -->
				
			</header> <!-- header end -->
				
			<div class="entry-content clearfix" itemprop="description">
				<?php the_content('Continue reading <span class="meta-nav">&rarr;</span>'); ?>
				<?php wp_link_pages( array('before' => '<div class="page-link"> <span> Pages: </span>', 'after' => '</div>')); ?>
			</div>
			
			<footer class="entry-meta">
				<span class="cat-links">
					<span>Posted in</span>
					<?php echo get_the_category_list(', '); ?>
				</span>
			</footer> <!-- footer end -->
			
		</article> <!-- article end -->
		
	<?php endif; ?>