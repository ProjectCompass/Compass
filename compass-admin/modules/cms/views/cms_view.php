<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
    case 'menu':
        echo
            make_menu('menu_item', '<i class="fa fa-edit"></i>', 'posts', 'index', '', get_setting('module_cms'), 'cms').
            make_menu('menu_subitem', '<i class="fa da"></i>'.lang('cms_posts'), 'posts', 'index', 'perm_listposts_', get_setting('module_cms'), 'cms').
            make_menu('menu_subitem', '<i class="fa da"></i>'.lang('cms_pages'), 'pages', 'index', 'perm_listpages_', get_setting('module_cms'), 'cms').
            make_menu('menu_subitem', '<i class="fa da"></i>'.lang('cms_comments').count_comments_unmoderated(), 'posts', 'comments', 'perm_comments_', get_setting('module_cms'), 'cms').
            make_menu('menu_subitem', '<i class="fa da"></i>'.lang('cms_medias'), 'medias', 'index', 'perm_medias_', get_setting('module_cms'), 'cms').
            make_menu('menu_subitem', '<i class="fa da"></i>'.lang('cms_themes'), 'themes', 'index', 'perm_themes_', get_setting('module_cms'), 'cms').
            make_menu('menu_subitem', '<i class="fa da"></i>'.lang('cms_stats'), 'stats', 'index', 'perm_stats_', get_setting('module_cms'), 'cms').
            make_menu('menu_subitem', '<i class="fa da"></i>'.lang('cms_settings'), 'settings', 'index', 'perm_contentssettings_', get_setting('module_cms'), 'cms').
            make_menu('menu_subitem', '<i class="fa da"></i>'.lang('core_homepage'), 'site', 'index', NULL, get_setting('module_cms'), 'cms').
            make_menu('menu_space')
        ;
        break;
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