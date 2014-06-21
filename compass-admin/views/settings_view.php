<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");
switch ($screen):
    case 'submenu':
        echo 
            make_menu('submenu_item', lang('settings'), 'settings').
            make_menu('submenu_item', lang('settings_aparence'), 'settings', 'aparence', 'perm_settings_', get_setting('general_advanced_settings')).
            make_menu('submenu_item', lang('core_permissions'), 'settings', 'permissions', 'perm_settings_', get_setting('general_advanced_settings'))
        ;
        break;
	case 'general':
		echo '<div id="settings-general" class="row">';
            echo '<div class="small-12 columns">';
				echo form_open(current_url(), 'id="settings-index-form-general"');
					echo '<div class="row">';
						echo '<div class="small-12 columns">';
							echo '<h3>'.lang('settings').'</h3>';
						echo '</div>';
					echo '</div>';
					echo '<div class="row">';
						echo '<div class="small-3 columns">';
			                echo form_label(lang('settings_title'));
		                echo '</div>'; 
		                echo '<div class="small-9 medium-7 large-5 left columns">';
						    echo form_input(array('name'=>'general_title_site'), set_value('general_title_site', get_setting('general_title_site')));
						echo '</div>';
					echo '</div>';
					echo '<div class="row">';
						echo '<div class="small-3 columns">';
			                echo form_label(lang('settings_description'));
		                echo '</div>'; 
		                echo '<div class="small-9 medium-7 large-5 left columns">';
						    echo form_textarea(array('name'=>'general_description_site', 'rows'=>'3'), set_value('general_description_site', get_setting('general_description_site')));
						echo '</div>';
					echo '</div>';
					echo '<div class="row">';
						echo '<div class="small-3 columns">';
			                echo form_label(lang('settings_url'));
		                echo '</div>';
		                echo '<div class="small-9 medium-7 large-5 left columns">';
						    echo form_input(array('name'=>'general_url_site', 'disabled'=>'disabled'), set_value('general_url_site', base_url()));
						echo '</div>';
					echo '</div>';
					echo '<div class="row">';
						echo '<div class="small-3 columns">';
			                echo form_label(lang('settings_email'));
		                echo '</div>'; 
		                echo '<div class="small-9 medium-7 large-5 left columns">';
						    echo form_input(array('name'=>'general_email_admin'), set_value('general_email_admin', get_setting('general_email_admin')));
						echo '</div>';
					echo '</div>';
					echo '<div class="row">';
						echo '<div class="small-3 columns">';
			                echo form_label(lang('settings_homepage'));
		                echo '</div>'; 
		                echo '<div class="small-9 medium-7 large-5 left columns">';
		                	$options = array();
		                    $options['core'] = lang('settings_login_screen');
		                    if(get_setting('module_cms') == 1) $options['cms'] = lang('settings_homepage_site');
		                    echo form_dropdown('general_homepage', $options, get_setting('general_homepage'));
						echo '</div>';
					echo '</div>';
					echo '<div class="row">';
						echo '<div class="small-3 columns">';
			                echo form_label(lang('settings_timezone'));
		                echo '</div>'; 
		                echo '<div class="small-9 medium-7 large-5 left columns">';
						    $this->load->helper('date');
						    echo timezone_menu(get_setting('general_timezone'));
						echo '</div>';
					echo '</div>';
					echo '<div class="row">';
						echo '<div class="small-4 medium-6 large-3 columns">';
			                echo form_label(lang('settings_summertime'));
		                echo '</div>'; 
		                echo '<div class="small-9 medium-7 large-5 left columns">';
						    echo form_radio(array('name'=>'general_timezone_summertime', 'id'=>'general_timezone_summertime_y'), '1', (get_setting('general_timezone_summertime')==1) ? TRUE : FALSE);
							echo form_label('Sim', 'general_timezone_summertime_y');
							echo form_radio(array('name'=>'general_timezone_summertime', 'id'=>'general_timezone_summertime_n'), '0', (get_setting('general_timezone_summertime')==0) ? TRUE : FALSE);
							echo form_label('Não', 'general_timezone_summertime_n');
						echo '</div>';
					echo '</div>';
					echo '<div class="row">';
						echo '<div class="small-4 medium-6 large-3 columns">';
			                echo form_label(lang('settings_date'));
		                echo '</div>'; 
		                echo '<div class="small-9 medium-7 large-5 left columns">';
						    echo form_radio(array('name'=>'general_date_format', 'id'=>'general_date_format'), 'd \d\e M \d\e Y', (get_setting('general_date_format')=='d \d\e M \d\e Y') ? TRUE : FALSE);
							echo form_label(date('d \d\e M \d\e Y'), 'general_date_format');
							echo '<br>';
							echo form_radio(array('name'=>'general_date_format', 'id'=>'general_date_format_2'), 'Y/m/d', (get_setting('general_date_format')=='Y/m/d') ? TRUE : FALSE);
							echo form_label(date('Y/m/d'), 'general_date_format_2');
							echo '<br>';
							echo form_radio(array('name'=>'general_date_format', 'id'=>'general_date_format_3'), 'm/d/Y', (get_setting('general_date_format')=='m/d/Y') ? TRUE : FALSE);
							echo form_label(date('m/d/Y'), 'general_date_format_3');
							echo '<br>';
							echo form_radio(array('name'=>'general_date_format', 'id'=>'general_date_format_4'), 'd/m/Y', (get_setting('general_date_format')=='d/m/Y') ? TRUE : FALSE);
							echo form_label(date('d/m/Y'), 'general_date_format_4');
							echo '<br>';
							$custom_checked = (get_setting('general_date_format') != 'd \d\e M \d\e Y' && get_setting('general_date_format') != 'Y/m/d' && get_setting('general_date_format') != 'm/d/Y' && get_setting('general_date_format') != 'd/m/Y') ? TRUE : FALSE;
							echo form_radio(array('name'=>'general_date_format', 'id'=>'general_date_format_5'), 'custom', $custom_checked);
							echo form_label(lang('settings_custom'), 'general_date_format_5');
							echo form_input(array('name'=>'general_date_format_custom'), set_value('general_date_format_custom', get_setting('general_date_format')));
						echo '</div>';
					echo '</div>';
					echo '<div class="row">';
						echo '<div class="small-4 medium-6 large-3 columns">';
			                echo form_label(lang('settings_time'));
		                echo '</div>'; 
		                echo '<div class="small-9 medium-7 large-5 left columns">';
							echo form_radio(array('name'=>'general_time_format', 'id'=>'general_time_format_3'), 'H:m', (get_setting('general_time_format')=='H:m') ? TRUE : FALSE);
							echo form_label(date('H:m'), 'general_time_format_3');
							echo '<br>';
							echo form_radio(array('name'=>'general_time_format', 'id'=>'general_time_format_4'), 'g:i A', (get_setting('general_time_format')=='g:i A') ? TRUE : FALSE);
							echo form_label(date('g:i A'), 'general_time_format_4');
							echo '<br>';
							$custom_checked = (get_setting('general_time_format') != 'H:m' && get_setting('general_time_format') != 'g:i A') ? TRUE : FALSE;
							echo form_radio(array('name'=>'general_time_format', 'id'=>'general_time_format_5'), 'custom', $custom_checked);
							echo form_label(lang('settings_custom'), 'general_time_format_5');
							echo form_input(array('name'=>'general_time_format_custom'), set_value('general_time_format_custom', get_setting('general_time_format')));
						echo '</div>';
					echo '</div>';
					echo '<div class="row">';
						echo '<div class="small-6 medium-6 large-4 columns">';
			                echo form_label(lang('settings_large_lists'));
		                echo '</div>'; 
		                echo '<div class="small-2 medium-5 large-4 left columns">';
							echo form_input(array('name'=>'general_large_list'), set_value('general_large_list', get_setting('general_large_list')));
						echo '</div>';
					echo '</div>';
					echo '<div class="row">';
						echo '<div class="small-6 medium-6 large-4 columns">';
			                echo form_label(lang('settings_small_lists'));
		                echo '</div>'; 
		                echo '<div class="small-2 medium-5 large-4 left columns">';
							echo form_input(array('name'=>'general_small_list'), set_value('general_small_list', get_setting('general_small_list')));
						echo '</div>';
					echo '</div>';
					echo '<div class="row">';
						echo '<div class="small-6 medium-6 large-4 columns">';
			                echo form_label(lang('settings_language'));
		                echo '</div>'; 
		                echo '<div class="small-2 medium-5 large-4 left columns">';
		                	$options = array();
		                	$languages = (directory_map('./compass-admin/language', TRUE));
		                	foreach ($languages as $lang):
		                    	$options[$lang] = ucfirst($lang);
		                	endforeach;
		                    echo form_dropdown('general_language', $options, get_setting('general_language'));
						echo '</div>';
					echo '</div>';
					echo form_submit(array('name'=>'save', 'class'=>'button radius tiny'), lang('core_save_settings'));
					echo form_submit(array('name'=>'advanced_settings', 'class'=>'button radius tiny secondary'), (get_setting('general_advanced_settings') == 0) ? lang('settings_advanced_enable') : lang('settings_advanced_disable'));
				echo form_close();
			echo '</div>';
		echo '</div>';
		break;
	case 'aparence':
		echo '<div id="settings-aparence" class="row">';
            echo '<div class="small-12 columns">';
			echo form_open(current_url(), 'id="settings-aparence-form-aparence"');
				echo '<div class="row">';
					echo '<div class="small-12 columns">';
						echo '<h3>'.lang('settings_aparence_config').'</h3>';
					echo '</div>';
					echo '<div class="small-12 medium-6 large-6 columns">';
						echo form_label(lang('settings_aparence_bg_color'));
						echo form_input(array('name'=>'aparence_background_color'), set_value('aparence_background_color', get_setting('aparence_background_color')));
						echo form_label(lang('settings_aparence_bg_image'));
						echo form_input(array('name'=>'aparence_background_image'), set_value('aparence_background_image', get_setting('aparence_background_image')));
						echo form_label(lang('settings_aparence_logo'));
						echo form_input(array('name'=>'aparence_logo'), set_value('aparence_logo', get_setting('aparence_logo')));
						echo form_label(lang('settings_aparence_footer'));
						echo form_textarea(array('name'=>'aparence_copy_footer', 'rows'=>3), set_value('aparence_copy_footer', get_setting('aparence_copy_footer')));
					echo '</div>';
					echo '<div class="small-12 medium-6 large-6 columns">';
						echo form_label(lang('settings_aparence_css_include'), 'aparence_active_css');
						echo form_checkbox(array('name'=>'aparence_active_css', 'id'=>'aparence_active_css'), '1', (get_setting('aparence_active_css')==1) ? TRUE : FALSE).form_label(' '.lang('settings_aparence_css_include_t'), 'aparence_active_css').'<br /><br />';
						echo form_label(lang('settings_aparence_css'));
						echo form_textarea(array('name'=>'aparence_css', 'rows'=>12), set_value('aparence_css', get_setting('aparence_css')));
					echo '</div>';
				echo '</div>';
				echo form_submit(array('name'=>'save', 'class'=>'button radius tiny'), lang('core_save_settings'));
			echo form_close();
			echo '</div>';
		echo '</div>';
		break;
	case 'permissions':
        echo '<div id="users-permissions" class="row">';
            echo '<div class="small-12 columns">';
                echo form_open(current_url(), 'id="users-settings-form-permissions"');
                    echo '<h3>'.lang('permissions').'</h3>';
                    echo '<table id="users-settings-table-permissions">';
                        echo '<thead>';
                            echo '<tr>';
                                echo '<th class="small-2 collums">'.lang('permissions').'/'.lang('users_levels').'</th>';
                                    $query_userslevels = $this->userslevels->get_all()->result();
                                    foreach ($query_userslevels as $line):
                                        echo '<th class="small-1 collums">('.$line->userlevel_id.') '.$line->userlevel_name.'</th>';
                                    endforeach;
                            echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                            $alllabelspermissions = array(
                                lang('permissions_users_active'),
                                /*    lang('core_cms'),
                                lang('users_permissions_list_posts'),
                                lang('users_permissions_view_posts'),
                                lang('users_permissions_insert_posts'),
                                lang('users_permissions_update_posts'),
                                lang('users_permissions_delete_posts'),
                                lang('users_permissions_list_pages'),
                                lang('users_permissions_view_pages'),
                                lang('users_permissions_insert_pages'),
                                lang('users_permissions_update_pages'),
                                lang('users_permissions_delete_pages'),
                                lang('users_permissions_ger_medias'),
                                lang('users_permissions_ger_comments'),
                                lang('users_permissions_ger_themes'),
                                lang('users_permissions_ger_stats'),
                                lang('users_permissions_update_settings'),*/
                                    lang('users'),
                                lang('permissions_list_users'),
                                lang('permissions_view_profiles'),
                                lang('permissions_insert_users'),
                                lang('permissions_update_users'),
                                lang('permissions_update_levels'),
                                lang('permissions_update_status'),
                                lang('permissions_users_delete'),
                                lang('permissions_users_settings'),
                                lang('permissions_update_perm'),
                                    lang('settings'),
                                lang('settings'),
                                    lang('core_tools'),
                                lang('tools'),
                                );
                            $allpermissions = array(
                                'perm_ative_',
                                /**    NULL,
                                'perm_listposts_',
                                'perm_viewposts_',
                                'perm_insertposts_',
                                'perm_updateposts_',
                                'perm_deleteposts_',
                                'perm_listpages_',
                                'perm_viewpages_',
                                'perm_insertpages_',
                                'perm_updatepages_',
                                'perm_deletepages_',
                                'perm_medias_',
                                'perm_comments_',
                                'perm_themes_',
                                'perm_stats_',
                                'perm_contentssettings_',**/
                                    NULL,
                                'perm_listusers_',
                                'perm_viewprofileusers_',
                                'perm_insertusers_',
                                'perm_updateusers_',
                                'perm_updateuserlevel_',
                                'perm_updateuserstatus_',
                                'perm_userdelete_',
                                'perm_userssettings_',
                                'perm_userspermissions_',
                                    NULL,
                                'perm_settings_',
                                    NULL,
                                'perm_tools_'
                            );
                            $labelarray = 0;
                            foreach ($allpermissions as $permission):
                                echo '<tr>';
                                    if ($permission):
                                        echo '<td>'.$alllabelspermissions[$labelarray].'</td>';
                                        $labelarray++;
                                        $columnarray = 1;
                                        foreach ($query_userslevels as $line):
                                            if ($permission == 'perm_ative_'):
                                                echo get_permission($permission, $line->userlevel_id);
                                            else:
                                                echo get_permission($permission, $line->userlevel_id, TRUE);
                                            endif;
                                            $columnarray++;
                                        endforeach;
                                    else:
                                        echo '<td colspan="'.$columnarray.'"><strong>'.$alllabelspermissions[$labelarray].'</strong></td>';
                                        $labelarray++;
                                    endif;
                                echo '</tr>';
                            endforeach;
                        echo '</tbody>';
                    echo '</table>';
                    echo form_submit(array('name'=>'save', 'class'=>'button radius tiny'), lang('core_save_settings'));
                echo form_close();
            echo '</div>';
        echo '</div>';
        break;
endswitch;