<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Stats model
 *
 * Performs the interaction with the stats table bd
 *
 * @package     Compass
 * @subpackage  Stats
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       1.0.0
 */

class Stats_model extends CI_Model {

    /**
     * Constructor
     *
     */
    public function __construct(){
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * Insert Stats
     *
     * Used in controllers with forms of insertion of stats data.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
	public function do_insert($data=NULL){
		if ($data != NULL):
			$this->db->insert('stats', $data);
		endif;
	}

	// --------------------------------------------------------------------

    /**
     * Get by in
     *
     * Used to return stats that contain a value in a column.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
	public function get_by_in($in=NULL, $by=NULL){
        if ($in != NULL):
            $this->db->like($in, $by);
        endif;
		return $this->db->get_where('stats');
	}

    // --------------------------------------------------------------------

    /**
     * Get all stats
     *
     * Used to return all stats without criteria searches.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function get_all(){
        return $this->db->get('stats');
    }	
}

/* End of file stats_model.php */
/* Location: ./application/models/stats_model.php */