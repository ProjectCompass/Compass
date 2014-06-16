<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Termmeta model
 *
 * Performs the interaction with the termmeta table bd
 *
 * @package     Compass
 * @subpackage  Terms
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       0.0.0
 */

class Termmeta_model extends CI_Model {

    /**
     * Constructor
     *
     */
    public function __construct(){
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * Insert Termmeta
     *
     * Used in controllers with forms of insertion of termmeta data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_insert($data=NULL, $redir=TRUE){
        if ($data != NULL):
            $this->db->insert('termmeta', $data);
            $insertid = mysql_insert_id();
            if ($this->db->affected_rows() > 0):
                set_msg('msgok', lang('model_insert_sucess'), 'sucess');
            else:
                set_msg('msgerror', lang('model_insert_error'), 'error');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Update Termmeta
     *
     * Used in controllers with forms of updation of termmeta data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_update($data=NULL, $condition=NULL, $redir=TRUE){
        if ($data != NULL && is_array($condition)):
            $this->db->update('termmeta', $data, $condition);
            if ($this->db->affected_rows() > 0):
                set_msg('msgok', lang('model_update_sucess'), 'sucess');
            else:
                set_msg('msgerro', lang('model_update_error'), 'error');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Delete Termmeta
     *
     * Used in controllers with forms of delete of termmeta data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_delete($condition=NULL, $redir=TRUE){
        if ($condition != NULL && is_array($condition)):
            $this->db->delete('termmeta', $condition);
            if ($this->db->affected_rows() > 0):
                set_msg('msgok', lang('model_delete_sucess'), 'sucess');
            else:
                set_msg('msgerro', lang('model_delete_error'), 'error');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by id
     *
     * Used to return the data from the termmeta id past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_id($id=NULL){
        if ($id != NULL):
            $this->db->where('termmeta_id', $id);
            $this->db->limit(1);
            return $this->db->get('termmeta');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------
    
    /**
     * Get by key
     *
     * Used to return the data from the termmeta key past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_key($key=NULL, $termid=NULL){
        if ($key != NULL):
            $this->db->where(array('termmeta_key' => $key, 'termmeta_termid' => $termid));
            $this->db->limit(1);
            return $this->db->get('termmeta');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------
    
    /**
     * Get by key and value
     *
     * Used to return the data from the termmeta key and value pasts.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_key_and_value($key=NULL, $value=NULL){
        if ($key != NULL):
            $this->db->where(array('termmeta_key' => $key, 'termmeta_value' => $value));
            return $this->db->get('termmeta');
        else:
            return FALSE;
        endif;
    }
}

/* End of file termmeta_model.php */
/* Location: ./include/models/termmeta_model.php */