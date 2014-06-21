<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Login class
 *
 * Controller of access to the system
 *
 * Maps to the following URL
 *      http://yoursite.com/login
 *
 * @package     Compass
 * @subpackage  Core
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       0.0.0
 */

class Login extends CI_Controller {

    /**
     * Constructor
     *
     */
	public function __construct(){
		parent::__construct();
        //loads of standard features dashboard
		initialize_dashboard();
	}

    // --------------------------------------------------------------------

    /**
     * The default page
     *
     * Page to access the system
     * Allows login to the system with username and password or email.
     *
     * @access     public
     * @since      0.0.0
     * @modify     0.0.0
     */
	public function index(){
        //if you are logged redirects to the dashboard
        if (be_logged(FALSE)) redirect('dashboard');
        //if there guarding cookie login information, performs automatically login
        if ($this->input->cookie(get_setting('system_prefix_cookie').'rembember_user')):
            $this->load->model('usermeta_model', 'usermeta');
            $cookie_saved = $this->input->cookie(get_setting('system_prefix_cookie').'rembember_user', TRUE);
            //return cookie
            if ($this->usermeta->get_by_value($cookie_saved)->row()):
                $query_cookie = $this->usermeta->get_by_value($cookie_saved)->row();
                $teste = $query_cookie->usermeta_userid;
                //creates session
                $query = $this->users->get_by_id($query_cookie->usermeta_userid)->row();
                $data = array(
                    set_session('user_id') => $query->user_id,
                    set_session('user_username') => $query->user_username,
                    set_session('user_email') => $query->user_email,
                    set_session('user_name') => $query->user_name,
                    set_session('user_level') => $query->user_level,
                    set_session('user_status') => $query->user_status,
                    set_session('user_displayname') => $query->user_displayname,
                    set_session('user_logged') => TRUE,
                    set_session('system_language') => get_setting('general_language'),
                );
                $this->session->set_userdata($data);
                audit('Login to the system', 'The user "'.$query->user_username.'" logged into the system from cookie "'.$cookie_saved.'" saved', 'login');
                if ($redirect != ''):
                    redirect($redirect);
                else:
                    redirect('dashboard'); 
                endif;
            endif;
        endif;
        //data validation
		$this->form_validation->set_message('required', lang('core_msg_required'));
        $this->form_validation->set_message('valid_email', lang('core_msg_email'));
        $this->form_validation->set_message('min_length', lang('login_msg_min_length'));
        $this->form_validation->set_rules('login', strtoupper(lang('login_field_login')), 'trim|required|strtolower');
        $this->form_validation->set_rules('password', strtoupper(lang('login_field_pass')), 'trim|required|min_length[6]');
        //performs login
        if ($this->form_validation->run()==TRUE):
            $login = $this->input->post('login', TRUE);
            $password = md5($this->input->post('password', TRUE));
            $redirect = $this->input->post('redirect', TRUE);
            //creates a login session
            if ($this->users->do_login($login, $password) == TRUE):
                $query = $this->users->get_by_login($login)->row();
                $data = array(
                    set_session('user_id') => $query->user_id,
                    set_session('user_username') => $query->user_username,
                    set_session('user_email') => $query->user_email,
                    set_session('user_name') => $query->user_name,
                    set_session('user_level') => $query->user_level,
                    set_session('user_status') => $query->user_status,
                    set_session('user_displayname') => $query->user_displayname,
                    set_session('user_logged') => TRUE,
                    set_session('system_language') => get_setting('general_language'),
                );
                $this->session->set_userdata($data);
                //audit
                audit('Login to the system', 'The user "'.$query->user_username.'" logged in the system', 'login');
                //create cookie to remember the user
                if ($this->input->post('remember') == '1'):
                    $this->load->model('usermeta_model', 'usermeta');
                    $key_cookie = substr(str_shuffle('6789asdfghjklzqwertyuiopxcvbnm12345'), 0, 40);
                    $cookie = array(
                        'name'   => get_setting('system_prefix_cookie').'rembember_user',
                        'value'  => $key_cookie,
                        'expire' => 2592000,
                        'secure' => FALSE
                    );
                    $this->input->set_cookie($cookie);
                    $data_usermeta['usermeta_userid'] = $query->user_id;
                    $data_usermeta['usermeta_key'] = get_setting('system_prefix_cookie').'rembember_user';
                    $data_usermeta['usermeta_value'] = $key_cookie;
                    $this->usermeta->do_insert($data_usermeta, FALSE);
                    set_msg('msgok', lang('login_msg_cookie'), 'sucess');
                endif;
                if ($redirect != ''):
                    redirect($redirect);
                else:
                    redirect('dashboard'); 
                endif;
            else:
                //blocks lighted by the login fails
                $query = $this->users->get_by_login($login)->row();
                if (empty($query)):
                    set_msg('errorlogin', lang('login_msg_user_not_exist'), 'error');
                    audit('Erro de login', 'User "'.$this->input->post('login').'" not exists', 'error');
                elseif($query->password != $password):
                    set_msg('errorlogin', lang('login_msg_pass_incorrect'), 'error');
                    audit('Erro de login', 'User "'.$this->input->post('login').'" wrong password', 'error');
                elseif($query->active != 1):
                    set_msg('errorlogin', lang('login_msg_user_inative'), 'error');
                    audit('Erro de login', 'User "'.$this->input->post('login').'" inactive', 'error');
                else:
                    set_msg('errorlogin', lang('login_msg_error'), 'error');
                    audit('Login Error', 'Login error unknown', 'error');
                endif;
                redirect('login');
            endif;
        endif;
        //mount the page layout
        set_theme('title', lang('login'));
        set_theme('content', load_module('login_view', 'login'));
        set_theme('template', 'template_view');
        load_template();
    }

    // --------------------------------------------------------------------

    /**
     * The logoff page
     *
     * Page to block access the system
     * Performs off the system.
     *
     * @access     public
     * @since      0.0.0
     * @modify     0.0.0
     */
	public function logoff(){
        //performs audit
        audit('Logoff the user', 'The user has logged off the system', 'logoff', FALSE);
        //if there is login cookie it will be deleted the cookie
        if ($this->input->cookie(get_setting('system_prefix_cookie').'rembember_user')):
            $cookie = array(
                'name'   => get_setting('system_prefix_cookie').'rembember_user',
                'value'  => '',
                'expire' => '',
                'secure' => FALSE
            );
            $this->input->set_cookie($cookie);
        endif;
        //clears the session
        $this->session->unset_userdata(array(
            set_session('user_id')=>'', 
            set_session('rs_user_username')=>'', 
            set_session('rs_user_email')=>'', 
            set_session('user_namel')=>'', 
            set_session('user_level')=>'', 
            set_session('user_status')=>'', 
            set_session('user_displayname')=>'', 
            set_session('user_logged')=>'',
            set_session('system_language') => ''
            ));
        $this->session->sess_destroy();
        $this->session->sess_create();
        set_msg('logoffok', lang('login_msg_loggoff'), 'sucess');
        redirect('login');
    }

    // --------------------------------------------------------------------

    /**
     * The signup page
     *
     * Page create profile in the system
     * Allows you to enter data into the system in order to create an account.
     *
     * @access     public
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function signup(){
        //if you are logged redirects to the dashboard
        if (be_logged(FALSE)) redirect('dashboard');
        //the registration of new users have not enabled, redirects to the login page
        if (get_setting('users_member_site') != 1):
            set_msg('errorlogin', lang('login_signup_not'), 'error');
            redirect('login');
        endif;
        //data validation
        $this->form_validation->set_rules('user_username', strtoupper(lang('login_field_user_name')), 'trim|required|min_length[6]|is_unique[users.user_username]|strtolower');
        $this->form_validation->set_rules('user_name', strtoupper(lang('login_field_name')), 'trim|required');
        $this->form_validation->set_rules('user_displayname', strtoupper(lang('login_field_display_name')), 'trim');
        $this->form_validation->set_rules('user_email', strtoupper(lang('login_field_email')), 'trim|required|valid_email|is_unique[users.user_email]|strtolower');
        $this->form_validation->set_rules('user_email2', strtoupper(lang('login_field_email_repeat')), 'trim|required|valid_email|matches[user_email]|strtolower');
        $this->form_validation->set_rules('user_pass', strtoupper(lang('login_field_pass')), 'trim|required|min_length[6]');
        $this->form_validation->set_rules('user_pass2', strtoupper(lang('login_field_pass_repeat')), 'trim|required|matches[user_pass]');
        $this->form_validation->set_message('required', lang('core_msg_required'));
        $this->form_validation->set_message('valid_email', lang('core_msg_email'));
        $this->form_validation->set_message('min_length', lang('login_msg_min_length'));
        $this->form_validation->set_message('is_unique', lang('core_msg_unique'));
        $this->form_validation->set_message('matches', lang('core_msg_matches'));
        //save data
        if ($this->form_validation->run() == TRUE):
            $data = elements(array('user_name', 'user_displayname', 'user_email', 'user_adress', 'user_url', 'user_doc'), $this->input->post());
            $data['user_pass'] = md5($this->input->post('user_pass'));
            $data['user_level'] = 5;
            $data['user_status'] = 1;
            $this->users->do_insert($data);
        endif;
        //mount the page layout
        set_theme('title', lang('login_signup'));
        set_theme('content', load_module('login_view', 'signup'));
        set_theme('template', 'template_view');
        load_template();
    }

    // --------------------------------------------------------------------

    /**
     * The newpassword page
     *
     * Page generate and send a new password to the user
     * Allows to generate and send a new password to the user.
     *
     * @access     public
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function newpassword(){
        //if you are logged redirects to the dashboard
        if (be_logged(FALSE)) redirect('dashboard');
        //data validation
        $this->form_validation->set_rules('user_email', strtoupper(lang('login_field_email')), 'trim|required|valid_email');
        $this->form_validation->set_message('required', lang('core_msg_required'));
        $this->form_validation->set_message('valid_email', lang('core_msg_email'));
        //send a new password
        if ($this->form_validation->run() == TRUE):
            $email = $this->input->post('user_email');
            $query = $this->users->get_by_email($email);
            if($query->num_rows()==1):
                $new_password = substr(str_shuffle('qwertyuiopasdfghjklzxcvbnm123456789'), 0, 6);
                $mensage = "<p>".lang('login_newpassword_email_p_1')." <strong>$new_password</strong></p><p>".lang('login_newpassword_email_p_2')."</p>";
                if ($this->system->send_email($email, lang('login_newpassword_email_title'), $mensage)):
                    $data['user_pass'] = md5($new_password);
                    $this->users->do_update($data, array('user_email'=>$email), FALSE);
                    audit('Reset Password', 'The user requested a new password by email', 'email');
                    set_msg('msgok', lang('login_msg_newpassword_sucess'), 'sucess');
                    redirect('login/newpassword');
                else:
                    set_msg('msgerror', lang('login_msg_newpassword_error'), 'error');
                    redirect('login/newpassword');
                endif;
            else:
                set_msg('msgerror', lang('core_msg_email_not_register'), 'error');
                redirect('login/newpassword');
            endif;
        endif;
        //mount the page layout
        set_theme('title', lang('login_newpassword'));
        set_theme('content', load_module('login_view', 'newpassword'));
        set_theme('template', 'template_view');
        load_template();
    }

   

}

/* End of file login.php */
/* Location: ./application/controllers/login.php */