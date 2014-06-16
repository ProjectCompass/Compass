<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Terms model
 *
 * Performs the interaction with the terms table bd
 * Terms are: categories, menus, tags ...
 *
 * @package     Compass
 * @subpackage  Terms
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       0.0.0
 */

class Terms_model extends CI_Model {

    /**
     * Constructor
     *
     */
    public function __construct(){
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * Insert Terms
     *
     * Used in controllers with forms of insertion of terms data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_insert($data=NULL, $redir=TRUE){
        if ($data != NULL):
            $this->db->insert('terms', $data);
            $insertid = mysql_insert_id();
            if ($this->db->affected_rows() > 0):
                audit('Registration of new term', 'Term "'.$data['term_name'].' / '.$data['term_type'].'" registered in the system', 'database');
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
     * Update Terms
     *
     * Used in controllers with forms of updation of term data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_update($data=NULL, $condition=NULL, $redir=TRUE){
        if ($data != NULL && is_array($condition)):
            $this->db->update('terms', $data, $condition);
            if ($this->db->affected_rows() > 0):
                audit('Edição de campo de infomrações de usuário', 'Alteração do campo "'.$data['userfield_name'].'"', 'database');
                set_msg('msgok', lang('model_update_sucess'), 'sucess');
            else:
                set_msg('msgerro', lang('model_update_error'), 'error');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Delete Terms
     *
     * Used in controllers with forms of delete of user data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_delete($condition=NULL, $redir=TRUE){
        if ($condition != NULL && is_array($condition)):
            $this->db->delete('terms', $condition);
            if ($this->db->affected_rows()>0):
                audit('Exclusion of terms', 'Term deleted from the system', 'database');
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
     * Used to return the data from the terms id past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_id($id=NULL){
        if ($id != NULL):
            $this->db->where('term_id', $id);
            $this->db->limit(1);
            return $this->db->get('terms');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by slug
     *
     * Used to return the data from the term slug past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_slug($slug=NULL){
        if ($slug != NULL):
            $this->db->where('term_slug', $slug);
            $this->db->limit(1);
            return $this->db->get('terms');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by type
     *
     * Used to return the data from the term type past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_type($type=NULL, $order='term_order', $orderby='asc'){
        if ($type != NULL):
            if ($order != NULL):
                $this->db->order_by($order, $orderby);
            endif;
            $this->db->where('term_type', $type);
            return $this->db->get_where('terms');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Return List terms
     *
     * Used to return a list of terms with various criteria of search filters.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function return_list($max_rows=NULL, $offset=NULL, $column=NULL, $order=NULL, $filter, $search){
        if($search!=NULL && $filter=='search'):
            $this->db->like('term_name', $search);
            $this->db->or_like('term_slug', $search);
            $this->db->or_like('term_description', $search);
            $this->db->or_like('term_posts', $search);
        endif;
        if($this->uri->segment(3)!='update'):
            $this->db->limit($max_rows, $offset);
        endif;
        $this->db->where('term_type', 'category');
        $this->db->order_by($column, $order);
        return $this->db->get_where('terms');
    }

    // --------------------------------------------------------------------

    /**
     * Get all terms
     *
     * Used to return all terms without criteria searches.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_all(){
        return $this->db->get('terms');
    }
}

/* End of file users_model.php */
/* Location: ./include/models/users_model.php */