<?php defined('BASEPATH') OR exit('No direct script access allowed');

switch ($screen) {
	case 'login':
        echo '<nav class="top-bar docs-bar" data-topbar="" role="navigation">';
            echo '<ul class="title-area">';
                echo '<li class="name">';
                    echo '<h1><a href="<?php echo base_url(); ?>"><i class="fa fa-compass"></i><span>Compass</span></a></h1>';
                echo '</li>';
            echo '</ul>';
            echo '<section class="top-bar-section">';
                echo '<ul class="right">';
                    echo '<li><a href="'.base_url('login').'">Acessar</a></li>';
                echo '</ul>';
                echo '<ul class="left">';
                    echo '<li><a href="">'.get_setting('general_title_system').'</a></li>';
                echo '</ul>';
            echo '</section>';
        echo '</nav>';
        echo '<div id="login-index" class="row">';
            echo '<br><br><br><br><br>';
            echo '<div class="small-8 large-3 small-centered columns">';
                errors_validating();
                get_msg('logoffok');
                get_msg('errorlogin');
                echo '<div class="login-sigall panel white">';
                    echo '<h3>'.lang('login_title_box').'</h3>';
                    echo form_open('login', 'id="login-index-form-login"');
                        echo form_input(array('name'=>'login', 'placeholder'=>lang('login_label_username_or_email')), set_value('login'), 'autofocus');
                        echo form_password(array('name'=>'password', 'placeholder'=>lang('login_field_pass')));
                        echo form_hidden('redirect', $this->session->userdata('redir_for'));
                        echo '<div class=row>';
                            echo '<div class="small-7 columns">';
                                echo form_checkbox(array('name'=>'remember', 'id'=>'remember'), '1');
                                echo form_label(lang('login_label_remember'), 'remember');
                            echo '</div>';
                            echo '<div class="small-5 columns">';
                                echo form_submit(array('name'=>'log', 'class'=>'button radius tiny right'), lang('core_enter'));
                            echo '</div>';
                        echo '</div>';
                    echo form_close();
                echo '</div>';
                echo '<div class="small-12 columns">';
                    echo '<p>'.anchor('', lang('login_label_return_homepage')).' | '.anchor('login/newpassword', lang('login_label_newpassword')).'</p>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;

    case 'signup':
        echo '<div id="login-signup" class="row">';
            echo '<div class="small-10 large-7 small-centered columns">';
                errors_validating();
                get_msg('msgok');
                echo '<div class="login-sigall panel white">';
                    echo '<h3>'.lang('login_siginup_title_box').'</h3>';
                    echo form_open('login/signup', 'id="login-signup-form-signup"');
                        echo form_label(lang('login_field_user_name'));
                        echo form_input(array('name'=>'user_username', 'placeholder'=>lang('login_field_user_name')), set_value('user_username'), 'autofocus');
                        echo form_label(lang('login_field_name'));
                        echo form_input(array('name'=>'user_name', 'placeholder'=>lang('login_field_name')), set_value('user_name'));
                        echo form_label(lang('login_field_display_name'));
                        echo form_input(array('name'=>'user_displayname', 'placeholder'=>lang('login_field_display_name')), set_value('user_displayname'));
                        echo '<div class="row">';
                            echo '<div class="small-6 columns">';
                                echo form_label(lang('login_field_email'));
                                echo form_input(array('name'=>'user_email', 'placeholder'=>lang('login_field_email')), set_value('user_email'));
                            echo '</div>';
                            echo '<div class="small-6 columns">';
                                echo form_label(lang('login_field_email_repeat'));
                                echo form_input(array('name'=>'user_email2', 'placeholder'=>lang('login_field_email_repeat')), set_value('user_email2'));
                            echo '</div>';
                            echo '<div class="small-6 columns">';
                                echo form_label(lang('login_field_pass'));
                                echo form_password(array('name'=>'user_pass', 'placeholder'=>lang('login_field_pass')), set_value('user_pass'));
                            echo '</div>';
                            echo '<div class="small-6 columns">';
                                echo form_label(lang('login_field_pass_repeat'));
                                echo form_password(array('name'=>'user_pass2', 'placeholder'=>lang('login_field_pass_repeat')), set_value('user_pass2'));
                            echo '</div>';
                        echo '</div>';
                        echo '<div class="row">';
                            echo '<div class="small-12 columns">';
                                echo anchor('login', lang('login_label_return_login')).' | '.anchor('', lang('login_label_return_homepage'));
                            echo '</div>';
                            echo '<div class="small-12 columns">';
                            echo form_submit(array('name'=>'save', 'class'=>'button radius tiny right'), lang('core_save'));
                            echo '</div>';
                        echo '</div>';
                    echo form_close();
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
    case 'newpassword':
        echo '<div id="login-newpassword" class="row">';
            echo '<br><br><br><br><br>';
            echo '<div class="small-10 large-4 small-centered columns">';
                errors_validating();
                get_msg('msgok');
                get_msg('msgerror');
                echo '<div class="login-sigall panel white">';
                    echo '<h3>'.lang('login_newpassword_title_box').'</h3> <p>'.lang('login_newpassword_p_box').'</p>';
                    echo form_open('login/newpassword', 'id="login-newpassword-form-newpassword');
                        echo form_input(array('name'=>'user_email', 'placeholder'=>lang('login_field_email')), set_value('user_email'), 'autofocus');
                        echo form_submit(array('name'=>'save', 'class'=>'button radius tiny'), lang('core_send'));
                    echo form_close();
                echo '</div>';
                echo '<div class="small-12 columns">'.anchor('login', lang('login_label_return_login')).'</div>';
            echo '</div>';
        echo '</div>';
        break;
}