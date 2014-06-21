<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dashboard class
 *
 * Controller of dashboard access
 *
 * Maps to the following URL
 * 		http://yoursite.com/users
 *
 * @package		Compass
 * @subpackage	Core
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.0.0
 */

class Dashboard extends CI_Controller {

	/**
	 * Constructor
	 *
	 */
	public function __construct(){
		parent::__construct();
		set_theme('helper', lang('help_dashboard'));
		//restricts access to people logged
		be_logged();
		//loads of standard features dashboard
		initialize_dashboard();
	}

	// --------------------------------------------------------------------

	/**
	 * The default page
	 *
	 * Page home system
	 * Quick Access Toolbar contains some functionality of the system.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function index(){
		//loads utilities
		$this->load->model('stats_model', 'stats');
		$this->load->helper('date');
		//mount the page layout
		init_highcharts();
		set_theme('title', lang('dashboard'));
		set_theme('content', load_module('dashboard_view', 'dashboard'));
		load_template();

	}
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */