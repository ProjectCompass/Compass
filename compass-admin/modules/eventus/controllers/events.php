<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Catalog class
 *
 * Controller of management books
 *
 * Maps to the following URL
 * 		http://yoursite.com/eventus
 *
 * @package		Compass
 * @subpackage	Eventus
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.1.0
 */

class Events extends CI_Controller {

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

	}

	// --------------------------------------------------------------------

	/**
	 * The default page
	 *
	 * Page home system
	 * Quick Access Toolbar contains some functionality of the system.
	 *
	 * @access     private
	 * @since      0.1.0
	 * @modify     0.1.0
	 */
	public function index(){
		//access permission
		access('perm_bookslist_');
		//variables paging and query
		$config = array(
		'model'=>'books',
		'method'=>'return_list',
		'pagination_url'=>base_url('books/catalog/index/'),
		'pagination_segment'=>4,
		'orderby_segment'=>6,
		'order_segment'=>8,
		'default_orderby'=>'book_title',
		'default_order'=>'asc',
		'filter_key_segment'=>9,
		'filter_value_segment'=>10
		);
		//creates a url search
		if ($this->input->post('search')) get_paging_search($this->input->post('search_for'), $config);
		//mount the page layout
		set_theme('footerinc', load_module('includes_view', 'selectinput'), FALSE);
		set_theme('footerinc', load_module('includes_view', 'deletereg'), FALSE);
		set_theme('title', lang('books'));
		set_theme('content', load_module('catalog_view', 'catalog', array('config'=>$config)));
		set_theme('helper', lang('books_helper_catalog'));
		load_template();
	}


	// --------------------------------------------------------------------

	/**
	 * The page insert
	 *
	 * Page of insert new event
	 * Allows you to enter the information needed to register a new event.
	 *
	 * @access     private
	 * @since      0.1.0
	 * @modify     0.1.0
	 */
	public function insert(){
		//mount the page layout
		init_tinymce(TRUE);
		init_tagsinputmaster();
		init_datetimepicker();
		set_theme('title', 'Inserir novo evento');
		set_theme('content', load_module('events_view', 'insert'));
		set_theme('helper', lang('books_helper_insert'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The page update
	 *
	 * Page of update user
	 * Allows you to change the registration information of a user.
	 *
	 * @access     private
	 * @since      0.1.0
	 * @modify     0.1.0
	 */
	public function update(){
		//access permission
		access('perm_booksupdate_');
		//remove cover
		if ($this->input->post('remove_cover')):
        	unlink('./compass-content/uploads/avatars/'.$this->books->get_by_id($this->uri->segment(4))->row()->book_cover);
			$thumbs = glob('./compass-content/uploads/avatars/thumbs/*_'.$this->books->get_by_id($this->uri->segment(4))->row()->book_cover);
			foreach ($thumbs as $file):
				unlink($file);
			endforeach;
			$this->books->do_update(array('book_cover'=>''), array('book_id'=>$this->uri->segment(4)));
        endif;
        //save cover
        if ($this->input->post('save_cover')):
        	$upload = $this->books->do_upload('book_cover');
        	if (is_array($upload) && $upload['file_name'] != ''):
				$data['book_cover'] = $upload['file_name'];
				$this->books->do_update($data, array('book_id'=>$this->uri->segment(4)));
			endif;
        endif;
        //data validation
		$this->form_validation->set_rules('book_title', strtoupper(lang('books_field_title')), 'trim|required');
        $this->form_validation->set_message('required', lang('core_msg_required'));
        //edit the data passed to the view
        if ($this->form_validation->run()==TRUE):
			$data = elements(array('book_title', 'book_author', 'book_tags', 'book_language', 'book_keywords', 'book_edition', 'book_publisher', 'book_city_publisher', 'book_year_publisher', 'book_number_pages', 'book_isbn', 'book_cdd', 'book_cdu', 'book_locale', 'book_price', 'book_synopsis'), $this->input->post());
			$data['book_date'] = get_local_to_gmt_date($this->input->post('book_date'));
			$upload = $this->books->do_upload('book_cover');
        	if (is_array($upload) && $upload['file_name'] != ''):
				$data['book_cover'] = $upload['file_name'];
			endif;
			$this->books->do_update($data, array('book_id'=>$this->input->post('book_id')));
        endif;
        //mount the page layout
        init_tinymce(TRUE);
		init_tagsinputmaster();
		init_datetimepicker();
		set_theme('title', lang('books_update'));
		set_theme('content', load_module('catalog_view', 'update'));
		set_theme('helper', lang('books_helper_update'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The page of details
	 *
	 * Page of details of the book
	 * Allows showing details of the books.
	 *
	 * @access     private
	 * @since      0.1.0
	 * @modify     0.1.0
	 */
	public function details(){
		//access permission
		access('perm_booksview_');
		//access('perm_viewbook_');
		set_theme('title', lang('books_details'));
		set_theme('content', load_module('catalog_view', 'details'));
		set_theme('template', 'base_view');
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The page delete
	 *
	 * Page of delete book
	 * Allows books deletes.
	 *
	 * @access     private
	 * @since      0.1.0
	 * @modify     0.1.0
	 */
	public function delete(){
		//access permission
		access('perm_booksdelete_');
		//Assigns the status '9'to the user, leaving it invisible to the system
		if ($this->uri->segment(3) != NULL):
			$query = $this->books->get_by_id($this->uri->segment(4));
			if ($query->num_rows() == 1):
				$query = $query->row();
				if ($query->book_id != 1):
					$this->books->do_delete(array('book_id'=>$query->book_id), FALSE);
					redirect ('books');
				else:
					set_msg('msgerror', lang('books_msg_no_delete'), 'error');
					redirect ('books');
				endif;
			else:
				set_msg('msgerror', lang('books_msg_not_found'), 'error');
				redirect ('books');
			endif;
		else:
			set_msg('msgerror', lang('books_msg_choose'), 'error');
			redirect ('books');
		endif;
	}

}

/* End of file books.php */
/* Location: ././books/controllers/catalog.php */