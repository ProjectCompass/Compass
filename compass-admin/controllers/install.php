<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Install class
 *
 * Controller of install system
 *
 * Maps to the following URL
 * 		http://yoursite.com/install
 *
 * @package		Compass
 * @subpackage	Core
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.0.0
 */

class Install extends CI_Controller {

	/**
	 * Constructor
	 *
	 */
	public function __construct(){
		parent::__construct();
		//loads of standard features dashboard
		$this->load->library(array('parser', 'system', 'form_validation'));
    	$this->load->helper(array('form', 'url', 'file', 'directory', 'array', 'text', 'paging'));
    	//loading css in header
	    set_theme('headerinc', load_css(array('normalize', 'style', 'foundation.min', 'app', 'font-awesome/css/font-awesome.min')), FALSE);
	    //loading js in footer
	    set_theme('headerinc', load_js(array('modernizr', 'jquery-1.9.1.min')), FALSE);
	    set_theme('footerinc', load_js(array('foundation.min', 'app')), FALSE);
		$this->lang->load('core', ($this->uri->segment(3)) ? $this->uri->segment(3) : 'english');
	}

	// --------------------------------------------------------------------

	/**
	 * The default page
	 *
	 * Page home install system
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function index(){
		if ($this->uri->segment(3) == NULL) redirect('install/index/english');
		//passes to the next screen
		if ($this->input->post('save')):
			redirect('install/second/'.$this->uri->segment(3));
		endif;
		//mount the page layout
		set_theme('title', $this->lang->line('install_error_title'));
		set_theme('content', load_module('install_view', 'first'));
		set_theme('template', 'base_view');
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The second page
	 *
	 * Page for preconditions for installing the system
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function second(){
		//resets settings
		$file_routes = '
<?php  if ( ! defined("BASEPATH")) exit("No direct script access allowed");
$route["default_controller"] = "install";
$route["404_override"] = "stop";
/* End of file routes.php */
/* Location: ./application/config/routes.php */';
			write_file('./compass-admin/config/routes.php', trim($file_routes));
			$file_config = '
<?php  if ( ! defined("BASEPATH")) exit("No direct script access allowed");
$config["base_url"]	= "";
$config["index_page"] = "";
$config["uri_protocol"]	= "AUTO";
$config["url_suffix"] = "";
$config["language"]	= "english";
$config["charset"] = "UTF-8";
$config["enable_hooks"] = FALSE;
$config["subclass_prefix"] = "COMPASS_";
$config["permitted_uri_chars"] = "a-z 0-9~%.:_\-";
$config["allow_get_array"]		= TRUE;
$config["enable_query_strings"] = FALSE;
$config["controller_trigger"]	= "c";
$config["function_trigger"]		= "m";
$config["directory_trigger"]	= "d"; // experimental not currently in use
$config["log_threshold"] = 0;
$config["log_path"] = "";
$config["log_date_format"] = "Y-m-d H:i:s";
$config["cache_path"] = "";
$config["encryption_key"] = "'.md5(time()).'";
$config["sess_cookie_name"]		= "compass_session";
$config["sess_expiration"]		= 7200;
$config["sess_expire_on_close"]	= TRUE;
$config["sess_encrypt_cookie"]	= TRUE;
$config["sess_use_database"]	= FALSE;
$config["sess_table_name"]		= "compass_sessions";
$config["sess_match_ip"]		= TRUE;
$config["sess_match_useragent"]	= TRUE;
$config["sess_time_to_update"]	= 300;
$config["cookie_prefix"]	= "";
$config["cookie_domain"]	= "";
$config["cookie_path"]		= "/";
$config["cookie_secure"]	= FALSE;
$config["global_xss_filtering"] = FALSE;$config["csrf_protection"] = FALSE;
$config["csrf_token_name"] = "csrf_test_name";
$config["csrf_cookie_name"] = "csrf_cookie_name";
$config["csrf_expire"] = 7200;
$config["compress_output"] = FALSE;
$config["time_reference"] = "local";
$config["rewrite_short_tags"] = FALSE;
$config["proxy_ips"] = "";
/* End of file config.php */
/* Location: ./application/config/config.php */';
			write_file('./compass-admin/config/config.php', trim($file_config));
			$file_database = '
<?php  if ( ! defined("BASEPATH")) exit("No direct script access allowed");
$active_group = "default";
$active_record = TRUE;
$db["default"]["hostname"] = "localhost";
$db["default"]["username"] = "root";
$db["default"]["password"] = "";
$db["default"]["database"] = "";
$db["default"]["dbdriver"] = "mysql";
$db["default"]["dbprefix"] = "";
$db["default"]["pconnect"] = TRUE;
$db["default"]["db_debug"] = TRUE;
$db["default"]["cache_on"] = FALSE;
$db["default"]["cachedir"] = "";
$db["default"]["char_set"] = "utf8";
$db["default"]["dbcollat"] = "utf8_general_ci";
$db["default"]["swap_pre"] = "";
$db["default"]["autoinit"] = TRUE;
$db["default"]["stricton"] = FALSE;
/* End of file database.php */
/* Location: ./application/config/database.php */';
			write_file('./compass-admin/config/database.php', trim($file_database));
		//passes to the next screen
		if ($this->input->post('save')):
			redirect('install/third/'.$this->uri->segment(3));
		endif;
		//mount the page layout
		set_theme('title', $this->lang->line('install_file_configs'));
		set_theme('content', load_module('install_view', 'second'));
		set_theme('template', 'base_view');
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The third page
	 *
	 * Page for database settings for installing the system
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function third(){
		//passes to the next screen
		//data validation
        $this->form_validation->set_rules('db_name', strtoupper($this->lang->line('install_third_field_name')), 'trim|required');
        $this->form_validation->set_rules('db_user', strtoupper($this->lang->line('install_third_field_user')), 'trim|required');
        $this->form_validation->set_rules('db_server', strtoupper($this->lang->line('install_third_field_server')), 'trim|required');
        $this->form_validation->set_message('required', $this->lang->line('core_msg_required'));
		if ($this->form_validation->run()):
			//create files
			$file_routes = '
<?php  if ( ! defined("BASEPATH")) exit("No direct script access allowed");
$route["default_controller"] = "install";
$route["404_override"] = "stop";
/* End of file routes.php */
/* Location: ./application/config/routes.php */';
			write_file('./compass-admin/config/routes.php', trim($file_routes));
			$file_config = '
<?php  if ( ! defined("BASEPATH")) exit("No direct script access allowed");
$config["base_url"]	= "'.base_url().'";
$config["index_page"] = "";
$config["uri_protocol"]	= "AUTO";
$config["url_suffix"] = "";
$config["language"]	= "english";
$config["charset"] = "UTF-8";
$config["enable_hooks"] = FALSE;
$config["subclass_prefix"] = "COMPASS_";
$config["permitted_uri_chars"] = "a-z 0-9~%.:_\-";
$config["allow_get_array"]		= TRUE;
$config["enable_query_strings"] = FALSE;
$config["controller_trigger"]	= "c";
$config["function_trigger"]		= "m";
$config["directory_trigger"]	= "d"; // experimental not currently in use
$config["log_threshold"] = 0;
$config["log_path"] = "";
$config["log_date_format"] = "Y-m-d H:i:s";
$config["cache_path"] = "";
$config["encryption_key"] = "'.md5(time()).'";
$config["sess_cookie_name"]		= "compass_session";
$config["sess_expiration"]		= 7200;
$config["sess_expire_on_close"]	= TRUE;
$config["sess_encrypt_cookie"]	= TRUE;
$config["sess_use_database"]	= FALSE;
$config["sess_table_name"]		= "compass_sessions";
$config["sess_match_ip"]		= TRUE;
$config["sess_match_useragent"]	= TRUE;
$config["sess_time_to_update"]	= 300;
$config["cookie_prefix"]	= "";
$config["cookie_domain"]	= "";
$config["cookie_path"]		= "/";
$config["cookie_secure"]	= FALSE;
$config["global_xss_filtering"] = FALSE;$config["csrf_protection"] = FALSE;
$config["csrf_token_name"] = "csrf_test_name";
$config["csrf_cookie_name"] = "csrf_cookie_name";
$config["csrf_expire"] = 7200;
$config["compress_output"] = FALSE;
$config["time_reference"] = "local";
$config["rewrite_short_tags"] = FALSE;
$config["proxy_ips"] = "";
/* End of file config.php */
/* Location: ./application/config/config.php */';
			write_file('./compass-admin/config/config.php', trim($file_config));
			$file_database = '
<?php  if ( ! defined("BASEPATH")) exit("No direct script access allowed");
$active_group = "default";
$active_record = TRUE;
$db["default"]["hostname"] = "'.$this->input->post('db_server').'";
$db["default"]["username"] = "'.$this->input->post('db_user').'";
$db["default"]["password"] = "'.$this->input->post('db_pass').'";
$db["default"]["database"] = "'.$this->input->post('db_name').'";
$db["default"]["dbdriver"] = "mysql";
$db["default"]["dbprefix"] = "";
$db["default"]["pconnect"] = TRUE;
$db["default"]["db_debug"] = TRUE;
$db["default"]["cache_on"] = FALSE;
$db["default"]["cachedir"] = "";
$db["default"]["char_set"] = "utf8";
$db["default"]["dbcollat"] = "utf8_general_ci";
$db["default"]["swap_pre"] = "";
$db["default"]["autoinit"] = TRUE;
$db["default"]["stricton"] = FALSE;
/* End of file database.php */
/* Location: ./application/config/database.php */';
			write_file('./compass-admin/config/database.php', trim($file_database));
			//connect to the database
			$this->load->database();
			$this->db->reconnect();
			//create table audits
			$sql_bd = "CREATE TABLE IF NOT EXISTS `audits` (
			  `audit_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `audit_userid` bigint(20) unsigned NOT NULL,
			  `audit_type` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `audit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `audit_process` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `audit_description` text CHARACTER SET utf8 NOT NULL,
			  `audit_query` text CHARACTER SET utf8 NOT NULL,
			  PRIMARY KEY (`audit_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
			$this->db->query($sql_bd);
			//create table settings
			$sql_bd = "CREATE TABLE IF NOT EXISTS `settings` (
			  `setting_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `setting_name` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `setting_value` text CHARACTER SET utf8 NOT NULL,
			  PRIMARY KEY (`setting_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
			$this->db->query($sql_bd);
			//create table termmeta
			$sql_bd = "CREATE TABLE IF NOT EXISTS `termmeta` (
			  `termmeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `termmeta_termid` bigint(20) unsigned NOT NULL,
			  `termmeta_key` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `termmeta_value` text CHARACTER SET utf8 NOT NULL,
			  PRIMARY KEY (`termmeta_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
			$this->db->query($sql_bd);
			//create table terms
			$sql_bd = "CREATE TABLE IF NOT EXISTS `terms` (
			  `term_id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
			  `term_name` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `term_description` text CHARACTER SET utf8 NOT NULL,
			  `term_slug` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `term_type` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `term_order` int(11) NOT NULL,
			  `term_posts` int(11) NOT NULL,
			  PRIMARY KEY (`term_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
			$this->db->query($sql_bd);
			//create table usermeta
			$sql_bd = "CREATE TABLE IF NOT EXISTS `usermeta` (
			  `usermeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `usermeta_userid` bigint(20) unsigned NOT NULL,
			  `usermeta_key` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `usermeta_value` text CHARACTER SET utf8 NOT NULL,
			  PRIMARY KEY (`usermeta_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
			$this->db->query($sql_bd);
			//create table users
			$sql_bd = "CREATE TABLE IF NOT EXISTS `users` (
			  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `user_username` varchar(255) NOT NULL,
			  `user_pass` varchar(64) NOT NULL DEFAULT '',
			  `user_name` varchar(50) NOT NULL DEFAULT '',
			  `user_email` varchar(100) NOT NULL DEFAULT '',
			  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `user_status` int(11) NOT NULL DEFAULT '0',
			  `user_level` int(11) NOT NULL DEFAULT '0',
			  `user_displayname` varchar(255) NOT NULL DEFAULT '',
			  PRIMARY KEY (`user_id`),
			  KEY `user_nicename` (`user_name`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
			$this->db->query($sql_bd);
			//create table userslevels
			$sql_bd = "CREATE TABLE IF NOT EXISTS `userslevels` (
			  `userlevel_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `userlevel_name` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `userlevel_level` int(11) NOT NULL,
			  `userlevel_description` text CHARACTER SET utf8 NOT NULL,
			  PRIMARY KEY (`userlevel_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
			$this->db->query($sql_bd);
			//redirect next steep
			redirect('install/fourth/'.$this->uri->segment(3));
		endif;
		//mount the page layout
		set_theme('title', $this->lang->line('install_file_configs'));
		set_theme('content', load_module('install_view', 'third'));
		set_theme('template', 'base_view');
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The fourth page
	 *
	 * Page for preconditions for installing the system
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function fourth(){
		//passes to the next screen
		if ($this->input->post('install')):
			redirect('install/fifth/'.$this->uri->segment(3));
		endif;
		//mount the page layout
		set_theme('title', $this->lang->line('install_file_configs'));
		set_theme('content', load_module('install_view', 'fourth'));
		set_theme('template', 'base_view');
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The fifth page
	 *
	 * Page for preconditions for installing the system
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function fifth(){
		//passes to the next screen
		//data validation
        $this->form_validation->set_rules('title', strtoupper($this->lang->line('install_fifth_title')), 'trim|required');
        $this->form_validation->set_rules('user', strtoupper($this->lang->line('install_fifth_user')), 'trim');
        $this->form_validation->set_rules('pass', strtoupper($this->lang->line('install_fifth_pass')), 'trim|required');
        $this->form_validation->set_rules('pass2', strtoupper($this->lang->line('install_fifth_pass')), 'trim|required|matches[pass]');
        $this->form_validation->set_rules('email', strtoupper($this->lang->line('install_fifth_email')), 'trim|required|valid_email|strtolower');
        $this->form_validation->set_message('required', $this->lang->line('core_msg_required'));
        $this->form_validation->set_message('matches', $this->lang->line('core_msg_matches'));
        $this->form_validation->set_message('valid_email', $this->lang->line('core_msg_email'));
		if ($this->form_validation->run()):
			//insert audit installation compass system
			$sql_bd = "INSERT INTO `audits` (`audit_id`, `audit_userid`, `audit_type`, `audit_date`, `audit_process`, `audit_description`, `audit_query`) VALUES
				('', 1, 'Install_view', '".date('Y-m-d H:i:s')."', 'Install Compass', 'Installing the compass system with all tables of the database and setagem presets in the system.', '');";
			$this->db->query($sql_bd);
			//insert attributions user in the database
			$sql_bd = "INSERT INTO `userslevels` (`userlevel_id`, `userlevel_name`, `userlevel_level`, `userlevel_description`) VALUES
				(1, '".$this->lang->line('users_userlevel_1_name')."', 1, '".$this->lang->line('users_userlevel_1_description')."'),
				(2, '".$this->lang->line('users_userlevel_2_name')."', 2, '".$this->lang->line('users_userlevel_2_description')."'),
				(3, '".$this->lang->line('users_userlevel_3_name')."', 3, '".$this->lang->line('users_userlevel_3_description')."'),
				(4, '".$this->lang->line('users_userlevel_4_name')."', 4, '".$this->lang->line('users_userlevel_4_description')."'),
				(5, '".$this->lang->line('users_userlevel_5_name')."', 5, '".$this->lang->line('users_userlevel_5_description')."');";
			$this->db->query($sql_bd);
			//insert basic system settings
			$sql_bd = "INSERT INTO `settings` (`setting_id`, `setting_name`, `setting_value`) VALUES
				('', 'general_title_site', '".$this->input->post('title')."'),
				('', 'general_description_site', '".$this->input->post('description')."'),
				('', 'general_url_site', '".base_url()."'),
				('', 'general_email_admin', '".$this->input->post('email')."'),
				('', 'general_large_list', '20'),
				('', 'general_small_list', '10'),
				('', 'general_advanced_settings', '0'),
				('', 'module_cms', '".$this->input->post('module_cms')."'),
				('', 'module_books', '".$this->input->post('module_books')."'),
				('', 'module_journal', '".$this->input->post('module_journal')."'),
				('', 'module_eventus', '".$this->input->post('module_eventus')."'),
				('', 'module_helpdesck', '".$this->input->post('module_helpdesck')."');";
			$this->db->query($sql_bd);
			//insert first system administrator user
			$sql_bd = "INSERT INTO `users` (`user_id`, `user_username`, `user_pass`, `user_name`, `user_email`, `user_registered`, `user_status`, `user_level`, `user_displayname`) VALUES
				(1, '".$this->input->post('user')."', '".md5($this->input->post('pass'))."', '', '".$this->input->post('email')."', '".date('Y-m-d H:i:s')."', 1, 1, '".$this->input->post('user')."');";
			$this->db->query($sql_bd);
			//insert basic system settings for modules
			$themes_directorys = directory_map('./compass-content/plugins/', TRUE);
            if (in_array('index.html', $themes_directorys)) unset($themes_directorys[array_search('index.html',$themes_directorys)]);
            foreach ($themes_directorys as $name_theme_directory):
                $theme_directory_details = directory_map('./compass-content/plugins/'.$name_theme_directory, TRUE);
                if (in_array('readme.txt', $theme_directory_details)):
					$sql_bd = "INSERT INTO `settings` (`setting_id`, `setting_name`, `setting_value`) VALUES
						('', 'module_".$name_theme_directory."', '".$this->input->post('module_'.$name_theme_directory)."');";
					$this->db->query($sql_bd);
				endif;
			endforeach;
			//enter settings for user permissions 
			$sql_bd = "INSERT INTO `settings` (`setting_id`, `setting_name`, `setting_value`) VALUES
				('', 'perm_ative_1', '1'),
				('', 'perm_ative_2', '1'),
				('', 'perm_ative_3', '1'),
				('', 'perm_ative_4', '1'),
				('', 'perm_ative_5', '1'),
				('', 'perm_listusers_1', '1'),
				('', 'perm_listusers_2', '1'),
				('', 'perm_listusers_3', '1'),
				('', 'perm_viewprofileusers_1', '1'),
				('', 'perm_viewprofileusers_2', '1'),
				('', 'perm_viewprofileusers_3', '1'),
				('', 'perm_viewprofileusers_4', '1'),
				('', 'perm_viewprofileusers_5', '1'),
				('', 'perm_insertusers_1', '1'),
				('', 'perm_insertusers_2', '1'),
				('', 'perm_updateusers_1', '1'),
				('', 'perm_updateusers_2', '1'),
				('', 'perm_userdelete_1', '1'),
				('', 'perm_userdelete_2', '1'),
				('', 'perm_userssettings_1', '1'),
				('', 'perm_userspermissions_1', '1'),
				('', 'perm_updateuserlevel_1', '1'),
				('', 'perm_updateuserlevel_2', '1'),
				('', 'perm_updateuserstatus_1', '1'),
				('', 'perm_updateuserstatus_2', '1'),
				('', 'perm_settings_1', '1'),
				('', 'perm_tools_1', '1'),
				('', 'general_timezone', '0'),
				('', 'general_timezone_summertime', '0'),
				('', 'general_language', '".$this->uri->segment(3)."');";
			$this->db->query($sql_bd);
			//rewrite the install.php controller
			$file_controller = '
<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Dashboard class
 *
 * Controller of install system
 *
 * Maps to the following URL
 * 		http://yoursite.com/install
 *
 * @package		Compass
 * @subpackage	Core
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.0.0
 */

class Install extends CI_Controller {

	/**
	 * Constructor
	 *
	 */
	public function __construct(){
		parent::__construct();
		//loads of standard features dashboard
		initialize_dashboard();
		$this->lang->load("core", ($this->uri->segment(3)) ? $this->uri->segment(3) : "english");
	}

	// --------------------------------------------------------------------

	/**
	 * The default page
	 *
	 * Page home install system
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function index(){
		redirect(base_url("stop"));
	}

	// --------------------------------------------------------------------

	/**
	 * The sixth page
	 *
	 * Page for preconditions for installing the system
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function sixth(){
		//mount the page layout
		set_theme("title", $this->lang->line("install_title"));
		set_theme("content", load_module("install_view", "sixth"));
		set_theme("template", "base_view");
		load_template();
	}
}
/* End of file install.php */
/* Location: ./application/controllers/install.php */
			';
			write_file('./compass-admin/controllers/install.php', trim($file_controller));
			//create files
			$file_routes = '
<?php  if ( ! defined("BASEPATH")) exit("No direct script access allowed");
$route["default_controller"] = "welcome";
$route["404_override"] = "stop";
/* End of file routes.php */
/* Location: ./application/config/routes.php */
			';
			write_file('./compass-admin/config/routes.php', trim($file_routes));
			redirect('install/sixth/'.$this->uri->segment(3));
		endif;
		//mount the page layout
		set_theme('title', $this->lang->line('install_title'));
		set_theme('content', load_module('install_view', 'fifth'));
		set_theme('template', 'base_view');
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The sixth page
	 *
	 * Page for preconditions for installing the system
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function sixth(){
		//mount the page layout
		set_theme('title', $this->lang->line('install_title'));
		set_theme('content', load_module('install_view', 'sixth'));
		set_theme('template', 'base_view');
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The redme page
	 *
	 * Page for preconditions for installing the system
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function readme(){
		//mount the page layout
		set_theme('title', $this->lang->line('install_readme'));
		set_theme('content', load_module('install_view', 'readme'));
		set_theme('template', 'base_view');
		load_template();
	}
}

/* End of file install.php */
/* Location: ./application/controllers/install.php */