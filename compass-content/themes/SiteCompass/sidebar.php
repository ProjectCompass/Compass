<div class="row">
	<div class="small-12 columns">
		<?php the_search_form(); ?>
	</div>
</div>

<div class="row">
	<div class="small-12 columns">
		<h3>Postagens recentes</h3>
	</div>
	<div class="small-12 columns">
		<?php the_recent_posts(5); ?>
	</div>
</div>

<div class="row">
	<div class="small-12 columns">
		<h3>Categorias</h3>
	</div>
	<div class="small-12 columns">
		<?php the_tags(); ?>
	</div>
</div>
