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
 * @since       1.0.0
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
		set_theme('submenu', load_module('settings', 'submenu'));
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
	 * @since      1.0.0
	 * @modify     1.0.0
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
		set_theme('content', load_module('settings', 'general'));
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
	 * @since      1.0.0
	 * @modify     1.0.0
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
		set_theme('content', load_module('settings', 'aparence'));
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
	 * @since      1.0.0
	 * @modify     1.0.0
	 */
	public function modules(){
		//access permission
		access('perm_settings_');
		//registers data passed through the form of view
		if ($this->input->post('save')):
			//get form values
			$settings = elements(array(
				'module_cms',
				'module_books',
				'module_journal',
				'module_eventus',
				'module_helpdesk'
				), $this->input->post());
			//set settings data
			foreach ($settings as $setting_name => $setting_value):
				set_setting($setting_name, $setting_value);
			endforeach;
			set_msg('msgok', lang('settings_msg_update_ok'), 'sucess');
			redirect('settings/modules');
		endif;
		//mount the page layout
		set_theme('title', lang('settings_modules'));
		set_theme('content', load_module('settings', 'modules'));
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
	 * @since      1.0.0
	 * @modify     1.0.0
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
	}
	
}

/* End of file settings.php */
/* Location: ./application/controllers/settings.php */