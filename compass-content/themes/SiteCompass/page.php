<?php get_header(); ?>

	<article id="content">
		<div class="row">
			<div class="small-12 columns main-page">
				<div class="row">
					<h2 class="page-title"><?php the_title(); ?></h2>
				</div>
				<div class="row">
					<div class="small-8 columns page-content">
						<p><?php the_content(); ?></p>
					</div>
					<div class="small-4 columns page-sidebar">
						<?php get_sidebar(); ?>
					</div>
				</div>
			</div>
		</div>
	</article>

<?php get_footer(); ?>
