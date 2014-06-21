<?php
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
//make the menus of modules
echo '<div class="off-canvas-wrap docs-wrap" id="container" data-offcanvas>';
    echo '<div class="inner-wrap" id="content">';
        //Aside Menu left for small screen and medium screen
        if (be_logged(FALSE) == TRUE):
            echo '<aside class="left-off-canvas-menu hide-for-large">';
                get_the_menu();
            echo '</aside>';
        endif;
        //Aside Menu right for small screen and medium screen
        if (be_logged(FALSE) == TRUE):
            echo '<aside class="right-off-canvas-menu hide-for-large">';
                echo '<ul class="off-canvas-list">';
                    echo '{submenu}';
                    echo '<span class="show-for-small">';
                        $iduserbe = (get_session('user_id')) ? get_session('user_id') : '0';
                        $infouser = $this->users->get_by_id($iduserbe)->row();
                        echo '<li><br></li>';
                        echo '<li><label>'.$infouser->user_displayname.'</label></li>';
                        echo '<li><a href="'.base_url('users/profile/'.$infouser->user_id.'').'">'.avatar(get_usermeta('user_image', get_session('user_id')), 150, 150).'</a></li>';
                        echo '<li><a href="'.base_url('users/profile/'.$infouser->user_id.'').'">'.lang('core_view_profile').'</a></li>';
                        echo '<li><a href="'.base_url('users/update/'.$infouser->user_id.'').'">'.lang('core_update_profile').'</a></li>';
                        echo '<li><a href="'.base_url('login/logoff').'">'.lang('core_logoff').'</a></li>';
                    echo '</span>';
                echo '</ul>';
            echo '</aside>';
        endif;
        //Topbar for small screen
        echo '<nav class="tab-bar show-for-small">';
            echo '<section class="right tab-bar-section">';
                echo '<h1 class="title-top"><i class="fa fa-compass" style="font-size:25px;"></i> {title_default}</h1>';
            echo '</section>';
            echo '<section class="left-small">';
                echo '<a class="left-off-canvas-toggle menu-icon" title="'.lang('core_menu').'" ><span></span></a>';
            echo '</section>';
            echo '<section class="right-small">';
                echo '<a class="right-off-canvas-toggle menu-icon" title="'.lang('tools').'"><span></span></a>';
            echo '</section>';
        echo '</nav>';
        //Topbar for medium screen and large screen
        echo '<nav class="top-bar hide-for-small" data-topbar>';
            echo '<section class="top-bar-section">';
                echo '<ul class="title-area">';
                    echo '<h1 class="title-top hide-for-small"><span class="hide-for-large left title-top-space"></span><i class="fa fa-compass" style="font-size:25px; text-align:center;"></i> {title_default}</h1>';
                echo '</ul>';
                if (be_logged(FALSE) == TRUE):
                    echo '<ul class="left hide-for-medium">{submenu}</ul>';
                endif;
                echo '<ul class="right">';
                    //Menu user profile
                    if (be_logged(FALSE) == TRUE):
                        $infouser = $this->users->get_by_id(get_session('user_id'))->row();
                    endif;
                    if (be_logged(FALSE) == FALSE):
                        echo '<li><a href="'.base_url('login').'">'.lang('core_login').'</a></li>';
                    else:
                        if (get_access('perm_comments_') == TRUE && get_setting('module_cms') == 1 && get_setting('module_install_cms') == 1):
                            echo '<li><a href="'.base_url('cms/comments').'"><i class="fa fa-comment"></i>'.count_comments_unmoderated().'</a></li>';
                        endif;
                        echo '<li>';
                            echo '<a href="#" data-dropdown="drop-helper" id="has-dropdown"><i class="fa fa-info help-b"></i></a>';
                            echo '<ul id="drop-helper" data-dropdown-content class="f-dropdown drop-help-top-bar">';
                                echo '<div class="nano">';
                                    echo '<div tabindex="0" class="nano-content">';
                                        echo '<p>{helper}</p>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</ul>';
                        echo '</li>';
                        echo '<li>';
                            echo '<a href="#" data-dropdown="drop-setting"><i class="fa fa-cog"></i></a>';
                            echo '<ul id="drop-setting" data-dropdown-content class="f-dropdown drop-setting-top-bar">';
                                echo '<div tabindex="0" class="nano-content">';
                                    echo '<div tabindex="0" class="color-box">';
                                        echo anchor('settings/options/color/pink/'.$this->uri->uri_string(current_url()), ' ', 'class="color-pink" title="'.lang('core_color_pink').'"');
                                        echo anchor('settings/options/color/red-dark/'.$this->uri->uri_string(current_url()), ' ', 'class="color-red-light" title="'.lang('core_color_red_dark').'"');
                                        echo anchor('settings/options/color/red-light/'.$this->uri->uri_string(current_url()), ' ', 'class="color-red-light" title="'.lang('core_color_red_light').'"');
                                        echo anchor('settings/options/color/orange/'.$this->uri->uri_string(current_url()), ' ', 'class="color-orange" title="'.lang('core_color_orange').'"');
                                        echo anchor('settings/options/color/green-light/'.$this->uri->uri_string(current_url()), ' ', 'class="color-green-light" title="'.lang('core_color_green_light').'"');
                                        echo anchor('settings/options/color/green-dark/'.$this->uri->uri_string(current_url()), ' ', 'class="color-green-dark" title="'.lang('core_color_green_dark').'"');
                                        echo anchor('settings/options/color/teal-light/'.$this->uri->uri_string(current_url()), ' ', 'class="color-teal-light" title="'.lang('core_color_teal_light').'"');
                                        echo anchor('settings/options/color/teal-dark/'.$this->uri->uri_string(current_url()), ' ', 'class="color-teal-dark" title="'.lang('core_color_teal_dark').'"');
                                        echo anchor('settings/options/color/blue-light/'.$this->uri->uri_string(current_url()), ' ', 'class="color-blue-light" title="'.lang('core_color_blue_light').'"');
                                        echo anchor('settings/options/color/blue/'.$this->uri->uri_string(current_url()), ' ', 'class="color-blue" title="'.lang('core_color_blue').'"');
                                        echo anchor('settings/options/color/purple-dark/'.$this->uri->uri_string(current_url()), ' ', 'class="color-purple-dark" title="'.lang('core_color_purple_dark').'"');
                                        echo anchor('settings/options/color/purple/'.$this->uri->uri_string(current_url()), ' ', 'class="color-purple" title="'.lang('core_color_purple').'"');
                                        echo anchor('settings/options/color/blue-medium/'.$this->uri->uri_string(current_url()), ' ', 'class="color-blue-medium" title="'.lang('core_color_blue_medium').'"');
                                        echo anchor('settings/options/color/blue-dark/'.$this->uri->uri_string(current_url()), ' ', 'class="color-blue-dark" title="'.lang('core_color_blue_dark').'"');
                                        echo anchor('settings/options/color/brown/'.$this->uri->uri_string(current_url()), ' ', 'class="color-brown" title="'.lang('core_color_brown').'"');
                                        echo anchor('settings/options/color/brown-dark/'.$this->uri->uri_string(current_url()), ' ', 'class="color-brown-dark" title="'.lang('core_color_brown_dark').'"');
                                        echo anchor('settings/options/color/grey/'.$this->uri->uri_string(current_url()), ' ', 'class="color-grey" title="'.lang('core_color_grey').'"');
                                        echo anchor('settings/options/color/black/'.$this->uri->uri_string(current_url()), ' ', 'class="color-black" title="'.lang('core_color_black').'"');
                                        echo '<div class="clear"></div>';
                                        echo '<hr>';
                                        echo '<div class="row">';
                                        echo form_label(lang('core_language').':');
                                        $languages = (directory_map('./compass-admin/language', TRUE));
                                        $options = array();
                                        $options[] = '← '.ucfirst(get_session('system_language'));
                                        foreach ($languages as $lang):
                                            $options[base_url('settings/options/language/').'/'.$lang] = ucfirst($lang);
                                        endforeach;
                                        echo form_dropdown('language', $options, get_session('system_language'), 'onChange="if(this.selectedIndex!=0)self.location=this.options[this.selectedIndex].value"');
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</ul>';
                        echo '</li>';
                        echo '<li>';
                            echo '<a href="#" data-dropdown="drop" id="has-dropdown">'.$infouser->user_displayname.' '.avatar(get_usermeta('user_image', get_session('user_id')), 30, 30).'</a>';
                            echo '<ul id="drop" data-dropdown-content class="f-dropdown drop-profile-top-bar">';
                                echo '<div class="left">';
                                    echo '<li><a href="'.base_url('users/profile/'.$infouser->user_id.'').'">'.avatar(get_usermeta('user_image', get_session('user_id')), 150, 150).'</a></li>';
                                echo '</div>';
                                echo '<div class="right">';
                                    echo '<li><a href="'.base_url('users/profile/'.$infouser->user_id.'').'">'.lang('core_view_profile').'</a></li>';
                                    echo '<li><a href="'.base_url('users/update/'.$infouser->user_id.'').'">'.lang('core_update_profile').'</a></li>';
                                    echo '<li><a href="'.base_url('login/logoff').'">'.lang('core_logoff').'</a></li>';
                                echo '</div>';
                            echo '</ul>';
                        echo '</li>';
                        echo '<li><span class="hide-for-large right title-top-space"></span></li>';
                    endif;
                echo '</ul>';
            echo '</section>';
        echo '</nav>';
        //Left canvas link and right canvas link for small screen and medium screen
        if (be_logged(FALSE) == TRUE):
            echo '<div class="tab-bar show-for-medium">';
                echo '<section class="left-small left-medium hide-for-large">';
                    echo '<a class="left-off-canvas-toggle menu-icon" title="'.lang('core_menu').'" ><span></span></a>';
                echo '</section>';
                echo '<section class="right-small right-medium hide-for-large">';
                    echo '<a class="right-off-canvas-toggle menu-icon" title="'.lang('tools').'"><span></span></a>';
                echo '</section>';
            echo '</div>';
        endif;
        //Content area
        echo '<section class="main-section page-'.get_url_class('class').' page-'.get_url_class('method').'">';
            echo '<div class="row">';
                if (be_logged(FALSE) == TRUE):
                    //menu for large
                    echo '<div class="small-2 large-2 hide-for-small hide-for-medium columns large-content-left" id="menu-left-page">';
                        get_the_menu();
                    echo '</div>';
                    //content
                    echo '<div class="large-10 columns large-content-right" id="content-page">';
                        echo '<div class="small-12 columns">';
                            get_msg('msgerror').get_msg('msgok').errors_validating();
                            echo '{content}';
                            echo '<br><br>';
                        echo '</div>';
                    echo '</div>';
                else:
                    //Content for no-logged
                    echo '<div class="small-12 columns" id="content-page">';
                        echo '<div class="small-12 columns">';
                            echo '{content}';
                            echo '<br><br>';
                        echo '</div>';
                    echo '</div>';
                endif;
            echo '</div>';
        echo '</section>';
        //Footer
        echo '<footer>';
            echo '<div class="row">';
                echo '<div class="small-12 columns">';
                    echo '<a href="#" class="right" target="_blanc">'.lang('core_copy').'</a>';
                    echo '<p>{footer}</p>';
                echo '</div>';
            echo '</div>';
        echo '</footer>';
        echo '<a class="exit-off-canvas"></a>';
    echo '</div>';
echo '</div>';
echo '{footerinc}';
echo '</body>';
echo '</html>';