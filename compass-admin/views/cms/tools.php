<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
    case 'submenu':
        echo
            make_menu('submenu_item', lang('cms_content'), 'cms', '', 'perm_listposts_').
            make_menu('submenu_item_drop', lang('cms_posts'), 'cms', 'posts', 'perm_listposts_').
                make_menu('submenu_item_drop_item', lang('cms_posts_all'), 'cms', 'posts', 'perm_listposts_').
                make_menu('submenu_item_drop_item', lang('cms_posts_new'), 'cms', 'insertpost', 'perm_insertposts_').
            make_menu('submenu_item_drop_close', '', '', '', 'perm_listposts_').
            make_menu('submenu_item_drop', lang('cms_pages'), 'cms', 'pages', 'perm_listpages_').
                make_menu('submenu_item_drop_item', lang('cms_pages_all'), 'cms', 'pages', 'perm_listpages_').
                make_menu('submenu_item_drop_item', lang('cms_pages_new'), 'cms', 'insertpage', 'perm_insertpages_').
            make_menu('submenu_item_drop_close', '', '', '', 'perm_listpages_').
            make_menu('submenu_item', lang('cms_comments'), 'cms', 'comments', 'perm_comments_').
            make_menu('submenu_item', lang('cms_medias'), 'cms', 'medias', 'perm_medias_').
            make_menu('submenu_item_drop', '<i class="fa fa-ellipsis-h"></i>').
                make_menu('submenu_item_drop_item', lang('cms_themes'), 'cms', 'themes', 'perm_themes_').
                make_menu('submenu_item_drop_item', lang('cms_stats'), 'cms', 'stats', 'perm_stats_').
                make_menu('submenu_item_drop_item', lang('cms_settings'), 'cms', 'settings', 'perm_contentssettings_').
            make_menu('submenu_item_drop_close')
        ;
        break;   
}