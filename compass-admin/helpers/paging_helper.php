<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Functions Paging
 *
 * Helper functions for operations with the system
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
 * Get Query
 *
 * Constructs a query list of elements of bd with various configurations and settings.
 * 
 * @access  private
 * @param   array string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_query($config=NULL, $type=NULL){
    $CI =& get_instance();
    $CI->load->helper('url');
    if (isset($config['pagination_rows']) == NULL):
        $config['pagination_rows'] = get_setting('general_large_list');
    endif;
    if ($type == 'all'):
        $search = get_url_title_original($CI->uri->segment($config['filter_value_segment']));
        $filter = $CI->uri->segment($config['filter_key_segment']);
        return $CI->
        $config['model']
        ->$config['method'](100000, NULL, $config['default_orderby'], $config['default_order'], $filter, $search);
    else:
        $pagination_segment_value = ($CI->uri->segment($config['pagination_segment'])) ? $CI->uri->segment($config['pagination_segment']) : 1;
        $offset = $pagination_segment_value*$config['pagination_rows']-$config['pagination_rows'];
        $orderby = ($CI->uri->segment($config['orderby_segment']) != NULL) ? $CI->uri->segment($config['orderby_segment']) : $config['default_orderby'];
        $order = ($CI->uri->segment($config['order_segment']) != NULL) ? $CI->uri->segment($config['order_segment']) : $config['default_order'];
        $search = get_url_title_original($CI->uri->segment($config['filter_value_segment']));
        $filter = $CI->uri->segment($config['filter_key_segment']);
        return $CI->$config['model']->$config['method']($config['pagination_rows'], $offset, $orderby, $order, $filter, $search);
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Get Pagination
 *
 * Constructs a menu paging with various configurations and settings.
 * 
 * @access  private
 * @param   array string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_pagination($config=NULL, $link=NULL){
    $CI =& get_instance();
    $CI->load->helper('url');
    $pagination = FALSE;
    if (isset($config['pagination_rows']) == NULL):
        $pagination_rows = get_setting('general_large_list');
    endif;
    $pagination_type = 'full';
    $pagination_segment = 3;
    $pagination_url = base_url();
    $pagination_start = 'first';
    $pagination_start_open = '<li>';
    $pagination_start_close = '</li>';
    $pagination_show_links = TRUE;
    $pagination_prefix = '<ul class="pagination right">';
    $pagination_sufix = '</ul>';
    $pagination_prefix_item = '<li class="arrow">';
    $pagination_sufix_item = '</li>';
    $pagination_prefix_item_active = '<li class="current">';
    $pagination_sufix_item_active = '</li>';
    $pagination_show_prev_next = TRUE;
    $pagination_prev = '&lt';
    $pagination_next = '&gt;';
    $pagination_show_first_last = TRUE;
    $pagination_first = '&laquo;';
    $pagination_last = '&raquo;';
    $pagination_itens = 5;
    foreach ($config as $k => $v) eval("\$".$k." = \"".$v."\";");
    if ($link == NULL):
        $link.= ($CI->uri->segment($pagination_segment+1) != NULL) ? '/'.$CI->uri->segment($pagination_segment+1) : NULL;
        $link.= ($CI->uri->segment($pagination_segment+2) != NULL) ? '/'.$CI->uri->segment($pagination_segment+2) : NULL;
        $link.= ($CI->uri->segment($pagination_segment+3) != NULL) ? '/'.$CI->uri->segment($pagination_segment+3) : NULL;
        $link.= ($CI->uri->segment($pagination_segment+4) != NULL) ? '/'.$CI->uri->segment($pagination_segment+4) : NULL;
        $link.= ($CI->uri->segment($pagination_segment+5) != NULL) ? '/'.$CI->uri->segment($pagination_segment+5) : NULL;
        $link.= ($CI->uri->segment($pagination_segment+6) != NULL) ? '/'.$CI->uri->segment($pagination_segment+6) : NULL;
        $link.= ($CI->uri->segment($pagination_segment+7) != NULL) ? '/'.$CI->uri->segment($pagination_segment+7) : NULL;
        $link.= ($CI->uri->segment($pagination_segment+8) != NULL) ? '/'.$CI->uri->segment($pagination_segment+8) : NULL;
    endif;
    $pagination_segment_value = ($CI->uri->segment($pagination_segment)) ? $CI->uri->segment($pagination_segment) : 1;
    $pagination_url = $pagination_url.'/';
    $num_results = get_query($config, 'all')->num_rows();
    $num_links = ($num_results/$pagination_rows)+1;
    echo $pagination_prefix;
    if ($pagination_type == 'full' && $pagination_show_first_last && $num_links > 1 && $pagination_segment_value != 1):
        echo $pagination_prefix_item.'<a href="'.$pagination_url.'1'.$link.'">'.$pagination_first.'</a>'.$pagination_sufix_item;
    endif;
    if ($pagination_show_prev_next && $pagination_segment_value != 1):
        echo $pagination_prefix_item.'<a href="'.$pagination_url.($pagination_segment_value-1).$link.'">'.$pagination_prev.'</a>'.$pagination_sufix_item;
    endif;
    $count = 1;
    if ($pagination_type == 'full' && $pagination_show_links == TRUE):
        while($count < $num_links):
            if ($pagination_segment_value == $count):
                echo $pagination_prefix_item_active.'<a href="'.$pagination_url.$count.$link.'">'.$count.'</a>'.$pagination_sufix_item_active;
            else:
                echo $pagination_prefix_item.'<a href="'.$pagination_url.$count.$link.'">'.$count.'</a>'.$pagination_sufix_item;
            endif;
            $count++;
        endwhile;
    endif;
    if ($pagination_type != 'full'):
        echo $pagination_prefix_item.'<a href="'.$pagination_url.'1'.$link.'">'.$pagination_start.'</a>'.$pagination_sufix_item;
    endif;
    if ($pagination_show_prev_next && $pagination_segment_value < $num_links-1):
        echo $pagination_prefix_item.'<a href="'.$pagination_url.($pagination_segment_value+1).$link.'">'.$pagination_next.'</a>'.$pagination_sufix_item;
    endif;
    if ($pagination_type == 'full' && $pagination_show_first_last && $pagination_segment_value < $count-1):
        echo $pagination_prefix_item.'<a href="'.$pagination_url.($count-1).$link.'">'.$pagination_last.'</a>'.$pagination_sufix_item;
    endif;
    echo $pagination_sufix;
}

// ------------------------------------------------------------------------

 /**
 * Get TH Orderby
 *
 * Returns the contents of <th> to sort the items in the tables.
 * 
 * @access  private
 * @param   string string string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_th_orderby($label=NULL, $column=NULL, $config){
    $CI =& get_instance();
    $CI->load->helper('url');
    $pre_url = $config['pagination_url'].'/0';
    $search_link = ($CI->uri->segment($config['filter_value_segment']) != NULL) ? '/'.$CI->uri->segment($config['filter_key_segment']).'/'.$CI->uri->segment($config['filter_value_segment']): NULL;
    $orderby = ($CI->uri->segment($config['orderby_segment']) != NULL) ? $CI->uri->segment($config['orderby_segment']) : $config['default_orderby'];
    $order = ($CI->uri->segment($config['order_segment']) != NULL) ? $CI->uri->segment($config['order_segment']) : $config['default_order'];
    if ($column!=NULL && $config['filter_value_segment']!=NULL):
        if ($column==$orderby && $order=='asc'):
            return "<a href='$pre_url/orderby/$column/order/desc$search_link'><div>$label <i class='fa fa-caret-up'></i></div></a>";
        elseif ($column==$orderby && $order=='desc'):
            return "<a href='$pre_url/orderby/$column/order/asc$search_link'><div>$label <i class='fa fa-caret-down'></i></div></a>";
        else:
            return "<a href='$pre_url/orderby/$column/order/asc$search_link'><div>$label</div></a>";
        endif;
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Get TH Orderby
 *
 * Returns the link to search on bd.
 * 
 * @access  private
 * @param   string string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_paging_search($search_for=NULL, $config=NULL){
    $CI =& get_instance();
    $CI->load->helper('url');
    $method = ($CI->router->method != NULL) ? '/0' : '/page/0';
    $orderby = '/orderby/'.$config['default_orderby'].'/order/'.$config['default_order'].'/';
    $search_for = ($search_for != NULL) ? 'search/'.url_title($search_for) : NULL;
    redirect($config['pagination_url'].$method.$orderby.$search_for);
}

