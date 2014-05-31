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
                                        'pagination'=>FALSE, 
                                        'limit'=>10000
                                        ); ?>
                <?php foreach (query_posts($query_conFig) as $post): ?>
                    <div class="row">
                        <div class="small-4 columns">
                            <?php the_screenshot($post, TRUE); ?>
                        </div>
                        <div class="small-8 columns">
                            <h2 class="post-title"><a href="<?php the_permalink($post); ?>" title="<?php the_title($post); ?>"><?php the_title($post); ?></a></h2>
                            <div class="post-meta">
                                <span>Publicado por: <?php the_author($post); ?>, em: <?php the_time($post); ?></span>
                            </div>
                            <p><?php the_excerpt($post); ?></p>
                            <a href="<?php the_permalink($post); ?>" class="button tiny right">Leia mais...</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </article>

<?php get_footer(); ?>
