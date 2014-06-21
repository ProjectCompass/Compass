<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Users class
 *
 * Controller of errors display
 *
 * Maps to the following URL
 * 		http://yoursite.com/stop
 *
 * @package		Compass
 * @subpackage	Core
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.0.0
 */

class Stop extends CI_Controller {

	/**
	 * Constructor
	 *
	 */
	public function __construct(){
		parent::__construct();
		//loads of standard features dashboard
		initialize_dashboard();
	}

	// --------------------------------------------------------------------

	/**
	 * The default page
	 *
	 * Page stop
	 * Allows approved users without access to certain elements of the system.
	 *
	 * @access     private
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function index(){
		//mount the page layout
        set_theme('title', lang('stop_access_denied'));
        set_theme('content', load_module('stop_view', 'stop'));
        load_template();
	}
}

/* End of file stop.php */
/* Location: ./application/controllers/stop.php */