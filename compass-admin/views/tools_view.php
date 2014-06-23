<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
    case 'submenu':
        echo 
            make_menu('submenu_item', lang('tools'), 'tools').
            make_menu('submenu_item', lang('modules'), 'tools', 'modules').
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
}