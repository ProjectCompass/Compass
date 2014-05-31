<?php get_header(); ?>

	<article id="content">
		<div class="row">
			<div class="small-12 columns">
				<h2 class="blog-title"><a href="<?php info_base_url(); ?>site/blog"><?php info_title(); ?> Blog</a> </h2>
			</div>
		</div>
		<div class="row">
			<div class="small-12 columns main-post">
				<div class="row">
					<h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
				</div>
				<div class="row">
					<div class="post-meta">
						<span>Publicado por: <?php the_author(); ?>, em: <?php the_time(); ?></span>
					</div>
				</div>
				<div class="row">
					<div class="small-8 small-centered columns post-content">
						<p><?php the_content(); ?></p>
					</div>
				</div>
			</div>
		</div>
	</article>

<?php get_footer(); ?>
