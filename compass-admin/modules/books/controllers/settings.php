<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Settings class
 *
 * Controller of management settings books
 *
 * Maps to the following URL
 * 		http://yoursite.com/books/settings
 *
 * @package		Compass
 * @subpackage	Books
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.1.0
 */

class Settings extends CI_Controller {

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
	public function index(){
		//access permission
		access('perm_bookssettings_');
		if ($this->input->post('save')):
			//SETTINGS
			$settings = elements(array(
				'books_loans_number_days',
				'books_loans_max_for_user',
				'books_reference_model'
				), $this->input->post());
			//PERMISSIONS
			$query_userslevels = $this->userslevels->get_all()->result();
			//receives the values ​​of the fields passed in view
			$allpermissions = array(
				'perm_bookslist_',
				'perm_booksview_',
				'perm_booksinsert_',
				'perm_booksupdate_',
				'perm_booksdelete_',
				'perm_booksloansgerencie_',
				'perm_bookssettings_'
				);
			//crossing rows and columns to get all the data from the table permissions
			foreach ($allpermissions as $column):
				foreach ($query_userslevels as $line):
					$permission = $column;
					$settings["$permission$line->userlevel_id"] = $this->input->post("$permission$line->userlevel_id");
				endforeach;
			endforeach;
			//gives all permissions to users level 1
			foreach ($allpermissions as $column):
				$permission = $column;
				$settings[$permission.'1'] = 1;
			endforeach;
			//INSERT THE SETTINGS IN BD
			foreach ($settings as $setting_name => $setting_value):
				set_setting($setting_name, $setting_value);
			endforeach;
			set_msg('msgok', lang('book_msg_settings_sucess'), 'sucess');
			redirect('books/settings');
		endif;
		set_theme('title', lang('books_settings'));
		set_theme('content', load_module('settings_view', 'settings'));
		set_theme('helper', lang('books_helper_settings'));
		load_template();
	}


}

/* End of file books.php */
/* Location: ././books/controllers/settings.php */