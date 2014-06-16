<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dashboard class
 *
 * Controller of management books
 *
 * Maps to the following URL
 * 		http://yoursite.com/books
 *
 * @package		Compass
 * @subpackage	Books
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.1.0
 */

class Books extends CI_Controller {

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
		if (get_setting('module_books') == 0) redirect('stop');
		//loads utilities
		$this->load->model('books_model', 'books');
		set_theme('submenu', load_module('books', 'submenu', 'books'));
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
		'pagination_url'=>base_url('books/catalog/'),
		'pagination_segment'=>3,
		'orderby_segment'=>5,
		'order_segment'=>7,
		'default_orderby'=>'book_title',
		'default_order'=>'asc',
		'filter_key_segment'=>8,
		'filter_value_segment'=>9
		);
		//creates a url search
		if ($this->input->post('search')) get_paging_search($this->input->post('search_for'), $config);
		//mount the page layout
		set_theme('footerinc', load_module('includes', 'selectinput'), FALSE);
		set_theme('footerinc', load_module('includes', 'deletereg'), FALSE);
		set_theme('title', lang('books'));
		set_theme('content', load_module('books', 'catalog', 'books', array('config'=>$config)));
		set_theme('helper', lang('books_helper_catalog'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * The page lists
	 *
	 * Page home system
	 * Quick Access Toolbar contains some functionality of the system.
	 *
	 * @access     private
	 * @since      0.1.0
	 * @modify     0.1.0
	 */
	public function catalog(){
		//doubles the method index
		$this->index();
	}

	// --------------------------------------------------------------------

	/**
	 * The page insert
	 *
	 * Page of insert new user
	 * Allows you to enter the information needed to register a new user.
	 *
	 * @access     private
	 * @since      0.1.0
	 * @modify     0.1.0
	 */
	public function insert(){
		//access permission
		access('perm_booksinsert_');
		//data validation
        $this->form_validation->set_rules('book_title', strtoupper(lang('books_field_title')), 'trim|required');
        $this->form_validation->set_rules('book_register', strtoupper(lang('books_field_register')), 'trim|required|is_unique[books.book_register]');
        $this->form_validation->set_message('required', lang('core_msg_required'));
        $this->form_validation->set_message('is_unique', lang('core_msg_unique'));
        //registers data passed through the form of view
        if ($this->form_validation->run() == TRUE):
            $data = elements(array('book_title', 'book_register', 'book_author', 'book_tags', 'book_language', 'book_keywords', 'book_edition', 'book_publisher', 'book_city_publisher', 'book_year_publisher', 'book_number_pages', 'book_isbn', 'book_cdd', 'book_cdu', 'book_locale', 'book_price', 'book_synopsis'), $this->input->post());
        	$data['book_date'] = get_local_to_gmt_date($this->input->post('book_date'));
			$upload = $this->books->do_upload('book_cover');
			if (is_array($upload) && $upload['file_name'] != ''):
				$data['book_cover'] = $upload['file_name'];
			endif;
            $idadded = $this->books->do_insert($data, FALSE);
            redirect(current_url());
		endif;
		//mount the page layout
		init_tinymce(TRUE);
		init_tagsinputmaster();
		init_datetimepicker();
		set_theme('title', lang('books_insert'));
		set_theme('content', load_module('books', 'insert', 'books'));
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
        	unlink('./compass-content/uploads/avatars/'.$this->books->get_by_id($this->uri->segment(3))->row()->book_cover);
			$thumbs = glob('./compass-content/uploads/avatars/thumbs/*_'.$this->books->get_by_id($this->uri->segment(3))->row()->book_cover);
			foreach ($thumbs as $file):
				unlink($file);
			endforeach;
			$this->books->do_update(array('book_cover'=>''), array('book_id'=>$this->uri->segment(3)));
        endif;
        //save cover
        if ($this->input->post('save_cover')):
        	$upload = $this->books->do_upload('book_cover');
        	if (is_array($upload) && $upload['file_name'] != ''):
				$data['book_cover'] = $upload['file_name'];
				$this->books->do_update($data, array('book_id'=>$this->uri->segment(3)));
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
			$this->books->do_update($data, array('book_id'=>$this->input->post('idbook')));
        endif;
        //mount the page layout
        init_tinymce(TRUE);
		init_tagsinputmaster();
		init_datetimepicker();
		set_theme('title', lang('books_update'));
		set_theme('content', load_module('books', 'update', 'books'));
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
		set_theme('content', load_module('books', 'details', 'books'));
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
			$query = $this->books->get_by_id($this->uri->segment(3));
			if ($query->num_rows() == 1):
				$query = $query->row();
				if ($query->book_id != 1):
					$this->books->do_delete(array('book_id'=>$query->book_id), FALSE);
					redirect ('books');
				else:
					set_msg('msgerror', lang('books_msg_no_delete'), 'error');
				endif;
			else:
				set_msg('msgerror', lang('books_msg_not_found'), 'error');
			endif;
		else:
			set_msg('msgerror', lang('books_msg_choose'), 'error');
		endif;
	}

	// --------------------------------------------------------------------

	/**
	 * The page loans
	 *
	 * Page of loan book
	 * Allows books loans.
	 *
	 * @access     private
	 * @since      0.1.0
	 * @modify     0.1.0
	 */
	public function loans(){
		//access permission
		access('perm_booksloansgerencie_');
		//loads of standard features dashboard
		$this->load->model('books_model', 'books');
        $this->load->model('loans_model', 'loans');
        $this->load->helper('date');
        $this->load->model('userslevels_model', 'userslevels');
		if ($this->uri->segment(3) == 'insert'):
			$this->form_validation->set_rules('loan_user_id', strtoupper(lang('books_loans_field_user')), 'trim|required');
	        $this->form_validation->set_rules('loan_book_id', strtoupper(lang('books_loans_field_book')), 'trim|required');
	        $this->form_validation->set_message('required', lang('core_msg_required'));
	        if ($this->form_validation->run()==TRUE):
	            $data = elements(array('loan_user_id', 'loan_book_id'), $this->input->post());
	            $data['loan_status'] = 1;
	            $data['loan_lender_id'] = get_session('user_id');
	            $data['loan_date'] = get_local_to_gmt_date(date('Y-m-d H:i:s'));
	            $this->loans->do_insert($data, FALSE);
	            set_msg('msgok', lang('books_msg_loan_insert_sucess'), 'sucess');
				redirect ('books/loans/insert');
	        endif;
	        set_theme('title', lang('books_loans_insert'));
	        set_theme('content', load_module('loans', 'insert_loan', 'books'));
	        set_theme('helper', lang('books_helper_loans_insert'));
	        load_template();
	    elseif ($this->uri->segment(3) == 'user'):
	    	//variables paging and query
	    	$user_url = ($this->uri->segment(5) != NULL && $this->uri->segment(5) != NULL) ? '/user/'.$this->uri->segment(5) : 'user/no';
	        $book_url = ($this->uri->segment(5) != NULL && $this->uri->segment(5) != NULL) ? '/book/'.$this->uri->segment(7) : 'book/no';
			$config = array(
			'model'=>'users',
			'method'=>'return_list',
			'pagination_url'=>base_url('books/loans/user/'.$user_url.$book_url),
			'pagination_segment'=>9,
			'orderby_segment'=>12,
			'order_segment'=>13,
			'default_orderby'=>'user_username',
			'default_order'=>'asc',
			'filter_key_segment'=>10,
			'filter_value_segment'=>11,
			'pagination_rows'=>50
			);
			//creates a url search
			if ($this->input->post('search')):
				redirect('books/loans/user'.$user_url.$book_url.'/page/0/search/'.url_title($this->input->post('search_for')));
			endif;
			//mount the page layout
			set_theme('title', lang('books_loans_user'));
			set_theme('content', load_module('loans', 'loan_user', 'books', array('config'=>$config)));
			set_theme('helper', lang('books_helper_loans_insert_user'));
			load_template();
		elseif ($this->uri->segment(3) == 'book'):
	    	//variables paging and query
	    	$user_url = ($this->uri->segment(5) != NULL && $this->uri->segment(5) != NULL) ? '/user/'.$this->uri->segment(5) : 'user/no';
	        $book_url = ($this->uri->segment(5) != NULL && $this->uri->segment(5) != NULL) ? '/book/'.$this->uri->segment(7) : 'book/no';
			$config = array(
			'model'=>'books',
			'method'=>'return_list',
			'pagination_url'=>base_url('books/loans/user/'.$user_url.$book_url),
			'pagination_segment'=>9,
			'orderby_segment'=>12,
			'order_segment'=>13,
			'default_orderby'=>'book_title',
			'default_order'=>'asc',
			'filter_key_segment'=>10,
			'filter_value_segment'=>11,
			'pagination_rows'=>50
			);
			//creates a url search
			if ($this->input->post('search')):
				redirect('books/loans/user'.$user_url.$book_url.'/page/0/search/'.url_title($this->input->post('search_for')));
			endif;
			//mount the page layout
			set_theme('title', lang('books_loans_book'));
			set_theme('content', load_module('loans', 'loan_book', 'books', array('config'=>$config)));
			set_theme('helper', lang('books_helper_loans_insert_book'));
			load_template();
		elseif ($this->uri->segment(3) == 'details'):
			//mount the page layout
			set_theme('title', 'Detalhes do empréstimo');
			set_theme('content', load_module('loans', 'details', 'books'));
			set_theme('template', 'base_view');
			load_template();
		elseif ($this->uri->segment(3) == 'finish'):
	        if ($this->uri->segment(4) != NULL && is_numeric($this->uri->segment(4))):
	            $data['loan_date_deliver'] = get_local_to_gmt_date(date('Y-m-d H:i:s'));
	            $data['loan_status'] = '';
	            $this->loans->do_update($data, array('loan_id'=>$this->uri->segment(4)), FALSE);
	            set_msg('msgok', lang('book_msg_loan_complete'), 'sucess');
	            redirect ('books/loans');
	        endif;
	    elseif ($this->uri->segment(3) == 'historic'):
			//variables paging and query
			$config = array(
			'model'=>'loans',
			'method'=>'return_list',
			'pagination_url'=>base_url('books/loans/historic/'),
			'pagination_segment'=>4,
			'orderby_segment'=>6,
			'order_segment'=>8,
			'default_orderby'=>'loan_date',
			'default_order'=>'desc',
			'filter_key_segment'=>8,
			'filter_value_segment'=>9, 
			'pagination_rows'=>get_setting('general_large_list')
			);
			//creates a url search
			if ($this->input->post('search')):
				redirect('books/loans/page/0'.$user_url.$book_url.'/page/0/search/'.url_title($this->input->post('search_for')));
			endif;
			//mount the page layout
			set_theme('title', lang('books_loans_historic'));
			set_theme('content', load_module('loans', 'historic', 'books', array('config'=>$config)));
			set_theme('helper', lang('books_helper_loans_historic'));
			load_template();
		else:
			//variables paging and query
			$config = array(
			'model'=>'loans',
			'method'=>'return_list',
			'pagination_url'=>base_url('books/catalog/'),
			'pagination_segment'=>3,
			'orderby_segment'=>5,
			'order_segment'=>7,
			'default_orderby'=>'loan_date',
			'default_order'=>'desc',
			'filter_key_segment'=>8,
			'filter_value_segment'=>9,
			'pagination_rows'=>250
			);
			//creates a url search
			if ($this->input->post('search')):
				redirect('books/loans/page/0'.$user_url.$book_url.'/page/0/search/'.url_title($this->input->post('search_for')));
			endif;
			//mount the page layout
			set_theme('title', lang('books_loans'));
			set_theme('content', load_module('loans', 'loans', 'books', array('config'=>$config)));
			set_theme('helper', lang('books_helper_loans'));
			load_template();
		endif;
	}

	// --------------------------------------------------------------------

	/**
	 * The page settings
	 *
	 * Page of settings books
	 * Allows books settings.
	 *
	 * @access     private
	 * @since      0.1.0
	 * @modify     0.1.0
	 */
	public function settings(){
		//access permission
		access('perm_bookssettings_');
		if ($this->input->post('save')):
			$settings = elements(array(
				'books_loans_number_days',
				'books_loans_max_for_user',
				'books_reference_model'
				), $this->input->post());
			foreach ($settings as $setting_name => $setting_value):
				set_setting($setting_name, $setting_value);
			endforeach;
			set_msg('msgok', lang('book_msg_settings_sucess'), 'sucess');
			redirect('books/settings');
		endif;
		set_theme('title', lang('books_settings'));
		set_theme('content', load_module('books', 'settings', 'books'));
		set_theme('helper', lang('books_helper_settings'));
		load_template();
	}


}

/* End of file books.php */
/* Location: ./application/controllers/books.php */