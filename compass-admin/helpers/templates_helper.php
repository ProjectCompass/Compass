<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Templates functions
 *
 * Helper functions used in construction operations for the system layout(template)
 *
 * @package     Compass
 * @subpackage  Core
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       0.0.0
 */


// ------------------------------------------------------------------------

 /**
 * Initialize Dashboard
 *
 * initialize the admin panel charging system and that the necessary resources are recurrent use.
 * 
 * @access  private
 * @param   no
 * @return  NULL
 * @since   0.0.0
 * @modify  0.0.0
 */
function initialize_dashboard(){
    $CI =& get_instance();
    //loads librarys, helpers and models recurrently used in the system
    $CI->load->library(array('parser', 'system', 'session', 'form_validation'));
    $CI->load->helper(array('form', 'url', 'array', 'text', 'paging'));
    $CI->load->model('users_model', 'users');
    $CI->load->model('usermeta_model', 'usermeta');
    //property standards as the title of the panel and roapé (are automatically entered in the system settings)
    set_theme('theme', 'default');
    set_theme('title_default', get_setting('general_title_site'));
    set_theme('footer', get_setting('aparence_copy_footer'));
    set_theme('template', 'template_view');
    set_theme('submenu', '');
    //loading css in header
    set_theme('headerinc', load_css(array('normalize', 'style', 'foundation.min', 'app', 'font-awesome/css/font-awesome.min')), FALSE);
    //loading js in footer
    set_theme('headerinc', load_js(array('modernizr', 'jquery-1.9.1.min')), FALSE);
    set_theme('footerinc', load_js(array('foundation.min', 'app')), FALSE);
    //startup package
    init_scrollbar();
}

// ------------------------------------------------------------------------

 /**
 * Load Module
 *
 * Loads a module of the system returning the requested screen in view format.
 * 
 * @access  private
 * @param   string string string array
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function load_module($module=NULL, $screen=NULL, $directory='core', $array=NULL){
    $CI =& get_instance();
    //performs the function only if the module is not null
    if ($module != NULL):
        $vars['screen'] = $screen;
        if ($array != NULL):
            foreach ($array as $k => $v):
                $vars[$k] = $v;
            endforeach;
        endif;
        if ($directory == NULL):
            $directory = 'core';
        endif;
        //loads the view
        return $CI->load->view("$directory/$module", $vars, TRUE);
    else:
        return FALSE;
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Set Theme
 *
 * Sets values ​​to the overall theme of the array class system, to be used throughout the system.
 * 
 * @access  private
 * @param   string string bool bool
 * @return  NULL
 * @since   0.0.0
 * @modify  0.0.0
 */
function set_theme($prop, $value, $replace=TRUE, $load_template=TRUE){
    $CI =& get_instance();
    $CI->load->library('system');
    if ($replace):
        $CI->system->theme[$prop] = $value;
    else:
        if (!isset($CI->system->theme[$prop])) $CI->system->theme[$prop] = '';
        $CI->system->theme[$prop] .= $value;
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Load Template
 *
 * Loads a template theme through the array as parameter.
 * 
 * @access  private
 * @param   no
 * @return  NULL
 * @since   0.0.0
 * @modify  0.0.0
 */
function load_template(){
    $CI =& get_instance();
    $CI->load->library('system');
    $CI->parser->parse($CI->system->theme['template'], $CI->system->theme);
}

// ------------------------------------------------------------------------

 /**
 * Load CSS
 *
 * Load one or more files. css in a folder.
 * 
 * @access  private
 * @param   string string string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function load_css($file=NULL, $folder='css', $media='all'){
    if ($file != NULL):
        $CI =& get_instance();
        $CI->load->helper('url');
        $return = '';
        if (is_array($file)):
            foreach ($file as $css):
                $return .= '<link rel="stylesheet" type="text/css" href="'.base_url("compass-content/includes/$folder/$css.css").'" media="'.$media.'" />';
            endforeach;
        else:
            $return .= '<link rel="stylesheet" type="text/css" href="'.base_url("compass-content/includes/$folder/$file.css").'" media="'.$media.'" />';
        endif;
    endif;
    return $return;
}

// ------------------------------------------------------------------------

 /**
 * Load JS
 *
 * Load one or more files. js folder or a remote server.
 * 
 * @access  private
 * @param   string string bool
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function load_js($file=NULL, $folder='js', $remote=FALSE){
    if ($file != NULL):
        $CI =& get_instance();
        $CI->load->helper('url');
        $return = '';
        if (is_array($file)):
            foreach ($file as $js):
                if ($remote):
                    $return .= '<script type="text/javascript" src="'.$js.'"></script>';
                else:
                    $return .= '<script type="text/javascript" src="'.base_url("compass-content/includes/$folder/$js.js").'"></script>';
                endif;
            endforeach;
        else:
            return NULL;
        endif;
    endif;
    return $return;
}

// ------------------------------------------------------------------------

 /**
 * Initialize Tinymce
 *
 * Initialize the tinymce to create textarea with html editor.
 * 
 * @access  private
 * @param   no
 * @return  NULL
 * @since   0.0.0
 * @modify  0.0.0
 */
function init_tinymce($is_small=FALSE){
    $CI =& get_instance();
    set_theme('headerinc', load_js(array('jquery.tinymce.min'),'scripts/tinymce'), FALSE);
    set_theme('headerinc', load_js(array('tinymce.min'),'scripts/tinymce'), FALSE);
    set_theme('headerinc', load_module('includes', ($is_small == TRUE) ? 'tinymcesmall' : 'tinymce'), FALSE);
}


// ------------------------------------------------------------------------

 /**
 * Initialize codemirror
 *
 * Initialize the CodeMirror to create textarea with html syntax.
 * 
 * @access  private
 * @param   no
 * @return  NULL
 * @since   0.0.0
 * @modify  0.0.0
 */
function init_codemirror(){
    $CI =& get_instance();
    set_theme('headerinc', load_css(array('codemirror'),'scripts/Codemirror'), FALSE);
    set_theme('headerinc', load_js(array('jquery.codemirror.min'),'scripts/Codemirror'), FALSE);
    set_theme('footerinc', load_module('includes', 'codemirror'), FALSE);
}

// ------------------------------------------------------------------------

 /**
 * Initialize Datetimepicker
 *
 * Initialize DateTimePicker to create inputs for insertion dates and times.
 * 
 * @access  private
 * @param   no
 * @return  NULL
 * @since   0.0.0
 * @modify  0.0.0
 */
function init_datetimepicker(){
    $CI =& get_instance();
    set_theme('headerinc', load_css(array('jquery.datetimepicker'),'scripts/DateTimePicker'), FALSE);
    set_theme('headerinc', load_js(array('jquery.datetimepicker'),'scripts/DateTimePicker'), FALSE);
    set_theme('footerinc', load_module('includes', 'datetimepicker'), FALSE);
}




//inicializar o tinymce para criação de textarea com editor html
function init_tagsinputmaster(){
    $CI =& get_instance();
    set_theme('headerinc', load_css(array('jquery.tagsinput'),'scripts/TagsInputMaster'), FALSE);
    set_theme('footerinc', load_js(array('jquery.tagsinput.min'),'scripts/TagsInputMaster'), FALSE);
    set_theme('footerinc', load_module('includes', 'tagsinputmaster'), FALSE);
}

// ------------------------------------------------------------------------

 /**
 * Initialize Colorpicker
 *
 * Initialize Colorpicker to create inputs for insertion colors code.
 * 
 * @access  private
 * @param   no
 * @return  NULL
 * @since   0.0.0
 * @modify  0.0.0
 */
function init_colorpicker(){
    $CI =& get_instance();
    set_theme('headerinc', load_css(array('evol.colorpicker'),'scripts/Colorpicker/css'), FALSE);
    set_theme('footerinc', load_js(array('jquery-ui.min', 'evol.colorpicker.min'),'scripts/Colorpicker/js'), FALSE);
    set_theme('footerinc', load_module('includes', 'colorpicker'), FALSE);
}

// ------------------------------------------------------------------------

 /**
 * Initialize Scrollbar
 *
 * Initialize Scrollbar to create custom scrollbars.
 * 
 * @access  private
 * @param   no
 * @return  NULL
 * @since   0.0.0
 * @modify  0.0.0
 */
function init_scrollbar(){
    $CI =& get_instance();
    set_theme('headerinc', load_css(array('scroller'),'scripts/Scrollbar'), FALSE);
    set_theme('footerinc', load_js(array('jquery.scroller.min'),'scripts/Scrollbar'), FALSE);
    set_theme('footerinc', load_module('includes', 'scrollbar'), FALSE);
}

// ------------------------------------------------------------------------

 /**
 * Initialize HightChart
 *
 * Initialize HightChart to create charts.
 * 
 * @access  private
 * @param   no
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function init_highcharts(){
    $CI =& get_instance();
    set_theme('footerinc', load_js(array('highcharts', 'modules/exporting'),'scripts/Highcharts'), FALSE);
}

// ------------------------------------------------------------------------

 /**
 * Make Menu
 *
 * Rules for assembly of the dashboard main menu.
 * 
 * @access  private
 * @param   string string string string string string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function make_menu($type=NULL, $title=NULL, $value_class=NULL, $value_method=NULL, $permission=NULL, $condition=1){
    $CI =& get_instance();
    $CI->load->helper('url');
    $class = $CI->router->class;
    $method = $CI->router->method;
    $user_level = get_session('user_level');
    if ($condition == 1):
        if ($type=='menu_open'):
            return '<ul class="off-canvas-list">';
        elseif ($type == 'menu_item'):
            if (get_access($permission) || $permission == NULL):
                if ($value_class != $class):
                    return '<li><a href="'.base_url($value_class).'">'.$title.'</a></li>';
                else:
                    return '<li><label><a href="'.base_url($value_class).'">'.$title.'</a></label></li>';
                endif;
            else:
                return '';
            endif;
        elseif ($type == 'menu_subitem'):
            if (get_access($permission) || $permission == NULL):
                if ($value_class == $class && $value_method != $method):
                    return '<li><a href="'.base_url($value_class.'/'.$value_method).'">'.$title.'</a></li>';
                elseif ($value_class == $class && $value_method == $method):
                    return '<li><label><a href="'.base_url($value_class).'/'.$value_method.'">'.$title.'</a></label></li>';
                endif;
            else:
                return '';
            endif;
        elseif ($type == 'menu_label'):
            return '<li><label>'.$title.'</label></li>';
        elseif ($type == 'menu_space'):
            return '<li><label class="space"></label></li>';
        elseif ($type == 'menu_close'):
            return '</ul>';
        elseif ($type == 'submenu_open'):
            return '<ul class="left">';
        elseif ($type == 'submenu_item'):
            if (get_access($permission) || $permission == NULL):
                return '<li><a href="'.base_url($value_class.'/'.$value_method).'">'.$title.'</a></li>';
            else:
                return '';
            endif;
        elseif ($type == 'submenu_item_drop'):
            if (get_access($permission) || $permission == NULL):
                $href = ($value_class != NULL && $value_method != NULL) ? 'href="'.base_url($value_class.'/'.$value_method).'"' : NULL;
                return '<li class="has-dropdown"><a '.$href.'>'.$title.'</a><ul class="dropdown">';
            else:
                return '';
            endif;
        elseif ($type == 'submenu_item_drop_item'):
            if (get_access($permission) || $permission == NULL):
                return '<li><a href="'.base_url($value_class.'/'.$value_method).'">'.$title.'</a></li>';
            else:
                return '';
            endif;
        elseif ($type == 'submenu_item_drop_close'):
            if (get_access($permission) || $permission == NULL):
                return '</ul></li>';
            else:
                return '';
            endif;
        elseif ($type == 'submenu_close'):
            return '</ul>';
        else:
            return NULL;
        endif;
    else:
        return NULL;
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Ger Url
 *
 * Returns the the class and method to the url cração identifiers pages.
 * 
 * @access  private
 * @param   string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_url_class($prop){
    $CI =& get_instance();
    $CI->load->helper('url');
    $class = $CI->router->class;
    $method = $CI->router->method;
    if ($prop == 'class'):
        if ($class != NULL):
            return $class;
        else:
            return '';
        endif;
    else:
        if ($class != NULL && $method == 'index'):
            return $class;
        elseif ($class != NULL && $method != 'index'):
            return $class.'-'.$method;
        endif;
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Ger Url Title Original
 *
 * Converts a segment of a url (the url format) to text format.
 * 
 * @access  private
 * @param   string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_url_title_original($url_string){
    $url_title = $url_string;
    $title_parts = array_map(NULL,
           explode("-",$url_title));
    return implode(" ",$title_parts);
}

// ------------------------------------------------------------------------

 /**
 * Load Layout
 *
 * Loads the file and returns a theme as a view.
 * 
 * @access  private
 * @param   string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function load_layout($layout_file){
    $CI =& get_instance();
    $CI->load->library('system');
    $CI->system->theme['code'] = $CI->load->file('./compass-content/themes/'.get_setting('content_site_theme').'/'.$layout_file);
    set_theme('template', 'blank_view');
    load_template();
}

// ------------------------------------------------------------------------

 /**
 * Load Layout Files
 *
 * Loads the array with files from the active directory layout.
 * 
 * @access  private
 * @param   no
 * @return  array
 * @since   0.0.0
 * @modify  0.0.0
 */
function load_layout_files(){
    $CI =& get_instance();
    $CI->load->helper('directory');
    return directory_map('./compass-content/themes/'.get_setting('content_site_theme'), TRUE);
}

// ------------------------------------------------------------------------

 /**
 * Count Comments Unmoderated
 *
 * Constructs <span> with the number of unmoderated comments.
 * 
 * @access  private
 * @param   string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function count_comments_unmoderated(){
    $CI =& get_instance();
    $CI->load->model('comments_model', 'comments');
    if ($CI->comments->get_by_status('unmoderated')->num_rows() > 0):
        return '<span class="count">'.$CI->comments->get_by_status('unmoderated')->num_rows().'</span>';
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Get CSS Theme
 *
 * Returns the options for the template definicas dashboard user.
 * 
 * @access  private
 * @param   no
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_css_theme(){
    $CI =& get_instance();
    $CI->load->helper('directory');
    $style = NULL;
    if (get_usermeta('user_theme', get_session('user_id')) == 'pink'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#DC4FAD !important;} a{color:#DC4FAD;} a:hover{color:#DC4F8D;}';
    elseif (get_usermeta('user_theme', get_session('user_id')) == 'red-dark'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#A10000 !important;} a{color:#A10000;} a:hover{color:#A1003D;}';
    elseif (get_usermeta('user_theme', get_session('user_id')) == 'red-light'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#FF0000 !important;} a{color:#FF0000;} a:hover{color:#A1003D;}';
    elseif (get_usermeta('user_theme', get_session('user_id')) == 'orange'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#FF8F32 !important;} a{color:#FF8F32;} a:hover{color:#C46C25;}';
    elseif (get_usermeta('user_theme', get_session('user_id')) == 'green-light'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#82BA00 !important;} a{color:#82BA00;} a:hover{color:#008A17;}';
    elseif (get_usermeta('user_theme', get_session('user_id')) == 'green-dark'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#008A17 !important;} a{color:#008A17;} a:hover{color:#00450B;}';
    elseif (get_usermeta('user_theme', get_session('user_id')) == 'teal-light'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#03B3B2 !important;} a{color:#03B3B2;} a:hover{color:#008299;}';
    elseif (get_usermeta('user_theme', get_session('user_id')) == 'teal-dark'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#008299 !important;} a{color:#008299;} a:hover{color:#024652;}';
    elseif (get_usermeta('user_theme', get_session('user_id')) == 'blue-light'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#5DB2FF !important;} a{color:#5DB2FF;} a:hover{color:#0072C6;}';
    elseif (get_usermeta('user_theme', get_session('user_id')) == 'blue'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#0072C6 !important;} a{color:#0072C6;} a:hover{color:#001940;}';
    elseif (get_usermeta('user_theme', get_session('user_id')) == 'purple-dark'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#4617B4 !important;} a{color:#4617B4;} a:hover{color:#461764;}';
    elseif (get_usermeta('user_theme', get_session('user_id')) == 'purple'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#8C0095 !important;} a{color:#8C0095;} a:hover{color:#4617B4;}';
    elseif (get_usermeta('user_theme', get_session('user_id')) == 'blue-medium'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#004B88 !important;} a{color:#004B88;} a:hover{color:#001940;}';
    elseif (get_usermeta('user_theme', get_session('user_id')) == 'blue-dark'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#001940 !important;} a{color:#001940;} a:hover{color:#001900;}';
    elseif (get_usermeta('user_theme', get_session('user_id')) == 'brown'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#570000 !important;} a{color:#570000;} a:hover{color:#380000;}';
    elseif (get_usermeta('user_theme', get_session('user_id')) == 'brown-dark'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#380000 !important;} a{color:#380000;} a:hover{color:#000000;}';
    elseif (get_usermeta('user_theme', get_session('user_id')) == 'grey'):
        $style .= '.top-bar, .top-bar section, .top-bar section > ul, .drop-profile-top-bar, .top-bar section ul li > a, .tab-bar, .tab-bar section{background:#585858 !important;} a{color:#585858;} a:hover{color:#333333;}';
    endif;
    return $style;
}