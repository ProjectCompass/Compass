<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tools class
 *
 * Controller of tools use
 *
 * Maps to the following URL
 * 		http://yoursite.com/tools
 *
 * @package		Compass
 * @subpackage	Core
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       1.0.0
 */

class Tools extends CI_Controller {

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
		//sets the block submenu controller
		set_theme('submenu', load_module('tools', 'submenu'));
		//loads utilities
		$this->load->model('audits_model', 'audits');
		set_theme('helper', lang('help_tools'));
		//access permission
		access('perm_tools_');
	}

	// --------------------------------------------------------------------

	/**
	 * The default page
	 *
	 * Page with list of tools
	 * Used to provide access to system tools.
	 *
	 * @access     private
	 * @since      1.0.0
	 * @modify     1.0.0
	 */
	public function index(){
		$this->audits();
	}

	// --------------------------------------------------------------------

	/**
	 * The audits page
	 *
	 * Page with show of audits
	 * Enables tracking of the latest transactions in the system, through the audit events in the database, login and uploads.
	 *
	 * @access     private
	 * @since      1.0.0
	 * @modify     1.0.0
	 */
	public function audits(){
		//mount the page layout
		set_theme('title', lang('tools_audits'));
		set_theme('content', load_module('tools', 'audits'));
		load_template();
	}

}

/* End of file audits.php */
/* Location: ./application/controllers/tools.php */