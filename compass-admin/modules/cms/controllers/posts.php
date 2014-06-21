<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Posts class
 *
 * Controller of pages of contents management
 *
 * Maps to the following URL
 * 		http://yoursite.com/cms
 *
 * @package		Compass
 * @subpackage	CMS
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.0.0
 */

class Posts extends CI_Controller {

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
	 * Page with list of posts
	 * Allows loads the posts method for listing and managing posts.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function index(){
		//access permission
		access('perm_listposts_');
		//Arrow useful settings to query the elements and paging
		$config = array(
		'model'=>'posts',
		'method'=>'return_list',
		'pagination_url'=>base_url('cms/posts/index/'),
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
		set_theme('title', lang('cms_posts'));
		set_theme('content', load_module('posts_view', 'list', array('config'=>$config)));
		set_theme('footerinc', load_module('includes_view', 'deletereg'), FALSE);
		set_theme('helper', lang('cms_help_posts'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The insert post page
	 *
	 * Page with insert of posts
	 * Inserts preliminary data from the db and post redirects to the edit page of the post.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function insert(){
		//access permission
		access('perm_insertposts_');
		//Inserts preliminary data from the db and post redirects to the edit page of the post.
		if ($this->uri->segment(4) == NULL):
			$data['post_type'] = 'post';
			$data['post_date'] = date('Y-m-d H:i:s');
			$data['post_date_publish'] = date('Y-m-d H:i:s');
			$data['post_status'] = 'draft';
			$data['post_author'] = get_session('user_id');
			$data['post_comment_status'] = '1';
			$idadded = $this->posts->do_insert($data, FALSE);
			redirect(base_url('cms/posts/update/'.$idadded));
		endif;
	}

	// --------------------------------------------------------------------

	/**
	 * The update post
	 *
	 * Page with update of posts
	 * Inserts preliminary data from the db and post redirects to the edit page of the post.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function update(){
		//access permission
		access('perm_updateposts_');
		//redirects to the page of posts if no id or slug is passed in url
		if ($this->uri->segment(4) == NULL || is_numeric($this->uri->segment(4)) == FALSE):
			set_msg('msgerror', lang('cms_posts_not_found_to_edit'), 'error');
			redirect(base_url('cms/posts'));
		endif;
		//registers data passed through the form of view
		if ($this->input->post('save') || $this->input->post('update') || $this->input->post('save_category') || $this->input->post('save_draft') || $this->input->post('save_trash')):
			$data = elements(array(
				'post_title',
				'post_content',
				'post_excerpt',
				'post_tags',
				'post_status',
				'post_comment_status'
				), $this->input->post());
			$data['post_type'] = 'post';
			$data['post_date_publish'] = get_local_to_gmt_date($this->input->post('post_date_publish'));
			$data['post_modified'] = date('Y-m-d H:i:s');
			$data['post_link'] = base_url().'/post/'.$this->input->post('post_slug');
			//saves the post as draft
			if ($this->input->post('save_draft')):
				$data['post_status'] = 'draft';
			//saves the post as publish
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
				$data['post_slug'] = substr(str_shuffle('6789012345aeiou'), 0, 5);
	        endif;
	        //insert the image path in db
			set_postmeta('image', $this->input->post('post_image'), $this->uri->segment(4));
			//insert the form data in db
			$this->posts->do_update($data, array('post_id'=>$this->uri->segment(4)), FALSE);
			redirect(current_url());
		endif;
		//mount the page layout
		if (isset($this->posts->get_by_id($this->uri->segment(4))->result()->post_slug) == NULL):
	        set_theme('headerinc', load_module('includes_view', 'slug'), FALSE);
	    endif;
		$this->session->set_userdata(array(set_session('post_msg') => ''));
		init_tinymce();
		init_tagsinputmaster();
		init_datetimepicker();
		set_theme('title', lang('cms_posts_update'));
		set_theme('content', load_module('posts_view', 'updatepost'));
		set_theme('headerinc', load_module('includes_view', 'simpletabs'), FALSE);
        set_theme('footerinc', load_module('includes_view', 'insertmediasmodal'), FALSE);
        set_theme('modal', load_module('medias_view', 'insertmediasmodal'), FALSE);
        set_theme('modal', load_module('medias_view', 'uploadmediasmodal'), FALSE);
        set_theme('helper', lang('cms_help_updatepost'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The delete post
	 *
	 * Page with delete of posts
	 * erases posts, postmetas and comments of post.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function delete(){
		//access permission
		access('perm_deleteposts_');
		//erases postmeta post
		if ($this->postmeta->get_by_postid($this->uri->segment(4))->num_rows() > 0):
			$this->postmeta->do_delete(array('postmeta_postid'=>$this->uri->segment(4)), FALSE);
		endif;
		//erases comments post
		if ($this->postmeta->get_by_postid($this->uri->segment(4))->num_rows() > 0):
			$this->comments->do_delete(array('comments_postid'=>$this->uri->segment(4)), FALSE);
		endif;
		$this->posts->do_delete(array('post_id'=>$this->uri->segment(4)), FALSE);
		set_msg('msgok', lang('cms_posts_delete_sucessfully'), 'sucess');
		redirect(base_url('cms/posts'));
	}

	// --------------------------------------------------------------------

	/**
	 * The page comments
	 *
	 * Page with list comments of posts
	 * Allows the listing and managing comments of posts.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function comments(){
		//access permission
		access('perm_comments_');
		//Arrow useful settings to query the elements and paging
		$config = array(
		'model'=>'comments',
		'method'=>'return_list',
		'pagination_url'=>base_url('cms/posts/comments/'),
		'pagination_segment'=>4,
		'pagination_rows'=>get_setting('general_large_list'),
		'orderby_segment'=>6,
		'order_segment'=>8,
		'default_orderby'=>'comment_date',
		'default_order'=>'desc',
		'filter_key_segment'=>9,
		'filter_value_segment'=>10
		);
		//creates a url search
		if ($this->input->post('search')) get_paging_search($this->input->post('search_for'), $config);
		//update data of the comment passed through the form of view
		if ($this->input->post('update')):
			$data['comment_author'] = $this->input->post('comment_author');
			$data['comment_authoremail'] = $this->input->post('comment_author_email');
			$data['comment_authorurl'] = $this->input->post('comment_author_url');
			$data['comment_content'] = $this->input->post('comment_content');
			$data['comment_status'] = $this->input->post('comment_status');
			$data['comment_user_moderator'] = get_session('user_id');
			$data['comment_date'] = get_local_to_gmt_date($this->input->post('comment_date'));
			$this->comments->do_update($data, array('comment_id'=>$this->input->post('idcomment')), FALSE);
			set_msg('msgok', 'Comentário Atualizado com sucesso.', 'sucess');
			redirect('cms/posts/comments');
		endif;
		//update data of the status comment passed in the url
		if ($this->uri->segment(4) == 'approve' && $this->uri->segment(5) != NULL):
			$data['comment_status'] = 'approved';
			$this->comments->do_update($data, array('comment_id'=>$this->uri->segment(5)), FALSE);
			set_msg('msgok', lang('cms_comments_msg_approved'), 'sucess');
			redirect('cms/posts/comments');
		elseif ($this->uri->segment(4) == 'unapprove' && $this->uri->segment(5) != NULL):
			$data['comment_status'] = 'unapproved';
			$this->comments->do_update($data, array('comment_id'=>$this->uri->segment(5)), FALSE);
			set_msg('msgok', lang('cms_comments_msg_unapproved'), 'sucess');
			redirect('cms/posts/comments');
		elseif ($this->uri->segment(4) == 'delete' && $this->uri->segment(5) != NULL):
			$this->comments->do_delete(array('comment_id'=>$this->uri->segment(5)), FALSE);
			set_msg('msgok', lang('cms_comments_msg_deleted'), 'sucess');
			redirect('cms/posts/comments');
		endif;
		//mount the page layout
		init_datetimepicker();
		set_theme('footerinc', load_module('includes_view', 'deletereg'), FALSE);
		set_theme('title', lang('cms_comments'));
		set_theme('content', load_module('comments_view', 'comments', array('config'=>$config)));
		set_theme('helper', lang('cms_help_comments'));
		load_template();
	}
}
/* End of file posts.php */
/* Location: ././cms/controllers/posts.php */