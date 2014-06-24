<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tools class
 *
 * Controller of tools use
 *
 * Maps to the following URL
 * 		http://yoursite.com/tools
 *
 * @package		Compass
 * @subpackage	Core
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.0.0
 */

class Tools extends CI_Controller {

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
		set_theme('submenu', load_module('tools_view', 'submenu'));
		//loads utilities
		$this->load->model('audits_model', 'audits');
		set_theme('helper', lang('help_tools'));
		//access permission
		access('perm_tools_');
	}

	// --------------------------------------------------------------------

	/**
	 * The default page
	 *
	 * Page with list of tools
	 * Used to provide access to system tools.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function index(){
		$this->modules();
	}

	// --------------------------------------------------------------------

	/**
	 * The plugins page
	 *
	 * Redirect the modules
	 * Used to provide access to system modules and plugins.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function plugins(){
		$this->modules();
	}

	// --------------------------------------------------------------------

	/**
	 * The modules page
	 *
	 * Page with manage modules
	 * Allows you to manage the system modules.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function modules(){
		$this->load->helper('file');
		$this->load->helper('directory');
		//ativate module
		if ($this->uri->segment(3) == 'ativate'):
			if (get_setting('module_'.$this->uri->segment(4)) == 1):
				set_setting('module_'.$this->uri->segment(4), 0);
				set_msg('msgok', lang('modules_msg_disabled_sucess'), 'sucess');
				redirect ('tools/modules');
			else:
				set_setting('module_'.$this->uri->segment(4), 1);
				set_msg('msgok', lang('modules_msg_ativated_sucess'), 'sucess');
				redirect ('tools/modules');
			endif;
		endif;
		//upload full plugin .zip files
		if ($this->input->post('upload')):
	        $config['upload_path'] = './compass-content/plugins/';
	        $config['allowed_types'] = 'zip';
	        $config['max_size'] = '5000';
	        $this->load->library('upload', $config);
	        //upload full themes .zip files
	        $upload = ($this->upload->do_upload('plugin_file')) ? $this->upload->data() : $this->upload->display_errors();
			//decompresses the file .zip
			if (is_array($upload) && $upload['file_name'] != ''):
				$zip_dir = '.\compass-content\plugins';
				$zip = new ZipArchive();
				if ($zip->open('.\compass-content\plugins\\'.$upload['file_name'])):
				    $zip->extractTo($zip_dir);
				    $zip->close();
				endif;
				unlink('.\compass-content\plugins\\'.$upload['file_name']);
				set_msg('msgok', lang('modules_msg_send_plugin_sucess'), 'sucess');
				redirect(base_url().'tools/modules/');
			else:
				set_msg('msgerror', $upload, 'error');
				redirect(current_url());
			endif;
		endif;
		//delete the folder of a plugin
		if ($this->uri->segment(3) == 'delete' && $this->uri->segment(4) != NULL):
			delete_files('./compass-content/plugins/'.$this->uri->segment(4).'/', TRUE);
			rmdir('./compass-content/plugins/'.$this->uri->segment(4).'/');
			set_msg('msgok', lang('modules_msg_delete_plugin_sucess'), 'sucess');
			redirect(base_url('tools/modules'));
		endif;
		//mount the page layout
		set_theme('title', lang('modules'));
		set_theme('content', load_module('tools_view', 'modules'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The audits page
	 *
	 * Page with show of audits
	 * Enables tracking of the latest transactions in the system, through the audit events in the database, login and uploads.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function audits(){
		//mount the page layout
		set_theme('title', lang('tools_audits'));
		set_theme('content', load_module('tools_view', 'audits'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The files page
	 *
	 * Page with show of files
	 * -.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function files(){
		if ($this->input->post('save') && $this->uri->segment(9) == NULL):
			if ($this->input->post('folder_name') != NULL):
				mkdir($this->input->post('folder_directory').$this->input->post('folder_name').'/');
				set_msg('msgok', lang('tools_files_msg_f_creted_sucess'), 'sucess');
				redirect(current_url());
			else:
				set_msg('msgerror', lang('tools_files_msg_f_creted_error'), 'error');
				redirect(current_url());
			endif;
		endif;
		if ($this->input->post('delete')):
			if ($this->input->post('file_directory') != NULL):
				if ($this->input->post('is_file')):
					$unlink = unlink($this->input->post('file_directory'));
					if ($unlink):
						set_msg('msgok', lang('tools_files_msg_delete_sucess'), 'sucess');
					else:
						set_msg('msgerror', lang('tools_files_msg_delete_error'), 'error');
					endif;
				else:
					$rmdir = rmdir($this->input->post('file_directory'));
					if ($rmdir):
						set_msg('msgok', lang('tools_files_msg_f_delete_sucess'), 'sucess');
					else:
						set_msg('msgerror', lang('tools_files_msg_f_delete_error'), 'error');
					endif;
				endif;
				redirect(current_url());
			endif;
		endif;
		//saves the file for the db
		if ($this->input->post('upload')):
			$config['upload_path'] = $this->input->post('folder_directory');
	        $config['allowed_types'] = '*';
	        $config['max_size'] = '20000';
	        $this->load->library('upload', $config);
	        if ($this->upload->do_upload('media_file')):
	            $upload = $this->upload->data();
	        else:
	            $upload = $this->upload->display_errors();
	        endif;
			if (is_array($upload) && $upload['file_name'] != ''):
				set_msg('msgok', lang('tools_uploadfiles_msg_sucess'), 'sucess');
				redirect(current_url());
			else:
				set_msg('msgerror', $upload, 'error');
				redirect(current_url());
			endif;
		endif;
		//mount the page layout
		init_datatables();
		set_theme('footerinc', load_module('includes_view', 'selectinput'), FALSE);
		set_theme('footerinc', load_module('includes_view', 'deletereg'), FALSE);
		set_theme('title', lang('tools_files'));
		set_theme('content', load_module('tools_view', 'files'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The page uploadfilesmodal
	 *
	 * Modal Page with upload files
	 * Allows the upload files.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function uploadfilesmodal(){
		//saves the media for the db
		if ($this->input->post('save')):
			$config['upload_path'] = './compass-content/uploads/files/';
	        $config['allowed_types'] = '*';
	        $config['max_size'] = '20000';
	        $this->load->library('upload', $config);
	        if ($this->upload->do_upload('media_file')):
	            $upload = $this->upload->data();
	        else:
	            $upload = $this->upload->display_errors();
	        endif;
			if (is_array($upload) && $upload['file_name'] != ''):
				set_msg('msgok', lang('tools_uploadfiles_msg_sucess'), 'sucess');
				redirect(base_url().'tools/uploadfilesmodal/saved/'.$upload['file_name']);
			else:
				set_msg('msgerror', $upload, 'error');
				redirect(current_url());
			endif;
		endif;
		//create the view manually
		echo '<!DOCTYPE html><html lang="pt-br"><head><meta charset="utf-8"></head><body>';
			echo load_css(array('style', 'foundation.min', 'app', 'font-awesome/css/font-awesome.min'));
			echo load_module('tools_view', 'uploadfiles');
			load_module('includes_view', 'selectinput');
		echo '</body></html>';
	}

}

/* End of file audits.php */
/* Location: ./application/controllers/tools.php */