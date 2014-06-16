<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Posts model
 *
 * Performs the interaction with the posts table bd
 *
 * @package     Compass
 * @subpackage  Posts
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       0.0.0
 */

class Posts_model extends CI_Model {

    /**
     * Constructor
     *
     */
    public function __construct(){
        parent::__construct();
    }

    // --------------------------------------------------------------------

    /**
     * Insert Posts
     *
     * Used in controllers with forms of insertion of post data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_insert($data=NULL, $redir=TRUE){
        if ($data != NULL):
            $this->db->insert('posts', $data);
            $insertid = mysql_insert_id();
            if ($this->db->affected_rows() > 0):
                audit('Registration a new post', 'Content registered in the system with the title"'.$data['post_title'].'". ', 'database');
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
     * Update Posts
     *
     * Used in controllers with forms of updation of post data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_update($data=NULL, $condition=NULL, $redir=TRUE, $audit=TRUE){
        if ($data != NULL && is_array($condition)):
            $this->db->update('posts', $data, $condition);
            if ($this->db->affected_rows()>0):
                if ($audit == TRUE):
                    audit('Editing post', 'Content with the title "'.$data['post_title'].'" had changed his registration in the system', 'database');
                    set_msg('msgok',  lang('model_update_sucess'), 'sucess');
                endif;
            else:
                set_msg('msgerro', lang('model_update_error'), 'error');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Delete Posts
     *
     * Used in controllers with forms of delete of post data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_delete($condition=NULL, $redir=TRUE){
        if ($condition != NULL && is_array($condition)):
            $this->db->delete('posts', $condition);
            if ($this->db->affected_rows()>0):
                audit('Deleting Content', 'Excluding the register content', 'database');
                set_msg('msgok', lang('model_delete_sucess'), 'sucess');
            else:
                set_msg('msgerro', lang('model_delete_error'), 'error');
            endif;
            if ($redir) redirect(current_url());
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Upload Medias
     *
     * Performs upload media.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_upload($field){
        $config['upload_path'] = './compass-content/uploads/medias/';
        $config['allowed_types'] = 'gif|jpg|png|pdf|doc|txt|zip';
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
     * Update Stat Post
     *
     * Used in controllers with forms of updation of stat post data.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function do_update_stat($data=NULL, $condition=NULL){
        if ($data != NULL && is_array($condition)):
            $this->db->update('posts', $data, $condition);
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by id
     *
     * Used to return the data from the post id past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_id($id=NULL){
        if ($id != NULL):
            $this->db->where('post_id', $id);
            $this->db->limit(1);
            return $this->db->get_where('posts');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by type
     *
     * Used to return the data from the post type past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_type($post_type=NULL){
        if ($post_type != NULL):
            $this->db->where('post_type', $post_type);
            return $this->db->get_where('posts');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by author
     *
     * Used to return the data from the post author past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_author($post_author=NULL){
        if ($post_author != NULL):
            $this->db->where('post_author', $post_author);
            return $this->db->get_where('posts');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by status
     *
     * Used to return the data from the post status past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_status($status=NULL, $post_type=NULL){
        if ($status != NULL):
            $this->db->where('post_status', $status);
            $this->db->where('post_type', $post_type);
            return $this->db->get_where('posts');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by visible
     *
     * Used to return the data from the post visible past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_visible($visible=NULL, $post_type=NULL){
        if ($visible != NULL):
            $this->db->where('post_visible', $visible);
            $this->db->where('post_type', $post_type);
            return $this->db->get_where('posts');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Get by slug
     *
     * Used to return the data from the post slug past.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_by_slug($slug=NULL){
        if ($slug != NULL):
            $this->db->where('post_slug', $slug);
            return $this->db->get_where('posts');
        else:
            return FALSE;
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Return List posts
     *
     * Used to return a list of posts with various criteria of search filters.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function return_list($max_rows=NULL, $offset=NULL, $column=NULL, $order=NULL, $filter=NULL, $search=NULL){
        if ($search!=NULL && $filter=='filter_status'):
            $this->db->where('post_status', $search);
        elseif ($search!=NULL && $filter=='filter_visible'):
            $this->db->where('post_visible', $search);
        elseif($search!=NULL && $filter=='search'):
            $this->db->like('post_title', $search);
            $this->db->or_like('post_date', $search);
            $this->db->or_like('post_comment_count', $search);
        endif;
        if ($filter != 'filter_tag'):
            $this->db->order_by($column, $order);
        endif;
        $this->db->where('post_type', 'post');
        $this->db->limit($max_rows, $offset);
        return $this->db->get_where('posts');
    }

    // --------------------------------------------------------------------

    /**
     * Return List pages
     *
     * Used to return a list of pages with various criteria of search filters.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function return_list_pages($max_rows=NULL, $offset=NULL, $column=NULL, $order=NULL, $filter, $search){
        if ($search!=NULL && $filter=='filter_status'):
            $this->db->where('post_status', $search);
        elseif ($search!=NULL && $filter=='filter_visible'):
            $this->db->where('post_visible', $search);
        elseif($search!=NULL && $filter=='search'):
            $this->db->like('post_title', $search);
            $this->db->or_like('post_date', $search);
            $this->db->or_like('post_comment_count', $search);
        endif;
        $this->db->where('post_type', 'page');
        $this->db->order_by($column, $order);
        $this->db->limit($max_rows, $offset);
        return $this->db->get_where('posts');
    }

    // --------------------------------------------------------------------

    /**
     * Return List medias
     *
     * Used to return a list of medias with various criteria of search filters.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function return_list_medias($max_rows=NULL, $offset=NULL, $column=NULL, $order=NULL, $filter, $search){
        if($search!=NULL && $filter=='search'):
            $this->db->like('post_title', $search);
            $this->db->or_like('post_slug', $search);
            $this->db->or_like('post_excerpt', $search);
        endif;
        if($this->uri->segment(3)!='update' && $this->uri->segment(3)!='saved'):
            $this->db->limit($max_rows, $offset);
        endif;
        $this->db->where('post_type', 'media');
        $this->db->where('post_type !=', 'post');
        $this->db->order_by($column, $order);
        return $this->db->get_where('posts');
    }

    // --------------------------------------------------------------------

    /**
     * Get all posts
     *
     * Used to return all psts without criteria searches.
     *
     * @access     private
     * @since      0.0.0
     * @modify     0.0.0
     */
    public function get_all(){
        return $this->db->get('posts');
    }
}

/* End of file users_model.php */
/* Location: ./include/models/users_model.php */