<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
    case 'first':
        echo '<div class="row" id="dashboard-index">';
            echo '<div class="small-8 small-centered columns">';
                echo '<br><br><br><br>';
                echo '<div class="panel white">';
                    echo $this->lang->line('install_first');
                    echo form_open(current_url(), 'id="install-third-form-bd"');
                        echo '<div class="row">';
                            echo '<div class="small-6 columns">';
                                echo $this->lang->line('install_first_p');
                            echo '</div>'; 
                            echo '<div class="small-6 left columns">';
                                $lang_url = ($this->uri->segment(3) != NULL) ? $this->uri->segment(3) : 'English';
                                echo '<a href="#" data-dropdown="drop1" class="button columns tiny secondary dropdown">'.ucfirst($lang_url).'</a><br>';
                                    echo'<ul id="drop1" data-dropdown-content class="f-dropdown">';
                                        $languages = (directory_map('./compass-admin/language', TRUE));
                                        foreach ($languages as $lang):
                                            echo '<li><a href="'.base_url('install/index/'.$lang).'">'.ucfirst($lang).'</a></li>';
                                        endforeach;
                                    echo '</ul>';
                            echo '</div>';
                        echo '</div>';
                        echo '<div class="row">';
                            echo '<div class="small-12 columns">';
                                echo form_submit(array('name'=>'save', 'class'=>'button tiny'), $this->lang->line('install_first_submit'));
                            echo '</div>';
                        echo '</div>';
                    echo form_close();
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
    case 'second':
        echo '<div class="row" id="dashboard-index">';
            echo '<div class="small-8 small-centered columns">';
                echo '<br><br>';
                echo '<div class="center"><div class="co-icon-m-compass"></div></div>';
                echo '<div class="panel white">';
                    echo $this->lang->line('install_second');
                    echo form_open(current_url(), 'id="install-third-form-bd"');
                        echo form_submit(array('name'=>'save', 'class'=>'button tiny'), $this->lang->line('install_second_submit'));
                    echo form_close();
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
    case 'third':
        echo '<div class="row" id="dashboard-index">';
            echo '<div class="small-8 small-centered columns">';
                echo '<br><br>';
                echo '<div class="center"><div class="co-icon-m-compass"></div></div>';
                echo '<div class="panel white">';
                    errors_validating();
                    echo $this->lang->line('install_third');
                    echo form_open(current_url(), 'id="install-third-form-bd"');
                        echo '<div class="row">';
                            echo '<div class="small-3 columns"><strong>'.$this->lang->line('install_third_field_name').'</strong></div>';
                            echo '<div class="small-4 columns">';
                                echo form_input(array('name'=>'db_name'), 'compass');
                            echo '</div>';
                            echo '<div class="small-5 columns">'.$this->lang->line('install_third_small_name').'</div>';
                        echo '</div>';
                        echo '<div class="row">';
                            echo '<div class="small-3 columns"><strong>'.$this->lang->line('install_third_field_user').'</strong></div>';
                            echo '<div class="small-4 columns">';
                                echo form_input(array('name'=>'db_user'), 'root');
                            echo '</div>';
                            echo '<div class="small-5 columns">'.$this->lang->line('install_third_small_user').'</div>';
                        echo '</div>';
                        echo '<div class="row">';
                            echo '<div class="small-3 columns"><strong>'.$this->lang->line('install_third_field_pass').'</strong></div>';
                            echo '<div class="small-4 columns">';
                                echo form_input(array('name'=>'db_pass'));
                            echo '</div>';
                            echo '<div class="small-5 columns">'.$this->lang->line('install_third_small_pass').'</div>';
                        echo '</div>';
                        echo '<div class="row">';
                            echo '<div class="small-3 columns"><strong>'.$this->lang->line('install_third_field_server').'</strong></div>';
                            echo '<div class="small-4 columns">';
                                echo form_input(array('name'=>'db_server'), 'localhost');
                            echo '</div>';
                            echo '<div class="small-5 columns">'.$this->lang->line('install_third_small_server').'</div>';
                        echo '</div>';
                        echo '<div class="row">';
                            echo '<div class="small-12 columns">';
                                echo form_submit(array('name'=>'install', 'class'=>'button tiny'), $this->lang->line('install_third_submit'));
                            echo '</div>';
                        echo '</div>';
                    echo form_close();
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
    case 'fourth':
        echo '<div class="row" id="dashboard-index">';
            echo '<div class="small-6 small-centered columns">';
                echo '<br><br>';
                echo '<div class="center"><div class="co-icon-m-compass"></div></div>';
                echo '<div class="panel white">';
                    echo $this->lang->line('install_fourth');
                    echo form_open(current_url(), 'id="install-third-form-bd"');
                        echo form_submit(array('name'=>'install', 'class'=>'button tiny'), $this->lang->line('install_fourth_submit'));
                    echo form_close();
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
    case 'fifth':
        echo '<div class="row" id="dashboard-index">';
            echo '<div class="small-8 small-centered columns">';
                echo '<br><br>';
                echo '<div class="center"><div class="co-icon-m-compass"></div></div>';
                echo '<div class="panel white">';
                    errors_validating();
                    echo $this->lang->line('install_fifth').' '.anchor('install/readme', 'ReadMe').' '.$this->lang->line('install_fifth_2');
                    echo form_open(current_url(), 'id="install-third-form-bd"');
                        echo '<div class="row">';
                            echo '<div class="small-3 columns">'.$this->lang->line('install_fifth_title').'</div>';
                            echo '<div class="small-6 columns end">';
                                echo form_input(array('name'=>'title'));
                            echo '</div>';
                        echo '</div>';
                        echo '<div class="row">';
                            echo '<div class="small-3 columns">'.$this->lang->line('install_fifth_description').'</div>';
                            echo '<div class="small-6 columns end">';
                                echo form_textarea(array('name'=>'description', 'rows'=>'4'));
                            echo '</div>';
                        echo '</div>';
                        echo '<div class="row">';
                            echo '<div class="small-3 columns">'.$this->lang->line('install_fifth_user').'</div>';
                            echo '<div class="small-6 columns end">';
                                echo form_input(array('name'=>'user'));
                            echo '</div>';
                        echo '</div>';
                        echo '<div class="row">';
                            echo '<div class="small-3 columns">'.$this->lang->line('install_fifth_pass').$this->lang->line('install_fifth_pass_small').'</div>';
                            echo '<div class="small-6 columns end">';
                                echo form_input(array('name'=>'pass'));
                                echo form_input(array('name'=>'pass2'));
                            echo '</div>';
                        echo '</div>';
                        echo '<div class="row">';
                            echo '<div class="small-3 columns">'.$this->lang->line('install_fifth_email').$this->lang->line('install_fifth_email_small').'</div>';
                            echo '<div class="small-6 columns end">';
                                echo form_input(array('name'=>'email'));
                            echo '</div>';
                        echo '</div>';
                        echo $this->lang->line('install_fifth_3');
                        echo '<div class="row">';
                            echo '<div class="small-4 medium-3 large-2 columns center">';
                                echo form_label('<div class="co-icon-m-compass"></div> <br>Core © Content Manager Press as System', 'module_books');
                                echo form_checkbox(array('name'=>'module_books', 'id'=>'module_books', 'disabled'=>'disabled'), '1', TRUE);
                            echo '</div>';
                            echo '<div class="small-4 medium-3 large-2 columns center end">';
                                echo form_label('<div class="co-icon-m-cms"></div> <br> CMS © Content Manager Press as System', 'module_cms');
                                echo form_checkbox(array('name'=>'module_cms', 'id'=>'module_cms'), '1', TRUE);
                            echo '</div>';
                            echo '<div class="small-4 medium-3 large-2 columns center end">';
                                echo form_label('<div class="co-icon-m-books"></div> <br> Books © Content Manager Press as System', 'module_books');
                                echo form_checkbox(array('name'=>'module_books', 'id'=>'module_books'), '1', FALSE);
                            echo '</div>';
                            echo '<div class="small-4 medium-3 large-2 columns center end">';
                                echo form_label('<div class="co-icon-m-journal"></div> <br> Journal © Content Manager Press as System', 'module_journal');
                                echo form_checkbox(array('name'=>'module_journal', 'id'=>'module_journal', 'disabled'=>'disabled'), '1', FALSE);
                            echo '</div>';
                            echo '<div class="small-4 medium-3 large-2 columns center end">';
                                echo form_label('<div class="co-icon-m-eventus"></div> <br> Eventus © Content Manager Press as System', 'module_eventus');
                                echo form_checkbox(array('name'=>'module_eventus', 'id'=>'module_eventus', 'disabled'=>'disabled'), '1', FALSE);
                            echo '</div>';
                            echo '<div class="small-4 medium-3 large-2 columns center end">';
                                echo form_label('<div class="co-icon-m-helpdesck"></div> <br> Helpdesck © Content Manager Press as System', 'module_helpdesk');
                                echo form_checkbox(array('name'=>'module_helpdesk', 'id'=>'module_helpdesk', 'disabled'=>'disabled'), '1', FALSE);
                            echo '</div>';
                        echo '</div>';
                        echo '<div class="row">';
                            echo '<div class="small-12 columns">';
                                echo form_submit(array('name'=>'install', 'class'=>'button tiny'), $this->lang->line('install_fifth_submit'));
                            echo '</div>';
                        echo '</div>';
                    echo form_close();
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
    case 'sixth':
        echo '<div class="row" id="dashboard-index">';
            echo '<div class="small-6 small-centered columns">';
                echo '<br><br>';
                echo '<div class="center"><div class="co-icon-m-compass"></div></div>';
                echo '<div class="panel white">';
                    echo $this->lang->line('install_sixth');
                    echo '<br>';
                    echo '<div class="row">';
                        echo '<div class="small-3 columns"><strong>'.$this->lang->line('install_sixth_user').'</strong></div>';
                        echo '<div class="small-9 columns">'.$this->users->get_by_id(1)->row()->user_username.'</div>';
                    echo '</div>';
                    echo '<br><br>';
                    echo '<div class="row">';
                        echo '<div class="small-3 columns"><strong>'.$this->lang->line('install_sixth_pass').'</strong></div>';
                        echo '<div class="small-9 columns"><i>'.$this->lang->line('install_sixth_pass_r').'</i></div>';
                    echo '</div>';
                    echo '<br><br>';
                    echo '<p>'.anchor('login', 'Log in', array('class'=>'button tiny')).'</p>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
    case 'readme':
        echo '<div class="row" id="dashboard-index">';
            echo '<div class="small-8 small-centered columns">';
                echo '<br><br>';
                echo '<div class="center"><div class="co-icon-m-compass"></div></div>';
                echo '<div class="panel white">';
                echo $this->lang->line('core_install_readme');
                echo form_open(current_url(), 'id="install-third-form-bd"');
                    echo form_submit(array('name'=>'save', 'class'=>'button tiny'), $this->lang->line('install_second_submit'));
                echo form_close();
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
}