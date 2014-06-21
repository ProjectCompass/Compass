<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
    case 'themes':
        echo '<div class="row" id="cms-themes">';
            echo '<div class="small-12 columns">';
                echo '<div class="row">';
                    if ($this->uri->segment(3) == 'update' && $this->uri->segment(4) != NULL):
                        echo '<div class="small-12 columns">';
                            echo '<h3 class="left">'.lang('cms_themes_update').'</h3>';
                        echo '</div>';
                        echo '<div class="small-12 columns">';
                            echo '<h5 class="left">'.ucfirst($this->uri->segment(4)).': '.$this->uri->segment(6).'</h5>';
                            echo '<div class="small-3 columns right">';
                                echo '<a href="#" data-dropdown="drop1" class="tiny button secondary dropdown expand">'.ucfirst($this->uri->segment(4)).'</a>';
                                echo '<ul id="drop1" data-dropdown-content class="f-dropdown">';
                                    $themes_directorys = directory_map('./compass-content/themes/', TRUE);
                                    foreach ($themes_directorys as $name_theme_directory):
                                        echo '<li><a href="'.base_url('cms/themes/update/').'/'.$name_theme_directory.'">'.$name_theme_directory.'</a></li>';
                                    endforeach;
                                echo '</ul>';
                            echo '</div>';
                            echo '<p class="right">'.lang('cms_themes_select_to_edit').'</p>';
                            echo '<div class="row">';
                                echo '<div class="small-9 columns">';
                                    echo form_open(current_url(), 'id="cms-themes-form-update"');
                                        if ($this->uri->segment(6) == NULL):
                                            redirect(base_url('cms/themes/update/'.$this->uri->segment(4).'/model/index.php'));
                                        endif;
                                        $file = read_file('./compass-content/themes/'.$this->uri->segment(4).'/'.$this->uri->segment(6));
                                        echo '<textarea name="file_data" id="codemirror">'.$file.'</textarea>';
                                        echo '<br>';
                                        echo form_submit(array('name'=>'save', 'class'=>'button radius small tiny'), lang('cms_themes_update_file'));
                                    echo form_close();
                                echo '</div>';
                                echo '<div class="small-3 columns">';
                                    $theme_directory_details = directory_map('./compass-content/themes/'.$this->uri->segment(4), TRUE);
                                    if (in_array('screenshot.png', $theme_directory_details)):
                                        echo '<img src="'.base_url('compass-content/themes').'/'.$this->uri->segment(4).'/screenshot.png'.'" widith="100%">';
                                    endif;
                                    echo '<h6><strong>'.lang('cms_themes_models').'</strong></h6>';
                                    foreach ($theme_directory_details as $theme_directory_file):
                                        echo anchor('cms/themes/update/'.$this->uri->segment(4).'/model/'.$theme_directory_file, $theme_directory_file).'<br><br>';
                                    endforeach;
                                    echo '<br>';
                                echo '</div>';
                            echo '</div>'; 
                        echo '</div>';
                    else:
                        echo '<div class="small-12 columns">';
                            echo '<h3 class="left">Temas</h3>';
                            echo anchor('cms/themes/insert', lang('cms_themes_insert'), 'class="addimg button button-tiny secondary radius left space-v-medium space-h-small"');
                        echo '</div>';
                        echo '<div class="small-4 columns">';
                            if ($this->uri->segment(3) == 'insert'):
                                echo '<div class="right space-v-medium">';
                                    echo form_open_multipart(current_url(), 'id="cms-themes-form-upload"');
                                        echo '<h3 class="left">'.lang('cms_themes_insert').'</h3>';
                                        echo form_upload(array('name'=>'layout_file', 'class'=>'border-dashed'), set_value('layout_file'));
                                        echo form_submit(array('name'=>'upload', 'class'=>'button tiny'), lang('cms_themes_send'));
                                        echo anchor('cms/themes', ' '.lang('cms_cancel'), array('class'=>'alertlink'));
                                    echo form_close();
                                echo '</div>';
                            endif;
                        echo '</div>';
                        $width = ($this->uri->segment(3) == 'insert') ? 8 : 12;
                        echo '<div class="small-'.$width.' columns">';
                            echo '<div class="row">';
                                $themes_directorys = directory_map('./compass-content/themes/', TRUE);
                                foreach ($themes_directorys as $name_theme_directory):
                                    $theme_directory_details = directory_map('./compass-content/themes/'.$name_theme_directory, TRUE);
                                    if (in_array('index.php', $theme_directory_details)):
                                        echo '<div class="small-6 medium-4 large-3 columns end">';
                                            if (in_array('screenshot.png', $theme_directory_details)):
                                                echo '<img src="'.base_url('compass-content/themes').'/'.$name_theme_directory.'/screenshot.png'.'" widith="100%">';
                                            endif;
                                            echo '<div class="panel">';
                                                if ($name_theme_directory == get_setting('content_site_theme')):
                                                    echo '<i><strong>'.ucfirst($name_theme_directory).'</strong></i><br>';
                                                    echo anchor('cms/themes/active/'.$name_theme_directory, lang('cms_themes_active'), array('class'=>'button button-tiny alert disabled right', 'title'=>'Layout ativo'));
                                                else:
                                                    echo '<i>'.ucfirst($name_theme_directory).'</i><br>';
                                                    echo anchor('cms/themes/active/'.$name_theme_directory, lang('cms_themes_activate'), array('class'=>'button button-tiny right', 'title'=>'Ativar layout'));
                                                endif;
                                                echo anchor('cms/themes/update/'.$name_theme_directory, '<i class="fa fa-pencil"></i>', array('class'=>'button secondary button-tiny right', 'title'=>lang('cms_update')));
                                                echo anchor('cms/themes/delete/'.$name_theme_directory, '<i class="fa fa-times"></i>', array('class'=>'button secondary button-tiny right deletereg', 'title'=>lang('cms_delete')));
                                            echo '</div>';
                                        echo '</div>';
                                    endif;
                                endforeach;
                            echo '</div>';
                        echo '</div>';
                    endif;
                echo '</div>';
            echo '</div>';
        break;
}