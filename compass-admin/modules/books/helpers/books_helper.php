<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Books functions
 *
 * Helper functions for operations with the module books
 *
 * @package     Compass
 * @subpackage  Books
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       0.1.0
 */

// ------------------------------------------------------------------------

 /**
 * Lang
 *
 * Returns essential objects for the module books.
 * 
 * @access  private
 * @param   no
 * @return  string, arrays, bool
 * @since   0.1.0
 * @modify  0.1.0
 */
function initialize_books(){
    $CI =& get_instance();
    //load file language
    $CI->lang->load('books', lang(FALSE));
    //blocks if the cms module is not enabled
    if (get_setting('module_books') == 0) redirect('stop');
    //loads utilities
    $CI->load->model('books_model', 'books');
    $CI->load->model('loans_model', 'loans');
    $CI->load->helper('date');
    set_theme('submenu', load_module('books_view', 'submenu'));
    //solicit install the module
    if (get_setting('module_install_'.$CI->uri->segment(1)) != '1'):
        set_msg('msgerror', lang('modules_msg_module_not_be_install'), 'error');
        redirect ('tools/modules');
    endif;
}

