<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Themes class
 *
 * Controller of themes of contents management
 *
 * Maps to the following URL
 * 		http://yoursite.com/cms/medias
 *
 * @package		Compass
 * @subpackage	CMS
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.0.0
 */

class Themes extends CI_Controller {

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
	 * The default page
	 *
	 * Page with list and manager of themes
	 * Allows loads the theme method for listing and managing themes.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function index(){
		//access permission
		access('perm_themes_');
		$this->load->helper('file');
		$this->load->helper('directory');
		//inserts the theme settings in bd
		if ($this->uri->segment(4) == 'active' && $this->uri->segment(5) != NULL):
			set_setting('content_site_theme', $this->uri->segment(5));
		endif;
		//delete the folder of a theme
		if ($this->uri->segment(4) == 'delete' && $this->uri->segment(5) != NULL):
			delete_files('./compass-content/themes/'.$this->uri->segment(5).'/', TRUE);
			rmdir('./compass-content/themes/'.$this->uri->segment(5).'/');
			set_msg('msgok', lang('cms_themes_msg_deleted_sucess'), 'sucess');
			redirect(base_url('cms/themes'));
		endif;
		//edit theme files
		if ($this->input->post('save')):
			write_file('./compass-content/themes/'.$this->uri->segment(5).'/'.$this->uri->segment(7), $this->input->post('file_data'));
			set_msg('msgok', lang('cms_themes_msg_file_update_sucess'), 'sucess');
			redirect(current_url());
		endif;
		//upload full themes .zip files
		if ($this->input->post('upload')):
	        $config['upload_path'] = './compass-content/themes/';
	        $config['allowed_types'] = 'zip';
	        $config['max_size'] = '5000';
	        $this->load->library('upload', $config);
	        //upload full themes .zip files
	        $upload = ($this->upload->do_upload('theme_file')) ? $this->upload->data() : $this->upload->display_errors();
			//decompresses the file .zip
			if (is_array($upload) && $upload['file_name'] != ''):
				$zip_dir = '.\compass-content\themes';
				$zip = new ZipArchive();
				if ($zip->open('.\compass-content\themes\\'.$upload['file_name'])):
				    $zip->extractTo($zip_dir);
				    $zip->close();
				endif;
				unlink('.\compass-content\themes\\'.$upload['file_name']);
				set_msg('msgok', lang('cms_themes_msg_insert_sucess'), 'sucess');
				redirect(base_url().'cms/themes/');
			else:
				set_msg('msgerror', $upload, 'error');
				redirect(current_url());
			endif;
		endif;
		//mount the page layout
		init_codemirror();
		set_theme('title', lang('cms_themes'));
		set_theme('content', load_module('themes_view', 'themes'));
		set_theme('footerinc', load_module('includes_view', 'deletereg'), FALSE);
		set_theme('helper', lang('cms_help_themes'));
		load_template();
	}
}
/* End of file themes.php */
/* Location: ././cms/controllers/themes.php */