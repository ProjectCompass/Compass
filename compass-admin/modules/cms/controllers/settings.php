<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Settings class
 *
 * Controller of settings of contents management
 *
 * Maps to the following URL
 * 		http://yoursite.com/cms/settings
 *
 * @package		Compass
 * @subpackage	CMS
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
		//load objects
		$this->load->helper('cms');
		//loads of standard features books module
		initialize_cms();
	}

	// --------------------------------------------------------------------

	/**
	 * The page settings
	 *
	 * Page with list settings of cms
	 * Allows the listing and managing settings of cms.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function index(){
		//access permission
		access('perm_contentssettings_');
		//loads utilities
		$this->load->helper('url');
		$this->load->model('settings_model', 'settings');
		//settings List
		$fields = array(
			'layout_site_title',
			'layout_site_description',
			'layout_site_home_content',
			'layout_site_footer',
			'site_page_home',
			'site_blog_num_posts',
			'site_comments_moderation',
			'site_menu_tools',
			'layout_model',
			'layout_background',
			'layout_width_site',
			'layout_width_content',
			'layout_width_sidebar',
			'layout_sidebar_position',
			'layout_text_font',
			'layout_text_size',
			'layout_text_color',
			'layout_link_color',
			'layout_link_visited',
			'layout_link_hover',
			'layout_header_background',
			'layout_title_font',
			'layout_title_size',
			'layout_title_color',
			'layout_description_font',
			'layout_description_size',
			'layout_description_color',
			'layout_sidebar_tabs_background',
			'layout_sidebar_background',
			'layout_sidebar_tabs_font',
			'layout_sidebar_tabs_size',
			'layout_sidebar_tabs_color',
			'layout_sidebar_font',
			'layout_sidebar_size',
			'layout_sidebar_color',
			'layout_pages_title_background',
			'layout_pages_background',
			'layout_pages_title_font',
			'layout_pages_title_size',
			'layout_pages_title_color',
			'layout_pages_text_font',
			'layout_pages_text_size',
			'layout_images_background',
			'layout_images_border_color',
			'layout_url_email',
			'layout_url_facebook',
			'layout_url_twitter',
			'layout_url_plus',
			'layout_url_instagram',
			'layout_url_dropbox',
			'layout_url_github',
			'layout_url_linkedin',
			'layout_url_vimeo',
			'layout_url_youtube',
			'layout_footer_background',
			'layout_footer_font',
			'layout_footer_size',
			'layout_footer_color',
			'layout_css'
			);
		//saves settings
		if ($this->input->post('save')):
			$settings = elements($fields, $this->input->post());
			foreach ($settings as $setting_name => $setting_value):
				set_setting($setting_name, $setting_value);
			endforeach;
			//permissions
			$query_userslevels = $this->userslevels->get_all()->result();
			//receives the values ​​of the fields passed in view
			$allpermissions = array(
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
                'perm_contentssettings_'
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
			//redirect
			set_msg('msgok', lang('cms_settings_msg_update_sucess'), 'sucess');
			redirect(current_url());
		endif;
		//clears all settings
		if ($this->input->post('clear')):
			foreach ($fields as $key):
				$this->settings->do_delete(array('setting_name'=>$key), FALSE);
			endforeach;
			set_msg('msgok', lang('cms_settings_msg_reverse'), 'sucess');
			redirect(base_url('cms/settings'));
		endif;
		//performs image upload the logo and saved in db
		if($this->input->post('upload_logo')):
			$upload = $this->posts->do_upload('layout_logo');
			if (is_array($upload) && $upload['file_name'] != ''):
				set_setting('layout_logo', $upload['file_name']);
			endif;
		//deletes the image the logo
		elseif($this->input->post('delete_logo')):
			unlink('./compass-content/uploads/medias/'.get_setting('layout_logo'));
			$thumbs = glob('./compass-content/uploads/medias/thumbs/*_'.get_setting('layout_logo'));
			foreach ($thumbs as $file):
				unlink($file);
			endforeach;
			set_setting('layout_logo', '');
		//performs image upload the capa and saved in db
		elseif($this->input->post('upload_capa')):
			$upload = $this->posts->do_upload('layout_capa');
			if (is_array($upload) && $upload['file_name'] != ''):
				set_setting('layout_capa', $upload['file_name']);
			endif;
		//deletes the image the capa
		elseif($this->input->post('delete_capa')):
			unlink('./compass-content/uploads/medias/'.get_setting('layout_capa'));
			$thumbs = glob('./compass-content/uploads/medias/thumbs/*_'.get_setting('layout_capa'));
			foreach ($thumbs as $file):
				unlink($file);
			endforeach;
			set_setting('layout_capa', '');
		//performs image upload the background and saved in db
		elseif($this->input->post('upload_background')):
			$upload = $this->posts->do_upload('layout_background_image');
			if (is_array($upload) && $upload['file_name'] != ''):
				set_setting('layout_background_image', $upload['file_name']);
			endif;
		//deletes the image the background
		elseif($this->input->post('delete_background')):
			unlink('./compass-content/uploads/medias/'.get_setting('layout_background_image'));
			$thumbs = glob('./compass-content/uploads/medias/thumbs/*_'.get_setting('layout_background_image'));
			foreach ($thumbs as $file):
				unlink($file);
			endforeach;
			set_setting('layout_background_image', '');
		endif;
		//mount the page layout
		init_colorpicker();
		set_theme('title', lang('cms_settings'));
		set_theme('content', load_module('settings_view', 'settings'));
		set_theme('modal', load_module('medias_view', 'uploadmediasmodal'), FALSE);
		set_theme('helper', lang('cms_help_settings'));
		load_template();
	}
}
/* End of file settings.php */
/* Location: ././cms/controllers/settings.php */