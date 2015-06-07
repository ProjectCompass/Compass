<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Autidts model
 *
 * Performs the interaction with the audits table bd
 *
 * @package     Compass
 * @subpackage  Posts
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       0.0.0
 */

class Audits_model extends CI_Model {

    // --------------------------------------------------------------------

    /**
     * Insert Audits
     *
     * Used in controllers with forms of insertion of audit data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_insert($data=NULL, $redir=FALSE){
        if ($data != NULL):
            $this->db->insert('audits', $data);
            if ($redir) redirect(current_url());
        endif;
    }
    
    // --------------------------------------------------------------------

    /**
     * Get by id
     *
     * Used to return the data from the audit id past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_id($id=NULL){
        if ($id != NULL):
            $this->db->where('audit_id', $id);
            $this->db->limit(1);
            return $this->db->get('audits');
        else:
            return FALSE;
        endif;
    }
    
    // --------------------------------------------------------------------

    /**
     * Get all audits
     *
     * Used to return all audits without criteria searches.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_all(){
        return $this->db->get('audits');
    }
}
