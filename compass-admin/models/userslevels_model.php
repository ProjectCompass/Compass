<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Userslevels model
 *
 * Performs the interaction with the userslevels table bd
 *
 * @package     Compass
 * @subpackage  Users
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       0.0.0
 */

class Userslevels_model extends CI_Model {

    /**
     * Constructor
     *
     */
    public function __construct(){
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * Insert Userlevel
     *
     * Used in controllers with forms of insertion of usermeta data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_insert($data=NULL, $redir=TRUE){
        if ($data != NULL):
            $this->db->insert('userslevels', $data);
            if ($this->db->affected_rows()>0):
                audit('Sign-level users', '"'.$data['userlevel_name'].'" registered in the system', 'database');
                set_msg('msgok', lang('model_insert_sucess'), 'sucess');
            else:
                set_msg('msgerror', lang('model_insert_error'), 'error');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Update Userlevel
     *
     * Used in controllers with forms of updation of userlevel data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_update($data=NULL, $condition=NULL, $redir=TRUE){
        if ($data != NULL && is_array($condition)):
            $this->db->update('userslevels', $data, $condition);
            if ($this->db->affected_rows()>0):
                audit('Editing user-level', 'Edition User Level "'.$data['userlevel_name'].'"', 'database');
                set_msg('msgok', lang('model_update_sucess'), 'sucess');
            else:
                set_msg('msgerro', lang('model_update_error'), 'error');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Delete Userlevel
     *
     * Used in controllers with forms of delete of userlevel data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_delete($condition=NULL, $redir=TRUE){
        if ($condition != NULL && is_array($condition)):
            $this->db->delete('userslevels', $condition);
            if ($this->db->affected_rows()>0):
                audit('Exclusion of field-level users', 'Deleted the user assignment', 'database');
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
     * Used to return the data from the userslevels id past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_id($id=NULL){
        if ($id != NULL):
            $this->db->where('userlevel_id', $id);
            $this->db->limit(1);
            return $this->db->get('userslevels');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by name
     *
     * Used to return the data from the userslevels name past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_name($name=NULL){
        if ($name != NULL):
            $this->db->where('userlevel_name', $name);
            $this->db->limit(1);
            return $this->db->get('userslevels');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get all userslevels
     *
     * Used to return all userslevels without criteria searches.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_all(){
        $this->db->order_by('userlevel_level', 'asc');
        return $this->db->get_where('userslevels');
    }
}

/* End of file userslevels_model.php */
/* Location: ./include/models/userslevels_model.php */