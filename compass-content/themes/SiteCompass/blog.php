<?php get_header(); ?>

	<article id="content">
		<div class="row">
			<div class="small-12 columns">
				<h2 class="blog-title"><a href="<?php info_base_url(); ?>site/blog"><?php info_title(); ?> Blog</a> </h2>
			</div>
		</div>
		<div class="row">
			<div class="small-12 columns main-post">
				<?php $query_conFig = array(
										'pagination'=>'true', 
										'pagination_url'=>'blog/', 
										'pagination_segment'=>4,
										'pagination_prefix'=>'<ul class=\"pagination\">',
										'pagination_sufix'=>'</ul>',
										'pagination_show_links'=>TRUE,
										'pagination_prefix_item'=>'<li>',
										'pagination_sufix_item'=>'</li>',
										'pagination_prefix_item_active'=>'<li class=\"current\"><b>',
										'pagination_sufix_item_active'=>'</b></li>',
										'pagination_start'=>'Início',
										'pagination_show_prev_next'=>TRUE,
										'pagination_prev'=>'Anterior',
										'pagination_next'=>'Próximo',
										'pagination_show_first_last'=>TRUE,
										'pagination_first'=>'Primeiro',
										'pagination_last'=>'Último'
										); ?>
				<?php foreach (query_posts($query_conFig) as $post): ?>
					<div class="row">
						<h2 class="post-title"><a href="<?php the_permalink($post); ?>" title="<?php the_title($post); ?>"><?php the_title($post); ?></a></h2>
					</div>
					<div class="row">
						<div class="post-meta">
							<span>Publicado por: <?php the_author($post); ?>, em: <?php the_time($post); ?></span>
						</div>
					</div>
					<div class="row">
						<div class="small-8 small-centered columns post-content">
							<?php the_screenshot($post, TRUE); ?>
							<p><?php the_excerpt($post); ?></p>
							<a href="<?php the_permalink($post); ?>" class="button right">Leia mais...</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="row">
			<div class="small-12 columns">
				<?php pagination($query_conFig); ?>
			</div>
		</div>
	</article>

<?php get_footer(); ?>
