<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Comments model
 *
 * Performs the interaction with the comments table bd
 *
 * @package     Compass
 * @subpackage  Posts
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       1.0.0
 */

class Comments_model extends CI_Model {

    /**
     * Constructor
     *
     */
    public function __construct(){
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * Insert Comments
     *
     * Used in controllers with forms of insertion of comment data.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function do_insert($data=NULL, $redir=TRUE){
        if ($data != NULL):
            $this->db->insert('comments', $data);
            $insertid = mysql_insert_id();
            if ($this->db->affected_rows()>0):
                audit('new comment', 'Reader comment "'.$data['comment_author'].'" registered in the system', 'database');
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
     * Update Comments
     *
     * Used in controllers with forms of updation of comment data.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function do_update($data=NULL, $condition=NULL, $redir=TRUE){
        if ($data != NULL && is_array($condition)):
            $this->db->update('comments', $data, $condition);
            if ($this->db->affected_rows()>0):
                audit('Editing comment', 'Amendment Review "'.$data['comment_content'].'"', 'database');
                set_msg('msgok', lang('model_update_sucess'), 'sucess');
            else:
                set_msg('msgerro', lang('model_update_error'), 'error');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Delete Comments
     *
     * Used in controllers with forms of delete of comment data.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function do_delete($condition=NULL, $redir=TRUE){
        if ($condition != NULL && is_array($condition)):
            $this->db->delete('comments', $condition);
            if ($this->db->affected_rows()>0):
                audit('Deleting comments', 'Deleted comment', 'database');
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
     * Used to return the data from the comment id past.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function get_by_id($id=NULL){
        if ($id != NULL):
            $this->db->where('comment_id', $id);
            $this->db->limit(1);
            return $this->db->get('comments');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by postid
     *
     * Used to return the data from the comment postid past.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function get_by_postid($postid=NULL){
        if ($postid != NULL):
            $this->db->select('*');
            $this->db->from('comments');
            $this->db->where('comment_postid', $postid);
            $this->db->where('comment_status', 'approved');
            $this->db->order_by('comment_date', 'asc');
            return $this->db->get_where();
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by type
     *
     * Used to return the data from the comment type past.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function get_by_type($type=NULL, $order=NULL){
        if ($type != NULL):
            if ($order == NULL):
                $this->db->order_by('comment_type', 'asc');
            else:
                $this->db->order_by($order, 'asc');
            endif;
            $this->db->where('comment_type', $type);
            return $this->db->get_where('comments');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by status
     *
     * Used to return the data from the comment status past.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function get_by_status($status=NULL){
        if ($status != NULL):
            $this->db->where('comment_status', $status);
            return $this->db->get_where('comments');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Return List comments
     *
     * Used to return a list of comments with various criteria of search filters.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function return_list($max_rows=NULL, $offset=NULL, $column=NULL, $order=NULL, $filter, $search){
        if ($search!=NULL && $filter=='filter_status'):
            $this->db->where('comment_status', $search);
        elseif($search!=NULL && $filter=='search'):
            $this->db->like('comment_author', $search);
            $this->db->or_like('comment_authoremail', $search);
            $this->db->or_like('comment_authorurl', $search);
            $this->db->or_like('comment_content', $search);
        endif;
        if($this->uri->segment(3)!='update'):
            $this->db->limit($max_rows, $offset);
        endif;
        $this->db->order_by($column, $order);
        return $this->db->get_where('comments');
    }

    // --------------------------------------------------------------------

    /**
     * Get all comments
     *
     * Used to return all comments without criteria searches.
     *
     * @access     private
     * @since      1.0.0
     * @modify     1.0.0
     */
    public function get_all(){
        return $this->db->get('comments');
    }
}

/* End of file users_model.php */
/* Location: ./include/models/users_model.php */