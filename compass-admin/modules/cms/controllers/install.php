<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Install class
 *
 * Controller of install system
 *
 * Maps to the following URL
 * 		http://yoursite.com/cms/install
 *
 * @package		Compass
 * @subpackage	CMS
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.1.0
 */

class Install extends CI_Controller {

	/**
	 * Constructor
	 *
	 */
	public function __construct(){
		parent::__construct();
		//loads of standard features dashboard
    	$this->load->helper(array('url', 'directory', 'file'));
    	$this->lang->load('cms', lang(FALSE));
	}

	// --------------------------------------------------------------------

	/**
	 * The default page
	 *
	 * Page home install module
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function index(){
		//connect to the database
		$this->load->database();
		$this->db->reconnect();
		//recreate file routes
		$file_routes = '
<?php  if ( ! defined("BASEPATH")) exit("No direct script access allowed");
$route["default_controller"] = "welcome";
$route["404_override"] = "stop";
//module cms
$route["site"] = "cms/site/index";
$route["blog"] = "cms/site/blog";
$route["blog/(:any)"] = "cms/site/blog/$1";
$route["page/(:any)"] = "cms/site/page/$1";
$route["post/(:any)"] = "cms/site/post/$1";
$route["media/(:any)"] = "cms/site/media/$1";
$route["tag/(:any)"] = "cms/site/tag/$1";
$route["author/(:any)"] = "cms/site/author/$1";
$route["search/(:any)"] = "cms/site/search/$1";
$route["site/error"] = "cms/site/error";
/* End of file routes.php */
/* Location: ./application/config/routes.php */
			';
		write_file('./compass-admin/config/routes.php', trim($file_routes));
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
		//insert post pattern in the database
		$sql_bd = "INSERT INTO `posts` (`post_id`, `post_author`, `post_date`, `post_date_publish`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `post_slug`, `post_link`, `post_type`, `post_comment_status`, `post_comment_count`, `post_order`, `post_modified`, `post_views`) VALUES
			(1, 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '".$this->lang->line('core_install_default_post_content')."', '".$this->lang->line('core_install_default_post_title')."', '', 'publish', '".url_title($this->lang->line('core_install_default_post_title'))."', '".base_url('site/post/'.url_title($this->lang->line('core_install_default_post_title')))."', 'post', 1, 1, 0, '".date('Y-m-d H:i:s')."', 0),
			(2, 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '".$this->lang->line('core_install_default_page_content')."', '".$this->lang->line('core_install_default_page_title')."', '', 'publish', '".url_title($this->lang->line('core_install_default_page_title'))."', '".base_url('site/page/'.url_title($this->lang->line('core_install_default_page_title')))."', 'page', 0, 0, 0, '".date('Y-m-d H:i:s')."', 0);";
		$this->db->query($sql_bd);
		//insert standard comment in the database
		$sql_bd = "INSERT INTO `comments` (`comment_id`, `comment_postid`, `comment_author`, `comment_authorid`, `comment_authoremail`, `comment_authorurl`, `comment_authorip`, `comment_date`, `comment_content`, `comment_status`, `comment_user_moderator`, `comment_type`, `comment_parent`) VALUES
			(1, 1, 'Sr. Compass', 0, '', '".$this->lang->line('core_compass_url')."', '', '".date('Y-m-d H:i:s')."', '".$this->lang->line('core_install_default_comm_content')."', 'approved', 1, '', 0);";
		$this->db->query($sql_bd);
		//enter settings for user permissions books
		$sql_bd = "INSERT INTO `settings` (`setting_id`, `setting_name`, `setting_value`) VALUES
				('', 'perm_listposts_1', '1'),
				('', 'perm_listposts_2', '1'),
				('', 'perm_listposts_3', '1'),
				('', 'perm_listposts_4', '1'),
				('', 'perm_viewposts_1', '1'),
				('', 'perm_viewposts_2', '1'),
				('', 'perm_viewposts_3', '1'),
				('', 'perm_viewposts_4', '1'),
				('', 'perm_viewposts_5', '1'),
				('', 'perm_insertposts_1', '1'),
				('', 'perm_insertposts_2', '1'),
				('', 'perm_insertposts_3', '1'),
				('', 'perm_insertposts_4', '1'),
				('', 'perm_updateposts_1', '1'),
				('', 'perm_updateposts_2', '1'),
				('', 'perm_updateposts_3', '1'),
				('', 'perm_updateposts_4', '1'),
				('', 'perm_deleteposts_1', '1'),
				('', 'perm_deleteposts_2', '1'),
				('', 'perm_deleteposts_3', '1'),
				('', 'perm_deleteposts_4', '1'),
				('', 'perm_listpages_1', '1'),
				('', 'perm_listpages_2', '1'),
				('', 'perm_listpages_3', '1'),
				('', 'perm_listpages_4', '1'),
				('', 'perm_viewpages_1', '1'),
				('', 'perm_viewpages_2', '1'),
				('', 'perm_viewpages_3', '1'),
				('', 'perm_viewpages_4', '1'),
				('', 'perm_viewpages_5', '1'),
				('', 'perm_insertpages_1', '1'),
				('', 'perm_insertpages_2', '1'),
				('', 'perm_updatepages_1', '1'),
				('', 'perm_updatepages_2', '1'),
				('', 'perm_deletepages_1', '1'),
				('', 'perm_deletepages_2', '1'),
				('', 'perm_germedia_1', '1'),
				('', 'perm_medias_1', '1'),
				('', 'perm_medias_2', '1'),
				('', 'perm_comments_1', '1'),
				('', 'perm_comments_2', '1'),
				('', 'perm_comments_3', '1'),
				('', 'perm_themes_1', '1'),
				('', 'perm_stats_1', '1'),
				('', 'perm_stats_2', '1'),
				('', 'perm_stats_3', '1'),
				('', 'perm_contentssettings_1', '1'),
				('', 'general_homepage', 'cms'),
				('', 'site_menu_tools', '1'),
				('', 'content_site_theme', 'SiteCompass'),
				('', 'site_blog_num_posts', '10');";
		$this->db->query($sql_bd);
		set_setting('module_install_'.$this->uri->segment(1), '1');
		redirect ('tools/modules');
	}
}

/* End of file install.php */
/* Location: ././cms/controllers/install.php */