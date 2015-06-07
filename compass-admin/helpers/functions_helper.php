<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Functions functions
 *
 * Helper functions for operations with the system
 *
 * @package     Compass
 * @subpackage  Core
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       0.0.0
 */

// ------------------------------------------------------------------------

 /**
 * Lang
 *
 * Returns a string language.
 * 
 * @access  private
 * @param   no
 * @return  string
 * @since   0.0.0
 * @modify  0.1.0
 */
function lang($key=NULL){
    $CI =& get_instance();
    $CI->load->helper('directory');
    if (get_usermeta('user_language', get_session('user_id')) != NULL):
        $language = get_usermeta('user_language', get_session('user_id'));
    else:
        if (get_session('system_language') == NULL):
            $language = get_setting('general_language');
        else:
            $language = get_session('system_language');
        endif;
    endif;
    //load language the core
    $CI->lang->load('core', $language);
    //the function lang
    if ($key != NULL && $key != FALSE):
        if ($CI->lang->line($key) != NULL):
            return $CI->lang->line($key);
        else:
            return '<strong>ERROR: LANGUAGE STRING "'.$key.'" NOT FOUND IN THE LANG "'.$language.'"</strong>';
        endif;
    elseif ($key == FALSE):
        return $language;
    else:
        return NULL;
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Errors Validating
 *
 * Displays validation errors in forms.
 * 
 * @access  private
 * @param   no
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function errors_validating(){
    if (validation_errors()):
        echo '<div data-alert class="alert-box alert">'.validation_errors('<div>', '</div>').'<a href="#" class="close">&times;</a></div>';
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Be Logged
 *
 * Checks if the User is logged into the system.
 * 
 * @access  private
 * @param   bool
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function be_logged($redir=TRUE){
    $CI =& get_instance();
    $CI->load->helper('url');
    $user_status = get_session('user_logged');
    if (!isset($user_status) || $user_status != TRUE):
        if ($redir):
            $CI->session->set_userdata(array('redir_for'=>current_url()));
            set_msg('errorlogin', lang('functions_be_logged_error'), 'error');
            redirect('login');
        else:
            return FALSE;
        endif;
    else:
        return TRUE;
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Set MSG
 *
 * Sets a message to be displayed on the next screen loaded.
 * 
 * @access  private
 * @param   string string string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function set_msg($id='msgerror', $msg=NULL, $type='error'){
    $CI =& get_instance();
    switch ($type):
        case 'error':
            $CI->session->set_flashdata($id, '<div data-alert class="alert-box alert">'.$msg.'<a href="#" class="close">&times;</a></div>');
            break;
        case 'sucess':
            $CI->session->set_flashdata($id, '<div data-alert class="alert-box success">'.$msg.'<a href="#" class="close">&times;</a></div>');
            break;
        default:
            $CI->session->set_flashdata($id, '<div data-alert class="alert-box">'.$msg.'<a href="#" class="close">&times;</a></div>');
            break;
    endswitch;
}

// ------------------------------------------------------------------------

 /**
 * Get MSG
 *
 * Checks for a message to be displayed on screen and displays tual.
 * 
 * @access  private
 * @param   string string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_msg($id, $printar=TRUE){
    $CI =& get_instance();
    if ($CI->session->flashdata($id)):
        if($printar):
            echo $CI->session->flashdata($id);
            return TRUE;
        else:
            return $CI->session->flashdata($id);
        endif;
    endif;
    return FALSE;
}

// ------------------------------------------------------------------------

 /**
 * Get Access
 *
 * Gives permission or not user access to specific module.
 * 
 * @access  private
 * @param   string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_access($permission=NULL){
    $CI =& get_instance();
    $CI->load->model('userslevels_model', 'userslevels');
    $user_logged = get_session('user_logged');
    $user_level = get_session('user_level');
    $access = FALSE;
    if ($user_logged != NULL && $permission != NULL):
        if (get_setting($permission.'1') == 1 && $user_level == 1 && get_setting('perm_ative_1') == 1):
            $access = TRUE;
        endif;
        $query = $CI->userslevels->get_all()->result();
        foreach ($query as $line):
            if (get_setting($permission.$line->userlevel_id) == 1 && $user_level == $line->userlevel_id && get_setting('perm_ative_'.$line->userlevel_id) == 1):
                $access = TRUE;
            endif;
        endforeach;
        if ($access == TRUE):
            return TRUE;
        else:
            return FALSE;
        endif;
    else:
        return FALSE;
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Access
 *
 * Returns the 1-simple access (yes or no), 
 * 2-average (only those who have permission can access),
 * 3-compound (who is allowed and if the level of the user to be changed is smaller than yours or if he ).
 * 
 * @access  private
 * @param   string string bool string string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
 function access($permission=NULL, $type=NULL, $return=FALSE, $level_url=NULL, $username_url=NULL){
    $CI =& get_instance();
    $CI->load->model('users_model', 'users');
    if ($type==NULL):
        if (get_access($permission) == FALSE):
            if ($return == FALSE): 
                redirect('stop'); 
            else: 
                return FALSE; 
            endif;
        else:
            if ($return == FALSE): 
                return NULL; 
            else: 
                return TRUE; 
            endif;
        endif;
    elseif ($type!=NULL && $type=='middle'):
        if (get_session('user_id') != $CI->uri->segment(3) && get_access($permission) == FALSE):
            if ($return == FALSE): 
                redirect('stop'); 
            else: 
                return FALSE; 
            endif;
        else:
            if ($return==FALSE):
                return NULL; 
            else: 
                return TRUE; 
            endif;
        endif;
    elseif ($type != NULL && $type == 'high'):
        $level_logado = get_session('user_level');
        $username_logado = get_session('user_username');
        if ($level_logado != 1):
            if ($level_url < $level_logado):
                if ($return==FALSE): 
                    redirect('stop'); 
                else: 
                    return FALSE; 
                endif;
            elseif ($username_url != $username_logado && $level_url == $level_logado):
                if ($return==FALSE): 
                    redirect('stop'); 
                else: 
                    return FALSE; 
                endif;
            elseif ($level_url > $level_logado && get_access($permission) == FALSE):
                if ($return == FALSE): 
                    redirect('stop'); 
                else: 
                    return FALSE; 
                endif;
            else:
                if ($return == FALSE): 
                    return NULL; 
                else: 
                    return TRUE; 
                endif;
            endif;
        else:
            if ($return == FALSE): 
                return NULL; 
            else: 
                return TRUE; 
            endif;
        endif;
    else:
        if ($return == FALSE): 
            return NULL; 
        else: 
            return TRUE; 
        endif;
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Thumb
 *
 * Generates a thumbnail of an image if it does not exist in compas-contents/uploads/medias folder.
 * 
 * @access  private
 * @param   string integer integer bool
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function thumb($image=NULL, $width=100, $height=75, $geratag=TRUE){
    $CI =& get_instance();
    $CI->load->helper('file');
    $thumb = $height.'x'.$width.'_'.$image;
    $thumbinfo = get_file_info('./compass-content/uploads/medias/thumbs/'.$thumb);
    if ($thumbinfo!=FALSE):
        $return = base_url('compass-content/uploads/medias/thumbs/'.$thumb);
    else:
        $CI->load->library('image_lib');
        $config['image_library'] = 'gd2';
        $config['source_image'] = './compass-content/uploads/medias/'.$image;
        $config['new_image'] = './compass-content/uploads/medias/thumbs/'.$thumb;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $width;
        $config['height'] = $height;
        $CI->image_lib->initialize($config);
        if ($CI->image_lib->resize()):
            $CI->image_lib->clear();
            $return = base_url('compass-content/uploads/medias/thumbs/'.$thumb);
        else:
            $return = FALSE;
        endif;
    endif;
    if ($geratag && $return != FALSE) $return = '<img src="'.$return.'" alt="" />';
    return $return;
}

// ------------------------------------------------------------------------

 /**
 * Thumb
 *
 * Generates a thumbnail of an image if it does not exist in compas-contents/uploads/avatars folder.
 * 
 * @access  private
 * @param   string integer integer bool
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function avatar($image=NULL, $width=100, $height=75, $geratag=TRUE){
    $CI =& get_instance();
    $CI->load->helper('file');
    $thumb = $height.'x'.$width.'_'.$image;
    $thumbinfo = get_file_info('./compass-content/uploads/avatars/thumbs/'.$thumb);
    if ($image != NULL):
        if ($thumbinfo!=FALSE):
            $return = base_url('compass-content/uploads/avatars/thumbs/'.$thumb);
        else:
            $CI->load->library('image_lib');
            $config['image_library'] = 'gd2';
            $config['source_image'] = './compass-content/uploads/avatars/'.$image;
            $config['new_image'] = './compass-content/uploads/avatars/thumbs/'.$thumb;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = $width;
            $config['height'] = $height;
            $CI->image_lib->initialize($config);
            if ($CI->image_lib->resize()):
                $CI->image_lib->clear();
                $return = base_url('compass-content/uploads/avatars/thumbs/'.$thumb);
            else:
                $return = FALSE;
            endif;
        endif;
        if ($geratag && $return != FALSE):
            $return = '<img src="'.$return.'" alt="" />';
        endif;
    else:
        $return = '<img src="'.base_url().'compass-content/includes/images/avatar.png" alt="" width="'.$width.'" height="'.$height.'" />';
    endif;
    return $return;
}

// ------------------------------------------------------------------------

 /**
 * Set Setting
 *
 * Saves or updates a config in bd.
 * 
 * @access  private
 * @param   string string
 * @return  NULL
 * @since   0.0.0
 * @modify  0.0.0
 */
function set_setting($key, $value=NULL){
    $CI =& get_instance();
    $CI->load->model('settings_model', 'settings');
    if ($CI->settings->get_by_name($key)->num_rows() == 1):
        if (trim($value) == ''):
            $CI->settings->do_delete(array('setting_name'=>$key), FALSE);
        else:
            $data = array(
                'setting_name' => $key,
                'setting_value' => $value
            );
            $CI->settings->do_update($data, array('setting_name'=>$key), FALSE);
        endif;
    else:
        $data = array(
            'setting_name' => $key,
            'setting_value' => $value
        );
        $CI->settings->do_insert($data, FALSE);
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Get Setting
 *
 * Return a config of in bd.
 * 
 * @access  private
 * @param   string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_setting($key){
    $CI =& get_instance();
    $CI->load->model('settings_model', 'settings');
    $setting = $CI->settings->get_by_name($key);
    if ($setting->num_rows()==1):
        $setting = $setting->row();
        return $setting->setting_value;
    else:
        return NULL;
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Set Usermeta
 *
 * Saves or updates a user meta in bd.
 * 
 * @access  private
 * @param   string string string
 * @return  NULL
 * @since   0.0.0
 * @modify  0.0.0
 */
function set_usermeta($key, $value=NULL, $iduser=NULL){
    $CI =& get_instance();
    $CI->load->model('usermeta_model', 'usermeta');
    if ($CI->usermeta->get_by_key($key, $iduser)->num_rows() == 1):
        if (trim($value) == ''):
            $CI->usermeta->do_delete(array('usermeta_key'=>$key, 'usermeta_userid'=>$iduser), FALSE);
        else:
            $data = array(
                'usermeta_key' => $key,
                'usermeta_value' => $value
            );
            $CI->usermeta->do_update($data, array('usermeta_key'=>$key, 'usermeta_userid'=>$iduser), FALSE);
        endif;
    else:
        $data = array(
            'usermeta_userid' => $iduser,
            'usermeta_key' => $key,
            'usermeta_value' => $value
        );
        $CI->usermeta->do_insert($data, FALSE);
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Get Usermeta
 *
 * Return a user meta of in bd.
 * 
 * @access  private
 * @param   string string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_usermeta($key, $userid=NULL){
    $CI =& get_instance();
    $CI->load->model('usermeta_model', 'usermeta');
    $usermeta = $CI->usermeta->get_by_key($key, $userid);
    if ($usermeta->num_rows()==1):
        $usermeta = $usermeta->row();
        return $usermeta->usermeta_value;
    else:
        return NULL;
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Get Termmeta
 *
 * Return a term meta of in bd.
 * 
 * @access  private
 * @param   string string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_termmeta($key, $termid=NULL){
    $CI =& get_instance();
    $CI->load->model('termmeta_model', 'termmeta');
    $termmeta = $CI->termmeta->get_by_key($key, $termid);
    if ($termmeta->num_rows()==1):
        $termmeta = $termmeta->row();
        return $termmeta->termmeta_value;
    else:
        return NULL;
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Set Postmeta
 *
 * Saves or updates a post meta in bd.
 * 
 * @access  private
 * @param   string string string
 * @return  NULL
 * @since   0.0.0
 * @modify  0.0.0
 */
function set_postmeta($key, $value=NULL, $idpost=NULL){
    $CI =& get_instance();
    $CI->load->model('postmeta_model', 'postmeta');
    if ($CI->postmeta->get_by_key($key, $idpost)->num_rows() == 1):
        if (trim($value) == ''):
            $CI->postmeta->do_delete(array('postmeta_key'=>$key, 'postmeta_postid'=>$idpost), FALSE);
        else:
            $data = array(
                'postmeta_key' => $key,
                'postmeta_value' => $value
            );
            $CI->postmeta->do_update($data, array('postmeta_key'=>$key, 'postmeta_postid'=>$idpost), FALSE);
        endif;
    elseif (trim($value) != ''):
        $data = array(
            'postmeta_postid' => $idpost,
            'postmeta_key' => $key,
            'postmeta_value' => $value
        );
        $CI->postmeta->do_insert($data, FALSE);
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Get Postmeta
 *
 * Return a post meta of in bd.
 * 
 * @access  private
 * @param   string string string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_postmeta($key, $postid=NULL, $value=NULL){
    $CI =& get_instance();
    $CI->load->model('postmeta_model', 'postmeta');
    if ($value == NULL):
        $postmeta = $CI->postmeta->get_by_key($key, $postid);
    else:
        $postmeta = $CI->postmeta->get_by_key_and_value_and_postid($key, $value, $postid);
    endif;
    if ($postmeta->num_rows()==1):
        $postmeta = $postmeta->row();
        return $postmeta->postmeta_value;
    else:
        return NULL;
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Get Permission
 *
 * Returns a row of user permissions table.
 * 
 * @access  private
 * @param   string string bool
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_permission($value=NULL, $level=NULL, $isativelevel=FALSE){
    $CI =& get_instance();
    if ($value != NULL && $level != NULL):
        if ($isativelevel == TRUE):
            if($level == 1)://levels desabilitados
                return '<td>'.form_checkbox(array('name'=>''.$value.$level.'', 'disabled'=>'disabled'), (get_setting('perm_ative_'.$level.'')==1) ? '' : '1', (get_setting(''.$value.$level.'')==1) ? TRUE : FALSE).'</td>';
            else://levels habilitados
                return '<td>'.form_checkbox(array('name'=>''.$value.$level.''), '1', (get_setting(''.$value.$level.'')==1) ? TRUE : FALSE, ''.(get_setting('perm_ative_'.$level.'')==1) ? '' : 'disabled="disabled"').'</td>';
            endif;
        elseif ($isativelevel != TRUE && $level == 1)://perm_active do administrador
            return '<td>'.form_checkbox(array('name'=>''.$value.$level.'', 'disabled'=>'disabled'), '1', (get_setting(''.$value.$level.'')==1) ? TRUE : FALSE).'</td>';
        elseif ($isativelevel != TRUE && $level != 1)://perm_active dos demais níveis
            return '<td>'.form_checkbox(array('name'=>''.$value.$level.''), '1', (get_setting(''.$value.$level.'')==1) ? TRUE : FALSE).'</td>';
        endif;
    else:
        return 'perm_dash_1'.",". 'perm_dash_2'.",". 'perm_dash_3'.",". 'perm_dash_4'.",". 'perm_dash_5'.",";
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Audit
 *
 * Set a record in audit table.
 * 
 * @access  private
 * @param   string string string bool
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function audit($process=NULL, $description=NULL, $type=NULL, $query=TRUE){
    $CI =& get_instance();
    $CI->load->model('audits_model', 'audits');
    if ($query):
        $last_query = $CI->db->last_query();
    else:
        $last_query = '';
    endif;
    if (be_logged(FALSE)):
        $user_id = get_session('user_id');
    else:
        $user_id = '';
    endif;
    $dados = array(
        'audit_userid' => $user_id,
        'audit_type' => $type,
        'audit_process' => $process,
        'audit_query' => $last_query,
        'audit_description' => $description,
    );
    $CI->audits->do_insert($dados);
}

// ------------------------------------------------------------------------

 /**
 * Set Session
 *
 * Returns a session with the prefix set to be set.
 * 
 * @access  private
 * @param   string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function set_session($session=NULL){
    $CI =& get_instance();
    $return_session = get_setting('system_prefix_session').$session;
    return $return_session;
}

// ------------------------------------------------------------------------

 /**
 * Get Session
 *
 * Returns a session with the default prefix.
 * 
 * @access  private
 * @param   string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_session($session=NULL){
    $CI =& get_instance();
    $CI->load->library('session');
    $return_session = $CI->session->userdata(get_setting('system_prefix_session').$session);
    return $return_session;
}

// ------------------------------------------------------------------------

 /**
 * Get GMT to Local Date
 *
 * Converts the data saved on the server for gtm date (set by the user) - view.
 * 
 * @access  private
 * @param   string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_gmt_to_local_date($date=NULL){
    $CI =& get_instance();
    $CI->load->helper('date');
    $timezone = (get_setting('general_timezone')) ? get_setting('general_timezone') : '0';
    $summertime = get_setting('general_timezone_summertime');
    if ($date==NULL):
        //takes the server date and convert to unix
        $date_unix = human_to_unix(date('Y-m-d H:i:s'));
        //apply timezone
        $date_local = gmt_to_local($date_unix, $timezone, $summertime);
        //converts it to human
        $date_human = unix_to_human($date_local, TRUE, 'eu');
        //return date of server timezone
        return $date_human;
    else:
        //convert to unix
        $date_unix = human_to_unix($date);
        //apply timezone
        $date_local = gmt_to_local($date_unix, $timezone, $summertime);
        //converts it to human
        $date_human = unix_to_human($date_local, TRUE, 'eu');
        //return date passed on the function of human timezone
        return $date_human;
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Local to GMT Pers
 *
 * Adaption of local_to_gmt function to calculate the time for the server 
 * (the original version of the function does not receive parametreos timezone and dst).
 * 
 * @access  private
 * @param   string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
 function local_to_gmt_pers($time = NULL, $timezone = 'UTC', $dst = FALSE){
    $CI =& get_instance();
    $CI->load->helper('date');
    if ($time == ''):
        return now();
    endif;
    $time -= timezones($timezone) * 3600;
    if ($dst == TRUE):
        $time += 3600;
    endif;
    return $time;
}

// ------------------------------------------------------------------------

 /**
 * Get Local to GMT date
 *
 * Converts date last user to date compatible with the server - controller.
 * 
 * @access  private
 * @param   string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_local_to_gmt_date($date){
    $CI =& get_instance();
    $CI->load->helper('date');
    $timezone = get_setting('general_timezone');
    $summertime = get_setting('general_timezone_summertime');
    if ($date):
        //converter para unix
        $date_unix = human_to_unix($date);
        //aplicar timezone
        $date_local = local_to_gmt_pers($date_unix, $timezone, $summertime);
        //converter para human
        $date_human = unix_to_human($date_local, TRUE, 'eu');
        //retornar data passada na função com timezone human
        return $date_human;
    else:
        return NULL;
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Set Stat
 *
 * Set in a statistical bd.
 * 
 * @access  private
 * @param   string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function set_stat($postid=''){
    $CI =& get_instance();
    $CI->load->helper('url');
    $CI->load->model('stats_model', 'stats');
    $CI->load->model('posts_model', 'posts');
    $data['stat_date'] = date('Y-m-d H:i:s');
    $data['stat_ip'] = $_SERVER["REMOTE_ADDR"];
    $data['stat_browser'] = $_SERVER["HTTP_USER_AGENT"];
    if (get_session('user_logged')) $data['stat_userid'] = get_session('user_id');
    $data['stat_postid'] = $postid;
    $data['stat_posturl'] = current_url();
    $CI->stats->do_insert($data);
    if ($CI->uri->segment(1) == 'post' && $CI->uri->segment(2) != NULL):
        $byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
        $id_post = $CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_id;
        $views_post = ++$CI->posts->get_by_id($id_post)->row()->post_views;
        $CI->posts->do_update_stat(array('post_views'=>$views_post), array('post_id'=>$id_post));
    elseif ($CI->uri->segment(1) == 'page' && $CI->uri->segment(2) != NULL):
        $byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
        $id_post = $CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_id;
        $views_post = ++$CI->posts->get_by_id($id_post)->row()->post_views;
        $CI->posts->do_update_stat(array('post_views'=>$views_post), array('post_id'=>$id_post));
    elseif ($CI->uri->segment(1) == 'media' && $CI->uri->segment(2) != NULL):
        $byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
        $id_post = $CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_id;
        $views_post = ++$CI->posts->get_by_id($id_post)->row()->post_views;
        $CI->posts->do_update_stat(array('post_views'=>$views_post), array('post_id'=>$id_post));
    endif;
}

// ------------------------------------------------------------------------

 /**
 * Excerpt
 *
 * Constructs a summary of a number of past letters and does not cut the last word.
 * 
 * @access  private
 * @param   string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function excerpt($string, $chars) {
    if (strlen($string) > $chars):
        while (substr($string, $chars, 1) <> ' ' && ($chars < strlen($string))):
            $chars++;
        endwhile;
    endif;
    return substr($string, 0, $chars)."...";
}

// ------------------------------------------------------------------------

 /**
 * Replace String
 *
 * Constructs a html code to wikimedia.
 * 
 * @access  private
 * @param   string
 * @return  string
 * @since   0.0.0
 * @modify  0.0.0
 */
function replace_string($string=NULL){
    if ($string != NULL):
        $string = str_replace(array('----'), array('<hr>'), $string);
        $string = str_replace(array('====== ', ' ======'), array('<h6>', '</h6>'), $string);
        $string = str_replace(array('===== ', ' ====='), array('<h5>', '</h5>'), $string);
        $string = str_replace(array('==== ', ' ===='), array('<h4>', '</h4>'), $string);
        $string = str_replace(array('=== ', ' ==='), array('<h3>', '</h3>'), $string);
        return str_replace(array('== ', ' =='), array('<h2>', '</h2><hr>'), $string);
    endif;
}