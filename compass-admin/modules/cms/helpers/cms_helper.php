<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS functions
 *
 * Helper used in mounting the site
 *
 * @package     Compass
 * @subpackage  CMS
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       0.0.0
 */

 /**
 * Initialize CMS
 *
 * Returns essential objects for the module books.
 * 
 * @access  private
 * @param   no
 * @return  
 * @since   0.1.0
 * @modify  0.1.0
 */
function initialize_cms(){
    $CI =& get_instance();
    //load file language
    $CI->lang->load('cms', lang(FALSE));
    //blocks if the cms module is not enabled
    if (get_setting('module_cms') == 0) redirect('stop');
    //loads utilities
    $CI->load->model('posts_model', 'posts');
	$CI->load->model('postmeta_model', 'postmeta');
	$CI->load->model('terms_model', 'terms');
	$CI->load->model('termmeta_model', 'termmeta');
	$CI->load->model('comments_model', 'comments');
    $CI->load->helper('date');
    set_theme('submenu', load_module('cms_view', 'submenu'));
    //solicit install the module
    if (get_setting('module_install_'.$CI->uri->segment(1)) != '1'):
        set_msg('msgerror', lang('modules_msg_module_not_be_install'), 'error');
        redirect ('tools/modules');
    endif;
}