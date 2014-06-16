<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Cms class
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

class Cms extends CI_Controller {

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
		//blocks if the cms module is not enabled
		if (get_setting('module_cms') == 0) redirect('stop');
		//sets the block submenu controller
		set_theme('submenu', load_module('tools', 'submenu', 'cms'));
		//loads utilities
		$this->load->model('posts_model', 'posts');
		$this->load->model('postmeta_model', 'postmeta');
		$this->load->model('terms_model', 'terms');
		$this->load->model('termmeta_model', 'termmeta');
		$this->load->model('comments_model', 'comments');
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
		$this->posts();
	}

	/**
	 * The site
	 *
	 * Page with list of posts
	 * Allows redirect to site.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function site(){
		redirect('site');
	}

	// --------------------------------------------------------------------

	/**
	 * The page posts
	 *
	 * Page with list, update, delete and insert of posts
	 * Allows the listing and managing posts.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function posts(){
		if ($this->uri->segment(3) == 'insert'):
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
		elseif ($this->uri->segment(3) == 'update'):
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
					//save the post and redirects to insertion of new category
					elseif ($this->input->post('save_category')):
						$this->session->set_userdata(array(set_session('post_msg') => '<div data-alert class="alert-box alert">'.lang('cms_posts_current_write').' <strong>'.$this->input->post('post_title').'</strong>, <a href="'.current_url().'">'.lang('cms_click_here').'</a> '.lang('cms_posts_current_write_continue').'<a href="#" class="close">&times;</a></div>'));
						redirect(base_url('cms/taxonomy'));
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
						$data['post_slug'] = 'post-'.substr(str_shuffle('6789012345'), 0, 5);
			        endif;
			        //insert the image path in db
					set_postmeta('image', $this->input->post('post_image'), $this->uri->segment(4));
					//insert the form data in db
					$this->posts->do_update($data, array('post_id'=>$this->uri->segment(4)), FALSE);
					//handle all categories and insert in db, if marked on the form
					$query_category = $this->terms->get_by_type('category')->result();
					foreach ($query_category as $line):
		            	if ($this->postmeta->get_by_key_and_value_and_postid('category', $line->term_id, $this->uri->segment(4))->num_rows() < 1):
					        if ($this->input->post('category-'.$line->term_slug) == TRUE):
		    					if ($this->postmeta->get_by_key_and_value_and_postid('category', $line->term_id, $this->uri->segment(4))->num_rows() < 1):
		    						$data = array(
							            'postmeta_postid' => $this->uri->segment(4),
							            'postmeta_key' => 'category',
							            'postmeta_value' => $line->term_id
							        );
							        $this->postmeta->do_insert($data, FALSE);
		                		endif;
		                	endif;
						else:
							if ($this->input->post('category-'.$line->term_slug) == FALSE):
								$this->postmeta->do_delete(array('postmeta_key'=>'category', 'postmeta_value' => $line->term_id, 'postmeta_postid'=>$this->uri->segment(4)), FALSE);
		            		endif;
		            	endif;
		            endforeach;
					redirect(current_url());
				endif;
				//mount the page layout
				if (isset($this->posts->get_by_id($this->uri->segment(4))->result()->post_slug) == NULL):
			        set_theme('headerinc', load_module('includes', 'slug'), FALSE);
			    endif;
				$this->session->set_userdata(array(set_session('post_msg') => ''));
				init_tinymce();
				init_tagsinputmaster();
				init_datetimepicker();
				set_theme('title', lang('cms_posts_update'));
				set_theme('content', load_module('posts', 'updatepost', 'cms'));
				set_theme('headerinc', load_module('includes', 'simpletabs'), FALSE);
		        set_theme('footerinc', load_module('includes', 'insertmediasmodal'), FALSE);
		        set_theme('modal', load_module('medias', 'insertmediasmodal', 'cms'), FALSE);
		        set_theme('modal', load_module('medias', 'uploadmediasmodal', 'cms'), FALSE);
		        set_theme('helper', lang('cms_help_updatepost'));
				load_template();
		elseif ($this->uri->segment(3) == 'delete' && $this->uri->segment(4) != NULL):
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
		else:
				//access permission
				access('perm_listposts_');
				//Arrow useful settings to query the elements and paging
				$config = array(
				'model'=>'posts',
				'method'=>'return_list',
				'pagination_url'=>base_url('cms/posts/'),
				'pagination_segment'=>3,
				'pagination_rows'=>get_setting('general_large_list'),
				'orderby_segment'=>5,
				'order_segment'=>7,
				'default_orderby'=>'post_modified',
				'default_order'=>'desc',
				'filter_key_segment'=>8,
				'filter_value_segment'=>9
				);
				//creates a url search
				if ($this->input->post('search')) get_paging_search($this->input->post('search_for'), $config);
				//mount the page layout
				set_theme('title', lang('cms_posts'));
				set_theme('content', load_module('posts', 'list', 'cms', array('config'=>$config)));
				set_theme('footerinc', load_module('includes', 'deletereg'), FALSE);
				set_theme('helper', lang('cms_help_posts'));
				load_template();
		endif;
	}

	// --------------------------------------------------------------------

	/**
	 * The page of insert, update, list and delete pages
	 *
	 * Page with list of pages
	 * Allows the listing and managing pages.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function pages(){
		if ($this->uri->segment(3) == 'insert'):
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
		elseif ($this->uri->segment(3) == 'update'):
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
			        set_theme('headerinc', load_module('includes', 'slug'), FALSE);
			    endif;
				$this->session->set_userdata(array(set_session('post_msg') => ''));
				init_tinymce();
				init_datetimepicker();
				set_theme('title', lang('cms_pages_update'));
				set_theme('content', load_module('pages', 'updatepage', 'cms'));
				set_theme('headerinc', load_module('includes', 'simpletabs'), FALSE);
		        set_theme('footerinc', load_module('includes', 'insertmediasmodal'), FALSE);
		        set_theme('modal', load_module('medias', 'insertmediasmodal', 'cms'), FALSE);
		        set_theme('modal', load_module('medias', 'uploadmediasmodal', 'cms'), FALSE);
		        set_theme('helper', lang('cms_help_updatepages'));
				load_template();
		elseif ($this->uri->segment(3) == 'delete' && $this->uri->segment(4) != NULL):
				//erases postmeta page
				if ($this->postmeta->get_by_postid($this->uri->segment(4))->num_rows() > 0):
					$this->postmeta->do_delete(array('postmeta_postid'=>$this->uri->segment(4)), FALSE);
				endif;
				$this->posts->do_delete(array('post_id'=>$this->uri->segment(4)), FALSE);
				set_msg('msgok', lang('cms_pages_delete_sucessfully'), 'sucess');
				redirect(base_url('cms/pages'));
		else:
				//access permission
				access('perm_listpages_');
				//Arrow useful settings to query the elements and paging
				$config = array(
				'model'=>'posts',
				'method'=>'return_list_pages',
				'pagination_url'=>base_url('cms/pages/'),
				'pagination_segment'=>3,
				'pagination_rows'=>get_setting('general_large_list'),
				'orderby_segment'=>5,
				'order_segment'=>7,
				'default_orderby'=>'post_modified',
				'default_order'=>'desc',
				'filter_key_segment'=>8,
				'filter_value_segment'=>9
				);
				//creates a url search
				if ($this->input->post('search')) get_paging_search($this->input->post('search_for'), $config);
				//mount the page layout
				set_theme('title', lang('cms_pages'));
				set_theme('content', load_module('pages', 'list', 'cms', array('config'=>$config)));
				set_theme('footerinc', load_module('includes', 'deletereg'), FALSE);
				set_theme('helper', lang('cms_help_pages'));
				load_template();
		endif;
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
		'pagination_url'=>base_url('cms/comments/'),
		'pagination_segment'=>3,
		'pagination_rows'=>get_setting('general_large_list'),
		'orderby_segment'=>5,
		'order_segment'=>7,
		'default_orderby'=>'comment_date',
		'default_order'=>'desc',
		'filter_key_segment'=>8,
		'filter_value_segment'=>9
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
			redirect('cms/comments');
		endif;
		//update data of the status comment passed in the url
		if ($this->uri->segment(3) == 'approve' && $this->uri->segment(4) != NULL):
			$data['comment_status'] = 'approved';
			$this->comments->do_update($data, array('comment_id'=>$this->uri->segment(4)), FALSE);
			set_msg('msgok', lang('cms_comments_msg_approved'), 'sucess');
			redirect('cms/comments');
		elseif ($this->uri->segment(3) == 'unapprove' && $this->uri->segment(4) != NULL):
			$data['comment_status'] = 'unapproved';
			$this->comments->do_update($data, array('comment_id'=>$this->uri->segment(4)), FALSE);
			set_msg('msgok', lang('cms_comments_msg_unapproved'), 'sucess');
			redirect('cms/comments');
		elseif ($this->uri->segment(3) == 'delete' && $this->uri->segment(4) != NULL):
			$this->comments->do_delete(array('comment_id'=>$this->uri->segment(4)), FALSE);
			set_msg('msgok', lang('cms_comments_msg_deleted'), 'sucess');
			redirect('cms/comments');
		endif;
		//mount the page layout
		init_datetimepicker();
		set_theme('footerinc', load_module('includes', 'deletereg'), FALSE);
		set_theme('title', lang('cms_comments'));
		set_theme('content', load_module('comments', 'comments', 'cms', array('config'=>$config)));
		set_theme('helper', lang('cms_help_comments'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The page medias
	 *
	 * Page with list medias of cms
	 * Allows the listing and managing medias of cms.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function medias(){
		//access permission
		access('perm_medias_');
		//Arrow useful settings to query the elements and paging
		$config = array(
		'model'=>'posts',
		'method'=>'return_list_medias',
		'pagination_url'=>base_url('cms/medias/'),
		'pagination_segment'=>3,
		'pagination_rows'=>get_setting('general_large_list'),
		'orderby_segment'=>5,
		'order_segment'=>7,
		'default_orderby'=>'post_date',
		'default_order'=>'desc',
		'filter_key_segment'=>8,
		'filter_value_segment'=>9
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
				set_msg('msgok', 'Mídia cadastrada com sucesso!', 'sucess');
				redirect(base_url().'cms/medias/saved/'.$idadded);
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
		elseif ($this->uri->segment(3) == 'delete'):
			if ($this->uri->segment(4) != NULL):
				$query = $this->posts->get_by_id($this->uri->segment(4));
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
		set_theme('footerinc', load_module('includes', 'selectinput'), FALSE);
		set_theme('footerinc', load_module('includes', 'deletereg'), FALSE);
		set_theme('title', lang('cms_medias'));
		set_theme('content', load_module('medias', 'medias', 'cms', array('config'=>$config)));
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
					$return .= '<img src="'.thumb($line->post_content,200,120,FALSE).'" class="returnimg close-reveal-modal positionrelative" alt="'.$line->post_title.'" title="Clique para inserir" /></a>';
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
				redirect(base_url().'cms/uploadmediasmodal/saved/'.$idadded);
			else:
				set_msg('msgerror', $upload, 'error');
				redirect(current_url());
			endif;
		endif;
		//create the view manually
		echo '<!DOCTYPE html><html lang="pt-br"><head><meta charset="utf-8"></head><body>';
			echo load_css(array('style', 'foundation.min', 'app', 'font-awesome/css/font-awesome.min'));
			echo load_module('medias', 'insertmedias', 'cms');
		echo '</body></html>';
	}

	// --------------------------------------------------------------------

	/**
	 * The page themes
	 *
	 * Page with list themes of site
	 * Allows the listing and managing themes of site.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function themes(){
		//access permission
		access('perm_themes_');
		$this->load->helper('file');
		$this->load->helper('directory');
		//inserts the theme settings in bd
		if ($this->uri->segment(3) == 'active' && $this->uri->segment(4) != NULL):
			set_setting('content_site_theme', $this->uri->segment(4));
		endif;
		//delete the folder of a theme
		if ($this->uri->segment(3) == 'delete' && $this->uri->segment(4) != NULL):
			delete_files('./compass-content/themes/'.$this->uri->segment(4).'/', TRUE);
			rmdir('./compass-content/themes/'.$this->uri->segment(4).'/');
			set_msg('msgok', lang('cms_themes_msg_deleted_sucess'), 'sucess');
			redirect(base_url('cms/themes'));
		endif;
		//edit theme files
		if ($this->input->post('save')):
			write_file('./compass-content/themes/'.$this->uri->segment(4).'/'.$this->uri->segment(6), $this->input->post('file_data'));
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
		set_theme('content', load_module('themes', 'themes', 'cms'));
		set_theme('footerinc', load_module('includes', 'deletereg'), FALSE);
		set_theme('helper', lang('cms_help_themes'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The page stats
	 *
	 * Page with list stats of site
	 * Allows the listing stats of site.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function stats(){
		//access permission
		access('perm_stats_');
		//loads utilities
		$this->load->model('stats_model', 'stats');
		$this->load->helper('date');
		//mount the page layout
		init_highcharts();
		set_theme('title', lang('cms_stats'));
		set_theme('content', load_module('stats', 'stats', 'cms'));
		set_theme('helper', lang('cms_help_stats'));
		load_template();
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
	public function settings(){
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
		set_theme('content', load_module('settings', 'settings', 'cms'));
		set_theme('modal', load_module('medias', 'uploadmediasmodal', 'cms'), FALSE);
		set_theme('helper', lang('cms_help_settings'));
		load_template();
	}
}

/* End of file cms.php */
/* Location: ./application/controllers/cms.php */