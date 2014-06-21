<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pages class
 *
 * Controller of pages of contents management
 *
 * Maps to the following URL
 * 		http://yoursite.com/cms/pages
 *
 * @package		Compass
 * @subpackage	CMS
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.0.0
 */

class Pages extends CI_Controller {

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
	 * Page with list of pages
	 * Allows loads the pages method for listing and managing pages.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function index(){
		//access permission
		access('perm_listpages_');
		//Arrow useful settings to query the elements and paging
		$config = array(
		'model'=>'posts',
		'method'=>'return_list_pages',
		'pagination_url'=>base_url('cms/pages/index'),
		'pagination_segment'=>4,
		'pagination_rows'=>get_setting('general_large_list'),
		'orderby_segment'=>6,
		'order_segment'=>8,
		'default_orderby'=>'post_modified',
		'default_order'=>'desc',
		'filter_key_segment'=>9,
		'filter_value_segment'=>10
		);
		//creates a url search
		if ($this->input->post('search')) get_paging_search($this->input->post('search_for'), $config);
		//mount the page layout
		set_theme('title', lang('cms_pages'));
		set_theme('content', load_module('pages_view', 'list', array('config'=>$config)));
		set_theme('footerinc', load_module('includes_view', 'deletereg'), FALSE);
		set_theme('helper', lang('cms_help_pages'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The insert post page
	 *
	 * Page with insert of pages
	 * Inserts preliminary data from the db and page redirects to the edit screen of the page.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function insert(){
		//access permission
		access('perm_insertpages_');
		//Inserts preliminary data from the db and page redirects to the edit page of the pages.
		$data['post_type'] = 'page';
		$data['post_date'] = date('Y-m-d H:i:s');
		$data['post_date_publish'] = date('Y-m-d H:i:s');
		$data['post_status'] = 'draft';
		$data['post_author'] = get_session('user_id');
		$data['post_comment_status'] = '0';
		$idadded = $this->posts->do_insert($data, FALSE);
		redirect(base_url('cms/pages/update/'.$idadded));
	}

	// --------------------------------------------------------------------

	/**
	 * The update page
	 *
	 * Page with update of pages
	 * Inserts preliminary data from the db and page redirects to the edit page of the page.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function update(){
		//access permission
		access('perm_updatepages_');
		//redirects to the page of posts if no id or slug is passed in url
		if ($this->uri->segment(4) == NULL || is_numeric($this->uri->segment(4)) == FALSE):
			set_msg('msgerror', lang('cms_pages_not_found_to_edit'), 'error');
			redirect(base_url('cms/pages'));
		endif;
		//registers data passed through the form of view
		if ($this->input->post('save') || $this->input->post('update') || $this->input->post('save_category') || $this->input->post('save_draft') || $this->input->post('save_trash')):
			$data = elements(array(
				'post_title',
				'post_content',
				'post_slug',
				'post_status',
				'post_comment_status'
				), $this->input->post());
			$data['post_type'] = 'page';
			$data['post_date_publish'] = get_local_to_gmt_date($this->input->post('post_date_publish'));
			$data['post_modified'] = date('Y-m-d H:i:s');
			$data['post_link'] = base_url().'/post/'.$this->input->post('post_slug');
			//saves the post as draft or publish
			if ($this->input->post('save_draft')):
				$data['post_status'] = 'draft';
			else:
				$data['post_status'] = 'publish';
			endif;
			//creates the slug to existing posts, but no slug
			if ($this->input->post('post_slug') != $this->posts->get_by_id($this->uri->segment(4))->row()->post_slug && $this->input->post('post_slug') != NULL):
	        	//now exitir slug in bd, adds characters
	        	if ($this->posts->get_by_slug(url_title($this->input->post('post_slug')))->num_rows() > 0):
					$data['post_slug'] = url_title($this->input->post('post_slug')).'-'.substr(str_shuffle('6789012345'), 0, 5);
				else:
					$data['post_slug'] = url_title($this->input->post('post_slug'));
				endif;
			//creates slug for slug without
			elseif ($this->input->post('post_slug') == NULL):
				$data['post_slug'] = 'page-'.substr(str_shuffle('6789012345'), 0, 5);
	        endif;
			set_postmeta('image', $this->input->post('post_image'), $this->uri->segment(4));
			$this->posts->do_update($data, array('post_id'=>$this->uri->segment(4)), FALSE);
			redirect(current_url());
		endif;
		//mount the page layout
		if (isset($this->posts->get_by_id($this->uri->segment(4))->result()->post_slug) == NULL):
	        set_theme('headerinc', load_module('includes_view', 'slug'), FALSE);
	    endif;
		$this->session->set_userdata(array(set_session('post_msg') => ''));
		init_tinymce();
		init_datetimepicker();
		set_theme('title', lang('cms_pages_update'));
		set_theme('content', load_module('pages_view', 'updatepage'));
		set_theme('headerinc', load_module('includes_view', 'simpletabs'), FALSE);
        set_theme('footerinc', load_module('includes_view', 'insertmediasmodal'), FALSE);
        set_theme('modal', load_module('medias_view', 'insertmediasmodal'), FALSE);
        set_theme('modal', load_module('medias_view', 'uploadmediasmodal'), FALSE);
        set_theme('helper', lang('cms_help_updatepages'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The delete page
	 *
	 * Page with delete of pages
	 * erases posts and postmetasof page.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function delete(){
		//access permission
		access('perm_deletepages_');
		//erases postmeta page
		if ($this->postmeta->get_by_postid($this->uri->segment(4))->num_rows() > 0):
			$this->postmeta->do_delete(array('postmeta_postid'=>$this->uri->segment(4)), FALSE);
		endif;
		$this->posts->do_delete(array('post_id'=>$this->uri->segment(4)), FALSE);
		set_msg('msgok', lang('cms_pages_delete_sucessfully'), 'sucess');
		redirect(base_url('cms/pages'));
	}
}
/* End of file posts.php */
/* Location: ././cms/controllers/pages.php */