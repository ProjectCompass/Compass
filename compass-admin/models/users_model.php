<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Users model
 *
 * Performs the interaction with the users table bd
 *
 * @package     Compass
 * @subpackage  Users
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       0.0.0
 */
class Users_model extends CI_Model {
    /**
     * Constructor
     */
    public function __construct(){
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * Insert Users
     *
     * Used in controllers with forms of insertion of user data.
     *
     * @access     private (public to sign the login controller)
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_insert($data=NULL, $redir=TRUE){
        if ($data != NULL):
            //insertion of data
            $this->db->insert('users', $data);
            //return the inserted id
            $insertid = mysql_insert_id();
            //audit of the operation in bd
            if ($this->db->affected_rows() > 0):
                audit('User Registration', 'User "'.$data['user_id'].'" registered in the system', 'database');
                set_msg('msgok', lang('model_insert_sucess'), 'sucess');
            else:
                set_msg('msgerror', lang('model_insert_error'), 'error');
            endif;
            if ($redir):
                redirect(current_url());
            else:
                return $insertid;
            endif;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Update Users
     *
     * Used in controllers with forms of updation of user data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_update($data=NULL, $condition=NULL, $redir=TRUE){
        if ($data != NULL && is_array($condition)):
            //update of data
            $this->db->update('users', $data, $condition);
            //audit of the operation in bd
            if ($this->db->affected_rows() > 0):
                audit('Editing user', 'Changing the user registration "'.$data['user_id'].'"', 'database');
                set_msg('msgok', lang('model_update_sucess'), 'sucess');
            else:
                set_msg('msgerro', lang('model_update_error'), 'error');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Delete Users
     *
     * Used in controllers with forms of delete of user data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_delete($condition=NULL, $redir=TRUE){
        if ($condition != NULL && is_array($condition)):
            //delete of data
            $user = $this->users->get_byid($condition['user_id'])->row()->user_email;
            $this->db->delete('users', $condition);
            //audit of the operation in bd
            if ($this->db->affected_rows() > 0):
                audit('Deleting Users', '', 'database');
                set_msg('msgok', lang('model_delete_sucess'), 'sucess');
            else:
                set_msg('msgerror', lang('model_delete_error'), 'error');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Sign in
     *
     * Used of checks if the username and password exist in bd.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_login($login=NULL, $password=NULL){
        if ($login && $password):
            //checks has passed the email address or username
            if(stripos($login, '@')):
                $this->db->where('user_email', $login);
            else:
                $this->db->where('user_username', $login);
            endif;
            //checks for username and password in bd
            $this->db->where('user_pass', $password);
            $this->db->where('user_status', 1);
            $query = $this->db->get('users');
            if ($query->num_rows == 1):
                return TRUE;
            else:
                return FALSE;
            endif;
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by id
     *
     * Used to return the data from the user id past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_id($id=NULL){
        if ($id != NULL):
            //search on bd
            $this->db->where('user_id', $id);
            //no more users with status 9 (deleted)
            $this->db->where('user_status !=', 9);
            //sets a limit line
            $this->db->limit(1);
            //return search on bd
            return $this->db->get_where('users');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by login
     *
     * Used to return the data from the user login (username or email) past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_login($login=NULL){
        if ($login != NULL):
            //checks has passed the email address or username
            if(stripos($login, '@')):
                $this->db->where('user_email', $login);
            else:
                $this->db->where('user_username', $login);
            endif;
            //sets a limit line
            $this->db->limit(1);
            //return search on bd
            return $this->db->get('users');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by email
     *
     * Used to return the data from the user email past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_email($email=NULL){
        if ($email != NULL):
            //search on bd
            $this->db->where('user_email', $email);
            //sets a limit line
            $this->db->limit(1);
            //return search on bd
            return $this->db->get('users');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Return List users
     *
     * Used to return a list of users with various criteria of search filters.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function return_list($max_rows=NULL, $start=NULL, $column=NULL, $order=NULL, $filter, $search){
        //apply search filter or user level
        if ($search != NULL && $filter == 'filter_level'):
            $this->db->where('user_level', $search);
        elseif($search != NULL && $filter == 'search'):
            $this->db->like('user_username', $search);
            $this->db->or_like('user_name', $search);
            $this->db->or_like('user_email', $search);
        endif;
        //search on bd
        $this->db->where('user_status !=', 9);
        $this->db->order_by($column, $order);
        $this->db->limit($max_rows, $start);
        //return search on bd
        return $this->db->get_where('users');
    }

    // --------------------------------------------------------------------

    /**
     * Get all users
     *
     * Used to return all users without criteria searches.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_all(){
        $this->db->where('user_status !=', 9);
        return $this->db->get_where('users');
    }


}