<?php defined('BASEPATH') OR exit('No direct script access allowed');

echo '<!DOCTYPE html>';
echo '<html lang="pt-br">';
echo '<head>';
    echo '<meta charset="utf-8">';
    echo '<title>';
        echo (isset($title)) ? '{title} | {title_default}' : '{title_default}';
    echo '</title>';
    echo '{headerinc}';
    $style = NULL;
    if (get_setting('aparence_active_css') == 1) $style .= get_setting('aparence_css');
    if (get_setting('aparence_background_color') != NULL) $style .= 'body{background-color:'.get_setting('aparence_background_color').' !important;}';
    if (get_setting('aparence_background_image') != NULL) $style .= 'body{background-image:url("'.get_setting('aparence_background_image').'") !important;}';
    echo '<style type="text/css">'.$style.get_css_theme().'</style>';
echo '</head>';
echo '<body>';
?>
<div class="off-canvas-wrap" data-offcanvas>
    <div class="inner-wrap">
        <?php get_the_top_bar(); ?>

        <aside class="left-off-canvas-menu hide-for-large-up">
            <?php get_the_menu(); ?>

        </aside>

        <aside class="right-off-canvas-menu">
            <ul class="off-canvas-list">
                <li><label>Users</label></li>
                <li><a href="#">Hari Seldon</a></li>
                <li><a href="#">...</a></li>
            </ul>
        </aside>

        <section class="main-section">
            <div class="row">
                <div id="menu" class="show-for-large-up large-2 columns">
                    <?php get_the_menu(); ?>
                </div>
                <div id="content" class="large-10 columns">
                    {content}
                </div>
            </div>
            <div id="footer" class="small-12 columns">
                <div class="row">
                    Orgulhosamente produzido com <a href="https://github.com/ProjectCompass/Compass"><i class="fa fa-compass"></i> Compass</a>!
                </div>
            </div>
        </section>

        <a class="exit-off-canvas"></a>

    </div>
</div>
        
<?php
echo '{footerinc}';
echo '</body>';
echo '</html>';