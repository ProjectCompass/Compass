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

class Books_model extends CI_Model {

    /**
     * Constructor
     *
     */
    public function __construct(){
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * Insert Books
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
            $this->db->insert('books', $data);
            //return the inserted id
            $insertid = mysql_insert_id();
            //audit of the operation in bd
            if ($this->db->affected_rows() > 0):
                audit('Book Registration', 'Book "'.$data['book_id'].'" registered in the system', 'database');
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
     * Update Books
     *
     * Used in controllers with forms of updation of book data.
     *
     * @access     private
     * @since      0.1.0
     * @modify     0.1.0
     */
    public function do_update($data=NULL, $condition=NULL, $redir=TRUE){
        if ($data != NULL && is_array($condition)):
            //update of data
            $this->db->update('books', $data, $condition);
            //audit of the operation in bd
            if ($this->db->affected_rows() > 0):
                audit('Editing book', 'Changing the book registration "'.$data['book_id'].'"', 'database');
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
     * Used in controllers with forms of delete of book data.
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
     * Upload Cover
     *
     * Performs upload cover.
     *
     * @access     private
     * @since      0.1.0
     * @modify     0.1.0
     */
    public function do_upload($field){
        $config['upload_path'] = './compass-content/uploads/medias/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '2000';
        $this->load->library('upload', $config);
        if ($this->upload->do_upload($field)):
            return $this->upload->data();
        else:
            return $this->upload->display_errors();
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by id
     *
     * Used to return the data from the book id past.
     *
     * @access     private
     * @since      0.1.0
     * @modify     0.1.0
     */
    public function get_by_id($id=NULL){
        if ($id != NULL):
            //search on bd
            $this->db->where('book_id', $id);
            //sets a limit line
            $this->db->limit(1);
            //return search on bd
            return $this->db->get_where('books');
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
        //apply search filter or book
        if ($search != NULL && $filter == 'filter_tag'):
            $this->db->like('book_tags', str_replace('-', ' ', $search));
        elseif ($search != NULL && $filter == 'search'):
            $this->db->like('book_register', $search);
            $this->db->or_like('book_title', $search);
            $this->db->or_like('book_author', $search);
            $this->db->or_like('book_keywords', $search);
        endif;
        //search on bd
        $this->db->order_by($column, $order);
        $this->db->limit($max_rows, $start);
        //return search on bd
        return $this->db->get_where('books');
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