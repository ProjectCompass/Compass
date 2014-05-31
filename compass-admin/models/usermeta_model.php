<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Usermeta model
 *
 * Performs the interaction with the usermeta table bd
 *
 * @package     Compass
 * @subpackage  Users
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       1.0.0
 */

class Usermeta_model extends CI_Model {

    /**
     * Constructor
     *
     */
    public function __construct(){
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * Insert Usermeta
     *
     * Used in controllers with forms of insertion of usermeta data.
     *
     * @access     private (public to sign the login controller)
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function do_insert($data=NULL, $redir=TRUE){
        if ($data != NULL):
            $this->db->insert('usermeta', $data);
            if ($this->db->affected_rows()>0):
                set_msg('msgok',lang('model_insert_sucess'), 'sucess');
            else:
                set_msg('msgerror', lang('model_insert_error'), 'error');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }
    
    // --------------------------------------------------------------------

    /**
     * Update Usermeta
     *
     * Used in controllers with forms of updation of usermeta data.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function do_update($data=NULL, $condition=NULL, $redir=TRUE){
        if ($data != NULL && is_array($condition)):
            $this->db->update('usermeta', $data, $condition);
            if ($this->db->affected_rows() > 0):
                set_msg('msgok', lang('model_update_sucess'), 'sucess');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }
    
    // --------------------------------------------------------------------

    /**
     * Delete Usermeta
     *
     * Used in controllers with forms of delete of usermeta data.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function do_delete($condition=NULL, $redir=TRUE){
        if ($condition != NULL && is_array($condition)):
            $this->db->delete('usermeta', $condition);
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
     * Upload images
     *
     * Performs upload images of the profiles (avatars) of users.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function do_upload($field){
        //configures and loads the library upload
        $config['upload_path'] = './compass-content/uploads/avatars/';
        $config['allowed_types'] = 'gif|jpg|png';
        $this->load->library('upload', $config);
        //returns the image uploaded
        if ($this->upload->do_upload($field)):
            return $this->upload->data();
        else:
            return $this->upload->display_errors();
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by key
     *
     * Used to return the data from the usermeta key past.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function get_by_key($key=NULL, $userid=NULL){
        if ($key != NULL):
            $this->db->where(array('usermeta_key' => $key, 'usermeta_userid' => $userid));
            $this->db->limit(1);
            return $this->db->get('usermeta');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by value
     *
     * Used to return the data from the usermeta key past.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function get_by_value($value=NULL){
        if ($value != NULL):
            $this->db->where(array('usermeta_value' => $value));
            $this->db->limit(1);
            return $this->db->get('usermeta');
        else:
            return FALSE;
        endif;
    }
}

/* End of file usermeta_model.php */
/* Location: ./include/models/usermeta_model.php */