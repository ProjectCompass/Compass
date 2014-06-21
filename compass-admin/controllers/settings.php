<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Settings class
 *
 * Controller of settings management
 *
 * Maps to the following URL
 * 		http://yoursite.com/settings
 *
 * @package		Compass
 * @subpackage	Core
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.0.0
 */

class Settings extends CI_Controller {
	
	/**
	 * Constructor
	 *
	 */
	public function __construct(){
		parent::__construct();
		//restricts access to people logged
		be_logged();
		//loads of standard features dashboard
		initialize_dashboard();
		//sets the block submenu controller
		set_theme('submenu', load_module('settings_view', 'submenu'));
		set_theme('helper', lang('help_settings'));
	}

	// --------------------------------------------------------------------

	/**
	 * The default page
	 *
	 * Page with list of general settings
	 * Allows the define the main system settings
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function index(){
		//access permission
		access('perm_settings_');
		$this->load->helper('directory');
		//registers data passed through the form of view
		if ($this->input->post('save')):
			//get form values
			$settings = elements(array(
				'general_title_site',
				'general_description_site',
				'general_url_site',
				'general_email_admin',
				'general_homepage',
				'general_timezone_summertime',
				'general_large_list',
				'general_small_list',
				'general_language'
				), $this->input->post());
			$settings['general_timezone'] = $this->input->post('timezones');
			//receives the input value if custom
			if ($this->input->post('general_date_format') == 'custom'):
				$settings['general_date_format'] = $this->input->post('general_date_format_custom');
			else:
				$settings['general_date_format'] = $this->input->post('general_date_format');
			endif;
			//receives the input value if custom
			if ($this->input->post('general_time_format') == 'custom'):
				$settings['general_time_format'] = $this->input->post('general_time_format_custom');
			else:
				$settings['general_time_format'] = $this->input->post('general_time_format');
			endif;
			//set settings data
			foreach ($settings as $setting_name => $setting_value):
				set_setting($setting_name, $setting_value);
			endforeach;
			set_msg('msgok', lang('settings_msg_update_ok'), 'sucess');
			redirect('settings');
		endif;
		//Enable advanced settings
		if ($this->input->post('advanced_settings')):
			if (get_setting('general_advanced_settings') == 0):
				set_setting('general_advanced_settings', 1);
				redirect(current_url());
			else:
				set_setting('general_advanced_settings', 0);
				redirect(current_url());
			endif;
		endif;
		//mount the page layout
		set_theme('title', lang('settings'));
		set_theme('content', load_module('settings_view', 'general'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The default page
	 *
	 * Page with list of aparence settings
	 * Allows the define the aparence of the dashboard settings
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function aparence(){
		//access permission
		access('perm_settings_');
		//registers data passed through the form of view
		if ($this->input->post('save')):
			//get form values
			$settings = elements(array(
				'aparence_background_color',
				'aparence_background_image',
				'aparence_logo',
				'aparence_copy_footer',
				'aparence_active_css',
				'aparence_css'
				), $this->input->post());
			//set settings data
			foreach ($settings as $setting_name => $setting_value):
				set_setting($setting_name, $setting_value);
			endforeach;
			set_msg('msgok', lang('settings_msg_update_ok'), 'sucess');
			redirect('settings/aparence');
		endif;
		//mount the page layout
		set_theme('title', lang('settings_aparence'));
		set_theme('content', load_module('settings_view', 'aparence'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The page permissions
	 *
	 * Settings page in user permissions system
	 * Allows you to configure the permissions for groups of users in the system.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function permissions(){
		//access permission
		access('perm_userspermissions_');
		//loads necessary tools
		$this->load->model('userslevels_model', 'userslevels');
		//saves changes made
		if ($this->input->post('save')):
			$query_userslevels = $this->userslevels->get_all()->result();
			//receives the values ​​of the fields passed in view
			$allpermissions = array(
				'perm_ative_',
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
				'perm_contentssettings_',
				'perm_listusers_',
				'perm_viewprofileusers_',
				'perm_insertusers_',
				'perm_updateusers_',
				'perm_updateuserlevel_',
				'perm_updateuserstatus_',
				'perm_userdelete_',
				'perm_userssettings_',
                'perm_userspermissions_',
				'perm_settings_',
				'perm_tools_',
				'perm_germedia_'
				);
			//crossing rows and columns to get all the data from the table permissions
			foreach ($allpermissions as $column):
				foreach ($query_userslevels as $line):
					$permission = $column;
					$settings["$permission$line->userlevel_id"] = $this->input->post("$permission$line->userlevel_id");
				endforeach;
			endforeach;
			//gives all permissions to users level 1
			foreach ($allpermissions as $column):
				$permission = $column;
				$settings[$permission.'1'] = 1;
			endforeach;
			//enters the settings in bd
			foreach ($settings as $setting_name => $setting_value):
				set_setting($setting_name, $setting_value);
			endforeach;
			set_msg('msgok', lang('users_msg_update_sucess'), 'sucess');
			redirect('settings/permissions');
		endif;
		//mount the page layout
		set_theme('title', lang('permissions'));
		set_theme('content', load_module('settings_view', 'permissions'));
		set_theme('helper', lang('help_permissions'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The options page
	 *
	 * Page with execute options settings
	 * Allows the define the options of the dashboard, such as color of the top bar
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function options(){
		//saves the option set by each user color of the top bar in bd
		if ($this->uri->segment(3) == 'color' && $this->uri->segment(4) != NULL):
			set_usermeta('user_theme', $this->uri->segment(4), get_session('user_id'));
			if ($this->uri->segment(5) != NULL):
				redirect($this->uri->segment(5).'/'.$this->uri->segment(6).'/'.$this->uri->segment(7));
			else:
				redirect(base_url('dashboard'));
			endif;
		endif;
		//saves the option set by each user language of the top bar in bd
		if ($this->uri->segment(3) == 'language' && $this->uri->segment(4) != NULL):
			set_usermeta('user_language', $this->uri->segment(4), get_session('user_id'));
            $this->session->set_userdata(array(set_session('system_language') => $this->uri->segment(4)));
			redirect(base_url('dashboard'));
		endif;
	}
	
}

/* End of file settings.php */
/* Location: ./application/controllers/settings.php */