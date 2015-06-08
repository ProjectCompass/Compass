<?php defined('BASEPATH') OR exit('No direct script access allowed');

function make_table($table_attibutes=array(), $head=array(), $body=array()){
    $CI =& get_instance();
    $CI->load->helper(array('url', 'paging'));
    $table_attibutes['type'] = (isset($table_attibutes['type'])) ? $table_attibutes['type'] : 'default';
    $table_attibutes['id'] = (isset($table_attibutes['id'])) ? $table_attibutes['id'] : NULL;
    $table_attibutes['class'] = (isset($table_attibutes['class'])) ? $table_attibutes['class'] : NULL;
    $table_attibutes['thead'] = (isset($table_attibutes['thead'])) ? $table_attibutes['thead'] : TRUE;
    $table_attibutes['tfoot'] = (isset($table_attibutes['tfoot'])) ? $table_attibutes['tfoot'] : TRUE;


    $config = array(
        'model'=>'users',
        'method'=>'return_list',
        'pagination_url'=>base_url('users/lists/'),
        'pagination_segment'=>3,
        'orderby_segment'=>5,
        'order_segment'=>7,
        'default_orderby'=>'user_username',
        'default_order'=>'asc',
        'filter_key_segment'=>8,
        'filter_value_segment'=>9
        );
        echo '<div id="users-index" class="row">';
            echo '<div class="small-8 columns">';
                echo '<h3 class="left">'.lang('users').'</h3>';
                echo anchor('users/insert', lang('core_insert_new'), 'class="button tiny radius button-h"');
            echo '</div>';
            echo '<div class="small-4 columns">';
                echo form_open(current_url(), 'id="users-index-form-search"');
                    echo '<div class="row">';
                        echo '<div class="row collapse">';
                            echo '<div class="small-7 columns">';
                                echo form_input(array('name'=>'search_for', 'placeholder'=>lang('core_search')), set_value('search', ($CI->uri->segment($config['filter_key_segment']) == 'search') ? $CI->uri->segment($config['filter_value_segment']) : NULL));
                            echo '</div>';
                            echo '<div class="small-5 columns">';
                                echo form_submit(array('name'=>'search', 'class'=>'small-11 button secondary tiny', 'title'=>lang('users_search')), lang('users_search'));
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo form_close();
            echo '</div>';
        echo '</div>';
        echo '<div class="row">';
            echo '<div class="small-12 columns links-filters">';
                $link_filter_current = ($CI->uri->segment($config['filter_value_segment']) == NULL) ? 'link-filter-current' : NULL;
                echo "<a class='link-filter-first ".$link_filter_current."' href='".$config['pagination_url']."'>".lang('users_all')."</a>";
                $query_userlevel = $CI->userslevels->get_all()->result();
                foreach ($query_userlevel as $line):
                    $CI->db->where('user_level', $line->userlevel_id);
                    $count_users_bylevel = $CI->users->get_all()->num_rows();
                    if ($count_users_bylevel > 0):
                        $link_filter_current = ($CI->uri->segment($config['filter_key_segment']) == 'filter_level' && $CI->uri->segment($config['filter_value_segment']) == $line->userlevel_id) ? 'link-filter-current' : NULL;
                        echo "<a class='".$link_filter_current."' href='".$config['pagination_url'].'/0/orderby/'.$config['default_orderby'].'/order/'.$config['default_order']."/filter_level/$line->userlevel_id"."'>$line->userlevel_name ($count_users_bylevel)</a>";
                    endif;
                endforeach;
            echo '</div>';
        echo '</div>';
        //CRIAÇÃO DO THEAD E TFOOT
        $theadANDtfoot = '';
        foreach ($head as $att):
            $att['type'] = (isset($att['type'])) ? $att['type'] : 'simple';
            $theadANDtfoot .= '<th';
                $theadANDtfoot .= (isset($att['class'])) ? ' class="'.$att['class'].'"' : '';
                $theadANDtfoot .= (isset($att['id'])) ? ' id="'.$att['id'].'"' : '';
                $theadANDtfoot .= '>';
                if ($att['type'] == 'order'):
                    $theadANDtfoot .= get_th_orderby($att['title'], $att['column'], $config);
                elseif ($att['type'] == 'simple'):
                    $theadANDtfoot .= $att['title'];
                endif;
            $theadANDtfoot .= '</th>';;
        endforeach;
        echo '<table id="users-index-table-list" class="columns">';
            if ($table_attibutes['thead'] == TRUE):
                echo '<thead>';
                    echo '<tr class="table-order">';
                        echo $theadANDtfoot;
                    echo '</tr>';
                echo '</thead>';
            endif;
            echo '<tbody>';
                $query_numrows = 0;
                foreach (get_query($config)->result() as $line):
                    echo '<tr><td>linha</td>';
                        foreach ($body as $att):
                            $att['type'] = (isset($att['type'])) ? $att['type'] : 'simple';
                            if ($att['type'] == 'simple'):
                                printf('<td>%s</td>', $line->$att['column']);
                            elseif ($att['type'] == 'block'):
                            endif;
                        endforeach;
                    echo '</tr>';
                    $query_numrows++;
                endforeach;

                foreach (get_query($config)->result() as $line):
                    if ($line->user_status != 9):
                        $query_userlevel = $CI->userslevels->get_by_id($line->user_level)->row();
                        echo '<tr>';
                            echo '<td class="table-operations"> ', avatar(get_usermeta("user_image", $line->user_id), 40, 40);
                            printf("<strong>%s</strong><br>%s %s</td>",
                                (access('perm_viewprofileusers_', NULL, TRUE)) ? anchor("users/profile/$line->user_id", $line->user_username, array('class'=>'table-item-featured', 'title'=>lang('core_profile'))) : $line->user_username, 
                                (access('perm_updateusers_', 'high', TRUE, $CI->users->get_by_id($line->user_id)->row()->user_level, $CI->users->get_by_id($line->user_id)->row()->user_username)) ? anchor("users/update/$line->user_id", lang('core_update'), array('class'=>'table-action table-action-first update', 'title'=>lang('core_update'))) : NULL, 
                                (access('perm_userdelete_', 'high', TRUE, $CI->users->get_by_id($line->user_id)->row()->user_level, $CI->users->get_by_id($line->user_id)->row()->user_username)) ? anchor("users/delete/$line->user_id", lang('core_delete'), array('class'=>'table-action delete deletereg', 'title'=>lang('core_delete'))) : NULL);
                            printf('<td>%s</td>', $line->user_name);
                            printf('<td>%s</td>', $line->user_email);
                            printf('<td>%s %s</td>', $query_userlevel->userlevel_name, ($line->user_status==0) ? '(Inativo)' : NULL);
                        echo '</tr>';
                        $query_numrows++;
                    endif;
                endforeach;
            echo '</tbody>';
            echo '<tfoot>';
                echo '<tr class="table-order">';
                    echo $theadANDtfoot;
                echo '</tr>';
            echo '</tfoot>';
        echo '</table>';
        echo '<div class="row">';
            echo '<div class="small-12 medium-6 large-4 columns">';
                $rows = get_query($config, 'all')->num_rows();
                echo "<small>".lang('core_showing')." ".$query_numrows." - $rows ".lang('core_registers')."</small>";
            echo '</div>';
            echo '<div class="small-12 medium-6 large-8 columns">';
                get_pagination($config);
            echo '</div>';
        echo '</div>';

}
