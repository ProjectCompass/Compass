<?php get_header(); ?>

	<div id="capa">
		<div class="row">
			<div class="small-12 columns">
				<?php if (get_setting('layout_capa') != NULL): ?>
					<img src="<?php info_capa_url(); ?>" />
				<?php else: ?>
					<img src="<?php info_theme_url(); ?>/images/compass-capa.png" />
				<?php endif; ?>
			</div>
		</div>
	</div>

	<article id="content-home">
		<div class="row">
			<div class="small-12 columns">
				<?php the_content_home(); ?>
			</div>
		</div>
	</article>

	<article id="content-home-posts">
		<div class="row">
			<div class="small-8 columns">
				<div class="row">
					<div class="small-12 columns">
						<h3>Atualizações do blog</h3>
					</div>
					<div class="small-12 columns">
						<?php $query_conFig = array('limit'=>5, 'offset'=>0, 'order'=>'desc'); ?>
						<?php foreach (query_posts($query_conFig) as $post): ?>
							<div class="row post-box">
								<div class="small-3 columns">
									<?php the_screenshot($post, TRUE); ?>
								</div>
								<div class="small-9 columns">
									<h3><a href="<?php the_permalink($post); ?>" title="<?php the_title($post); ?>"><?php the_title($post); ?></a></h3>
									<span>Publicado por: <?php the_author($post); ?>, em: <?php the_time($post); ?></span>
									<p><?php the_excerpt($post,200); ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="small-12 columns">
						<a href="<?php info_base_url(); ?>blog" class="right">Ver todos os posts do blog</a> 
					</div>
				</div>
			</div>
			<div class="small-4 columns">
				<div class="row">
					<span class="small-6 columns advertiser">ANUNCIE AQUI!</span>
					<span class="small-5 columns advertiser">ANUNCIE AQUI!</span>
				</div>
				<br>
				<div class="row">
					<span class="small-5 columns advertiser">ANUNCIE AQUI!</span>
					<span class="small-6 columns advertiser">ANUNCIE AQUI!</span>
				</div>
				<br>
				<div class="row">
					<span class="small-6 columns advertiser">ANUNCIE AQUI!</span>
					<span class="small-5 columns advertiser">ANUNCIE AQUI!</span>
				</div>
			</div>
		</div>
	</article>

<?php get_footer(); ?>
