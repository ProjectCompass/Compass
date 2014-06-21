<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Install class
 *
 * Controller of install system
 *
 * Maps to the following URL
 * 		http://yoursite.com/books/install
 *
 * @package		Compass
 * @subpackage	Books
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.1.0
 */

class Install extends CI_Controller {

	/**
	 * Constructor
	 *
	 */
	public function __construct(){
		parent::__construct();
		//loads of standard features dashboard
    	$this->load->helper(array('url'));
	}

	// --------------------------------------------------------------------

	/**
	 * The default page
	 *
	 * Page home install module
	 *
	 * @access     public
	 * @since      0.1.0
	 * @modify     0.1.0
	 */
	public function index(){
		//connect to the database
		$this->load->database();
		$this->db->reconnect();
		//create table books
		$sql_bd = "CREATE TABLE IF NOT EXISTS `books` (
		  `book_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  `book_register` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `book_title` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `book_author` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `book_tags` text CHARACTER SET utf8 NOT NULL,
		  `book_keywords` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `book_language` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `book_edition` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `book_publisher` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `book_year_publisher` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `book_city_publisher` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `book_number_pages` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `book_isbn` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `book_cdd` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `book_cdu` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `book_synopsis` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `book_locale` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `book_date` datetime NOT NULL,
		  `book_price` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `book_cover` varchar(255) CHARACTER SET utf8 NOT NULL,
		  PRIMARY KEY (`book_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
		$this->db->query($sql_bd);
		//create table loans
		$sql_bd = "CREATE TABLE IF NOT EXISTS `loans` (
		  `loan_id` bigint(20) NOT NULL AUTO_INCREMENT,
		  `loan_lender_id` bigint(20) NOT NULL,
		  `loan_user_id` bigint(20) NOT NULL,
		  `loan_book_id` bigint(20) NOT NULL,
		  `loan_date` datetime NOT NULL,
		  `loan_date_deliver` datetime NOT NULL,
		  `loan_status` varchar(255) CHARACTER SET utf8 NOT NULL,
		  PRIMARY KEY (`loan_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
		$this->db->query($sql_bd);
		//enter settings for user permissions books
		$sql_bd = "INSERT INTO `settings` (`setting_id`, `setting_name`, `setting_value`) VALUES
			('', 'perm_bookslist_1', '1'),
			('', 'perm_bookslist_2', '1'),
			('', 'perm_bookslist_3', '1'),
			('', 'perm_bookslist_4', '1'),
			('', 'perm_bookslist_5', '1'),
			('', 'perm_booksview_1', '1'),
			('', 'perm_booksview_2', '1'),
			('', 'perm_booksview_3', '1'),
			('', 'perm_booksview_4', '1'),
			('', 'perm_booksview_5', '1'),
			('', 'perm_booksinsert_1', '1'),
			('', 'perm_booksinsert_2', '1'),
			('', 'perm_booksinsert_3', '1'),
			('', 'perm_booksupdate_1', '1'),
			('', 'perm_booksupdate_2', '1'),
			('', 'perm_booksupdate_3', '1'),
			('', 'perm_booksdelete_1', '1'),
			('', 'perm_booksdelete_2', '1'),
			('', 'perm_booksloansgerencie_1', '1'),
			('', 'perm_booksloansgerencie_2', '1'),
			('', 'perm_booksloansgerencie_3', '1'),
			('', 'perm_bookssettings_1', '1'),
			('', 'books_loans_number_days', '5'),
			('', 'books_loans_max_for_user', '2'),
			('', 'books_reference_model', '[author], <strong>[title]</strong>. [edition]. ed. [city]: [publisher], [pages] p.');";
		$this->db->query($sql_bd);
		set_setting('module_install_'.$this->uri->segment(1), '1');
		redirect ('tools/modules');
	}
}

/* End of file install.php */
/* Location: ./books/controllers/install.php */