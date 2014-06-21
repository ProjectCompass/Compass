<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Medias class
 *
 * Controller of medias of contents management
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

class Medias extends CI_Controller {

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
	 * Page with list and manager of medias
	 * Allows loads the medias method for listing and managing medias.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function index(){
		//access permission
		access('perm_medias_');
		//Arrow useful settings to query the elements and paging
		$config = array(
		'model'=>'posts',
		'method'=>'return_list_medias',
		'pagination_url'=>base_url('cms/medias/index'),
		'pagination_segment'=>4,
		'pagination_rows'=>get_setting('general_large_list'),
		'orderby_segment'=>6,
		'order_segment'=>8,
		'default_orderby'=>'post_date',
		'default_order'=>'desc',
		'filter_key_segment'=>9,
		'filter_value_segment'=>10
		);
		//creates a url search
		if ($this->input->post('search')) get_paging_search($this->input->post('search_for'), $config);
		//registers data passed through the form of view
		if ($this->input->post('save')):
			//upload media to the system
			$upload = $this->posts->do_upload('media_file');
			//enters data media in bd
			if (is_array($upload) && $upload['file_name'] != ''):
				$title = substr($upload['file_name'],0,strrpos($upload['file_name'],'.'));
				$data['post_title'] = ($this->input->post('media_name') == NULL) ? get_url_title_original($title) : $this->input->post('media_name');
				$data['post_type'] = 'media';
				$data['post_date'] = date('Y-m-d H:i:s');
				$data['post_status'] = 'inherit';
				$data['post_author'] = get_session('user_id');
				$data['post_slug'] = url_title($title);
				$data['post_excerpt'] = $this->input->post('media_description');
				$data['post_link'] = base_url('/compass-content/uploads/medias').'/'.$upload['file_name'];
				$data['post_content'] = $upload['file_name'];
				$idadded = $this->posts->do_insert($data, FALSE);
				set_msg('msgok', lang('cms_medias_msg_inserted_sucess'), 'sucess');
				redirect(base_url().'cms/medias/index/saved/'.$idadded);
			else:
				set_msg('msgerror', $upload, 'error');
				redirect(current_url());
			endif;
		//update of data from media
		elseif ($this->input->post('update')):
			$title = substr($upload['file_name'],0,strrpos($upload['file_name'],'.'));
			$data['post_title'] = ($this->input->post('media_name') == NULL) ? get_url_title_original($title) : $this->input->post('media_name');
			$data['post_modified'] = date('Y-m-d H:i:s');
			$data['post_author'] = get_session('user_id');
			$data['post_slug'] = url_title($title);
			$data['post_excerpt'] = $this->input->post('media_description');
			$this->posts->do_update($data, array('post_id'=>$this->input->post('idmedia')));
			set_msg('msgok', lang('cms_medias_msg_update_sucess'), 'sucess');
			redirect(current_url());
		//delete of data from media
		elseif ($this->uri->segment(4) == 'delete'):
			if ($this->uri->segment(5) != NULL):
				$query = $this->posts->get_by_id($this->uri->segment(5));
				if ($query->num_rows()==1):
					$query = $query->row();
					unlink('./compass-content/uploads/medias/'.$query->post_content);
					$thumbs = glob('./compass-content/uploads/medias/thumbs/*_'.$query->post_content);
					foreach ($thumbs as $file):
						unlink($file);
					endforeach;
					$this->posts->do_delete(array('post_id'=>$query->post_id), FALSE);
				endif;
			else:
				set_msg('msgerror', lang('cms_medias_msg_not_found_to_delete'), 'error');
			endif;
			redirect('cms/medias');
		endif;
		//mount the page layout
		set_theme('footerinc', load_module('includes_view', 'selectinput'), FALSE);
		set_theme('footerinc', load_module('includes_view', 'deletereg'), FALSE);
		set_theme('title', lang('cms_medias'));
		set_theme('content', load_module('medias_view', 'medias', array('config'=>$config)));
		set_theme('helper', lang('cms_help_medias'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The page insertmediasmodal
	 *
	 * Modal Page with list medias and insert of tinymce box
	 * Allows the listing and insert medias of tinymce box.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function insertmediasmodal(){
		//insert the json in the html page header
		header('Content-Type: application/x-json; charset=utf-8');
		//search images on bd
		$this->db->like('post_title', $this->input->post('search_img'));
		if ($this->input->post('search_img')=='') $this->db->limit(20);
		$this->db->order_by('post_id', 'desc');
		$this->db->where('post_type', 'media');
		$query = $this->posts->get_all();
		$return = lang('cms_medias_not_found_results');
		//displays the latest media
		if ($query->num_rows()>0):
			$return = '';
			$query = $query->result();
			foreach ($query as $line):
				$media_ext = substr($line->post_content, strlen($line->post_content)-3, 3);
				if($media_ext == 'png' || $media_ext == 'jpg' || $media_ext == 'gif'):
					$return .= '<a href="javascript:;" onclick="$(\'.tinymce\').tinymce().execCommand(\'mceInsertContent\',true,\'<img src='.base_url("compass-content/uploads/medias/$line->post_content").' />\');return false;">';
					$return .= '<img src="'.thumb($line->post_content,200,120,FALSE).'" class="returnimg close-reveal-modal positionrelative" alt="'.$line->post_title.'" title="'.lang('cms_medias_click_me').'" /></a>';
				endif;
			endforeach;
		endif;
		echo (json_encode($return));
	}

	// --------------------------------------------------------------------

	/**
	 * The page uploadmediasmodal
	 *
	 * Modal Page with upload medias
	 * Allows the upload medias.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function uploadmediasmodal(){
		//saves the media for the db
		if ($this->input->post('save')):
			$upload = $this->posts->do_upload('media_file');
			if (is_array($upload) && $upload['file_name'] != ''):
				$title = substr($upload['file_name'],0,strrpos($upload['file_name'],'.'));
				$data['post_title'] = ($this->input->post('media_name') == NULL) ? get_url_title_original($title) : $this->input->post('media_name');
				$data['post_type'] = 'media';
				$data['post_date'] = date('Y-m-d H:i:s');
				$data['post_status'] = 'inherit';
				$data['post_author'] = get_session('user_id');
				$data['post_slug'] = url_title($title);
				$data['post_excerpt'] = $this->input->post('media_description');
				$data['post_link'] = base_url('/compass-content/uploads/medias').'/'.$upload['file_name'];
				$data['post_content'] = $upload['file_name'];
				$idadded = $this->posts->do_insert($data, FALSE);
				set_msg('msgok', lang('cms_medias_msg_inserted_sucess'), 'sucess');
				redirect(base_url().'cms/medias/uploadmediasmodal/saved/'.$idadded);
			else:
				set_msg('msgerror', $upload, 'error');
				redirect(current_url());
			endif;
		endif;
		//create the view manually
		echo '<!DOCTYPE html><html lang="pt-br"><head><meta charset="utf-8"></head><body>';
			echo load_css(array('style', 'foundation.min', 'app', 'font-awesome/css/font-awesome.min'));
			echo load_module('medias_view', 'insertmedias');
		echo '</body></html>';
	}
}
/* End of file medias.php */
/* Location: ././cms/controllers/medias.php */