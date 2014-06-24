<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
    case 'submenu':
        echo 
            make_menu('submenu_item', lang('tools'), 'tools').
            make_menu('submenu_item', lang('modules'), 'tools', 'modules').
            make_menu('submenu_item', lang('tools_files'), 'tools', 'files').
            make_menu('submenu_item', lang('tools_audits'), 'tools', 'audits')
        ;
        break;
    case 'audits':
        echo '<div id="tools-audits" class="row">';
            echo '<div class="small-12 columns">';
                echo '<h3>'.lang('tools_audits').'</h3>';
                echo '<table id="tools-audits-table-list" class="small-12">';
                    echo '<thead>';
                        echo '<tr>';
                            echo '<th class="collums"></th>';
                            echo '<th class="small-2 collums">'.lang('tools_audits_user').'</th>';
                            echo '<th class="small-2 collums">'.lang('tools_audits_time').'</th>';
                            echo '<th class="small-3 collums">'.lang('tools_audits_operation').'</th>';
                            echo '<th class="small-3 collums">'.lang('tools_audits_obs').'</th>';
                            echo '<th class="small-2 collums">'.lang('tools_audits_type').'</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                        $this->db->limit(100);
                        $queryaudit = $this->audits->get_all()->result();
                        $count = 1;
                        foreach ($queryaudit as $line):
                            $queryuser = $this->users->get_by_id($line->audit_userid)->row();
                            echo '<tr>';
                            printf('<td>%s</td>', $count);
                            printf('<td>%s</td>', ($line->audit_userid != 0)?$queryuser->user_displayname:'Desconhecido');
                            printf('<td>%s</td>', date('d/m/Y H:i:s', strtotime($line->audit_date)));
                            printf('<td>%s</td>', '<span data-tooltip class="has-tip tip-top" title="'.$line->audit_query.'">'.$line->audit_process.'</span>');
                            printf('<td>%s</td>', $line->audit_description);
                            printf('<td>%s</td>', $line->audit_type);
                            echo '</tr>';
                            $count ++;
                        endforeach;
                    echo '</tbody>';
                echo '</table>';
            echo '</div>';
        echo '</div>';
        break;
    case 'modules':
        echo '<div id="tools-audits" class="row">';
            echo '<div class="small-12 columns">';
                echo '<div class="row">';
                    echo '<div class="small-12 columns">';
                        echo '<h3 class="left">'.lang('modules_plugins').'</h3>';
                        echo anchor('tools/modules/insert', lang('core_insert_new'), 'class="addimg button button-tiny secondary radius space-v-medium space-h-small"');
                    echo '</div>';
                echo '</div>';
                echo '<div class="row">';
                    $width_tables_insert = ($this->uri->segment(3) == 'insert') ? '8' : '12';
                    if ($this->uri->segment(3) == 'insert'):
                        echo '<div class="small-4 columns">';
                            echo form_open_multipart(current_url(), 'id="tools-modules-form-upload"');
                                echo '<h5 class="left">'.lang('modules_plugins_insert').'</h5>';
                                echo form_upload(array('name'=>'plugin_file'), set_value('plugin_file'));
                                echo form_submit(array('name'=>'upload', 'class'=>'button tiny'), lang('core_send'));
                                echo anchor('tools/modules', ' '.lang('core_cancel'), array('class'=>'alertlink'));
                            echo form_close();
                        echo '</div>';
                    endif;
                    echo '<div class="small-'.$width_tables_insert.' columns">';
                        echo '<table id="tools-modules-table-list" class="small-12">';
                            echo '<thead>';
                                echo '<tr>';
                                    echo '<th class="small-3 collums">'.lang('modules_plugin_name').'</th>';
                                    echo '<th class="small-9 collums">'.lang('modules_description').'</th>';
                                echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                                $themes_directorys = directory_map('./compass-content/plugins/', TRUE);
                                if (in_array('index.html', $themes_directorys)) unset($themes_directorys[array_search('index.html',$themes_directorys)]);
                                foreach ($themes_directorys as $name_theme_directory):
                                    $theme_directory_details = directory_map('./compass-content/plugins/'.$name_theme_directory, TRUE);
                                    if (in_array('readme.txt', $theme_directory_details)):
                                        echo '<tr class="table-order">';
                                            //open the the_file reading
                                            $the_file = './compass-content/plugins/'.$name_theme_directory.'/readme.txt';
                                            //Through the fopen the_file loads, fread read the_file and identifies its size through the filesize, the nl2br turns line breaks in <br>.
                                            $the_text = nl2br(fread(fopen($the_file,'r'), filesize($the_file)));
                                            //Search the variable $ the_text the stretch that starts at the position of the starting character of the element 'Contributors' within the variable $ the_text and concludes the starting character position of the first occurrence of element' <br 'from the occurrence of the element' Contributors: 'in variable the_text. The str_replace deletes the module title
                                            $the_module_name = str_replace('Module Name: ', '', substr($the_text, strripos($the_text, 'Module Name: '), strpos(substr($the_text, strripos($the_text, 'Module Name: ')), '<br')));
                                            $the_module_url = str_replace('Module URI: ', '', substr($the_text, strripos($the_text, 'Module URI: '), strpos(substr($the_text, strripos($the_text, 'Module URI: ')), '<br')));
                                            $the_module_description = str_replace('Description: ', '', substr($the_text, strripos($the_text, 'Description: '), strpos(substr($the_text, strripos($the_text, 'Description: ')), '<br')));
                                            $the_module_author = str_replace('Author: ', '', substr($the_text, strripos($the_text, 'Author: '), strpos(substr($the_text, strripos($the_text, 'Author: ')), '<br')));
                                            $the_module_author_url = str_replace('Author URI: ', '', substr($the_text, strripos($the_text, 'Author URI: '), strpos(substr($the_text, strripos($the_text, 'Author URI: ')), '<br')));
                                            $the_module_version = str_replace('Version: ', '', substr($the_text, strripos($the_text, 'Version: '), strpos(substr($the_text, strripos($the_text, 'Version: ')), '<br')));
                                            printf('<td class="table-operations"><strong>%s</strong><br>%s%s%s</td>', 
                                                anchor("$name_theme_directory", $the_module_name, array('class'=>'table-item-featured')),
                                                (get_setting('module_'.$name_theme_directory) == '1') ? anchor("tools/modules/ativate/$name_theme_directory", lang('core_desactivate'), array('class'=>'table-action table-action-first delete')) : anchor("tools/modules/ativate/$name_theme_directory", lang('core_activate'), array('class'=>'table-action table-action-first')),
                                                anchor("tools/modules/delete/$name_theme_directory", lang('core_delete'), array('class'=>'table-action table-action delete')),
                                                (get_setting('module_install_'.$name_theme_directory) != 1) ? anchor("$name_theme_directory/install", lang('core_install'), array('class'=>'table-action table-action delete')) : ''
                                                );
                                            printf('<td style="max-height:200px !important;">%s</td>', $the_module_description.' <a href="#" data-reveal-id="Modal-'.$name_theme_directory.'">'.lang('modules_more').'</a><br>'.lang('modules_version').' <strong>'.$the_module_version.'</strong> | '.lang('modules_by').' '.anchor($the_module_author_url, $the_module_author).' | '.anchor($the_module_url, lang('modules_plugin_view_url'))
                                                );
                                            echo '<div id="Modal-'.$name_theme_directory.'" class="reveal-modal" data-reveal><h3>'.ucfirst($name_theme_directory).'/readme.txt</h3><p>'.replace_string($the_text).'</p><a class="close-reveal-modal">&#215;</a></div>';
                                        echo '</tr>';
                                    endif;
                                endforeach;
                            echo '</tbody>';
                        echo '</table>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
            echo '<div class="small-12 columns">';
                echo '<h3>'.lang('modules_compass').'</h3>';
                echo '<table id="tools-modules-table-list" class="small-12">';
                    echo '<thead>';
                        echo '<tr>';
                            echo '<th class="small-3 collums">'.lang('modules_name').'</th>';
                            echo '<th class="small-9 collums">'.lang('modules_description').'</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                        $themes_directorys = directory_map('./compass-admin/modules/', TRUE);
                        if (in_array('index.html', $themes_directorys)) unset($themes_directorys[array_search('index.html',$themes_directorys)]);
                        foreach ($themes_directorys as $name_theme_directory):
                            $theme_directory_details = directory_map('./compass-admin/modules/'.$name_theme_directory, TRUE);
                            if (in_array('readme.txt', $theme_directory_details)):
                                echo '<tr class="table-order">';
                                    //abrir o the_file em leitura
                                    $the_file = './compass-admin/modules/'.$name_theme_directory.'/readme.txt';
                                    //transformamos as quebras de linha em etiquetas <br>
                                    //Por meio do fopen o the_file é carregado, o fread ler o the_file e identifica seu tamanho por meio do filesize, o nl2br transforma as quebras de linhas em <br>.
                                    $the_text = nl2br(fread(fopen($the_file,'r'), filesize($the_file)));
                                    //Procura na variável $the_text o trecho que inicia na posição do caractere inicial do elemento 'Contributors: ' dentro da variável $the_text e conclui na posição do caractere inicial da primeira ocorrência do elemento '<br' a partir da ocorrencia do elemento 'Contributors: ', na variável the_text. O str_replace apaga o título do módulo
                                    $the_module_name = str_replace('Module Name: ', '', substr($the_text, strripos($the_text, 'Module Name: '), strpos(substr($the_text, strripos($the_text, 'Module Name: ')), '<br')));
                                    $the_module_url = str_replace('Module URI: ', '', substr($the_text, strripos($the_text, 'Module URI: '), strpos(substr($the_text, strripos($the_text, 'Module URI: ')), '<br')));
                                    $the_module_description = str_replace('Description: ', '', substr($the_text, strripos($the_text, 'Description: '), strpos(substr($the_text, strripos($the_text, 'Description: ')), '<br')));
                                    $the_module_author = str_replace('Author: ', '', substr($the_text, strripos($the_text, 'Author: '), strpos(substr($the_text, strripos($the_text, 'Author: ')), '<br')));
                                    $the_module_author_url = str_replace('Author URI: ', '', substr($the_text, strripos($the_text, 'Author URI: '), strpos(substr($the_text, strripos($the_text, 'Author URI: ')), '<br')));
                                    $the_module_version = str_replace('Version: ', '', substr($the_text, strripos($the_text, 'Version: '), strpos(substr($the_text, strripos($the_text, 'Version: ')), '<br')));
                                    printf('<td class="table-operations"><strong>%s</strong><br>%s%s</td>', 
                                        anchor("$name_theme_directory", $the_module_name, array('class'=>'table-item-featured')),
                                        (get_setting('module_'.$name_theme_directory) == '1') ? anchor("tools/modules/ativate/$name_theme_directory", lang('core_desactivate'), array('class'=>'table-action table-action-first delete')) : anchor("tools/modules/ativate/$name_theme_directory", lang('core_activate'), array('class'=>'table-action table-action-first')),
                                        (get_setting('module_install_'.$name_theme_directory) != 1) ? anchor("$name_theme_directory/install", 'Instalar', array('class'=>'table-action table-action delete')) : ''
                                        );
                                    printf('<td style="max-height:200px !important;">%s</td>', $the_module_description.' <a href="#" data-reveal-id="Modal-'.$name_theme_directory.'">'.lang('modules_more').'</a><br>'.lang('modules_version').' <strong>'.$the_module_version.'</strong> | '.lang('modules_by').' '.anchor($the_module_author_url, $the_module_author).' | '.anchor($the_module_url, lang('modules_view_url'))
                                        );
                                    echo '<div id="Modal-'.$name_theme_directory.'" class="reveal-modal" style="height:450px; overflow-y:auto;" data-reveal><h3>'.ucfirst($name_theme_directory).'/readme.txt</h3><p>'.replace_string($the_text).'</p><a class="close-reveal-modal">&#215;</a></div>';
                                echo '</tr>';
                            endif;
                        endforeach;
                    echo '</tbody>';
                echo '</table>';
            echo '</div>';
        echo '</div>';
        break;
    case 'uploadfiles':
        echo '<div class="small-12 small-centered columns">';
            get_msg('msgerror').get_msg('msgok').errors_validating();
            $actionmedia = $this->uri->segment(3);
            if ($actionmedia != 'saved'): 
                echo '<h5>'.lang('tools_uploadfiles').'</h5>'; 
            endif;
            echo form_open_multipart(current_url(), 'id="tools-files-form-upload"');
                if ($actionmedia != 'saved'):
                    echo form_upload(array('name'=>'media_file'), set_value('media_file'));
                endif;
                if ($this->uri->segment(3) == NULL || $this->uri->segment(3) != 'saved'):
                    echo form_submit(array('name'=>'save', 'class'=>'button radius tiny'), lang('core_send'));
                    echo '<h5>'.lang('tools_uploadfiles_recents').'</h5>';
                    $files_directorys = directory_map('./compass-content/uploads/files/', TRUE);
                    $count = 0;
                    foreach ($files_directorys as $name_file):
                        if ($count < 10):
                            echo '<div class="row">';
                                echo '<small>';
                                    echo '<div class="small-4 columns">';
                                        echo $name_file;
                                    echo '</div>';
                                    echo '<div class="small-8 columns">';
                                        echo anchor_popup(base_url('compass-content/uploads/files').'/'.$name_file);
                                    echo '</div>';
                                    echo '<hr>';
                                echo '</small>';
                            echo '</div>';
                        endif;
                        $count++;
                    endforeach;
                    echo '<div class="row">';
                        echo '<small><strong>';
                            echo '<div class="small-4 columns">Directory</div>';
                            echo '<div class="small-8 columns">'.anchor_popup(base_url('compass-content/uploads/files')).'</div>';
                        echo '</strong></small>';
                    echo '</div>';
                elseif ($this->uri->segment(3) == 'saved'):
                    echo '<div class="small-7 small-centered columns">';
                        echo '<img src="'.base_url('compass-content/uploads/files').'/'.$this->uri->segment(4).'">';
                    echo '</div>';
                    echo '<div class="row collapse">';
                        echo '<div class="small-10 columns">';
                            echo '<input type="text" value="'.base_url('compass-content/uploads/files').'/'.$this->uri->segment(4).'" class="select" />';
                        echo '</div>';
                        echo '<div class="small-2 columns">';
                            echo anchor_popup(base_url('compass-content/uploads/files').'/'.$this->uri->segment(4), '<i class="fa fa-picture-o"></i>', array('class'=>'button tiny', 'title'=>lang('core_view')));
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="small-12 columns">';
                        echo '<small><em>'.lang('tools_uploadfiles_small_copy').'</em></small><br/><br/>';
                    echo '</div>';
                endif;
            echo form_close();
        echo '</div>';
        break;
    case 'uploadfilesmodal':
        echo '<div id="insertfilemodal" class="reveal-modal medium" data-reveal>';
            echo '<div class="row">';
                echo '<iframe src="'.base_url().'tools/uploadfilesmodal/" class="small-12 columns" height="350px" scrolling="yes" frameborder="0"></iframe>';
            echo '</div>';
            echo '<a class="close-reveal-modal">&#215;</a>';
        echo '</div>';
        break;
    case 'files':
        echo '<div id="tools-files" class="row">';
            echo '<div class="small-12 columns">';
                echo '<div class="row">';
                    echo '<div class="small-12 columns">';
                        echo '<h3 class="left">'.lang('tools_files').'</h3>';
                    echo '</div>';
                    echo '<div class="small-6 columns end" style="position:absolute; top:35px; z-index:9;">';
                        $url3 = 'root/';
                        $url4 = ($this->uri->segment(4)) ? $this->uri->segment(4).'/' : NULL;
                        $url5 = ($this->uri->segment(5)) ? $this->uri->segment(5).'/' : NULL;
                        $url6 = ($this->uri->segment(6)) ? $this->uri->segment(6).'/' : NULL;
                        $url7 = ($this->uri->segment(7)) ? $this->uri->segment(7).'/' : NULL;
                        $url8 = ($this->uri->segment(8)) ? $this->uri->segment(8).'/' : NULL;
                        if ($this->uri->segment(8)):
                            $url_ant = base_url().'tools/files/'.$url3.$url4.$url5.$url6.$url7;
                        elseif ($this->uri->segment(7)):
                            $url_ant = base_url().'tools/files/'.$url3.$url4.$url5.$url6;
                        elseif ($this->uri->segment(6)):
                            $url_ant = base_url().'tools/files/'.$url3.$url4.$url5;
                        elseif ($this->uri->segment(5)):
                            $url_ant = base_url().'tools/files/'.$url3.$url4;
                        else:
                            $url_ant = base_url().'tools/files/'.$url3;
                        endif;
                        echo anchor(base_url().'tools/files/'.$url3, '<i class="fa fa-home" style="font-size:20px;"></i>', array('class'=>'button button-tiny radius space-v-medium space-h-small secondary', 'title'=>lang('tools_files_home')));
                        echo anchor($url_ant, '<i class="fa fa-arrow-circle-left" style="font-size:20px;"></i>', array('class'=>'button button-tiny radius space-v-medium space-h-small secondary', 'title'=>lang('tools_files_return')));
                        echo '<a href="#" data-dropdown="drop2" class="button button-tiny radius space-v-medium space-h-small dropdown secondary"><i class="fa fa-cloud-upload" style="font-size:20px;"></i> '.lang('tools_files_upload').'_______</a>';
                        echo '<ul id="drop2" data-dropdown-content class="f-dropdown">';
                            echo '<li style="padding:15px;">';
                                echo form_open_multipart(current_url(), 'id="tools-files-form-insert-folder"');
                                        echo '<div class="row">';
                                            echo '<div class="small-12 columns">';
                                                echo form_label('tools_files_upload_file');
                                                echo form_upload(array('name'=>'media_file'), set_value('media_file'));
                                            echo '</div>';
                                        echo '</div>';
                                        echo form_hidden('folder_directory', './compass-content/uploads/files/'.$url4.$url5.$url6.$url7.$url8);
                                        echo form_submit(array('name'=>'upload', 'class'=>'button radius tiny'), lang('tools_files_upload'));
                                echo form_close();
                            echo '</li>';
                        echo '</ul>';
                        if ($url8 == NULL):
                            echo '<a href="#" data-dropdown="drop1" class="button button-tiny radius space-v-medium space-h-small dropdown secondary"><i class="fa fa-folder-open" style="font-size:20px;"></i> '.lang('tools_files_new_folder').'_______</a><br>';
                            echo '<ul id="drop1" data-dropdown-content class="f-dropdown">';
                                echo '<li style="padding:15px;">';
                                    echo form_open_multipart(current_url(), 'id="tools-files-form-insert-folder"');
                                            echo '<div class="row">';
                                                echo '<div class="small-12 columns">';
                                                    echo form_label(lang('tools_files_new_folder'));
                                                    echo form_input(array('name'=>'folder_name', 'placeholder'=>lang('tools_files_new_folder_name')));
                                                echo '</div>';
                                            echo '</div>';
                                            echo form_hidden('folder_directory', './compass-content/uploads/files/'.$url4.$url5.$url6.$url7.$url8);
                                            echo form_submit(array('name'=>'save', 'class'=>'button radius tiny'), lang('core_save'));
                                    echo form_close();
                                echo '</li>';
                            echo '</ul>';
                        else:
                            echo '<a href="#" data-dropdown="drop1" class="button button-tiny radius space-v-medium space-h-small disabled"><i class="fa fa-folder-open" style="font-size:20px;"></i> '.lang('tools_files_new_folder').'_______</a>';
                        endif;
                        echo '<span>'.lang('tools_files_route').': &bull;/'.$url4.$url5.$url6.$url7.$url8.'</span>';
                    echo '</div>';
                echo '</div>';
                echo '<div class="row">';
                    echo '<div class="small-12 columns data-table-div">';
                        echo '<table id="tools-files-table-list" class="small-12 data-table">';
                            echo '<thead>';
                                echo '<tr>';
                                    echo '<th><i class="fa fa-star"></i></th>';
                                    echo '<th class="small-7">'.lang('tools_files_name').'</th>';
                                    echo '<th class="small-1">'.lang('tools_files_size').'</th>';
                                    echo '<th class="small-1">'.lang('tools_files_type').'</th>';
                                    echo '<th class="small-2">'.lang('tools_files_date').'</th>';
                                    echo '<th class="small-1">'.lang('tools_files_actions').'</th>';
                                echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                                $themes_directorys = directory_map('./compass-content/uploads/files/'.$url4.$url5.$url6.$url7.$url8, TRUE);
                                foreach ($themes_directorys as $name_directory):
                                echo '<tr>';
                                    if (strstr($name_directory, '.')):
                                        $link_directory = anchor_popup(base_url().'compass-content/uploads/files/'.$url4.$url5.$url6.$url7.$url8.$name_directory, $name_directory);
                                        if(strstr($name_directory, '.jpg') || strstr($name_directory, '.jpeg') || strstr($name_directory, '.png') || strstr($name_directory, '.gif')):
                                            $icon_directory = '<img src="'.base_url().'/compass-content/uploads/files/'.$url4.$url5.$url6.$url7.$url8.$name_directory.'" width="30px;" /> ';
                                        else:
                                            $icon_directory = '<i class="fa fa-file-o" style="font-size:30px;"></i> ';
                                        endif;
                                    else:
                                        $link_directory = anchor(base_url().'tools/files/'.$url3.$url4.$url5.$url6.$url7.$url8.$name_directory, $name_directory);
                                        $icon_directory = '<i class="fa fa-folder-open" style="font-size:30px; color:#5da423"></i> ';
                                    endif;
                                    $date = date("d/m/Y H:i", filemtime('./compass-content/uploads/files/'.$url4.$url5.$url6.$url7.$url8.$name_directory));
                                    $ext = pathinfo('./compass-content/uploads/files/'.$url4.$url5.$url6.$url7.$url8.$name_directory, PATHINFO_EXTENSION);
                                    $size = filesize('./compass-content/uploads/files/'.$url4.$url5.$url6.$url7.$url8.$name_directory);
                                    echo '<td >&bull;</td>';
                                    echo '<td>'.$icon_directory.$link_directory.'</td>';
                                    echo '<td>'.$size.'<small>bytes</small></td>';
                                    echo '<td>'.$ext.'</td>';
                                    echo '<td>'.$date.'</td>';
                                    echo '<td>';
                                        echo form_open(current_url());
                                            echo form_hidden('file_directory', './compass-content/uploads/files/'.$url4.$url5.$url6.$url7.$url8.$name_directory);
                                            echo form_hidden('is_file', strstr($name_directory, '.') ? TRUE : FALSE);
                                            echo form_submit(array('name'=>'delete', 'class'=>'button-alertlink'), lang('core_delete'));
                                        echo form_close();
                                    echo '</td>';
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
