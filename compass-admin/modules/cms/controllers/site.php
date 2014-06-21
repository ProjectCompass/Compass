<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Site class
 *
 * Controller of viewing the contents of the Content Management System
 *
 * Maps to the following URL
 * 		http://yoursite.com/stop
 *
 * @package		Compass
 * @subpackage	CMS
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.0.0
 */

class Site extends CI_Controller {

	public function __construct(){
		parent::__construct();
		//loads utilities
		$this->load->helper(array('cms', 'site', 'url'));
		$this->load->model('users_model', 'users');
		$this->load->model('comments_model', 'comments');
		$this->load->model('posts_model', 'posts');
	}

	// --------------------------------------------------------------------

	/**
	 * The default page
	 *
	 * Page home of site
	 * Allows viewing the main content of the site.
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function index($id_or_slug_page=NULL){
		//Arrow visitation statistics
		set_stat();
		//Allows selection of the site's homepage, and the home (default), or other specific blog page.
		if (get_setting('site_page_home') == 'home' || get_setting('site_page_home') == NULL):
			//Loads the theme's home.php file if it exists, otherwise load the index.php file.
			if (in_array('home.php', load_layout_files())):
				load_layout('home.php');
			else:
				load_layout('index.php');
			endif;
		elseif (get_setting('site_page_home') == 'blog'):
			//Loads the theme's blog.php file if it exists, otherwise load the index.php file.
			if (in_array('blog.php', load_layout_files())):
				load_layout('blog.php');
			else:
				load_layout('index.php');
			endif;
		else:
			redirect(base_url().'site/page'.get_setting('site_page_home'));
		endif;
	}

	// --------------------------------------------------------------------

	/**
	 * The page blog
	 *
	 * Page blog of site
	 * Displays the list of content type post.
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function blog($blog_page=1){
		//Arrow visitation statistics
		set_stat();
		//Loads the theme's blog.php file if it exists, otherwise load the index.php file.
		if (in_array('blog.php', load_layout_files())):
			load_layout('blog.php');
		else:
			load_layout('index.php');
		endif;
	}

	// --------------------------------------------------------------------

	/**
	 * The page post
	 *
	 * Page single post of site
	 * Displays the content type post.
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function post($id_or_slug_post=NULL){
		//checks if the id was passed or post slug in the url
		$byid_or_byslug = (is_numeric($this->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
		//if no value is passed id or slug in the url redirects to the main stop
		if ($this->uri->segment(2) == NULL || $this->posts->$byid_or_byslug($this->uri->segment(2))->row()->post_id == NULL) redirect(base_url('site/error'));
		//Arrow visitation statistics
		set_stat();
		//updates the number of comments for this post
		$data_posts['post_comment_count'] = $this->comments->get_by_postid($this->posts->$byid_or_byslug($this->uri->segment(2))->row()->post_id)->num_rows();
		$this->posts->do_update($data_posts, array('post_id'=>$this->posts->$byid_or_byslug($this->uri->segment(2))->row()->post_id), FALSE, FALSE);
		// the form is submitted the comments
		if ($this->input->post('save_comment')):
			//Requires the name and email of the comment
			if ($this->input->post('comment_author') == NULL):
				set_msg('msgcomment', lang('cms_site_msg_author_name_requir', 'cms'), 'error');
				redirect(current_url());
			elseif ($this->input->post('comment_authoremail') == NULL):
				set_msg('msgcomment', lang('cms_site_msg_author_email_requir', 'cms'), 'error');
				redirect(current_url());
			endif;
			//retrieves the data passed through the form
			$data['comment_postid'] = $this->input->post('idpost');
			$data['comment_author'] = $this->input->post('comment_author');
			$data['comment_authorid'] = get_session('user_id');
			$data['comment_authoremail'] = $this->input->post('comment_authoremail');
			$data['comment_authorurl'] = $this->input->post('comment_authorurl');
			$data['comment_content'] = $this->input->post('comment_content');
			$data['comment_authorip'] = $_SERVER["REMOTE_ADDR"];
			$data['comment_date'] = date('Y-m-d H:i:s');
			//assigns the status of disapproved if Comment moderation is enabled
			if (get_setting('site_comments_moderation') == '1'):
				$data['comment_status'] = 'unmoderated';
			else:
				$data['comment_status'] = 'approved';
			endif;
			//enter the review on bd and redirects to the main post
			$idadded = $this->comments->do_insert($data, FALSE);
			set_msg('msgcomment', lang('cms_site_msg_comment_post_sucess','cms'), 'sucess');
			redirect(current_url());
		endif;
		//Loads the theme's single.php file if it exists, otherwise load the index.php file.
		if (in_array('single.php', load_layout_files())):
			load_layout('single.php');
		else:
			load_layout('index.php');
		endif;
	}

	// --------------------------------------------------------------------

	/**
	 * The page page
	 *
	 * Page single page of site
	 * Displays the content type single page.
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function page($id_or_slug_page=NULL){
		//checks if the id was passed or post slug in the url
		$byid_or_byslug = (is_numeric($this->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
		//if no value is passed id or slug in the url redirects to the main stop
		if ($this->uri->segment(2) == NULL || $this->posts->$byid_or_byslug($this->uri->segment(2))->row()->post_id == NULL) redirect(base_url('site/error'));
		//arrow visitation statistics
		set_stat();
		//Loads the theme's page.php file if it exists, otherwise loads the theme's singel.php file, otherwise if it exists load the index.php file.
		if (in_array('page.php', load_layout_files())):
			load_layout('page.php');
		elseif (in_array('single.php', load_layout_files())):
			load_layout('single.php');
		else:
			load_layout('index.php');
		endif;
	}

	// --------------------------------------------------------------------

	/**
	 * The page media
	 *
	 * Page single media of site
	 * Displays the content type media.
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function media($id_media=NULL){
		//if no value is passed id or slug in the url redirects to the main stop
		if ($this->uri->segment(2) == NULL) redirect(base_url('site/error'));
		//arrow visitation statistics
		set_stat();
		//loads the theme's archive.php file if it exists, otherwise load the index.php file.
		if (in_array('archive.php', load_layout_files())):
			load_layout('archive.php');
		else:
			load_layout('index.php');
		endif;
	}

	// --------------------------------------------------------------------

	/**
	 * The page category
	 *
	 * Page of category posts show
	 * Displays the content type post of the single category.
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function tag($slug_tag=NULL){
		//if no value is passed id or slug in the url redirects to the main stop
		if ($this->uri->segment(2) == NULL) redirect(base_url('site/error'));
		//arrow visitation statistics
		set_stat();
		//Loads the theme's category.php file if it exists, otherwise loads the theme's singel.php file, otherwise if it exists load the index.php file.
		if (in_array('tag.php', load_layout_files())):
			load_layout('tag.php');
		elseif (in_array('archive.php', load_layout_files())):
			load_layout('archive.php');
		else:
			load_layout('index.php');
		endif;
	}

	// --------------------------------------------------------------------

	/**
	 * The page author
	 *
	 * Page of category posts show
	 * Displays the content type post of the single author.
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function author($id_author=NULL){
		//if no value is passed id in the url redirects to the main stop
		if ($this->uri->segment(2) == NULL) redirect(base_url('site/error'));
		//Arrow visitation statistics
		set_stat();
		//Loads the theme's author.php file if it exists, otherwise loads the theme's singel.php file, otherwise if it exists load the index.php file.
		if (in_array('author.php', load_layout_files())):
			load_layout('author.php');
		elseif (in_array('archive.php', load_layout_files())):
			load_layout('archive.php');
		else:
			load_layout('index.php');
		endif;
	}

	// --------------------------------------------------------------------

	/**
	 * The page search
	 *
	 * Page of search content show
	 * Displays the content for searchs.
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function search($term_search=NULL){
		//if no value is passed term in the url redirects to the main stop
		if ($this->input->post('search')):
			redirect(current_url().'/'.url_title($this->input->post('search_for')));
		endif;
		//Arrow visitation statistics
		set_stat();
		//Loads the theme's search.php file if it exists, otherwise loads the theme's singel.php file, otherwise if it exists load the index.php file.
		if (in_array('search.php', load_layout_files())):
			load_layout('search.php');
		elseif (in_array('archive.php', load_layout_files())):
			load_layout('archive.php');
		else:
			load_layout('index.php');
		endif;
	}

	// --------------------------------------------------------------------

	/**
	 * The page error
	 *
	 * Page of errors
	 * Displays the error 404.
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function error(){
		//Arrow visitation statistics
		set_stat();
		//loads the theme's 404.php file if it exists, otherwise load the index.php file.
		if (in_array('404.php', load_layout_files())):
			load_layout('404.php');
		else:
			load_layout('index.php');
		endif;
	}
}

/* End of file site.php */
/* Location: ././cms/controllers/site.php */