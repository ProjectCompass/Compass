<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Postmeta model
 *
 * Performs the interaction with the postmeta table bd
 *
 * @package     Compass
 * @subpackage  Posts
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       1.0.0
 */

class Postmeta_model extends CI_Model {

    /**
     * Constructor
     *
     */
    public function __construct(){
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * Insert Postmetas
     *
     * Used in controllers with forms of insertion of postmeta data.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function do_insert($data=NULL, $redir=TRUE){
        if ($data != NULL):
            $this->db->insert('postmeta', $data);
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
     * Update Postmetas
     *
     * Used in controllers with forms of updation of postmeta data.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function do_update($data=NULL, $condition=NULL, $redir=TRUE){
        if ($data != NULL && is_array($condition)):
            $this->db->update('postmeta', $data, $condition);
            if ($this->db->affected_rows()>0):
                set_msg('msgok',  lang('model_update_sucess'), 'sucess');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }
    
    // --------------------------------------------------------------------

    /**
     * Delete Postmetas
     *
     * Used in controllers with forms of delete of postmeta data.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function do_delete($condition=NULL, $redir=TRUE){
        if ($condition != NULL && is_array($condition)):
            $this->db->delete('postmeta', $condition);
            if ($this->db->affected_rows()>0):
                set_msg('msgok', lang('model_delete_sucess'), 'sucess');
            else:
                set_msg('msgerror', lang('model_delete_error'), 'error');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by key
     *
     * Used to return the data from the postmeta key past.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function get_by_key($key=NULL, $postid=NULL){
        if ($key != NULL):
            $this->db->where(array('postmeta_key' => $key, 'postmeta_postid' => $postid));
            return $this->db->get('postmeta');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by value
     *
     * Used to return the data from the postmeta value past.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function get_by_value($value=NULL, $userid=NULL){
        if ($value != NULL):
            $this->db->where(array('postmeta_value' => $value, 'postmeta_postid' => $postid));
            $this->db->limit(1);
            return $this->db->get('postmeta');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by postid
     *
     * Used to return the data from the postmeta postid past.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function get_by_postid($postid=NULL){
        if ($postid != NULL):
            $this->db->where(array('postmeta_postid' => $postid));
            return $this->db->get_where('postmeta');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by key and value
     *
     * Used to return the data from the postmeta key and value past.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function get_by_key_and_value($key=NULL, $value=NULL){
        if ($key != NULL):
            $this->db->where(array('postmeta_key' => $key, 'postmeta_value' => $value));
            return $this->db->get('postmeta');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by key, value and postid
     *
     * Used to return the data from the postmeta key, value and postid past.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function get_by_key_and_value_and_postid($key=NULL, $value=NULL, $postid=NULL){
        if ($key != NULL):
            $this->db->where(array('postmeta_key' => $key, 'postmeta_value' => $value, 'postmeta_postid' => $postid));
            return $this->db->get('postmeta');
        else:
            return FALSE;
        endif;
    }
}

/* End of file postmeta_model.php */
/* Location: ./include/models/postmeta_model.php */