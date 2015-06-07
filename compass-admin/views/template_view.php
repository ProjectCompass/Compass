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
        <nav class="top-bar docs-bar" data-topbar="" role="navigation">
            <ul class="title-area">
                <li class="name">
                    <h1><a href="<?php echo base_url(); ?>"><i class="fa fa-compass"></i><span>Compass</span></a></h1>
                </li>
                <li class="toggle-topbar menu-icon"><a href=""><span>Menu</span></a></li>
            </ul>
            <section class="top-bar-section">
                <ul id="top-bar-user" class="right">
                    <li class="has-dropdown">
                        <?php
                        $iduserbe = (get_session('user_id')) ? get_session('user_id') : '0';
                        $infouser = $this->users->get_by_id($iduserbe)->row();
                        ?>
                        <a href="#">Olá, Rodrigo Sousa <img id="top-bar-img-user-small" src="<?php echo avatar(get_usermeta('user_image', get_session('user_id')), 160, 160, FALSE); ?>" /></a>
                        <ul class="dropdown">
                            <li id="img-profile"><a href="<?php echo base_url('users/profile/'.$infouser->user_id.'') ?>">
                                <img id="top-bar-img-user-large" src="<?php echo avatar(get_usermeta('user_image', get_session('user_id')), 160, 160, FALSE); ?>" /></a>
                            </li>
                            <li><a href="<?php echo base_url('users/profile/'.$infouser->user_id.'') ?>">Rodrigo Sousa</a></li>
                            <li><a href="<?php echo base_url('users/update/'.$infouser->user_id.'') ?>">Editar meu perfil</a></li>
                            <li><a href="<?php echo base_url('login/logoff'); ?>">Sair</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="left">
                    <li class="has-dropdown">
                        <a href="#"><?php print get_setting('general_title_system'); ?></a>
                        <ul class="dropdown">
                            <li><a href="<?php echo base_url(); ?>">Visitar página inicial</a></li>
                            <li><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                            <li><a href="<?php echo base_url('dashboard/info'); ?>">Informações do sistema</a></li>
                        </ul>
                    </li>
                </ul>
            </section>
        </nav>

        <aside class="left-off-canvas-menu">
            <ul class="off-canvas-list">
                <li><label>Foundation</label></li>
                <li><a href="#">The Psychohistorians</a></li>
                <li><a href="#">...</a></li>
            </ul>
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
                <div id="menu" class="show-for-large large-2 columns">
                    <?php get_the_menu(); ?>
                </div>
                <div id="content" class="large-10 columns">
                    {content}
                </div>
            </div>
            <div id="footer" class="small-12 columns">
                <div class="row">
                    Orgulhosamente produzido com <a href="https://github.com/ProjectCompass/Compass">Compass</a>!
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