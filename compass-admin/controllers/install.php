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
		//passes to the next screen
		if ($this->input->post('save')):
			redirect('install/second/'.$this->uri->segment(3));
		endif;
		//mount the page layout
		set_theme('title', $this->lang->line('install_error_title'));
		set_theme('content', load_module('install', 'first'));
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
		set_theme('content', load_module('install', 'second'));
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
			//create table books
			$sql_bd = "CREATE TABLE IF NOT EXISTS `books` (
			  `book_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `book_register` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `book_title` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `book_author` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `book_tags` text CHARACTER SET utf8 NOT NULL,
			  `book_keywords` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `book_language` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `book_edition` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `book_publisher` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `book_year_publisher` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `book_city_publisher` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `book_number_pages` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `book_isbn` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `book_cdd` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `book_cdu` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `book_synopsis` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `book_locale` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `book_date` datetime NOT NULL,
			  `book_price` varchar(255) CHARACTER SET utf8 NOT NULL,
			  `book_cover` varchar(255) CHARACTER SET utf8 NOT NULL,
			  PRIMARY KEY (`book_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
			$this->db->query($sql_bd);
			//create table loans
			$sql_bd = "CREATE TABLE IF NOT EXISTS `loans` (
			  `loan_id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `loan_lender_id` bigint(20) NOT NULL,
			  `loan_user_id` bigint(20) NOT NULL,
			  `loan_book_id` bigint(20) NOT NULL,
			  `loan_date` datetime NOT NULL,
			  `loan_date_deliver` datetime NOT NULL,
			  `loan_status` varchar(255) CHARACTER SET utf8 NOT NULL,
			  PRIMARY KEY (`loan_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
			$this->db->query($sql_bd);
			//create table comments
			$sql_bd = "CREATE TABLE IF NOT EXISTS `comments` (
			  `comment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `comment_postid` bigint(20) unsigned NOT NULL DEFAULT '0',
			  `comment_author` tinytext NOT NULL,
			  `comment_authorid` bigint(20) unsigned NOT NULL DEFAULT '0',
			  `comment_authoremail` varchar(100) NOT NULL DEFAULT '',
			  `comment_authorurl` varchar(200) NOT NULL DEFAULT '',
			  `comment_authorip` varchar(100) NOT NULL DEFAULT '',
			  `comment_date` datetime NOT NULL,
			  `comment_content` text NOT NULL,
			  `comment_status` varchar(20) NOT NULL DEFAULT '1',
			  `comment_user_moderator` bigint(20) unsigned NOT NULL,
			  `comment_type` varchar(20) NOT NULL DEFAULT '',
			  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`comment_id`),
			  KEY `comment_post_ID` (`comment_postid`),
			  KEY `comment_approved_date_gmt` (`comment_status`),
			  KEY `comment_parent` (`comment_parent`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
			$this->db->query($sql_bd);
			//create table postmeta
			$sql_bd = "CREATE TABLE IF NOT EXISTS `postmeta` (
			  `postmeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `postmeta_postid` bigint(20) unsigned NOT NULL,
			  `postmeta_key` varchar(255) DEFAULT NULL,
			  `postmeta_value` longtext,
			  PRIMARY KEY (`postmeta_id`),
			  KEY `post_id` (`postmeta_postid`),
			  KEY `meta_key` (`postmeta_key`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
			$this->db->query($sql_bd);
			//create table posts
			$sql_bd = "CREATE TABLE IF NOT EXISTS `posts` (
			  `post_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
			  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `post_date_publish` datetime NOT NULL,
			  `post_content` longtext NOT NULL,
			  `post_title` text NOT NULL,
			  `post_excerpt` text NOT NULL,
			  `post_tags` text NOT NULL,
			  `post_status` varchar(20) NOT NULL DEFAULT 'publish',
			  `post_slug` varchar(255) NOT NULL,
			  `post_link` varchar(255) NOT NULL DEFAULT '',
			  `post_type` varchar(20) NOT NULL DEFAULT 'post',
			  `post_comment_status` int(11) NOT NULL,
			  `post_comment_count` bigint(20) NOT NULL DEFAULT '0',
			  `post_order` int(11) NOT NULL DEFAULT '0',
			  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `post_views` bigint(20) NOT NULL,
			  PRIMARY KEY (`post_id`),
			  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`post_id`),
			  KEY `post_author` (`post_author`)
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
			//create table stats
			$sql_bd = "CREATE TABLE IF NOT EXISTS `stats` (
			  `stat_id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `stat_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `stat_ip` varchar(255) NOT NULL,
			  `stat_browser` varchar(255) NOT NULL,
			  `stat_postid` bigint(20) NOT NULL,
			  `stat_posturl` text NOT NULL,
			  `stat_userid` bigint(20) NOT NULL,
			  PRIMARY KEY (`stat_id`),
			  UNIQUE KEY `stat_id` (`stat_id`),
			  KEY `stat_id_2` (`stat_id`)
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
		set_theme('content', load_module('install', 'third'));
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
		set_theme('content', load_module('install', 'fourth'));
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
				(1, 1, 'Install', '".date('Y-m-d H:i:s')."', 'Install Compass', 'Installing the compass system with all tables of the database and setagem presets in the system.', '');";
			$this->db->query($sql_bd);
			//insert attributions user in the database
			$sql_bd = "INSERT INTO `userslevels` (`userlevel_id`, `userlevel_name`, `userlevel_level`, `userlevel_description`) VALUES
				(1, '".$this->lang->line('users_userlevel_1_name')."', 1, '".$this->lang->line('users_userlevel_1_description')."'),
				(2, '".$this->lang->line('users_userlevel_2_name')."', 2, '".$this->lang->line('users_userlevel_2_description')."'),
				(3, '".$this->lang->line('users_userlevel_3_name')."', 3, '".$this->lang->line('users_userlevel_3_description')."'),
				(4, '".$this->lang->line('users_userlevel_4_name')."', 4, '".$this->lang->line('users_userlevel_4_description')."'),
				(5, '".$this->lang->line('users_userlevel_5_name')."', 5, '".$this->lang->line('users_userlevel_5_description')."');";
			$this->db->query($sql_bd);
			//insert post pattern in the database
			$sql_bd = "INSERT INTO `posts` (`post_id`, `post_author`, `post_date`, `post_date_publish`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `post_slug`, `post_link`, `post_type`, `post_comment_status`, `post_comment_count`, `post_order`, `post_modified`, `post_views`) VALUES
				(1, 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '".$this->lang->line('core_install_default_post_content')."', '".$this->lang->line('core_install_default_post_title')."', '', 'publish', '".url_title($this->lang->line('core_install_default_post_title'))."', '".base_url('site/post/'.url_title($this->lang->line('core_install_default_post_title')))."', 'post', 1, 1, 0, '".date('Y-m-d H:i:s')."', 0),
				(2, 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '".$this->lang->line('core_install_default_page_content')."', '".$this->lang->line('core_install_default_page_title')."', '', 'publish', '".url_title($this->lang->line('core_install_default_page_title'))."', '".base_url('site/page/'.url_title($this->lang->line('core_install_default_page_title')))."', 'page', 0, 0, 0, '".date('Y-m-d H:i:s')."', 0);";
			$this->db->query($sql_bd);
			//insert standard comment in the database
			$sql_bd = "INSERT INTO `comments` (`comment_id`, `comment_postid`, `comment_author`, `comment_authorid`, `comment_authoremail`, `comment_authorurl`, `comment_authorip`, `comment_date`, `comment_content`, `comment_status`, `comment_user_moderator`, `comment_type`, `comment_parent`) VALUES
				(1, 1, 'Sr. Compass', 0, '', '".$this->lang->line('core_compass_url')."', '', '".date('Y-m-d H:i:s')."', '".$this->lang->line('core_install_default_comm_content')."', 'approved', 1, '', 0);";
			$this->db->query($sql_bd);
			//insert basic system settings
			$sql_bd = "INSERT INTO `settings` (`setting_id`, `setting_name`, `setting_value`) VALUES
				(1, 'general_title_site', '".$this->input->post('title')."'),
				(2, 'general_description_site', '".$this->input->post('description')."'),
				(3, 'general_url_site', '".base_url()."'),
				(4, 'general_email_admin', '".$this->input->post('email')."'),
				(5, 'general_homepage', ''),
				(6, 'general_large_list', '20'),
				(7, 'general_small_list', '10'),
				(8, 'general_advanced_settings', '0'),
				(9, 'module_cms', '".$this->input->post('module_cms')."'),
				(10, 'module_books', '".$this->input->post('module_books')."'),
				(11, 'module_journal', '".$this->input->post('module_journal')."'),
				(12, 'module_eventus', '".$this->input->post('module_eventus')."'),
				(13, 'module_helpdesck', '".$this->input->post('module_helpdesck')."');";
			$this->db->query($sql_bd);
			//insert first system administrator user
			$sql_bd = "INSERT INTO `users` (`user_id`, `user_username`, `user_pass`, `user_name`, `user_email`, `user_registered`, `user_status`, `user_level`, `user_displayname`) VALUES
				(1, '".$this->input->post('user')."', '".md5($this->input->post('pass'))."', '', '".$this->input->post('email')."', '".date('Y-m-d H:i:s')."', 1, 1, '".$this->input->post('user')."');";
			$this->db->query($sql_bd);
			//enter settings for user permissions 
			$sql_bd = "INSERT INTO `settings` (`setting_id`, `setting_name`, `setting_value`) VALUES
				(14, 'perm_ative_1', '1'),
				(15, 'perm_ative_2', '1'),
				(16, 'perm_ative_3', '1'),
				(17, 'perm_ative_4', '1'),
				(18, 'perm_ative_5', '1'),
				(19, 'perm_listusers_1', '1'),
				(20, 'perm_listusers_2', '1'),
				(21, 'perm_listusers_3', '1'),
				(22, 'perm_viewprofileusers_1', '1'),
				(23, 'perm_viewprofileusers_2', '1'),
				(24, 'perm_viewprofileusers_3', '1'),
				(25, 'perm_viewprofileusers_4', '1'),
				(26, 'perm_viewprofileusers_5', '1'),
				(27, 'perm_insertusers_1', '1'),
				(28, 'perm_insertusers_2', '1'),
				(29, 'perm_updateusers_1', '1'),
				(30, 'perm_updateusers_2', '1'),
				(31, 'perm_userdelete_1', '1'),
				(32, 'perm_userdelete_2', '1'),
				(33, 'perm_userssettings_1', '1'),
				(34, 'perm_userspermissions_1', '1'),
				(35, 'perm_updateuserlevel_1', '1'),
				(36, 'perm_updateuserlevel_2', '1'),
				(37, 'perm_updateuserstatus_1', '1'),
				(38, 'perm_updateuserstatus_2', '1'),
				(39, 'perm_settings_1', '1'),
				(40, 'perm_tools_1', '1'),
				(41, 'perm_germedia_1', '1'),
				(42, 'perm_viewbook_1', '1'),
				(43, 'perm_gerbook_1', '1'),
				(44, 'perm_updatebook_1', '1'),
				(45, 'perm_deletebook_1', '1'),
				(46, 'perm_gerloans_1', '1'),
				(47, 'perm_tools_2', '1'),
				(48, 'perm_listposts_1', '1'),
				(49, 'perm_listposts_2', '1'),
				(50, 'perm_listposts_3', '1'),
				(51, 'perm_listposts_4', '1'),
				(52, 'perm_viewposts_1', '1'),
				(53, 'perm_viewposts_2', '1'),
				(54, 'perm_viewposts_3', '1'),
				(55, 'perm_viewposts_4', '1'),
				(56, 'perm_viewposts_5', '1'),
				(57, 'perm_insertposts_1', '1'),
				(58, 'perm_insertposts_2', '1'),
				(59, 'perm_insertposts_3', '1'),
				(60, 'perm_insertposts_4', '1'),
				(61, 'perm_updateposts_1', '1'),
				(62, 'perm_updateposts_2', '1'),
				(63, 'perm_updateposts_3', '1'),
				(64, 'perm_updateposts_4', '1'),
				(65, 'perm_deleteposts_1', '1'),
				(66, 'perm_deleteposts_2', '1'),
				(67, 'perm_deleteposts_3', '1'),
				(68, 'perm_deleteposts_4', '1'),
				(69, 'perm_listpages_1', '1'),
				(70, 'perm_listpages_2', '1'),
				(71, 'perm_listpages_3', '1'),
				(72, 'perm_listpages_4', '1'),
				(73, 'perm_viewpages_1', '1'),
				(74, 'perm_viewpages_2', '1'),
				(75, 'perm_viewpages_3', '1'),
				(76, 'perm_viewpages_4', '1'),
				(77, 'perm_viewpages_5', '1'),
				(78, 'perm_insertpages_1', '1'),
				(79, 'perm_insertpages_2', '1'),
				(80, 'perm_updatepages_1', '1'),
				(81, 'perm_updatepages_2', '1'),
				(82, 'perm_deletepages_1', '1'),
				(83, 'perm_deletepages_2', '1'),
				(84, 'perm_medias_1', '1'),
				(85, 'perm_medias_2', '1'),
				(86, 'perm_comments_1', '1'),
				(87, 'perm_comments_2', '1'),
				(88, 'perm_comments_3', '1'),
				(89, 'perm_themes_1', '1'),
				(90, 'perm_stats_1', '1'),
				(91, 'perm_stats_2', '1'),
				(92, 'perm_stats_3', '1');";
			$this->db->query($sql_bd);
			//additional settings
			$sql_bd = "INSERT INTO `settings` (`setting_id`, `setting_name`, `setting_value`) VALUES
				(94, 'general_timezone', '0'),
				(95, 'general_timezone_summertime', '0'),
				(96, 'general_language', '".$this->uri->segment(3)."'),
				(97, 'content_site_theme', 'SiteCompass'),
				(98, 'site_blog_num_posts', '10');";
			$this->db->query($sql_bd);
			//enter settings for user permissions books
			$sql_bd = "INSERT INTO `settings` (`setting_id`, `setting_name`, `setting_value`) VALUES
				(99, 'perm_bookslist_1', '1'),
				(100, 'perm_bookslist_2', '1'),
				(101, 'perm_bookslist_3', '1'),
				(102, 'perm_bookslist_4', '1'),
				(103, 'perm_bookslist_5', '1'),
				(104, 'perm_booksview_1', '1'),
				(105, 'perm_booksview_2', '1'),
				(106, 'perm_booksview_3', '1'),
				(107, 'perm_booksview_4', '1'),
				(108, 'perm_booksview_5', '1'),
				(109, 'perm_booksinsert_1', '1'),
				(110, 'perm_booksinsert_2', '1'),
				(111, 'perm_booksinsert_3', '1'),
				(112, 'perm_booksupdate_1', '1'),
				(113, 'perm_booksupdate_2', '1'),
				(114, 'perm_booksupdate_3', '1'),
				(115, 'perm_booksdelete_1', '1'),
				(116, 'perm_booksdelete_2', '1'),
				(117, 'perm_booksloansgerencie_1', '1'),
				(118, 'perm_booksloansgerencie_2', '1'),
				(119, 'perm_booksloansgerencie_3', '1'),
				(120, 'perm_bookssettings_1', '1'),
				(121, 'books_loans_number_days', '5'),
				(122, 'books_loans_max_for_user', '2'),
				(123, 'books_reference_model', '[author], <strong>[title]</strong>. [edition]. ed. [city]: [publisher], [pages] p.');";
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
		set_theme("content", load_module("install", "sixth"));
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
/* Location: ./application/config/routes.php */';
			write_file('./compass-admin/config/routes.php', trim($file_routes));
			redirect('install/sixth/'.$this->uri->segment(3));
		endif;
		//mount the page layout
		set_theme('title', $this->lang->line('install_title'));
		set_theme('content', load_module('install', 'fifth'));
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
		set_theme('content', load_module('install', 'sixth'));
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
		set_theme('content', load_module('install', 'readme'));
		set_theme('template', 'base_view');
		load_template();
	}
}

/* End of file install.php */
/* Location: ./application/controllers/install.php */