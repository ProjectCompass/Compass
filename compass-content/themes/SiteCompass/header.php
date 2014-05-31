<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title><?php info_title(TRUE); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php info_foundation_url(); ?>">
	<link rel="stylesheet" type="text/css" href="<?php info_stylesheet_url(); ?>">
	<link rel="stylesheet" type="text/css" href="<?php info_font_awesome_url(); ?>">
	<script type="text/javascript" src="<?php info_jquery_url(); ?>"></script>
	<?php the_header(); ?>
</head>
<body>
	<nav class="top-bar" data-topbar>
	  	<ul class="title-area">
	    	<li class="name">
	      		<h1><a href="<?php info_base_url(); ?>"><?php info_title(); ?></a></h1>
	    	</li>
	    	<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	  	</ul>
	  	<section class="top-bar-section">
	    	<?php
	    		$config = array('menu_open'=>'<ul class=\"right\">', 'menu_close'=>'</ul>');
	    		get_menu($config);
	    	?>
	  	</section>
	</nav>

	<header>
		<div class="row">
			<div class="small-12 medium-12 large-6 columns">
				<h1><?php info_title(); ?></h1>
				<span><?php info_description(); ?></span>
			</div>
			<div class="small-12 medium-12 large-6 hide-for-small columns">
				<a href="<?php get_network_url('email'); ?>" class="social-network" title="Contatos"><i class="fa fa-envelope email"></i></a>
				<a href="<?php get_network_url('facebook'); ?>" class="social-network" title="Facebook"><i class="fa fa-facebook-square facebook"></i></a>
				<a href="<?php get_network_url('twitter'); ?>" class="social-network" title="Twitter"><i class="fa fa-twitter-square twitter"></i></a>
				<a href="<?php get_network_url('instagram'); ?>" class="social-network" title="Instagram"><i class="fa fa-instagram instagram"></i></a>
				<a href="<?php get_network_url('plus'); ?>" class="social-network" title="Google+"><i class="fa fa-google-plus plus"></i></a>
				<a href="<?php get_network_url('dropbox'); ?>" class="social-network" title="Dropbox"><i class="fa fa-dropbox dropbox"></i></a>
				<a href="<?php get_network_url('github'); ?>" class="social-network" title="Github"><i class="fa fa-github github"></i></a>
				<a href="<?php get_network_url('linkedin'); ?>" class="social-network" title="Linkedin"><i class="fa fa-linkedin-square linkedin"></i></a>
				<a href="<?php get_network_url('vimeo'); ?>" class="social-network" title="Vimeo"><i class="fa fa-vimeo-square vimeo"></i></a>
				<a href="<?php get_network_url('youtube'); ?>" class="social-network" title="youtube"><i class="fa fa-youtube-square youtube"></i></a>
			</div>
		</div>
	</header>