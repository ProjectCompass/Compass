<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
	case 'menu':
        echo 
            make_menu('menu_item', '<i class="fa fa-book"></i>', 'catalog', 'catalog', '', get_setting('module_books'), 'books').
            make_menu('menu_subitem', '<i class="fa da"></i>'.lang('books_catalog'), 'catalog', 'index', '', get_setting('module_books'), 'books').
            make_menu('menu_subitem', '<i class="fa da"></i>'.lang('books_loans'), 'loans', 'index', '', get_setting('module_books'), 'books').
            make_menu('menu_subitem', '<i class="fa da"></i>'.lang('settings'), 'settings', 'index', '', get_setting('module_books'), 'books').
            make_menu('menu_space')
        ;
        break; 
    case 'submenu':
        echo
            make_menu('submenu_item_drop', lang('books'), 'books', 'catalog', '').
                make_menu('submenu_item_drop_item', lang('books_all'), 'books', 'catalog', '').
                make_menu('submenu_item_drop_item', lang('books_insert'), 'books', 'catalog/insert', '').
            make_menu('submenu_item_drop_close', '', '', '', 'perm_listposts_').
            make_menu('submenu_item_drop', lang('books_loans'), 'books', 'loans', '').
                make_menu('submenu_item_drop_item', lang('books_loans_actives'), 'books', 'loans', '').
                make_menu('submenu_item_drop_item', lang('books_loans_insert'), 'books', 'loans/insert', '').
            make_menu('submenu_item_drop_close', '', '', '', 'perm_listposts_').
            make_menu('submenu_item', lang('books_loans_historic'), 'books', 'loans/historic', '').
            make_menu('submenu_item', lang('books_settings'), 'books', 'settings', '')
        ;
        break;
}