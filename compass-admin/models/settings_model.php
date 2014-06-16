<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Settings model
 *
 * Performs the interaction with the settings table bd
 *
 * @package     Compass
 * @subpackage  Users
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       0.0.0
 */

class Settings_model extends CI_Model {

	/**
     * Constructor
     *
     */
    public function __construct(){
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * Insert Setting
     *
     * Used in controllers with forms of insertion of setting data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
	public function do_insert($data=NULL, $redir=TRUE){
		if ($data != NULL):
			$this->db->insert('settings', $data);
			if ($this->db->affected_rows() > 0):
				audit('Insersão de configurações', 'O usuário realizou alterações nas configurações do sistema', 'database');
				set_msg('msgok', lang('model_insert_sucess'), 'sucess');
			else:
				set_msg('msgerror', lang('model_insert_error'), 'error');
			endif;
			if ($redir) redirect(current_url());
		endif;
	}
	
	// --------------------------------------------------------------------

    /**
     * Update Settings
     *
     * Used in controllers with forms of updation of setting data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
	public function do_update($data=NULL, $condition=NULL, $redir=TRUE){
		if ($data != NULL && is_array($condition)):
			$this->db->update('settings', $data, $condition);
			if ($this->db->affected_rows() > 0):
				audit('Change settings', 'The user made ​​changes to the system settings', 'database');
				set_msg('msgok', lang('model_update_sucess'), 'sucess');
			endif;
			if ($redir) redirect(current_url());
		endif;
	}
	
	// --------------------------------------------------------------------

    /**
     * Delete Settings
     *
     * Used in controllers with forms of delete of setting data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
	public function do_delete($condition=NULL, $redir=TRUE){
		if ($condition != NULL && is_array($condition)):
			$this->db->delete('settings', $condition);
			if ($this->db->affected_rows() > 0):
				set_msg('msgok', lang('model_delete_sucess'), 'sucess');
			else:
				set_msg('msgerror', lang('model_delete_error'), 'error');
			endif;
			if ($redir) redirect(current_url());
		endif;
	}

	// --------------------------------------------------------------------

    /**
     * Get by name
     *
     * Used to return the data from the setting name past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
	public function get_by_name($name=NULL){
		if ($name != NULL):
			$this->db->where('setting_name', $name);
			$this->db->limit(1);
			return $this->db->get('settings');
		else:
			return FALSE;
		endif;
	}	
}

/* End of file settings_model.php */
/* Location: ./application/models/settings_model.php */