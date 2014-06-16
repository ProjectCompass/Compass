<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Welcome class
 *
 * Controller f oviewing the homepage of the System
 *
 * Maps to the following URL
 * 		http://yoursite.com/welcome
 *
 * @package		Compass
 * @subpackage	Core
 * @copyright	Copyright (c) 2014, Compass, Inc.
 * @author		Francisco Rodrigo Cunha de Sousa
 * @link		http://rodrigosousa.info
 * @since       0.0.0
 */

class Welcome extends CI_Controller {

	/**
	 * The default page
	 *
	 * Page home of system
	 * Allows redirecting of homepage of the system.
	 *
	 * @access     public
	 * @since      0.0.0
	 * @modify     0.0.0
	 */
	public function index(){
		//loads utilities
		$this->load->helper(array('functions', 'url'));
		//redirects
		if (get_setting('general_homepage') == 'core'):
			redirect('login');
		elseif (get_setting('general_homepage') == 'cms' && get_setting('module_cms') == 1):
			redirect('site');
		else:
			redirect('login');
		endif;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */