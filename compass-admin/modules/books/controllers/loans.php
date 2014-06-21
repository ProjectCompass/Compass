<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Loans class
 *
 * Controller of management loans books
 *
 * Maps to the following URL
 * 		http://yoursite.com/books/loans
 *
 * @package		Compass
 * @subpackage	Books
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.1.0
 */

class Loans extends CI_Controller {

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
		$this->load->helper('books');
		//loads of standard features books module
		initialize_books();
		//access permission
		access('perm_booksloansgerencie_');
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
		//variables paging and query
		$config = array(
		'model'=>'loans',
		'method'=>'return_list',
		'pagination_url'=>base_url('books/loans/index'),
		'pagination_segment'=>4,
		'orderby_segment'=>6,
		'order_segment'=>8,
		'default_orderby'=>'loan_date',
		'default_order'=>'desc',
		'filter_key_segment'=>9,
		'filter_value_segment'=>10,
		'pagination_rows'=>250
		);
		//mount the page layout
		set_theme('title', lang('books_loans'));
		set_theme('content', load_module('loans_view', 'loans', array('config'=>$config)));
		set_theme('helper', lang('books_helper_loans'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * Insert page
	 *
	 * Page Insert
	 * Lets make new loans.
	 *
	 * @access     private
	 * @since      0.1.0
	 * @modify     0.1.0
	 */
	public function insert(){
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
        set_theme('content', load_module('loans_view', 'insert_loan'));
        set_theme('helper', lang('books_helper_loans_insert'));
        load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * User page
	 *
	 * Page user
	 * Allows you to select the user to make the loan.
	 *
	 * @access     private
	 * @since      0.1.0
	 * @modify     0.1.0
	 */
	public function user(){
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
		set_theme('content', load_module('loans_view', 'loan_user', array('config'=>$config)));
		set_theme('helper', lang('books_helper_loans_insert_user'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * User book
	 *
	 * Page book
	 * Allows you to select the book to make the loan.
	 *
	 * @access     private
	 * @since      0.1.0
	 * @modify     0.1.0
	 */
	public function book(){
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
		set_theme('content', load_module('loans_view', 'loan_book', array('config'=>$config)));
		set_theme('helper', lang('books_helper_loans_insert_book'));
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * User details
	 *
	 * Page details
	 * Allows you to track the details of the loan.
	 *
	 * @access     private
	 * @since      0.1.0
	 * @modify     0.1.0
	 */
	public function details(){
		//mount the page layout
		set_theme('title', 'Detalhes do empréstimo');
		set_theme('content', load_module('loans_view', 'details'));
		set_theme('template', 'base_view');
		load_template();
	}

	// --------------------------------------------------------------------

	/**
	 * User finish
	 *
	 * Page finish
	 * Allows complete the loan.
	 *
	 * @access     private
	 * @since      0.1.0
	 * @modify     0.1.0
	 */
	public function finish(){
		if ($this->uri->segment(4) != NULL && is_numeric($this->uri->segment(4))):
            $data['loan_date_deliver'] = get_local_to_gmt_date(date('Y-m-d H:i:s'));
            $data['loan_status'] = '';
            $this->loans->do_update($data, array('loan_id'=>$this->uri->segment(4)), FALSE);
            set_msg('msgok', lang('book_msg_loan_complete'), 'sucess');
            redirect ('books/loans');
        endif;
	}

	// --------------------------------------------------------------------

	/**
	 * User historic
	 *
	 * Page historic
	 * Lets see all the loans already completed.
	 *
	 * @access     private
	 * @since      0.1.0
	 * @modify     0.1.0
	 */
	public function historic(){
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
		set_theme('content', load_module('loans_view', 'historic', array('config'=>$config)));
		set_theme('helper', lang('books_helper_loans_historic'));
		load_template();
	}

}

/* End of file books.php */
/* Location: ././books/controllers/loans.php */