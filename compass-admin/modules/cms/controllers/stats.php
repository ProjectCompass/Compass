<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Stats class
 *
 * Controller of stats of contents management
 *
 * Maps to the following URL
 * 		http://yoursite.com/cms/stats
 *
 * @package		Compass
 * @subpackage	CMS
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.0.0
 */

class Stats extends CI_Controller {

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
		$this->load->helper('cms');
		//loads of standard features books module
		initialize_cms();
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
	public function index(){
		//access permission
		access('perm_stats_');
		//loads utilities
		$this->load->model('stats_model', 'stats');
		$this->load->helper('date');
		//mount the page layout
		init_highcharts();
		set_theme('title', lang('cms_stats'));
		set_theme('content', load_module('stats_view', 'stats'));
		set_theme('helper', lang('cms_help_stats'));
		load_template();
	}
}
/* End of file stats.php */
/* Location: ././cms/controllers/stats.php */