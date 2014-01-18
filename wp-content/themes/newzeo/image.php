<?php get_header(); ?>

	<div class="row">
		<div class="twelvecol">
			<?php if (function_exists('breadcrumbs')) breadcrumbs(); ?>
		</div>
	</div>
	
	<div class="row">
		<div class="twelvecol">
			<?php while ( have_posts() ) : the_post(); ?>
			 
			<article id="post-id" class="post-class clearfix attachment-image" itemscope itemtype="http://schema.org/Article">
			
				<header class="entry-header">
					<div class="entry-meta">
					
						<a class="tooltip" rel="bookmark" title="<?php echo get_the_time(); ?>" href="<?php echo get_permalink(); ?>">
							<time class="entry-date" pubdate="" itemprop="dateCreated" datetime="<?php echo get_the_date( 'c' ) ?>"><?php echo get_the_date(); ?></time>
						</a>
						
						<span class="sep"> | by </span>
						
						<span class="author vcard">
							<a class="url tooltip fn" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="View all posts by <?php echo get_the_author(); ?>"><span itemprop="author"><?php echo get_the_author(); ?></span>
							</a>
						</span>
						
						<h1 class="entry-title" itemprop="name"><?php the_title(); ?></h1>
							
					</div>
				</header>

				<div class="entry-content" itemprop="description">
					
					<div class="entry-attachment">
						
						<!-- Show full size image attachment -->
						<p class="aligncenter ">
							<?php echo wp_get_attachment_image( $post->ID, 'full' ); ?>
							<?php  if ( !empty($post->post_excerpt) ) the_excerpt(); the_title(); ?>
						</p>
						
					</div>
					
					<div class="clearfix"></div>	
					
					<!-- Attachment thumbnail gallery -->
					<div class="attachment-thumbnail-bottom"><?php attachmentgallery(thumbnail,'0','0') ?></div>
					
					<div class="clearfix"></div>
					
				</div> <!-- entry-content end -->
				
				<footer class="entry-meta">
					<div class="socialshareboxsingle clearfix">
						<p class="alignleft">Share this post, let the world know: <?php social_button();?></p>
					</div>
				</footer>
				
				<?php edit_post_link('Edit', '<span class="edit-link">', '</span>'); ?>
			
			</article> <!-- article end -->	
			
			<?php comments_template( '', true ); ?>
		
		<?php endwhile; // end of the loop. ?>
		
		</div> <!-- twelvecol end -->
		
	</div> <!-- row end -->
	
<?php get_footer(); ?>