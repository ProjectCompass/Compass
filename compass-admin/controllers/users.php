<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Users class
 *
 * Controller of pages of user management
 *
 * Maps to the following URL
 * 		http://yoursite.com/users
 *
 * @package		Compass
 * @subpackage	Core
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.0.0
 */

class Users extends CI_Controller {

	/**
	 * Constructor
	 *
	 */
	public function __construct(){
		parent::__construct();
		//restricts access to people logged
		//loads of standard features dashboard
		initialize_dashboard();
		//loads utilities
		$this->load->model('usermeta_model', 'usermeta');
		$this->load->model('userslevels_model', 'userslevels');
	}

	// --------------------------------------------------------------------

	/**
	 * The default page
	 *
	 * Page with list of members
	 * Allows the availability of links to CRUD operations for users, 
	 * their ordering, performing simple research, applying filters and pagination of results.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function index(){
		//variables paging and query
		$this->load->helper('make_table');
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
		//creates a url search
		if ($this->input->post('search')) get_paging_search($this->input->post('search_for'), $config);
		//translate the table of user levels
		$this->userslevels->do_update(array('userlevel_name'=>lang('users_userlevel_1_name'), 'userlevel_description'=>lang('users_userlevel_1_description')), array('userlevel_id'=>1), FALSE);
		$this->userslevels->do_update(array('userlevel_name'=>lang('users_userlevel_2_name'), 'userlevel_description'=>lang('users_userlevel_2_description')), array('userlevel_id'=>2), FALSE);
		$this->userslevels->do_update(array('userlevel_name'=>lang('users_userlevel_3_name'), 'userlevel_description'=>lang('users_userlevel_3_description')), array('userlevel_id'=>3), FALSE);
		$this->userslevels->do_update(array('userlevel_name'=>lang('users_userlevel_4_name'), 'userlevel_description'=>lang('users_userlevel_4_description')), array('userlevel_id'=>4), FALSE);
		$this->userslevels->do_update(array('userlevel_name'=>lang('users_userlevel_5_name'), 'userlevel_description'=>lang('users_userlevel_5_description')), array('userlevel_id'=>5), FALSE);
		//mount the page layout
		//set_theme('footerinc', load_module('includes_view', 'deletereg'), FALSE);
		load_template(lang('users'), load_module('users_view', 'users', array('config'=>$config)), 'template_view');
	}

	// --------------------------------------------------------------------

	/**
	 * The page lists
	 *
	 * Page with list of members 
	 * Allows the availability of links to CRUD operations for users, 
	 * their ordering, performing simple research, applying filters and pagination of results.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function lists(){
		//doubles the method index
		$this->index();
	}

	// --------------------------------------------------------------------

	/**
	 * The page profile
	 *
	 * Page view profile page of users
	 * Lets you view information of users registered in the system.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function profile(){
		//access permission
		access('perm_viewprofileusers_', 'middle');
		//mount the page layout
		set_theme('title', lang('users_profile'));
		set_theme('content', load_module('users_view', 'profile'));
		set_theme('helper', lang('help_users_profile'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The page insert
	 *
	 * Page of insert new user
	 * Allows you to enter the information needed to register a new user.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function insert(){
		//access permission
		access('perm_insertusers_');
		//data validation
        $this->form_validation->set_rules('user_username', strtoupper(lang('users_field_user_name')), 'trim|required|min_length[6]|is_unique[users.user_username]|strtolower');
        $this->form_validation->set_rules('user_name', strtoupper(lang('users_field_name')), 'trim|required');
        $this->form_validation->set_rules('user_displayname', strtoupper(lang('users_field_display_name')), 'trim');
        $this->form_validation->set_rules('user_email', strtoupper(lang('users_field_email')), 'trim|required|valid_email|is_unique[users.user_email]|strtolower');
        $this->form_validation->set_rules('user_email2', strtoupper(lang('users_field_email_repeat')), 'trim|required|valid_email|matches[user_email]|strtolower');
        $this->form_validation->set_rules('user_pass', strtoupper(lang('users_field_pass')), 'trim|required|min_length[6]');
        $this->form_validation->set_rules('user_pass2', strtoupper(lang('users_field_pass_repeat')), 'trim|required|matches[user_pass]');
        //adds validation to the fields inserted in method settings
        $query_users_fields = $this->terms->get_by_type('userfield')->result();
        foreach ($query_users_fields as $line):
        	if (get_termmeta('termmeta_register', $line->term_id) == 1):
	        	if (get_termmeta('termmeta_required', $line->term_id) == 1):
	        		$this->form_validation->set_rules($line->term_slug, $line->term_name, 'required');
	        	endif;
        	endif;
        endforeach;
        $this->form_validation->set_message('required', lang('core_msg_required'));
        $this->form_validation->set_message('is_unique', lang('core_msg_unique'));
        $this->form_validation->set_message('matches', lang('core_msg_matches'));
        $this->form_validation->set_message('min_length', lang('core_msg_length'));
        $this->form_validation->set_message('valid_email', lang('core_msg_email'));
        //registers data passed through the form of view
        if ($this->form_validation->run() == TRUE):
            $data_users = elements(array('user_username', 'user_email', 'user_name', 'user_displayname', 'user_level'), $this->input->post());
            $data_users['user_pass'] = md5($this->input->post('user_pass'));
            $data_users['user_status'] = 1;
            $data_users['user_registered'] = date('Y-m-d H:i:s');
            $query_users_fields = $this->terms->get_by_type('userfield')->result();
            $idadded = $this->users->do_insert($data_users, FALSE);
            //insert additional data in the db table usermeta
            $data_usermeta['usermeta_userid'] = $idadded;
            foreach ($query_users_fields as $line):
            	if (get_termmeta('termmeta_register', $line->term_id) == 1):
            		$data_usermeta['usermeta_key'] = $line->term_slug;
		            $data_usermeta['usermeta_value'] = $this->input->post($line->term_slug);
		            $this->usermeta->do_insert($data_usermeta, FALSE);
            	endif;
            endforeach;
            redirect(current_url());
		endif;
		//mount the page layout
		load_template(lang('users_insert'), load_module('users_view', 'insert'));
	}

	// --------------------------------------------------------------------

	/**
	 * The page update
	 *
	 * Page of update user
	 * Allows you to change the registration information of a user.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function update(){
		//access permission
		access('perm_updateusers_', 'high', FALSE, $this->users->get_by_id($this->uri->segment(3))->row()->user_level, $this->users->get_by_id($this->uri->segment(3))->row()->user_username);
		//upload and register the user avatar
		if($this->input->post('upload_image')):
        	$upload = $this->usermeta->do_upload('user_image');
			if (is_array($upload) && $upload['file_name'] != ''):
				set_usermeta('user_image', $upload['file_name'], $this->input->post('iduser'));
			endif;
		endif;
		//delete the user avatar
		if($this->input->post('delete_image')):
        	unlink('./compass-content/uploads/avatars/'.get_usermeta('user_image', $this->input->post('iduser')));
			$thumbs = glob('./compass-content/uploads/avatars/thumbs/*_'.get_usermeta('user_image', $this->input->post('iduser')));
			foreach ($thumbs as $file):
				unlink($file);
			endforeach;
			set_usermeta('user_image', '', $this->input->post('iduser'));
        endif;
        //data validation
		$this->form_validation->set_rules('user_name', strtoupper(lang('users_field_name')), 'trim|required');
        $this->form_validation->set_rules('user_displayname', strtoupper(lang('users_field_display_name')), 'trim');
        //data email validation
        if($this->input->post('user_email') != $this->input->post('emailuser')):
        	$this->form_validation->set_rules('user_email', 'E-MAIL', 'trim|required|valid_email|is_unique[rs_users.user_email]|strtolower');
        endif;
        //data password validation
        if($this->input->post('user_pass') != NULL):
        	$this->form_validation->set_rules('user_pass', strtoupper(lang('users_field_pass_new')), 'trim|required|min_length[6]');
        	$this->form_validation->set_rules('user_pass2', strtoupper(lang('users_field_pass_new_repeat')), 'trim|required|matches[user_pass]');
        endif;
        //adds validation to the fields inserted in method settings
        $query_users_fields = $this->terms->get_by_type('userfield')->result();
        foreach ($query_users_fields as $line):
        	if (get_termmeta('termmeta_update', $line->term_id) == 1):
	        	if (get_termmeta('termmeta_required', $line->term_id) == 1):
	        		$this->form_validation->set_rules($line->term_slug, $line->term_name, 'required');
	        	endif;
        	endif;
        endforeach;
        $this->form_validation->set_message('required', lang('core_msg_required'));
        $this->form_validation->set_message('is_unique', lang('core_msg_unique'));
        $this->form_validation->set_message('matches', lang('core_msg_matches'));
        $this->form_validation->set_message('valid_email', lang('core_msg_email'));
        //edit the data passed to the view
        if ($this->form_validation->run() == TRUE):
        	$data_users = elements(array('user_name', 'user_displayname'), $this->input->post());
        	//update password, level, status and email as access
        	if ($this->input->post('user_pass') != ''):
            	$data_users['user_pass'] = md5($this->input->post('user_pass'));
			endif;
        	if (access('perm_updateuserlevel_', 'high', TRUE, $this->users->get_by_id($this->uri->segment(3))->row()->user_level, $this->users->get_by_id($this->uri->segment(3))->row()->user_username) != FALSE && $this->input->post('iduser') != 1):
        		$data_users['user_level'] = $this->input->post('user_level');
        	endif;
        	if (access('perm_updateuserstatus_', 'high', TRUE, $this->users->get_by_id($this->uri->segment(3))->row()->user_level, $this->users->get_by_id($this->uri->segment(3))->row()->user_username) != FALSE && $this->input->post('iduser') != 1):
        		$data_users['user_status'] = $this->input->post('user_status');
        	endif;
        	if($this->input->post('user_email') != $this->input->post('emailuser')):
        		$data_users['user_email'] = $this->input->post('user_email');
        	endif;
            //update usersfields
            $query_users_fields = $this->terms->get_by_type('userfield')->result();
            foreach ($query_users_fields as $line):
            	if (get_termmeta('termmeta_update', $line->term_id) == 1):
            		$data_usermeta[$line->term_slug] = $this->input->post($line->term_slug);
            	endif;
            endforeach;
            //update usermeta
            $data_usermeta = elements(array('user_url', 'user_doc', 'user_about', 'user_adress'), $this->input->post());
			foreach ($data_usermeta as $usermeta_key => $usermeta_value):
				set_usermeta($usermeta_key, $usermeta_value, $this->input->post('iduser'));
			endforeach;
			$this->users->do_update($data_users, array('user_id'=>$this->input->post('iduser')));
        endif;
        //mount the page layout
		set_theme('title', lang('users_update'));
		set_theme('content', load_module('users_view', 'update'));
		set_theme('helper', lang('help_update'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The page delete
	 *
	 * Page of delete user
	 * Allows user deletes, assigns the status '9'to the user, leaving it invisible to the system.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function delete(){
		//access permission
		access('perm_userdelete_', 'high');
		//Assigns the status '9'to the user, leaving it invisible to the system
		$iduser = $this->uri->segment(3);
		if ($iduser != NULL):
			$query = $this->users->get_by_id($iduser);
			if ($query->num_rows() == 1):
				$query = $query->row();
				if ($query->user_id != 1):
					$data['user_status'] = 9;
	        		$this->users->do_update($data, array('user_id'=>$iduser), FALSE);
	        		set_msg('msgok', lang('users_msg_delete_ok'), 'sucess');
					redirect ('users');
				else:
					set_msg('msgerror', lang('users_msg_no_delete'), 'error');
				endif;
			else:
				set_msg('msgerror', lang('users_msg_no_found'), 'error');
			endif;
		else:
			set_msg('msgerror', lang('users_msg_no_find'), 'error');
		endif;
		redirect('users');
	}

	// --------------------------------------------------------------------

	/**
	 * The page settings
	 *
	 * Page of the user module configuration
	 * Allows users to configure the module.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function settings(){
		//access permission
		access('perm_userssettings_');
		//loads necessary tools
		$this->load->model('userslevels_model', 'userslevels');
		$this->load->model('terms_model', 'terms');
		$this->load->model('termmeta_model', 'termmeta');
		//saves general settings in bd
		if ($this->input->post('save')):
			$settings = elements(array(
				'users_member_site',
				'users_level_default',
				'users_numrows_list',
				'users_show_adress'
				), $this->input->post());
			foreach ($settings as $setting_name => $setting_value):
				set_setting($setting_name, $setting_value);
			endforeach;
			if ($this->input->post('users_clear_remembers') == '1'):
				$this->load->model('usermeta_model', 'usermeta');
				$this->usermeta->do_delete(array('usermeta_key'=>setting('system_prefix_cookie').'rembember_user'), FALSE);
			endif;
			set_msg('msgok', lang('users_msg_update_settings'), 'sucess');
			redirect('users/settings');
		endif;
		//Enable users to advanced settings
		if ($this->input->post('advanced_settings')):
			if (get_setting('users_advanced_settings') == 0):
				set_setting('users_advanced_settings', 1);
				redirect(current_url());
			else:
				set_setting('users_advanced_settings', 0);
				redirect(current_url());
			endif;
		endif;
		//save new levels users 
		if ($this->input->post('save_userlevel')):
	        $this->form_validation->set_rules('userlevel_name', 'NOME DA ATRIBUIÇÃO', 'trim|required|min_length[3]');
	    	$this->form_validation->set_rules('userlevel_description', 'DESCRIÇÃO DA ATRIBUIÇÃO', 'trim|required');
	    	$this->form_validation->set_rules('userlevel_level', 'NÍVEL DA ATRIBUIÇÃO', 'trim|required');
	    	$this->form_validation->set_message('required', lang('core_msg_required'));
        	$this->form_validation->set_message('min_length', lang('core_msg_length'));
			if ($this->form_validation->run() == TRUE):
	            $data_userslevels = elements(array('userlevel_name', 'userlevel_level', 'userlevel_description'), $this->input->post());
	            $this->userslevels->do_insert($data_userslevels);
			endif;
		endif;
		//update levels users 
		if ($this->input->post('update_userlevel') && $this->uri->segment(4) != NULL):
	    	$this->form_validation->set_rules('userlevel_name', 'NOME DA ATRIBUIÇÃO', 'trim|required|min_length[3]');
	    	$this->form_validation->set_rules('userlevel_description', 'DESCRIÇÃO DA ATRIBUIÇÃO', 'trim|required');
	    	$this->form_validation->set_rules('userlevel_level', 'NÍVEL DA ATRIBUIÇÃO', 'trim|required');
	        $this->form_validation->set_message('required', "O campo %s é requerido");
	        $this->form_validation->set_message('min_length', "O campo %s é curto demais");
			if ($this->form_validation->run() == TRUE):
	            $data_userslevels = elements(array('userlevel_name', 'userlevel_level', 'userlevel_description'), $this->input->post());
	            $this->userslevels->do_update($data_userslevels, array('userlevel_id'=>$this->uri->segment(4)));
			endif;
		endif;
		//delete levels users
		if ($this->uri->segment(3) == 'deleteuserlevel' && $this->uri->segment(4) != NULL && $this->uri->segment(4) > 5):
			$this->userslevels->do_delete(array('userlevel_id'=>$this->uri->segment(4)), FALSE);
			redirect('users/settings');
		//prevents users deleting administrators
		elseif ($this->uri->segment(3) == 'deleteuserlevel' && $this->uri->segment(4) != NULL && $this->uri->segment(4) <= 5):
			set_msg('msgerror', lang('users_msg_userlevel_no_delete'), 'error');
			redirect('users/settings');
		endif;
		//save new user field
		if ($this->input->post('save_userfield')):
	        $this->form_validation->set_rules('term_name', 'TÍTULO DO CAMPO', 'trim|required|min_length[3]');
	        $this->form_validation->set_rules('term_slug', 'SLUG DO CAMPO', 'trim|required|min_length[3]');
	        $this->form_validation->set_rules('term_order', 'ORDEM DO CAMPO', 'trim|required');
	        $this->form_validation->set_rules('termmeta_description', 'DESCRIÇÃO DO CAMPO', 'trim|required');
	        $this->form_validation->set_message('required', lang('core_msg_required'));
        	$this->form_validation->set_message('min_length', lang('core_msg_length'));
			if ($this->form_validation->run() == TRUE):
	            $data_terms = elements(array('term_name', 'term_slug', 'term_type', 'term_order'), $this->input->post());
	            $idadded = $this->terms->do_insert($data_terms, FALSE);
	            $fields = array('termmeta_type', 'termmeta_description', 'termmeta_required', 'termmeta_register', 'termmeta_profile', 'termmeta_update', 'term_option1_name', 'term_option1_value', 'term_option2_name', 'term_option2_value', 'term_option3_name', 'term_option3_value', 'term_option4_name', 'term_option4_value');
	            $data_termmeta['termmeta_termid'] = $idadded;
	            $arr = 0;
            	foreach ($fields as $field):
		            if ($this->input->post($fields[$arr])!=NULL):
		            	$data_termmeta['termmeta_key'] = $fields[$arr];
		            	$data_termmeta['termmeta_value'] = $this->input->post($fields[$arr]);
		            	$arr++;
		            	$this->termmeta->do_insert($data_termmeta, FALSE);
		            endif;
            	endforeach;
            	redirect(current_url());
			endif;
		endif;
		//update user field
		if ($this->input->post('update_userfield') && $this->uri->segment(4) != NULL):
	        $this->form_validation->set_rules('term_name', 'TÍTULO DO CAMPO', 'trim|required|min_length[3]');
	        $this->form_validation->set_rules('term_slug', 'SLUG DO CAMPO', 'trim|required|min_length[3]');
	        $this->form_validation->set_message('required', lang('core_msg_required'));
        	$this->form_validation->set_message('min_length', lang('core_msg_length'));
			if ($this->form_validation->run()==TRUE):
	            $data_term = elements(array('term_name', 'term_slug', 'term_order'), $this->input->post());
	        	$this->terms->do_update($data_term, array('term_id'=>$this->input->post('idterm')), FALSE);
	            $fields = array('termmeta_type', 'termmeta_description', 'termmeta_required', 'termmeta_register', 'termmeta_profile', 'termmeta_update', 'term_option1_name', 'term_option1_value', 'term_option2_name', 'term_option2_value', 'term_option3_name', 'term_option3_value', 'term_option4_name', 'term_option4_value');
	            $arr = 0;
            	foreach ($fields as $field):
		            	$data_termmeta['termmeta_value'] = $this->input->post($fields[$arr]);
		            	$this->termmeta->do_update($data_termmeta, array('termmeta_termid'=>$this->uri->segment(4), 'termmeta_key'=>$fields[$arr]), FALSE);
		            	$arr++;
            	endforeach;
            	redirect(current_url());
			endif;
		endif;
		//delete user field
		if ($this->uri->segment(3) == 'deleteuserfield' && $this->uri->segment(4) != NULL):
			$this->terms->do_delete(array('term_id'=>$this->uri->segment(4)), FALSE);
			$this->termmeta->do_delete(array('termmeta_termid'=>$this->uri->segment(4)), FALSE);
			redirect('users/settings');
		endif;
		//mount the page layout
		set_theme('title', lang('users_settings'));
		set_theme('content', load_module('users_view', 'settings'), FALSE);
		if (get_setting('users_advanced_settings') == 1):
			set_theme('content', load_module('users_view', 'usersfields'), FALSE);
			set_theme('content', load_module('users_view', 'userslevels'), FALSE);
		endif;
		set_theme('headerinc', load_module('includes_view', 'deletereg'), FALSE);
		set_theme('helper', lang('help_users_settings'));
		load_template();
	}
}

/* End of file users.php */
/* Location: ./application/controllers/users.php */