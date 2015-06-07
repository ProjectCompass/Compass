<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
    case 'submenu':
        echo 
            make_menu('submenu_item', lang('users'), 'users', NULL, 'perm_listusers_').
            make_menu('submenu_item', lang('core_insert_new'), 'users', 'insert', 'perm_insertusers_').
            make_menu('submenu_item', lang('settings'), 'users', 'settings', 'perm_userssettings_').
            make_menu('submenu_item', lang('core_profile'), 'users', 'profile/'.get_session('user_id'), 'perm_userspermissions_')
        ;
        break;
    case 'users':
        echo '<div id="users-index" class="row">';
            echo '<div class="small-8 columns">';
                echo '<h3 class="left">'.lang('users').'</h3>';
                echo anchor('users/insert', lang('core_insert_new'), 'class="addimg button button-tiny secondary radius space-v-medium space-h-small"');
            echo '</div>';
            echo '<div class="small-4 columns">';
                echo form_open(current_url(), 'id="users-index-form-search"');
                    echo '<div class="row">';
                        echo '<div class="row collapse">';
                            echo '<div class="small-7 columns">';
                                echo form_input(array('name'=>'search_for', 'placeholder'=>lang('core_search')), set_value('search', ($this->uri->segment($config['filter_key_segment']) == 'search') ? $this->uri->segment($config['filter_value_segment']) : NULL));
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
                $link_filter_current = ($this->uri->segment($config['filter_value_segment']) == NULL) ? 'link-filter-current' : NULL;
                echo "<a class='link-filter-first ".$link_filter_current."' href='".$config['pagination_url']."'>".lang('users_all')."</a>";
                $query_userlevel = $this->userslevels->get_all()->result();
                foreach ($query_userlevel as $line):
                    $this->db->where('user_level', $line->userlevel_id);
                    $count_users_bylevel = $this->users->get_all()->num_rows();
                    if ($count_users_bylevel > 0):
                        $link_filter_current = ($this->uri->segment($config['filter_key_segment']) == 'filter_level' && $this->uri->segment($config['filter_value_segment']) == $line->userlevel_id) ? 'link-filter-current' : NULL;
                        echo "<a class='".$link_filter_current."' href='".$config['pagination_url'].'/0/orderby/'.$config['default_orderby'].'/order/'.$config['default_order']."/filter_level/$line->userlevel_id"."'>$line->userlevel_name ($count_users_bylevel)</a>";
                    endif;
                endforeach;
            echo '</div>';
        echo '</div>';
        echo '<table id="users-index-table-list" class="columns">';
            echo '<thead>';
                echo '<tr class="table-order">';
                    echo '<th class="small-4 collums">'.get_th_orderby(lang('users_field_user_name'), 'user_username', $config).'</th>';
                    echo '<th class="small-3 collums">'.get_th_orderby(lang('users_field_name'), 'user_name', $config).'</th>';
                    echo '<th class="small-3 collums">'.get_th_orderby(lang('users_field_email'), 'user_email', $config).'</th>';
                    echo '<th class="small-2 collums">'.lang('users_levels').'</th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
                $query_numrows = 0;
                foreach (get_query($config)->result() as $line):
                    if ($line->user_status != 9):
                        $query_userlevel = $this->userslevels->get_by_id($line->user_level)->row();
                        echo '<tr>';
                            echo '<td class="table-operations"> ', avatar(get_usermeta("user_image", $line->user_id), 40, 40);
                            printf("<strong>%s</strong><br>%s %s</td>",
                                (access('perm_viewprofileusers_', NULL, TRUE)) ? anchor("users/profile/$line->user_id", $line->user_username, array('class'=>'table-item-featured', 'title'=>lang('core_profile'))) : $line->user_username, 
                                (access('perm_updateusers_', 'high', TRUE, $this->users->get_by_id($line->user_id)->row()->user_level, $this->users->get_by_id($line->user_id)->row()->user_username)) ? anchor("users/update/$line->user_id", lang('core_update'), array('class'=>'table-action table-action-first update', 'title'=>lang('core_update'))) : NULL, 
                                (access('perm_userdelete_', 'high', TRUE, $this->users->get_by_id($line->user_id)->row()->user_level, $this->users->get_by_id($line->user_id)->row()->user_username)) ? anchor("users/delete/$line->user_id", lang('core_delete'), array('class'=>'table-action delete deletereg', 'title'=>lang('core_delete'))) : NULL);
                            printf('<td>%s</td>', $line->user_name);
                            printf('<td>%s</td>', $line->user_email);
                            printf('<td>%s %s</td>', $query_userlevel->userlevel_name, ($line->user_status==0) ? '(Inativo)' : NULL);
                        echo '</tr>';
                        $query_numrows++;
                    endif;
                endforeach;
            echo '</tbody>';
            echo '<thead>';
                echo '<tr class="table-order">';
                    echo '<th class="small-4 collums">'.get_th_orderby(lang('users_field_user_name'), 'user_username', $config).'</th>';
                    echo '<th class="small-3 collums">'.get_th_orderby(lang('users_field_name'), 'user_name', $config).'</th>';
                    echo '<th class="small-3 collums">'.get_th_orderby(lang('users_field_email'), 'user_email', $config).'</th>';
                    echo '<th class="small-2 collums">'.lang('users_levels').'</th>';
                echo '</tr>';
            echo '</thead>';
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
            break;
    case 'profile':
        $iduser = $this->uri->segment(3);
        if ($iduser==NULL):
            set_msg('msgerror', lang('users_msg_choose_profile'), 'error');
            redirect('users');
        endif;
        $query = $this->users->get_by_id($iduser)->row();
            echo '<ul class="pricing-table small-12 medium-10 large-8 small-centered columns">';
                echo '<li class="title">'.avatar(get_usermeta('user_image', $query->user_id), 150, 150).'<br>'.$query->user_name.'<br>'.$query->user_displayname.'</li>';
                if ($query->user_email) echo '<li class="price">'.$query->user_email.'</li>';
                if (get_usermeta('user_about', $query->user_id)) echo '<li class="description">'.get_usermeta('user_about', $query->user_id).'</li>  ';
                if (get_usermeta('user_url', $query->user_id)) echo '<li class="bullet-item">'.lang('users_field_url').' '.anchor(get_usermeta('user_url', $query->user_id), get_usermeta('user_url', $query->user_id)).'</li>';
                if ($query->user_level) echo '<li class="bullet-item">'.lang('users_levels').' '.$this->userslevels->get_by_id($query->user_level)->row()->userlevel_name.'</li>';
                if (get_setting('users_show_adress')):
                    if (get_usermeta('user_adress', $query->user_id)) echo '<li class="description">'.lang('users_field_address').' '.get_usermeta('user_adress', $query->user_id).'</li>';
                    if (get_usermeta('user_doc', $query->user_id)) echo '<li class="bullet-item">'.lang('users_field_doc').' '.get_usermeta('user_doc', $query->user_id).'</li>';
                endif;
                $query_usersfields = $this->terms->get_by_type('userfield')->result();
                foreach ($query_usersfields as $line):
                    if (get_termmeta('termmeta_profile', $line->term_id) == 1):
                        if (get_termmeta('termmeta_type', $line->term_id) == 'input'):
                            echo '<li class="bullet-item">'.$line->term_name.': '.get_usermeta($line->term_name, $query->user_id).'</li>';
                        elseif(get_termmeta('termmeta_type', $line->term_id) == 'textarea'):
                            echo '<li class="description">'.$line->term_name.': '.get_usermeta($line->term_name, $query->user_id).'</li>';
                        elseif(get_termmeta('termmeta_type', $line->term_id) == 'select'):
                            echo '<li class="bullet-item">'.$line->term_name.': '.get_usermeta($line->term_name, $query->user_id).'</li>';
                        elseif(get_termmeta('termmeta_type', $line->term_id) == 'radio'):
                            echo '<li class="bullet-item">'.$line->term_name.': ';
                            if (get_termmeta('term_option1_value', $line->term_id)==get_usermeta($line->term_slug, $query->user_id)):
                                echo get_termmeta('term_option1_name', $line->term_id);
                            elseif (get_termmeta('term_option2_value', $line->term_id)==get_usermeta($line->term_slug, $query->user_id)):
                                echo get_termmeta('term_option2_name', $line->term_id);
                            elseif (get_termmeta('term_option3_value', $line->term_id)==get_usermeta($line->term_slug, $query->user_id)):
                                echo get_termmeta('term_option3_name', $line->term_id);
                            elseif (get_termmeta('term_option4_value', $line->term_id) == get_usermeta($line->term_slug, $query->user_id)):
                                echo get_termmeta('term_option4_name', $line->term_id);
                            endif;
                            echo '</li>';
                        elseif (get_termmeta('termmeta_type', $line->term_id)=='checkbox' && get_usermeta($line->term_slug, $query->user_id)==1):
                            echo '<li class="bullet-item">'.$line->term_name.'</li>';
                        endif;
                    endif;
                endforeach;
            echo '<li class="cta-button">
                    <a class="button tiny" href="'.base_url('users/update/'.$query->user_id.NULL).'">'.lang('users_edit_profile').'</a>
                </li>
            </ul>';
        break;
    case 'insert':
        echo '<div id="users-insert" class="row">';
            echo '<div class="small-12 medium-10 large-8 columns">';
                echo '<h3>Cadastrar novo usuário</h3>';
                echo form_open('users/insert', 'id="users-insert-form-insert"');
                    echo form_label(lang('users_field_user_name'));
                    echo form_input(array('name'=>'user_username', 'placeholder'=>lang('users_field_user_name')), set_value('user_username'), 'autofocus');
                    echo '<div class="row">';
                        echo '<div class="small-6 columns">';
                            echo form_label(lang('users_field_email'));
                            echo form_input(array('name'=>'user_email', 'placeholder'=>lang('users_field_email')), set_value('user_email'));
                        echo '</div>';
                        echo '<div class="small-6 columns">';
                            echo form_label(lang('users_field_email_repeat'));
                            echo form_input(array('name'=>'user_email2', 'placeholder'=>lang('users_field_email_repeat')), set_value('user_email2'));
                        echo '</div>';
                    echo '</div>';
                    echo form_label(lang('users_field_name'));
                    echo form_input(array('name'=>'user_name', 'placeholder'=>lang('users_field_name')), set_value('user_name'));
                    echo form_label(lang('users_field_display_name'));
                    echo form_input(array('name'=>'user_displayname', 'placeholder'=>lang('users_field_display_name')), set_value('user_displayname'));
                    echo '<div class="row">';
                        echo '<div class="small-6 columns">';
                            echo form_label(lang('users_field_pass'));
                            echo form_password(array('name'=>'user_pass', 'placeholder'=>lang('users_field_pass')), set_value('user_pass'));
                        echo '</div>';
                        echo '<div class="small-6 columns">';
                            echo form_label(lang('users_field_pass_repeat'));
                            echo form_password(array('name'=>'user_pass2', 'placeholder'=>lang('users_field_pass_repeat')), set_value('user_pass2'));
                        echo '</div>';
                    echo '</div>';
                    $options = array();
                    $query_userslevels = $this->userslevels->get_all()->result();
                    foreach ($query_userslevels as $line):
                        if (access('perm_updateuserlevel_', 'high', TRUE, $line->userlevel_id, NULL)): 
                            $options[$line->userlevel_id] = $line->userlevel_name;
                            $show_dropdown = TRUE;
                        endif;
                    endforeach;
                    if (isset($show_dropdown)):
                        echo '<div class="row">';
                            echo '<div class="small-6 columns">';
                                echo form_label(lang('users_userlevel_for'));
                                echo form_dropdown('user_level', $options, get_setting('users_level_default'));
                            echo '</div>';
                        echo '</div>';
                    else:
                        echo form_hidden(get_setting('users_level_default'));
                    endif;
                    $query_usersfields = $this->terms->get_by_type('userfield')->result();
                    foreach ($query_usersfields as $line):
                        if (get_termmeta('termmeta_register', $line->term_id) == 1):
                            if (get_termmeta('termmeta_type', $line->term_id) == 'input'):
                                echo form_label($line->term_name).form_input(array('name'=>$line->term_slug, 'placeholder'=>$line->term_name), set_value($line->term_slug));
                            elseif (get_termmeta('termmeta_type', $line->term_id) == 'textarea'):
                                echo form_label($line->term_name).form_textarea(array('name'=>$line->term_slug, 'placeholder'=>$line->term_name), set_value($line->term_slug));
                            elseif (get_termmeta('termmeta_type', $line->term_id) == 'select'):
                                echo form_label($line->term_name);
                                $options = array(
                                    NULL => '--',
                                    get_termmeta('term_option1_value', $line->term_id) => get_termmeta('term_option1_name', $line->term_id),
                                    get_termmeta('term_option2_value', $line->term_id) => get_termmeta('term_option2_name', $line->term_id),
                                    get_termmeta('term_option3_value', $line->term_id) => get_termmeta('term_option3_name', $line->term_id),
                                    get_termmeta('term_option4_value', $line->term_id) => get_termmeta('term_option4_name', $line->term_id),
                                    );
                                echo form_dropdown($line->term_slug, $options, get_usermeta($line->term_slug));
                            elseif (get_termmeta('termmeta_type', $line->term_id) == 'radio'):
                                echo form_label($line->term_name);
                                if (get_termmeta('term_option1_value', $line->term_id)!=NULL):
                                    echo form_radio(array('name'=>$line->term_slug), get_termmeta('term_option1_value', $line->term_id)).form_label(get_termmeta('term_option1_name', $line->term_id));
                                endif;
                                if (get_termmeta('term_option2_value', $line->term_id)!=NULL):
                                    echo form_radio(array('name'=>$line->term_slug), get_termmeta('term_option2_value', $line->term_id)).form_label(get_termmeta('term_option2_name', $line->term_id));
                                endif;
                                if (get_termmeta('term_option3_value', $line->term_id)!=NULL):
                                    echo form_radio(array('name'=>$line->term_slug), get_termmeta('term_option3_value', $line->term_id)).form_label(get_termmeta('term_option3_name', $line->term_id));
                                endif;
                                if (get_termmeta('term_option4_value', $line->term_id)!=NULL):
                                    echo form_radio(array('name'=>$line->term_slug), get_termmeta('term_option4_value', $line->term_id)).form_label(get_termmeta('term_option4_name', $line->term_id));
                                endif;
                            elseif (get_termmeta('termmeta_type', $line->term_id) == 'checkbox'):
                                echo form_label($line->term_name);
                                echo form_checkbox(array('name'=>$line->term_slug), '1').form_label($line->term_name);
                            endif;
                        endif;
                    endforeach;
                    echo '<div class=row>';
                        echo '<div class="small-12 columns">';
                            echo form_submit(array('name'=>'save', 'class'=>'button radius small tiny'), lang('core_save'));
                        echo '</div>';
                    echo '</div>';
                echo form_close();
            echo '</div>';
        echo '</div>';
        break;
    case 'update':
        $iduser = $this->uri->segment(3);
        if ($iduser==NULL):
            set_msg('msgerror', lang('users_msg_no_find_delete'), 'error');
            NULL('users');
        endif;
        $query = $this->users->get_by_id($iduser)->row();
        echo '<div id="users-update" class="row">';
            echo '<div class="small-12 medium-10 large-8 columns">';
                echo '<h3>'.lang('users_update').'</h3>';
                echo form_open_multipart(current_url(), 'id="users-update-form-update"');
                    echo form_label(lang('users_field_user_name'));
                    echo form_input(array('name'=>'user_username', 'disabled'=>'disabled'), set_value('user_username', $query->user_username));
                    echo '<div class="row">';
                        echo '<div class="small-8 columns">';
                            $query_userslevels = $this->userslevels->get_all()->result();
                            $options = array();
                            foreach ($query_userslevels as $line):
                                if (access('perm_updateuserlevel_', 'high', TRUE, $line->userlevel_id, NULL)): 
                                    $options[$line->userlevel_id] = $line->userlevel_name;
                                    $show_dropdown = TRUE;
                                endif;
                            endforeach;
                            if (isset($show_dropdown)):
                                echo form_label(lang('users_userlevel_for'));
                                echo form_dropdown('user_level', $options, $query->user_level);
                            else:
                                echo form_label(lang('users_userlevel_for'));
                                echo form_input(array('disabled'=>'disabled'), $this->userslevels->get_by_id($query->user_level)->row()->userlevel_name);
                            endif;
                        echo '</div>';
                        echo '<div class="small-4 columns">';
                            if (access('perm_updateuserstatus_', NULL, TRUE)): 
                                echo form_label('Ativar usuário');
                                $options = array('1'=>lang('core_yes'), '0'=>lang('core_no'));
                                echo form_dropdown('user_status', $options, $query->user_status);
                            else:
                                echo form_label(lang('users_active'));
                                echo form_input(array('disabled'=>'disabled'), ($query->user_status == '0') ? lang('core_no') : lang('core_yes'));
                            endif;
                        echo '</div>';
                    echo '</div>';
                    echo form_label(lang('users_field_name'));
                    echo form_input(array('name'=>'user_name', 'placeholder'=>lang('users_field_name')), set_value('user_name', $query->user_name));
                    echo form_label(lang('users_field_display_name'));
                    echo form_input(array('name'=>'user_displayname', 'placeholder'=>lang('users_field_display_name')), set_value('user_displayname', $query->user_displayname));
                    echo form_label(lang('users_field_doc'));
                    echo form_input(array('name'=>'user_doc', 'placeholder'=>lang('users_field_doc')), set_value('user_doc', get_usermeta('user_doc', $query->user_id)));
                    echo form_label(lang('users_field_email'));
                    echo form_input(array('name'=>'user_email', 'placeholder'=>lang('users_field_email')), set_value('user_email', $query->user_email));
                    echo form_label(lang('users_field_url'));
                    echo form_input(array('name'=>'user_url', 'placeholder'=>lang('users_field_url')), set_value('user_about', get_usermeta('user_url', $query->user_id)));
                    echo form_label(lang('users_field_about'));
                    echo form_textarea(array('name'=>'user_about', 'rows'=>'3', 'placeholder'=>lang('users_field_about')), set_value('user_about', get_usermeta('user_about', $query->user_id)));
                    echo form_label(lang('users_field_address'));
                    echo form_input(array('name'=>'user_adress', 'placeholder'=>lang('users_field_address')), set_value('user_adress', get_usermeta('user_adress', $query->user_id)));
                    echo '<div class="row">';
                        echo '<div class="small-6 columns">';
                            echo form_label(lang('users_field_pass_new'));
                            echo form_password(array('name'=>'user_pass', 'placeholder'=>lang('users_field_pass')), set_value('user_pass'));
                        echo '</div>';
                        echo '<div class="small-6 columns">';
                            echo form_label(lang('users_field_pass_new_repeat'));
                            echo form_password(array('name'=>'user_pass2', 'placeholder'=>lang('users_field_pass_repeat')), set_value('user_pass2'));
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="row">';
                        echo '<div class="small-12 columns">';
                            echo form_label(lang('users_profile_image'));
                            if (get_usermeta('user_image', $query->user_id) == NULL):
                                echo '<div class="small-7 columns">';
                                    echo form_upload(array('name'=>'user_image'), set_value('user_image'));
                                echo '</div>';
                                echo '<div class="small-2 columns left">';
                                    echo form_submit(array('name'=>'upload_image', 'class'=>'button tiny'), lang('users_send_image'));
                                echo '</div>';
                            else:
                                echo '<div class="small-2 columns">';
                                    echo avatar(get_usermeta('user_image', $query->user_id));
                                echo '</div>';
                                echo '<div class="small-2 columns left">';
                                    echo form_submit(array('name'=>'delete_image', 'class'=>'button alert tiny'), lang('users_remove_image'));
                                echo '</div>';
                            endif;
                        echo '</div>';
                    echo '</div>';
                    $query_usersfields = $this->terms->get_by_type('userfield')->result();
                    foreach ($query_usersfields as $line):
                        if (get_termmeta('termmeta_update', $line->term_id) == 1):
                            if (get_termmeta('termmeta_type', $line->term_id) == 'input'):
                                echo form_label($line->term_name).form_input(array('name'=>$line->term_slug, 'placeholder'=>$line->term_name), set_value($line->term_slug, get_usermeta($line->term_slug, $query->user_id)));
                            elseif (get_termmeta('termmeta_type', $line->term_id) == 'textarea'):
                                echo form_label($line->term_name).form_textarea(array('name'=>$line->term_slug, 'rows'=>'3', 'placeholder'=>$line->term_name), set_value($line->term_slug, get_usermeta($line->term_slug, $query->user_id)));
                            elseif (get_termmeta('termmeta_type', $line->term_id) == 'select'):
                                echo form_label($line->term_name);
                                $options = array(
                                    NULL => '--',
                                    get_termmeta('term_option1_value', $line->term_id) => get_termmeta('term_option1_name', $line->term_id),
                                    get_termmeta('term_option2_value', $line->term_id) => get_termmeta('term_option2_name', $line->term_id),
                                    get_termmeta('term_option3_value', $line->term_id) => get_termmeta('term_option3_name', $line->term_id),
                                    get_termmeta('term_option4_value', $line->term_id) => get_termmeta('term_option4_name', $line->term_id),
                                    );
                                echo form_dropdown($line->term_slug, $options, get_usermeta($line->term_slug, $query->user_id));
                            elseif (get_termmeta('termmeta_type', $line->term_id) == 'radio'):
                                echo form_label($line->term_name);
                                if (get_termmeta('term_option1_value', $line->term_id)!=NULL):
                                    echo form_radio(array('name'=>$line->term_slug), get_termmeta('term_option1_value', $line->term_id), (get_usermeta($line->term_slug, $query->user_id)==get_termmeta('term_option1_value', $line->term_id)) ? TRUE : FALSE).form_label(get_termmeta('term_option1_name', $line->term_id));
                                endif;
                                if (get_termmeta('term_option2_value', $line->term_id)!=NULL):
                                    echo form_radio(array('name'=>$line->term_slug), get_termmeta('term_option2_value', $line->term_id), (get_usermeta($line->term_slug, $query->user_id)==get_termmeta('term_option2_value', $line->term_id)) ? TRUE : FALSE).form_label(get_termmeta('term_option2_name', $line->term_id));
                                endif;
                                if (get_termmeta('term_option3_value', $line->term_id)!=NULL):
                                    echo form_radio(array('name'=>$line->term_slug), get_termmeta('term_option3_value', $line->term_id), (get_usermeta($line->term_slug, $query->user_id)==get_termmeta('term_option3_value', $line->term_id)) ? TRUE : FALSE).form_label(get_termmeta('term_option3_name', $line->term_id));
                                endif;
                                if (get_termmeta('term_option4_value', $line->term_id)!=NULL):
                                    echo form_radio(array('name'=>$line->term_slug), get_termmeta('term_option4_value', $line->term_id), (get_usermeta($line->term_slug, $query->user_id)==get_termmeta('term_option4_value', $line->term_id)) ? TRUE : FALSE).form_label(get_termmeta('term_option4_name', $line->term_id));
                                endif;
                            elseif (get_termmeta('termmeta_type', $line->term_id) == 'checkbox'):
                                echo form_label($line->term_name);
                                echo form_checkbox(array('name'=>$line->term_slug), '1', (get_usermeta($line->term_slug, $query->user_id)==1) ? TRUE : FALSE).form_label($line->term_name);
                            endif;
                        endif;
                    endforeach;
                    echo form_hidden(array('iduser'=>$iduser, 'emailuser'=>$query->user_email));
                    echo '<div class=row><div class="small-3 columns">'.form_submit(array('name'=>'save', 'class'=>'button radius small tiny'), lang('core_save')).'</div></div>';
                echo form_close();
            echo '</div>';
        echo '</div>';
        break;
    case 'settings':
        echo '<div id="user-settings" class="row">';
            echo '<div class="small-12 columns">';
                echo '<h3>'.lang('users_settings_general').'</h3>';
            echo '</div>';
            echo '<div class="small-12 columns">';
                echo form_open(current_url(), 'id="users-settings-form-general"');
                    echo '<div class="row">';
                        echo '<div class="small-4 columns">';
                            echo form_label(lang('users_settings_signup'));
                        echo '</div>';
                        echo '<div class="small-8 columns">';
                            echo form_radio(array('name'=>'users_member_site', 'id'=>'users_member_site_y'), '1', (get_setting('users_member_site')==1) ? TRUE : FALSE);
                            echo form_label(lang('core_yes'), 'users_member_site_y');
                            echo form_radio(array('name'=>'users_member_site', 'id'=>'users_member_site_n'), '0', (get_setting('users_member_site')==0) ? TRUE : FALSE);
                            echo form_label(lang('core_no'), 'users_member_site_n');
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="row">';
                        echo '<div class="small-8 medium-6 large-4 columns">';
                            echo form_label(lang('users_settings_default_userlevel'));
                        echo '</div>'; 
                        echo '<div class="small-4 medium-3 large-3 left columns">';
                            $query_userslevels = $this->userslevels->get_all()->result();
                            $options = array();
                            foreach ($query_userslevels as $line):
                                $options[$line->userlevel_id] = $line->userlevel_name;
                            endforeach;
                            echo form_dropdown('users_level_default', $options, get_setting('users_level_default'));
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="row">';
                        echo '<div class="small-4 columns">';
                            echo form_label(lang('users_settings_show_address'));
                        echo '</div>';
                        echo '<div class="small-8 columns">';
                            echo form_radio(array('name'=>'users_show_adress', 'id'=>'users_show_adress_y'), '1', (get_setting('users_show_adress')==1) ? TRUE : FALSE);
                            echo form_label(lang('core_yes'), 'users_show_adress_y');
                            echo form_radio(array('name'=>'users_show_adress', 'id'=>'users_show_adress_n'), '0', (get_setting('users_show_adress')==0) ? TRUE : FALSE);
                            echo form_label(lang('core_no'), 'users_show_adress_n');
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="row">';
                        echo '<div class="small-4 columns">';
                            echo form_label(lang('users_settings_clear'));
                        echo '</div>';
                        echo '<div class="small-8 columns">';
                            echo form_checkbox(array('name'=>'users_clear_remembers', 'id'=>'users_clear_remembers_y'), '1');
                            echo form_label(lang('core_yes'), 'users_clear_remembers_y');
                        echo '</div>';
                    echo '</div>';
                    echo form_submit(array('name'=>'save', 'class'=>'button radius tiny'), lang('core_save_settings'));
                    echo form_submit(array('name'=>'advanced_settings', 'class'=>'button radius tiny secondary'), (get_setting('users_advanced_settings') == 0) ? lang('users_settings_advanced_enable') : lang('users_settings_advanced_disable'));
                echo form_close();
            echo '</div>';
        echo '</div>';
        break;
    case 'usersfields':
        echo '<div id="user-settings" class="row">';
            echo '<div class="small-12 columns">';
                echo '<div class="row">';
                    echo '<div class="small-12 columns">';
                        echo '<h3>'.lang('users_usersfields').'</h3>';
                    echo '</div>';
                    echo '<div class="small-5 columns">';
                        if ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL):
                            $query_term = $this->terms->get_by_id($this->uri->segment(4))->row();
                        endif;
                        echo '<h5>'.lang('users_usersfields_insert').'</h5>';
                        if ($this->uri->segment(3) == NULL):
                            set_theme('headerinc', load_module('includes_view', 'elementshiden'), FALSE);
                        endif;
                        echo form_open(current_url(), 'id="users-settings-form-insert-field"');
                            echo '<div class="row">';
                                echo '<div class="small-4 columns">';
                                    echo form_label('<strong>'.lang('users_usersfields_new_type').'</strong>');
                                echo '</div>';
                                echo '<div class="small-5 columns">';
                                    $options = array(
                                        NULL=>'--',
                                        'input'=>'Input',
                                        'textarea'=>'Textarea',
                                        'select'=>'Select',
                                        'radio'=>'Radio',
                                        'checkbox'=>'Checkbox'
                                        );
                                    echo form_dropdown('termmeta_type', $options, ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? get_termmeta('termmeta_type', $this->uri->segment(4)) : NULL);
                                echo '</div>';
                                echo '<div class="small-3 columns">';
                                    echo form_input(array('name'=>'term_order', 'placeholder'=>lang('users_usersfields_order')), set_value('term_order', ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? $query_term->term_order : NULL));
                                echo '</div>';
                            echo '</div>';
                            echo '<div class="elements">';
                                echo '<div class="row input textarea select radio checkbox">';
                                    echo '<div class="small-6 columns">';
                                        echo form_input(array('name'=>'term_name', 'placeholder'=>lang('users_usersfields_title_label')), set_value('term_name', ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? $query_term->term_name : NULL));
                                    echo '</div>';
                                    echo '<div class="small-6 columns">';
                                        echo form_input(array('name'=>'term_slug', 'placeholder'=>lang('users_usersfields_slug_name')), set_value('term_slug', ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? $query_term->term_slug : NULL));
                                    echo '</div>';
                                    echo '<div class="small-12 columns">';
                                        echo form_textarea(array('name'=>'termmeta_description', 'rows'=>'3', 'placeholder'=>lang('users_usersfields_description')), set_value('termmeta_description', ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? get_termmeta('termmeta_description', $this->uri->segment(4)) : NULL));
                                    echo '</div>';
                                echo '</div>';
                                if (get_termmeta('termmeta_type', $this->uri->segment(4)) == 'select' || get_termmeta('termmeta_type', $this->uri->segment(4)) == 'radio' || get_termmeta('termmeta_type', $this->uri->segment(4)) == NULL):
                                    echo '<div class="row select radio">';
                                        echo '<div class="small-6 medium-6 large-3 columns">';
                                            echo form_label(lang('users_usersfields_option').' a');
                                            echo form_input(array('name'=>'term_option1_name', 'placeholder'=>lang('users_usersfields_name')), set_value('term_option1_name', ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? get_termmeta('term_option1_name', $this->uri->segment(4)) : NULL));
                                            echo form_input(array('name'=>'term_option1_value', 'placeholder'=>lang('users_usersfields_value')), set_value('term_option1_value', ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? get_termmeta('term_option1_value', $this->uri->segment(4)) : NULL));
                                        echo '</div>';
                                        echo '<div class="small-6 medium-6 large-3 columns">';
                                            echo form_label(lang('users_usersfields_option').' b');
                                            echo form_input(array('name'=>'term_option2_name', 'placeholder'=>lang('users_usersfields_name')), set_value('term_option2_name', ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? get_termmeta('term_option2_name', $this->uri->segment(4)) : NULL));
                                            echo form_input(array('name'=>'term_option2_value', 'placeholder'=>lang('users_usersfields_value')), set_value('term_option2_value', ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? get_termmeta('term_option2_value', $this->uri->segment(4)) : NULL));
                                        echo '</div>';
                                        echo '<div class="small-6 medium-6 large-3 columns">';
                                            echo form_label(lang('users_usersfields_option').' c');
                                            echo form_input(array('name'=>'term_option3_name', 'placeholder'=>lang('users_usersfields_name')), set_value('term_option3_name', ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? get_termmeta('term_option3_name', $this->uri->segment(4)) : NULL));
                                            echo form_input(array('name'=>'term_option3_value', 'placeholder'=>lang('users_usersfields_value')), set_value('term_option3_value', ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? get_termmeta('term_option3_value', $this->uri->segment(4)) : NULL));
                                        echo '</div>';
                                        echo '<div class="small-6 medium-6 large-3 columns">';
                                            echo form_label(lang('users_usersfields_option').' d');
                                            echo form_input(array('name'=>'term_option4_name', 'placeholder'=>lang('users_usersfields_name')), set_value('term_option4_name', ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? get_termmeta('term_option4_name', $this->uri->segment(4)) : NULL));
                                            echo form_input(array('name'=>'term_option4_value', 'placeholder'=>lang('users_usersfields_value')), set_value('term_option4_value', ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? get_termmeta('term_option4_value', $this->uri->segment(4)) : NULL));
                                        echo '</div>';
                                    echo '</div>';
                                endif;
                                echo '<div class="row input textarea select radio checkbox">';
                                    echo '<div class="small-6 medium-6 large-3 columns">';
                                        echo form_label(lang('users_usersfields_show_required'));
                                        echo form_dropdown('termmeta_required', array('1'=>lang('core_yes'), '0'=>lang('core_no')), ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? get_termmeta('termmeta_required', $this->uri->segment(4)) : '1');
                                    echo '</div>';
                                    echo '<div class="small-6 medium-6 large-3 columns">';
                                        echo form_label(lang('users_usersfields_show_register'));
                                        echo form_dropdown('termmeta_register', array('1'=>lang('core_yes'), '0'=>lang('core_no')), ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? get_termmeta('termmeta_register', $this->uri->segment(4)) : '1');
                                    echo '</div>';
                                    echo '<div class="small-6 medium-6 large-3 columns">';
                                        echo form_label(lang('users_usersfields_show_profile'));
                                        echo form_dropdown('termmeta_profile', array('1'=>lang('core_yes'), '0'=>lang('core_no')), ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? get_termmeta('termmeta_profile', $this->uri->segment(4)) : '1');
                                    echo '</div>';
                                    echo '<div class="small-6 medium-6 large-3 columns">';
                                        echo form_label(lang('users_usersfields_show_update'));
                                        echo form_dropdown('termmeta_update', array('1'=>lang('core_yes'), '0'=>lang('core_no')), ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL) ? get_termmeta('termmeta_update', $this->uri->segment(4)) : '1');
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                            if ($this->uri->segment(3) == 'updateuserfield' && $this->uri->segment(4) != NULL):
                                echo form_hidden('idterm', $this->uri->segment(4));
                            endif;
                            echo form_hidden('term_type', 'userfield');
                            echo form_submit(array('name'=>($this->uri->segment(3) == 'updateuserfield') ? 'update_userfield' : 'save_userfield', 'class'=>'button radius tiny'), ($this->uri->segment(3) == 'updateuserfield') ? lang('users_usersfields_update') : lang('users_usersfields_insert'));
                            echo anchor('users/settings', ' '.lang('core_cancel'), array('class'=>'alertlink'));
                        echo form_close();
                    echo '</div>';
                    echo '<div class="small-7 columns">';
                        echo '<h5>'.lang('users_usersfields').'</h5>';
                            echo '<table id="users-settings-table-fields">';
                                echo '<thead>';
                                    echo '<tr>';
                                        echo '<th class="small-1 collums">'.lang('users_usersfields_order').'</th>';
                                        echo '<th class="small-3 collums">'.lang('users_usersfields_name').'</th>';
                                        echo '<th class="small-2 collums">'.lang('users_usersfields_label').'</th>';
                                        echo '<th class="small-4 collums">'.lang('users_usersfields_description').'</th>';
                                        echo '<th class="small-2 collums">'.lang('users_usersfields_type').'</th>';
                                    echo '</tr>';
                                echo '</thead>';
                                echo '<tbody>';
                                    $query = $this->terms->get_by_type('userfield')->result();
                                    $id = 1;
                                    foreach ($query as $line):
                                        if ($line->term_type=='userfield'):
                                        echo '<tr>';
                                            printf('<td>%s</td>', $line->term_order);
                                            printf('<td class="table-operations"><strong>%s</strong><br>%s %s</td>', $line->term_name,
                                                anchor("users/settings/updateuserfield/$line->term_id", lang('core_update'), array('class'=>'table-actions update', 'title'=>lang('core_update'))), 
                                                anchor("users/settings/deleteuserfield/$line->term_id", lang('core_delete'), array('class'=>'table-actions delete deletereg', 'title'=>lang('core_delete'))));
                                            printf('<td>%s</td>', $line->term_slug);
                                            printf('<td>%s</td>', get_termmeta('termmeta_description', $line->term_id));
                                            printf('<td>%s</td>', get_termmeta('termmeta_type', $line->term_id));
                                        echo '</tr>';
                                        $id++;
                                        endif;
                                    endforeach;
                                echo '</tbody>';
                            echo '</table>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
    case 'userslevels':
        echo '<div id="user-settings" class="row">';
            echo '<div class="small-12 columns">';
                echo '<div class="row">';
                    echo '<div class="small-12 columns">';
                        echo '<h3>'.lang('users_userslevels').'</h3>';
                    echo '</div>';
                    echo '<div class="small-5 columns">';
                        if ($this->uri->segment(3) == 'updateuserlevel' && $this->uri->segment(4) != NULL):
                            $query_userslevels = $this->userslevels->get_by_id($this->uri->segment(4))->row();
                        endif;
                        echo form_open(current_url(), 'id="users-settings-form-insert-atribuition"');
                            echo '<h5>'.lang('users_userslevels_insert').'</h5>';
                            echo '<div class="row">';
                                echo '<div class="small-9 columns">';
                                    echo form_input(array('name'=>'userlevel_name', 'placeholder'=>lang('users_userslevels_new_name')), set_value('userlevel_name', ($this->uri->segment(3) == 'updateuserlevel' && $this->uri->segment(4) != NULL) ? $query_userslevels->userlevel_name : NULL));
                                echo '</div>';
                                echo '<div class="small-3 columns">';
                                    echo form_input(array('name'=>'userlevel_level', 'placeholder'=>lang('users_userslevels_level')), set_value('userlevel_level', ($this->uri->segment(3) == 'updateuserlevel' && $this->uri->segment(4) != NULL) ? $query_userslevels->userlevel_level : NULL));
                                echo '</div>';
                            echo '</div>';
                            echo form_textarea(array('name'=>'userlevel_description', 'rows'=>'3', 'placeholder'=>lang('users_userslevels_new_description')), set_value('userlevel_description', ($this->uri->segment(3) == 'updateuserlevel' && $this->uri->segment(4) != NULL) ? $query_userslevels->userlevel_description : NULL));
                            if ($this->uri->segment(3) == 'updateuserlevel' && $this->uri->segment(4) != NULL):
                                echo form_hidden('iduserlevel', $this->uri->segment(4));
                            endif;
                            echo form_submit(array('name'=>($this->uri->segment(3) == 'updateuserlevel' && $this->uri->segment(4) != NULL) ? 'update_userlevel' : 'save_userlevel', 'class'=>'button radius tiny'), ($this->uri->segment(3) == 'updateuserlevel' && $this->uri->segment(4) != NULL) ? lang('users_userslevels_update') : lang('users_userslevels_insert'));
                            echo anchor('users/settings', ' '.lang('core_cancel'), array('class'=>'alertlink'));
                        echo form_close();
                    echo '</div>';
                    echo '<div class="small-7 columns">';
                        echo '<h5>Atribuições</h5>';
                        echo '<table id="users-settings-table-atribuitions">';
                            echo '<thead>';
                                echo '<tr>';
                                    echo '<th class="small-1 collums">'.lang('users_userslevels_level').'</th>';
                                    echo '<th class="small-4 collums">'.lang('users_userslevels_name').'</th>';
                                    echo '<th class="small-7 collums">'.lang('users_userslevels_description').'</th>';
                                echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                                $query = $this->userslevels->get_all()->result();
                                foreach ($query as $line):   
                                    echo '<tr>';
                                        printf('<td>%s</td>', $line->userlevel_level);
                                        printf('<td class="table-operations"><strong>%s</strong><br>%s %s</td>', $line->userlevel_name,
                                            anchor("users/settings/updateuserlevel/$line->userlevel_id", lang('core_update'), array('class'=>'table-actions update', 'title'=>lang('core_update'))), 
                                            anchor("users/settings/deleteuserlevel/$line->userlevel_id", lang('core_delete'), array('class'=>'table-actions delete deletereg', 'title'=>lang('core_delete'))));
                                        printf('<td>%s</td>', $line->userlevel_description);
                                    echo '</tr>';
                                endforeach;
                            echo '</tbody>';
                        echo '</table>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
}