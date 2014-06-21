<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Books model
 *
 * Performs the interaction with the books table bd
 *
 * @package     Compass
 * @subpackage  Books
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       0.1.0
 */

class Loans_model extends CI_Model {

    /**
     * Constructor
     *
     */
    public function __construct(){
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * Insert Loan
     *
     * Used in controllers with forms of insertion of book data.
     *
     * @access     private
     * @since      0.1.0
     * @modify     0.1.0
     */
    public function do_insert($data=NULL, $redir=TRUE){
        if ($data != NULL):
            //insertion of data
            $this->db->insert('loans', $data);
            //return the inserted id
            $insertid = mysql_insert_id();
            //audit of the operation in bd
            if ($this->db->affected_rows() > 0):
                audit('Loan book', 'Loan book id "'.$data['loan_idbook'].'" for user "'.$data['loan_iduser'].'"', 'database');
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
     * Update Loan
     *
     * Used in controllers with forms of updation of loan data.
     *
     * @access     private
     * @since      0.1.0
     * @modify     0.1.0
     */
    public function do_update($data=NULL, $condition=NULL, $redir=TRUE){
        if ($data != NULL && is_array($condition)):
            //update of data
            $this->db->update('loans', $data, $condition);
            //audit of the operation in bd
            if ($this->db->affected_rows() > 0):
                audit('Editing loan', 'Changing the loan registration "'.$data['loan_id'].'"', 'database');
                set_msg('msgok', lang('model_update_sucess'), 'sucess');
            else:
                set_msg('msgerro', lang('model_update_error'), 'error');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Delete Books
     *
     * Used in controllers with forms of delete of loan data.
     *
     * @access     private
     * @since      0.1.0
     * @modify     0.1.0
     */
    public function do_delete($condition=NULL, $redir=TRUE){
        if ($condition != NULL && is_array($condition)):
            //delete of data
            $this->db->delete('books', $condition);
            //audit of the operation in bd
            if ($this->db->affected_rows() > 0):
                audit('Deleting books', '', 'database');
                set_msg('msgok', lang('model_delete_sucess'), 'sucess');
            else:
                set_msg('msgerror', lang('model_delete_error'), 'error');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by id
     *
     * Used to return the data from the loan id past.
     *
     * @access     private
     * @since      0.1.0
     * @modify     0.1.0
     */
    public function get_by_id($id=NULL){
        if ($id != NULL):
            //search on bd
            $this->db->where('loan_id', $id);
            //sets a limit line
            $this->db->limit(1);
            //return search on bd
            return $this->db->get_where('loans');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by book_id
     *
     * Used to return the data from the loan id past.
     *
     * @access     private
     * @since      0.1.0
     * @modify     0.1.0
     */
    public function get_by_book_id($book_id=NULL){
        if ($book_id != NULL):
            $this->db->where(array('loan_book_id' => $book_id, 'loan_status' => 1));
            return $this->db->get('loans');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by user_id
     *
     * Used to return the data from the loan id past.
     *
     * @access     private
     * @since      0.1.0
     * @modify     0.1.0
     */
    public function get_by_user_id($user_id=NULL){
        if ($user_id != NULL):
            $this->db->where(array('loan_user_id' => $user_id, 'loan_status' => 1));
            return $this->db->get('loans');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Return List books
     *
     * Used to return a list of books with various criteria of search filters.
     *
     * @access     private
     * @since      0.1.0
     * @modify     0.1.0
     */
    public function return_list($max_rows=NULL, $start=NULL, $column=NULL, $order=NULL, $filter, $search){
        //search on bd
        $this->db->order_by($column, $order);
        $this->db->limit($max_rows, $start);
        
        //return search on bd
        return $this->db->get_where('loans');
    }

    // --------------------------------------------------------------------

    /**
     * Get all books
     *
     * Used to return all books without criteria searches.
     *
     * @access     private
     * @since      0.1.0
     * @modify     0.1.0
     */
    public function get_all(){
        return $this->db->get_where('books');
    }
}

/* End of file books_model.php */
/* Location: ./include/models/books_model.php */