<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Templates functions
 *
 * Helper used in mounting the site
 *
 * @package     Compass
 * @subpackage  CMS
 * @copyright   Copyright (c) 2014, Compass, Inc.
 * @author      Francisco Rodrigo Cunha de Sousa
 * @link        http://rodrigosousa.info
 * @since       0.0.0
 */

// ------------------------------------------------------------------------
                     //SYSTEM INFORMATION AND SITE//
// ------------------------------------------------------------------------

 /**
 * Info Title
 *
 * Displays the title of the site.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function info_title($title_page=FALSE){
	$CI =& get_instance();
	if ($title_page):
		if ($CI->uri->segment(2) == 'post' || $CI->uri->segment(2) == 'page' || $CI->uri->segment(2) == 'media'):
			echo the_title().' | '.get_setting('general_title_site');
		else:
			echo get_setting('general_title_site');
		endif;
	else:
		if (get_setting('layout_site_title')):
			echo get_setting('layout_site_title');
		else:
			echo get_setting('general_title_site');
		endif;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * Info Description
 *
 * Displays the description of the site.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function info_description(){
	$CI =& get_instance();
	if (get_setting('layout_site_description')):
		echo get_setting('layout_site_description');
	else:
		echo get_setting('general_description_site');
	endif;
}

// ------------------------------------------------------------------------

 /**
 * Info Email Admin
 *
 * Displays the e-mail of the adiministrator of the site.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function info_email_admin(){
	$CI =& get_instance();
	echo get_setting('general_email_admin');
}

// ------------------------------------------------------------------------

 /**
 * Info Base URL
 *
 * Displays the url of the site.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function info_base_url(){
	$CI =& get_instance();
	$CI->load->helper('url');
	echo base_url();
}

// ------------------------------------------------------------------------

 /**
 * Info Logo URL
 *
 * Displays the logo image url of the site.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function info_logo_url(){
	$CI =& get_instance();
	echo base_url().'compass-content/uploads/medias/'.get_setting('layout_logo');
}

// ------------------------------------------------------------------------

 /**
 * Info Capa URL
 *
 * Displays the capa image url of the site.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function info_capa_url(){
	$CI =& get_instance();
	echo base_url().'compass-content/uploads/medias/'.get_setting('layout_capa');
}

// ------------------------------------------------------------------------

 /**
 * Info Stylesheet URL
 *
 * Displays the stylesheet url of the current theme of the site.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function info_stylesheet_url(){
	$CI =& get_instance();
	$CI->load->helper('url');
	echo base_url('compass-content/themes/'.get_setting('content_site_theme').'/style.css');
}

// ------------------------------------------------------------------------

 /**
 * Info jquery URL
 *
 * Displays the jquery url of the system.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function info_jquery_url(){
	$CI =& get_instance();
	$CI->load->helper('url');
	echo base_url('compass-content/includes/js/jquery-1.9.1.min.js');
}

// ------------------------------------------------------------------------

 /**
 * Info Foundation Stylesheet URL
 *
 * Displays the stylesheet foundation url of the system.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function info_foundation_url(){
	$CI =& get_instance();
	$CI->load->helper('url');
	echo base_url('compass-content/includes/css/foundation.min.css');
}

// ------------------------------------------------------------------------

 /**
 * Info Foundation Javascript URL
 *
 * Displays the javascript foundation url of the system.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function info_foundation_javascript_url(){
	$CI =& get_instance();
	$CI->load->helper('url');
	echo base_url('compass-content/includes/js/foundation.min.js');
}

// ------------------------------------------------------------------------

 /**
 * Info Font Awesome URL
 *
 * Displays the stylesheet font awesome url of the system.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function info_font_awesome_url(){
	$CI =& get_instance();
	$CI->load->helper('url');
	echo base_url('compass-content/includes/css/font-awesome/css/font-awesome.min.css');
}

// ------------------------------------------------------------------------

 /**
 * Info Theme URL
 *
 * Displays the current theme url.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function info_theme_url($echo=TRUE){
	$CI =& get_instance();
	$CI->load->helper('url');
	echo base_url('compass-content/themes/'.get_setting('content_site_theme').'/');
}

// ------------------------------------------------------------------------

 /**
 * Info Theme Name
 *
 * Displays the current theme name.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function info_theme_name(){
	$CI =& get_instance();
	echo get_setting('content_site_theme');
}

// ------------------------------------------------------------------------
                     //INCLUDE FILES OF THE CURRENT THEME//
// ------------------------------------------------------------------------

 /**
 * Get Header
 *
 * Include the header.php theme file.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_header(){
	$CI =& get_instance();
    $CI->load->helper('directory');
    $theme_directory_files = directory_map('./compass-content/themes/'.get_setting('content_site_theme'), TRUE);
    if (in_array('header.php', $theme_directory_files)):
		echo $CI->load->file('./compass-content/themes/'.get_setting('content_site_theme').'/header.php');
	else:
		return NULL;
	endif;
	echo get_functions();
}

// ------------------------------------------------------------------------

 /**
 * Get Sidebar
 *
 * Include the sidebar.php theme file.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_sidebar(){
	$CI =& get_instance();
    $CI->load->helper('directory');
    $theme_directory_files = directory_map('./compass-content/themes/'.get_setting('content_site_theme'), TRUE);
    if (in_array('sidebar.php', $theme_directory_files)):
		echo $CI->load->file('./compass-content/themes/'.get_setting('content_site_theme').'/sidebar.php');
	else:
		return NULL;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * Get Footer
 *
 * Include the footer.php theme file.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_footer(){
	$CI =& get_instance();
    $CI->load->helper('directory');
    $theme_directory_files = directory_map('./compass-content/themes/'.get_setting('content_site_theme'), TRUE);
    if (in_array('footer.php', $theme_directory_files)):
		echo $CI->load->file('./compass-content/themes/'.get_setting('content_site_theme').'/footer.php');
	else:
		return NULL;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * Get Functions
 *
 * Include the functions.php theme file.
 * 
 * @access  public
 * @param   no
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_functions(){
	$CI =& get_instance();
    $CI->load->helper('directory');
    $theme_directory_files = directory_map('./compass-content/themes/'.get_setting('content_site_theme'), TRUE);
    if (in_array('functions.php', $theme_directory_files)):
		echo $CI->load->file('./compass-content/themes/'.get_setting('content_site_theme').'/functions.php');
	else:
		return NULL;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * Get Include
 *
 * Include the specific theme file.
 * 
 * @access  public
 * @param   string
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_include($file=NULL){
	$CI =& get_instance();
    $CI->load->helper('directory');
    $theme_directory_files = directory_map('./compass-content/themes/'.get_setting('content_site_theme'), TRUE);
    if (in_array($file, $theme_directory_files)):
		echo $CI->load->file('./compass-content/themes/'.get_setting('content_site_theme').'/'.$file);
	else:
		return NULL;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * Get Network URL
 *
 * Construction of the taxonomy menu created in the CMS page.
 * 
 * @access  public
 * @param   array
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_menu($config=NULL, $num_max=25){
	$CI =& get_instance();
	$CI->load->helper('url');
	$CI->load->model('terms_model', 'terms');
	$menu_open = '<ul>';
	$menu_close = '</ul>';
	$menu_item_open = '<li>';
	$menu_item_close = '</li>';
	foreach ($config as $k => $v) eval("\$".$k." = \"".$v."\";");
	$CI->db->where('post_status', 'publish');
	$query = $CI->posts->get_by_type('page')->result();
	echo $menu_open;
	echo $menu_item_open.'<a href="'.base_url('site').'">'.lang('core_homepage').'</a>'.$menu_item_close;
	$count = 0;
	foreach ($query as $line):
		if ($count < $num_max):
			echo $menu_item_open.'<a href="'.base_url('page/').'/'.$line->post_slug.'">'.$line->post_title.'</a>'.$menu_item_close;
		endif;
		$count ++;
	endforeach;
	echo $menu_close;
}

// ------------------------------------------------------------------------
            //CONSTRUCTION OF THE ELEMENTS OF THE SITE PAGES//
// ------------------------------------------------------------------------

 /**
 * Query Posts
 *
 * Returns a list of posts with certain settings.
 * 
 * @access  public
 * @param   array string
 * @return  array
 * @since   0.0.0
 * @modify  0.0.0
 */
function query_posts($config=array(), $post_type='post'){
	$CI =& get_instance();
	$CI->load->helper('url');
	$CI->load->model('posts_model', 'posts');
	$CI->load->model('postmeta_model', 'postmeta');
	if ($CI->uri->segment(1) == 'post'):
		$CI->db->where('post_id', $CI->uri->segment(2));
		if (is_numeric($CI->uri->segment(2))):
			$CI->db->or_where('post_id', $CI->uri->segment(2));
		else:
			$CI->db->or_where('post_slug', $CI->uri->segment(2));
		endif;
		$CI->db->limit(1);
		return $CI->posts->get_all()->result();
	else:
		$limit = NULL;
		$offset = NULL;
		$tags = NULL;
		$order = 'desc';
		$pagination = FALSE;
		$pagination_segment = 2;
		$config_num_show = get_setting('general_large_list');
		if ($CI->uri->segment(1) == 'blog'):
			$config_num_show = get_setting('site_blog_num_posts');
		elseif ($CI->uri->segment(1) == ''):
			$config_num_show = get_setting('site_blog_num_posts');
		endif;
		foreach ($config as $k => $v) eval("\$".$k." = \"".$v."\";");
		$pagination_segment_value = ($CI->uri->segment($pagination_segment)) ? $CI->uri->segment($pagination_segment) : 1;
		if ($limit != NULL):
			$CI->db->limit($limit, $offset);
		endif;
		if ($CI->uri->segment(1) == 'author'):
			$CI->db->where('post_author', $CI->uri->segment(2));
			if ($CI->posts->get_all()->num_rows() < 1):
				echo '<p>'.lang('cms_helper_no_results_found', 'cms').'</p>';
			endif;
			$CI->db->where('post_author', $CI->uri->segment(2));
		endif;
		if ($CI->uri->segment(1) == 'search'):
			$CI->db->like('post_title', str_replace('-', ' ', $CI->uri->segment(2)));
            $CI->db->or_like('post_date', str_replace('-', ' ', $CI->uri->segment(2)));
            $CI->db->or_like('post_excerpt', str_replace('-', ' ', $CI->uri->segment(2)));
            $CI->db->or_like('post_content', str_replace('-', ' ', $CI->uri->segment(2)));
		endif;
		if ($CI->uri->segment(1) == 'tag'):
			$tags = $CI->uri->segment(2);
		endif;
		if ($tags != NULL):
			$tags = explode(',', $tags);
			foreach ($tags as $tag):
				$CI->db->or_like('post_tags', str_replace('-', ' ', $tag));
			endforeach;
		endif;
		if ($CI->uri->segment(1) == 'tag'):
			$CI->db->or_like('post_tags', str_replace('-', ' ', $CI->uri->segment(1)));
		endif;
		if ($pagination == TRUE):
			if ($limit == NULL):
				$limit = $config_num_show;//limite pego na setting
			endif;
			if ($pagination_segment_value != 1):
				$offset = $offset+$pagination_segment_value*$config_num_show-$config_num_show;
			endif;
			$CI->db->limit($limit, $offset);
		endif;
		$CI->db->order_by('post_date_publish', $order);
		$CI->db->where('post_type', $post_type);
		$CI->db->where('post_status', 'publish');
		return $CI->posts->get_all()->result();
	endif;
}

// ------------------------------------------------------------------------

 /**
 * Pagination
 *
 * Build the menu paging query.
 * 
 * @access  public
 * @param   array string
 * @return  array
 * @since   0.0.0
 * @modify  0.0.0
 */
function pagination($config=NULL, $post_type='post'){
	$CI =& get_instance();
	$CI->load->model('posts_model', 'posts');
	$CI->load->model('postmeta_model', 'postmeta');
	$limit = NULL;
	$offset = NULL;
	$tags = NULL;
	$pagination = FALSE;
	$pagination_type = 'full';
	$pagination_segment = 3;
	$pagination_url = NULL;
	$pagination_start = 'Start';
	$pagination_start_open = NULL;
	$pagination_start_close = NULL;
	$pagination_show_links = TRUE;
	$pagination_prefix = NULL;
	$pagination_sufix = NULL;
	$pagination_prefix_item = NULL;
	$pagination_sufix_item = NULL;
	$pagination_prefix_item_active = NULL;
	$pagination_sufix_item_active = NULL;
	$pagination_show_prev_next = TRUE;
	$pagination_prev = '&lt;prev';
	$pagination_next = 'next&gt;';
	$pagination_show_first_last = TRUE;
	$pagination_first = '&laquo;first';
	$pagination_last = 'last&raquo;';
	$config_num_show = 10;
	$config_num_show = get_setting('site_blog_num_posts');
	if ($CI->uri->segment(2) == 'blog'):
		$pagination_url = 'blog/';
		$pagination_segment = 5;
		$config_num_show = get_setting('site_blog_num_posts');
	elseif ($CI->uri->segment(2) == ''):
		$pagination_url = '';
		$pagination_segment = 3;
		$config_num_show = get_setting('site_blog_num_posts');
	endif;
	foreach ($config as $k => $v) eval("\$".$k." = \"".$v."\";");
	$pagination_segment_value = ($CI->uri->segment($pagination_segment)) ? $CI->uri->segment($pagination_segment) : 1;
	$pagination_url = base_url().$pagination_url;
	$CI->db->limit(100000, $offset);
	if ($tags != NULL):
		$tags = explode(',', $tags);
		foreach ($tags as $tag):
			$CI->db->or_like('post_tags', str_replace('-', ' ', $tag));
		endforeach;
	endif;
	if ($CI->uri->segment(2) == 'tag'):
		$CI->db->or_like('post_tags', str_replace('-', ' ', $CI->uri->segment(3)));
	endif;
	$CI->db->where('post_type', $post_type);
	$CI->db->where('post_status', 'publish');
	$num_results = $CI->posts->get_all()->num_rows();
	$num_links = ($num_results/$config_num_show)+1;
	if ($CI->uri->segment(2) == 'tag' || $CI->uri->segment(2) == 'author' || $CI->uri->segment(2) == 'search'):
		$pagination = FALSE;
	endif;
	if ($pagination == TRUE):
		echo $pagination_prefix;
		if ($pagination_type == 'full' && $pagination_show_first_last && $num_links > 1 && $pagination_segment_value != 1):
			echo $pagination_prefix_item.'<a href="'.$pagination_url.'1'.'">'.$pagination_first.'</a>'.$pagination_sufix_item;
		endif;
		if ($pagination_show_prev_next && $pagination_segment_value != 1):
			echo $pagination_prefix_item.'<a href="'.$pagination_url.($pagination_segment_value-1).'">'.$pagination_prev.'</a>'.$pagination_sufix_item;
		endif;
		$count = 1;
		if ($pagination_type == 'full' && $pagination_show_links == TRUE):
			while($count < $num_links):
				if ($pagination_segment_value == $count):
					echo $pagination_prefix_item_active.'<a href="'.$pagination_url.$count.'">'.$count.'</a>'.$pagination_sufix_item_active;
				else:
					echo $pagination_prefix_item.'<a href="'.$pagination_url.$count.'">'.$count.'</a>'.$pagination_sufix_item;
				endif;
				$count++;
			endwhile;
		endif;
		if ($pagination_type != 'full'):
			echo $pagination_prefix_item.'<a href="'.$pagination_url.'1'.'">'.$pagination_start.'</a>'.$pagination_sufix_item;
		endif;
		if ($pagination_show_prev_next && $pagination_segment_value < $num_links-1):
			echo $pagination_prefix_item.'<a href="'.$pagination_url.($pagination_segment_value+1).'">'.$pagination_next.'</a>'.$pagination_sufix_item;
		endif;
		if ($pagination_type == 'full' && $pagination_show_first_last && $pagination_segment_value < $count-1):
			echo $pagination_prefix_item.'<a href="'.$pagination_url.($count-1).'">'.$pagination_last.'</a>'.$pagination_sufix_item;
		endif;
		echo $pagination_sufix;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The Header
 *
 * Includes elements css of definitions in the header of your site pages.
 * 
 * @access  public
 * @param   bool
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
 function the_header($css=TRUE){
	$CI =& get_instance();
	if($css == TRUE && get_setting('layout_model') == 1):
		$fonts = array(''=>'','helvetica'=>'"Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif', 'verdana'=>'Verdana, Geneva, sans-serif','georgia'=>'Georgia, "Times New Roman", Times, serif','courier'=>'"Courier New", Courier, monospace','arial'=>'Arial, Helvetica, sans-serif','tahoma'=>'Tahoma, Geneva, sans-serif','trebuchet'=>'"Trebuchet MS", Arial, Helvetica, sans-serif','arialblack'=>'"Arial Black", Gadget, sans-serif','times'=>'"Times New Roman", Times, serif','palatino'=>'"Palatino Linotype", "Book Antiqua", Palatino, serif','lucida'=>'"Lucida Sans Unicode", "Lucida Grande", sans-serif','serif'=>'"MS Serif", "New York", serif','monaco'=>'"Lucida Console", Monaco, monospace','comic'=>'"Comic Sans MS", cursive',);
		$style = NULL;
		$style .= '<style type="text/css">';
		if (get_setting('layout_background')) $style .= 'body{background-color: '.get_setting('layout_background').' !important; margin: 0 auto !important;}';
		if (get_setting('layout_background_image')) $style .= 'body{background-image: url("'.base_url().'compass-content/uploads/medias/'.get_setting('layout_background_image').'") !important;}';
		if (get_setting('layout_width_site')) $style .= 'body{ width:'.get_setting('layout_width_site').' !important;}';
		if (get_setting('layout_width_content')) $style .= 'article{ width:'.get_setting('layout_width_content').'!important;}';
		if (get_setting('layout_width_sidebar')) $style .= 'aside{ width:'.get_setting('layout_width_sidebar').' !important;}';
		if (get_setting('layout_sidebar_position') == 'left') $style .= 'aside{float:left !important} article{float:right !important} footer{clear:both;}';
		if (get_setting('layout_sidebar_position') == 'right') $style .= 'aside{float:right !important} article{float:left !important}footer{clear:both;}';
		if (get_setting('layout_text_font')) $style .= 'body, body *{font-family:'.$fonts[get_setting('layout_text_font')].' !important;}';
		if (get_setting('layout_text_size')) $style .= 'body, body *{font-size:'.get_setting('layout_text_size').' !important;}';
		if (get_setting('layout_text_color')) $style .= 'body, body *{color:'.get_setting('layout_text_color').' !important;}';
		if (get_setting('layout_link_color')) $style .= 'a{color:'.get_setting('layout_link_color').' !important;} button, .button{color: #fff !important; background-color:'.get_setting('layout_link_color').' !important;}';
		if (get_setting('layout_link_visited')) $style .= 'a:link{color:'.get_setting('layout_link_visited').' !important;}';
		if (get_setting('layout_link_hover')) $style .= 'a:hover{color:'.get_setting('layout_link_hover').' !important;}';
		if (get_setting('layout_header_background')) $style .= 'header, #capa, .capa{background:'.get_setting('layout_header_background').' !important;}';
		if (get_setting('layout_title_font')) $style .= 'h1{font-family:'.$fonts[get_setting('layout_title_font')].' !important;}';
		if (get_setting('layout_title_size')) $style .= 'h1{font-size:'.get_setting('layout_title_size').' !important;}';
		if (get_setting('layout_title_color')) $style .= 'h1{color:'.get_setting('layout_title_color').' !important;}';
		if (get_setting('layout_description_font')) $style .= 'span{font-family:'.$fonts[get_setting('layout_description_font')].' !important;}';
		if (get_setting('layout_description_size')) $style .= 'span{font-size:'.get_setting('layout_description_size').' !important;}';
		if (get_setting('layout_description_color')) $style .= 'span{color:'.get_setting('layout_description_color').' !important;}';
		if (get_setting('layout_sidebar_tabs_background')) $style .= 'aside h1, aside h2, aside h3, aside h4, aside h5{background-color:'.get_setting('layout_sidebar_tabs_background').' !important;}';
		if (get_setting('layout_sidebar_background')) $style .= 'aside{background-color:'.get_setting('layout_sidebar_background').' !important;}';
		if (get_setting('layout_sidebar_font')) $style .= 'aside, aside *{font-family:'.$fonts[get_setting('layout_sidebar_font')].' !important;}';
		if (get_setting('layout_sidebar_size')) $style .= 'aside, aside *{font-size:'.get_setting('layout_sidebar_size').' !important;}';
		if (get_setting('layout_sidebar_color')) $style .= 'aside, aside *{color:'.get_setting('layout_sidebar_color').' !important;}';
		if (get_setting('layout_sidebar_tabs_font')) $style .= 'aside h1, aside h2, aside h3, aside h4, aside h5{font-family:'.$fonts[get_setting('layout_sidebar_tabs_font')].' !important;}';
		if (get_setting('layout_sidebar_tabs_size')) $style .= 'aside h1, aside h2, aside h3, aside h4, aside h5{font-size:'.get_setting('layout_sidebar_tabs_size').' !important;}';
		if (get_setting('layout_sidebar_tabs_color')) $style .= 'aside h1, aside h2, aside h3, aside h4, aside h5{color:'.get_setting('layout_sidebar_tabs_color').' !important;}';
		if (get_setting('layout_pages_title_background')) $style .= 'article h1, article h2, article h3, article h4, article h5{background-color:'.get_setting('layout_pages_title_background').' !important;}';
		if (get_setting('layout_pages_background')) $style .= 'article, article{background-color:'.get_setting('layout_sidebar_background').' !important;}';
		if (get_setting('layout_pages_background')) $style .= 'article, article *{font-family:'.$fonts[get_setting('layout_pages_text_font')].' !important;}';
		if (get_setting('layout_pages_text_size')) $style .= 'article, article *{font-size:'.get_setting('layout_pages_text_size').' !important;}';
		if (get_setting('layout_pages_background')) $style .= 'article{color:'.get_setting('layout_pages_background').' !important;}';
		if (get_setting('layout_pages_title_font')) $style .= 'article h1, article h2, article h3, article h4, article h5{font-family:'.$fonts[get_setting('layout_pages_title_font')].' !important;}';
		if (get_setting('layout_pages_title_size')) $style .= 'article h1, article h2, article h3, article h4, article h5{font-size:'.get_setting('layout_pages_title_size').' !important;}';
		if (get_setting('layout_pages_title_color')) $style .= 'article h1, article h2, article h3, article h4, article h5{color:'.get_setting('layout_pages_title_color').' !important;}';
		if (get_setting('layout_images_background')) $style .= 'img{background-color:'.get_setting('layout_images_background').' !important;}';
		if (get_setting('layout_images_border_color')) $style .= 'img{border:1px solid '.get_setting('layout_images_border_color').' !important;}';
		if (get_setting('layout_footer_background')) $style .= 'footer{background-color:'.get_setting('layout_footer_background').' !important;}';
		if (get_setting('layout_footer_font')) $style .= 'footer, footer *{font-family:'.$fonts[get_setting('layout_footer_font')].' !important;}';
		if (get_setting('layout_footer_size')) $style .= 'footer, footer *{font-size:'.get_setting('layout_footer_size').' !important;}';
		if (get_setting('layout_footer_color')) $style .= 'footer, footer *{color:'.get_setting('layout_footer_color').' !important;}';
		if (get_setting('layout_css')) $style .= get_setting('layout_css');
		$style .= '</style>';
		echo $style;
	endif;
	if($css == TRUE && get_setting('site_menu_tools') == 1):
		$style = NULL;
		$style = '<style type="text/css">';
		$style .= 'ul.left-bar {font-size: 12px; margin: 0; padding: 0; list-style: none; width: 100px; position: fixed; top: 25%;}';
		$style .= 'ul.left-bar *{z-index: 9999;}';
		$style .= 'ul.left-bar li {position: relative; margin: 10px; left: 15px;}';
		$style .= 'ul.left-bar li ul {position: absolute; left: 26px; top: 0; display: none;}';
		$style .= 'ul.left-bar li > a {display: block; text-decoration: none; color: #777; background: #f1f1f1; border-radius: 5px; padding: 5px; border: 1px solid #f1f1f1; border-bottom: 0;}';
		$style .= 'ul.left-bar, ul.left-bar li ul {margin: 0; padding: 0; list-style: none; width: 60px; min-height: 60px;}';
		$style .= 'ul.left-bar li:hover ul {display: block;}';
		$style .= 'ul.left-bar li ul{width: 120px;}';
		$style .= 'ul.left-bar li ul li{margin: 0px; border-radius: none;}';
		$style .= 'ul.left-bar li ul li a {padding: 2px 5px; border-radius: 0px;}';
		$style .= 'ul.left-bar li ul li:hover a {background: #f9f9f9ul.left-bar li.left-bar-compass;}';
		$style .= '</style>';
		echo $style;
		echo '<ul class="left-bar">';
		    echo '<li><a title="Compass"><img src="'.base_url().'/compass-content/includes/images/compass.png" /></a>';
		      	echo '<ul>';
			        echo '<li><a href="'.base_url().'" title="Página inicial">Página inicial</a></li>';
			        echo '<li><a href="'.base_url('dashboard').'" title="Dashboard">Dashboard</a></li>';
			        echo '<li><a href="'.base_url('cms').'" title="Conteúdo">Contents</a></li>';
			        echo '<li><a href="'.base_url('users').'" title="Usuários">Users</a></li>';
		      	echo '</ul>';
		    echo '</li>';
		    echo '<li><a title="Novo"><img src="'.base_url().'/compass-content/includes/images/new.png" /></a>';
		      	echo '<ul>';
			        echo '<li><a href="'.base_url('cms/posts/insert').'" title="Novo post">Post</a></li>';
			        echo '<li><a href="'.base_url('cms/pages/insert').'" title="Nova página">Página</a></li>';
			        echo '<li><a href="'.base_url('cms/medias').'" title="Nova mídia">Mídia</a></li>';
			        echo '<li><a href="'.base_url('users/insert').'" title="Novo usuário">Usuário</a></li>';
		      	echo '</ul>';
		    echo '</li>';
		    echo '<li><a title="'.get_session('user_name').'">'.avatar(get_usermeta('user_image', get_session('user_id')), 150, 150).'</a>';
		      	echo '<ul>';
			        echo '<li><a href="'.base_url('users/profile').'/'.get_session('user_id').'">Ver pefil</a></li>';
			        echo '<li><a href="'.base_url('users/update').'/'.get_session('user_id').'">Editar perfil</a></li>';
			        echo '<li><a href="'.base_url('login/logoff').'">Sair</a></li>';
		      	echo '</ul>';
		    echo '</li>';
		    if ($CI->uri->segment(3) == 'post'):
		    	echo '<li><a href="'.base_url('cms/posts/update/'.$CI->uri->segment(3)).'"><img src="'.base_url().'/compass-content/includes/images/edit.png" /></a></li>';
		    elseif ($CI->uri->segment(3) == 'page'):
		    	echo '<li><a href="'.base_url('cms/pages/update/'.$CI->uri->segment(3)).'"><img src="'.base_url().'/compass-content/includes/images/edit.png" /></a></li>';
		    endif;
		echo '</ul>';
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The ID
 *
 * Displays the id of the post, page, media or other element of bd.
 * 
 * @access  public
 * @param   bool
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_id($query=NULL){
	$CI =& get_instance();
	$CI->load->model('posts_model', 'posts');
	$byid_or_byslug = (is_numeric($CI->uri->segment(3))) ? 'get_by_id' : 'get_by_slug';
	if ($CI->uri->segment(2) == 'post' || $CI->uri->segment(2) == 'page' || $CI->uri->segment(2) == 'media'):
		echo $CI->posts->$byid_or_byslug($CI->uri->segment(3))->row()->post_id;
	else:
		echo $query->post_id;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The Slug
 *
 * Displays the slug of the post, page, media or other element of bd.
 * 
 * @access  public
 * @param   array
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_slug($query=NULL){
	$CI =& get_instance();
	$CI->load->model('posts_model', 'posts');
	$byid_or_byslug = (is_numeric($CI->uri->segment(3))) ? 'get_by_id' : 'get_by_slug';
	if ($CI->uri->segment(2) == 'post' || $CI->uri->segment(2) == 'page' || $CI->uri->segment(2) == 'media'):
		echo $CI->posts->$byid_or_byslug($CI->uri->segment(3))->row()->post_slug;
	else:
		echo $query->post_slug;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The Permalink
 *
 * Displays the permalink of the post, page, media or other element of bd.
 * 
 * @access  public
 * @param   array
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_permalink($query=NULL){
	$CI =& get_instance();
	$CI->load->helper('url');
	$CI->load->model('posts_model', 'posts');
	$byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
	if ($CI->uri->segment(1) == 'post'):
		echo base_url('post').'/'.$CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_slug;
	elseif ($CI->uri->segment(1) == 'page'):
		echo base_url('page').'/'.$CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_slug;
	elseif ($CI->uri->segment(1) == 'media'):
		echo base_url('media').'/'.$CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_slug;
	else:
		echo base_url('post').'/'.$query->post_slug;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The Title
 *
 * Displays the title of the post, page, media or other element of bd.
 * 
 * @access  public
 * @param   array
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_title($query=NULL){
	$CI =& get_instance();
	$CI->load->model('posts_model', 'posts');
	$byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
	if ($CI->uri->segment(1) == 'post' || $CI->uri->segment(1) == 'page' || $CI->uri->segment(1) == 'media'):
		echo $CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_title;
	else:
		echo $query->post_title;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The Author
 *
 * Displays the author of the post, page, media or other element of bd.
 * 
 * @access  public
 * @param   array bool
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_author($query=NULL, $islink=FALSE){
	$CI =& get_instance();
	$CI->load->model('posts_model', 'posts');
	$CI->load->model('users_model', 'users');
	$byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
	if ($CI->uri->segment(1) == 'post' || $CI->uri->segment(1) == 'page' || $CI->uri->segment(1) == 'media'):
		$user = $CI->users->get_by_id($CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_author)->row();
	else:
		$user = $CI->users->get_by_id($query->post_author)->row();
	endif;
	if ($user->user_displayname != NULL):
		$user_name = $user->user_displayname;
	elseif ($user->user_name != NULL):
		$user_name = $user->user_name;
	elseif ($user->user_username != NULL):
		$user_name = $user->user_username;
	endif;
	if ($islink):
		echo '<a href="'.base_url('site/author').'/'.$user->user_username.'">'.the_author($query).'</a>';
	else:
		echo $user_name;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The Author image
 *
 * Displays the author avatar of the post, page, media or other element of bd.
 * 
 * @access  public
 * @param   array bool
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_author_image($query=NULL, $isimg=FALSE){
	$CI =& get_instance();
	$CI->load->model('posts_model', 'posts');
	$byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
	if ($CI->uri->segment(1) == 'post' || $CI->uri->segment(1) == 'page' || $CI->uri->segment(1) == 'media'):
		$user_screenshot = get_usermeta('user_image', $CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_title);
	else:
		$user_screenshot = get_usermeta('user_image', $query->post_author);
	endif;
	if ($isimg):
		echo '<img src="'.base_url().'compass-content/uploads/medias/'.$user_screenshot.'"/>';
	else:
		echo base_url().'compass-content/uploads/medias/'.$user_screenshot;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The Date
 *
 * Displays the date of the post, page, media or other element of bd.
 * 
 * @access  public
 * @param   array bool
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_date($query=NULL, $format=NULL){
	$CI =& get_instance();
	$CI->load->model('posts_model', 'posts');
	$byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
	if ($CI->uri->segment(1) == 'post' || $CI->uri->segment(1) == 'page' || $CI->uri->segment(1) == 'media'):
		if ($format != NULL):
			echo date($format, strtotime($CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_date_publish));
		else:
			if (get_setting('system_date_format') || get_setting('system_time_format')):
				echo date(get_setting('system_date_format'), strtotime($CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_date_publish));
			else:
				echo $CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_date_publish;
			endif;
		endif;
	else:
		if ($format != NULL):
			echo date($format, strtotime($query->post_date_publish));
		else:
			if (get_setting('system_date_format') || get_setting('system_time_format')):
				echo date(get_setting('system_date_format'), strtotime($query->post_date_publish));
			else:
				echo $query->post_date_publish;
			endif;
		endif;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The Time
 *
 * Displays the time of the post, page, media or other element of bd.
 * 
 * @access  public
 * @param   array bool
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_time($query=NULL, $format=NULL){
	$CI =& get_instance();
	$CI->load->model('posts_model', 'posts');
	$byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
	if ($CI->uri->segment(1) == 'post' || $CI->uri->segment(1) == 'page' || $CI->uri->segment(1) == 'media'):
		if ($format != NULL):
			echo date($format, strtotime($CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_date_publish));
		else:
			if (get_setting('system_date_format') || get_setting('system_time_format')):
				echo date(get_setting('system_date_format').' '.get_setting('system_time_format'), strtotime($CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_date_publish));
			else:
				echo $CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_date_publish;
			endif;
		endif;
	else:
		if ($format != NULL):
			echo date($format, strtotime($query->post_date_publish));
		else:
			if (get_setting('system_date_format') || get_setting('system_time_format')):
				echo date(get_setting('system_date_format').' '.get_setting('system_time_format'), strtotime($query->post_date_publish));
			else:
				echo $query->post_date_publish;
			endif;
		endif;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The Content
 *
 * Displays the content of the post, page, media or other element of bd.
 * 
 * @access  public
 * @param   array
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_content($query=NULL){
	$CI =& get_instance();
	$CI->load->helper('url');
	$CI->load->model('posts_model', 'posts');
	$byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
	if ($CI->uri->segment(1) == 'post' || $CI->uri->segment(1) == 'page'):
		echo $CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_content;
	elseif ($CI->uri->segment(1) == 'media'):
		if (substr($CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_content, -3) == 'gif' || substr($CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_content, -3) == 'jpg' || substr($CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_content, -3) == 'png'):
			echo '<img src="'.base_url().'/compass-content/uploads/medias/'.$CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_content.'" />';
		else:
			echo '<a href="'.base_url().'/compass-content/uploads/medias/'.$CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_content.'">'.base_url().'/compass-content/uploads/medias/'.$CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_content.'</a>';
		endif;
	else:
		echo $query->post_content;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The Content Home
 *
 * Displays the content home of the homepage of the site.
 * 
 * @access  public
 * @param   array
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_content_home(){
	echo get_setting('layout_site_home_content');
}

// ------------------------------------------------------------------------

 /**
 * The Excerpt
 *
 * Displays the excerpt of the post of bd.
 * 
 * @access  public
 * @param   array
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_excerpt($query=NULL, $chars=NULL){
	$CI =& get_instance();
	$CI->load->model('posts_model', 'posts');
	$byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
	if ($CI->uri->segment(1) == 'post' || $CI->uri->segment(1) == 'page' || $CI->uri->segment(1) == 'media'):
		echo $CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_excerpt;
	else:
		if ($query->post_excerpt):
			if ($chars != NULL):
				echo excerpt($query->post_excerpt, $chars);
			else:
				echo $query->post_excerpt;
			endif;
		else:
			if ($chars != NULL):
				echo excerpt($query->post_content, $chars);
			else:
				echo $query->post_content;
			endif;
		endif;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The Comment Count
 *
 * Displays the comment count of the post of bd.
 * 
 * @access  public
 * @param   array
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_comments_count($query=NULL){
	$CI =& get_instance();
	$CI->load->model('posts_model', 'posts');
	$byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
	if ($CI->uri->segment(1) == 'post' || $CI->uri->segment(1) == 'page' || $CI->uri->segment(1) == 'media'):
		echo $CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_comment_count;
	else:
		echo $query->post_comment_count;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The Views
 *
 * Displays the views of the post, page, media or other element of bd.
 * 
 * @access  public
 * @param   array
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_views($query=NULL){
	$CI =& get_instance();
	$CI->load->model('posts_model', 'posts');
	$byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
	if ($CI->uri->segment(1) == 'post' || $CI->uri->segment(1) == 'page' || $CI->uri->segment(1) == 'media'):
		echo $CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_views;
	else:
		echo $query->post_views;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The Category
 *
 * Displays the categories of the post.
 * 
 * @access  public
 * @param   array string bool
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_category($query=NULL, $separator=', ', $islink=FALSE){
	$CI =& get_instance();
	$CI->load->model('terms_model', 'terms');
	$CI->load->model('posts_model', 'posts');
	$CI->load->model('postmeta_model', 'postmeta');
	$byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
	if ($CI->uri->segment(1) == 'post' || $CI->uri->segment(1) == 'page' || $CI->uri->segment(1) == 'media'):
		$post_id = $CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_id;
		if ($CI->postmeta->get_bypostid($post_id)->num_rows() > 0):
			$categories = NULL;
			$CI->db->where('postmeta_key', 'category');
			$query_postmeta_categories = $CI->postmeta->get_bypostid($post_id)->result();
			foreach ($query_postmeta_categories as $line):
				if ($islink == TRUE):
					$categories[] = '<a href="'.base_url().'site/category/'.$CI->terms->get_by_id($line->postmeta_value)->row()->term_slug.'">'.$CI->terms->get_by_id($line->postmeta_value)->row()->term_name.'</a>';
				else:
					$categories[] = $CI->terms->get_by_id($line->postmeta_value)->row()->term_name;
				endif;
			endforeach;
			$categories = ($categories != array()) ? join($separator, $categories) : NULL;
			echo $categories;
		endif;
	else:
		if ($CI->postmeta->get_bypostid($query->post_id)->num_rows() > 0):
			$categories = NULL;
			$CI->db->where('postmeta_key', 'category');
			$query_postmeta_categories = $CI->postmeta->get_bypostid($query->post_id)->result();
			foreach ($query_postmeta_categories as $line):
				if ($islink == TRUE):
					$categories[] = '<a href="'.base_url().'site/category/'.$CI->terms->get_by_id($line->postmeta_value)->row()->term_slug.'">'.$CI->terms->get_by_id($line->postmeta_value)->row()->term_name.'</a>';
				else:
					$categories[] = $CI->terms->get_by_id($line->postmeta_value)->row()->term_name;
				endif;
			endforeach;
			$categories = ($categories != array()) ? join($separator, $categories) : NULL;
			echo $categories;
		endif;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The Screenshot
 *
 * Displays the screenshot of the post.
 * 
 * @access  public
 * @param   array bool integer integer
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_screenshot($query=NULL, $isimg=FALSE, $width=300, $height=300){
	$CI =& get_instance();
	$CI->load->model('posts_model', 'posts');
	$byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
	if ($CI->uri->segment(1) == 'post' || $CI->uri->segment(1) == 'page' || $CI->uri->segment(1) == 'media'):
		$post_screenshot = get_postmeta('image', $CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_id);
	else:
		$post_screenshot = get_postmeta('image', $query->post_id);
	endif;
	if ($post_screenshot != NULL):
		if ($isimg == TRUE && $post_screenshot != NULL):
			echo '<img src="'.$post_screenshot.'"/>';
		else:
			echo $post_screenshot;
		endif;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The Comments
 *
 * Displays the comments of a post.
 * 
 * @access  public
 * @param   string array
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_comments($title='Comments', $config=array()){
	$CI =& get_instance();
	$CI->load->model('posts_model', 'posts');
	$CI->load->model('comments_model', 'comments');
	$author_image_width = 50;
	$author_image_height = 50;
	foreach ($config as $k => $v) eval("\$".$k." = \"".$v."\";");
	$byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
	if ($title != NULL):
		echo '<h5>'.$title.'</h5>';
	endif;
	if ($CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_comment_count > 0):
		$query_comments = $CI->comments->get_by_postid($CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_id)->result();
		foreach($query_comments as $line):
			$comment_id = $line->comment_id;
			$comment_author_image = avatar(get_usermeta('user_image', $line->comment_authorid), $author_image_width, $author_image_height);
			$comment_author_name = $line->comment_author;
			$comment_author_id = $line->comment_authorid;
			$comment_author_url = $line->comment_authorurl;
			$comment_author_email = $line->comment_authoremail;
			$comment_date = get_gmt_to_local_date($line->comment_date);
			$comment_content = $line->comment_content;
			echo "<div class='comments'>
						$comment_author_image
						<span><b>$comment_author_name</b></span>
						<small>$comment_date</small>
						<p>$comment_content</p>
					</div>";
		endforeach;
	endif;
}

// ------------------------------------------------------------------------

 /**
 * The Comment Form
 *
 * Displays the comment form of a post.
 * 
 * @access  public
 * @param   string string
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_comment_form($title='Write your own review', $attributes=NULL){
	$CI =& get_instance();
	$CI->load->helper('form', 'functions');
	$CI->load->model('posts_model', 'posts');
	$CI->lang->load('cms', lang(FALSE));
	$byid_or_byslug = (is_numeric($CI->uri->segment(2))) ? 'get_by_id' : 'get_by_slug';
	if ($CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_comment_status == 1):
		if ($title != NULL):
			echo '<h5>'.$title.'</h5>';
		endif;
		echo form_open(current_url(), ($attributes == NULL) ? 'id="comment_form"' : $attributes);
			get_msg('msgcomment');
			echo form_label('Nome');
	        echo form_input(array('name'=>'comment_author', 'placeholder'=>lang('cms_helper_field_name', 'cms')), set_value('comment_author'));
	        echo form_label('E-mail');
	        echo form_input(array('name'=>'comment_authoremail', 'placeholder'=>lang('cms_helper_field_email', 'cms')), set_value('comment_authoremail'));
	        echo form_label('URL');
	        echo form_input(array('name'=>'comment_authorurl', 'placeholder'=>lang('cms_helper_field_url')), set_value('comment_authorurl'));
	        echo form_label('Comentário');
	        echo form_textarea(array('name'=>'comment_content', 'rows'=>'5', 'placeholder'=>lang('cms_helper_field_comment')), set_value('comment_content'));
	        echo '<small>'.lang('cms_helper_comment_footer', 'cms').' <abbr title="HyperText Markup Language">HTML</abbr>:  <code>&lt;a href=&quot;&quot; title=&quot;&quot;&gt; &lt;abbr title=&quot;&quot;&gt; &lt;acronym title=&quot;&quot;&gt; &lt;b&gt; &lt;blockquote cite=&quot;&quot;&gt; &lt;cite&gt; &lt;code&gt; &lt;del datetime=&quot;&quot;&gt; &lt;em&gt; &lt;i&gt; &lt;q cite=&quot;&quot;&gt; &lt;strike&gt; &lt;strong&gt; </code></small>';
	        echo '<br />';
	        if (be_logged(FALSE) == TRUE):
	            echo form_hidden('idauthor', get_session('user_id'));
	        else:
	        	echo form_hidden('idauthor', NULL);
	        endif;
	        echo form_hidden('idpost', $CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_id);
	        echo form_submit(array('name'=>'save_comment', 'class'=>'button radius tiny'), lang('cms_helper_comment_publish', 'cms'));
		echo form_close();
	else:
		if ($CI->posts->$byid_or_byslug($CI->uri->segment(2))->row()->post_comment_count > 0):
			echo lang('cms_helper_comment_closed', 'cms');
		endif;
	endif;
}


// ------------------------------------------------------------------------
                //LOADING BLOCK CODES (PLUGINS) THE THEME//
// ------------------------------------------------------------------------

 /**
 * Get Network URL
 *
 * Loads the url of social networks defined in the configuration page of the website user.
 * 
 * @access  public
 * @param   string
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function get_network_url($network=NULL){
	$CI =& get_instance();
	if ($network == 'email') echo 'mailto:'.get_setting('layout_url_email');
	if ($network == 'facebook') echo get_setting('layout_url_facebook');
	if ($network == 'twitter') echo get_setting('layout_url_twitter');
	if ($network == 'instagram') echo get_setting('layout_url_instagram');
	if ($network == 'plus') echo get_setting('layout_url_plus');
	if ($network == 'dropbox') echo get_setting('layout_url_dropbox');
	if ($network == 'github') echo get_setting('layout_url_github');
	if ($network == 'linkedin') echo get_setting('layout_url_linkedin');
	if ($network == 'vimeo') echo get_setting('layout_url_vimeo');
	if ($network == 'youtube') echo get_setting('layout_url_youtube');
}

// ------------------------------------------------------------------------

 /**
 * The Recent Posts
 *
 * Displays a number of recent posts.
 * 
 * @access  public
 * @param   integer integer array
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_recent_posts($limit=5, $offset=0, $attributes=NULL){
	$CI =& get_instance();
	$query_conFig = array('limit'=>$limit, 'offset'=>$offset, 'order'=>'desc');
	foreach (query_posts($query_conFig, 'post') as $post):
		echo '<div class="row collapse '.$attributes.'">';
			echo '<div class="small-3 columns">';
				echo '<img src="'.get_postmeta('image', $post->post_id).'"/>';
			echo '</div>';
			echo '<div class="small-9 columns">';
				echo '<h6><a href="'.base_url('post/'.$post->post_slug).'">'.$post->post_title.'</a></h6>';
			echo '</div>';
		echo '</div>';
	endforeach;
}

// ------------------------------------------------------------------------

 /**
 * The Categories
 *
 * Displays the categories on the site.
 * 
 * @access  public
 * @param   string
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_tags($attributes=NULL){
	$CI =& get_instance();
	$CI->load->model('terms_model', 'terms');
	$query_tags_list = $CI->posts->get_all()->result();
    $all_tags = NULL;
    foreach ($query_tags_list as $line):
        if (isset($line->post_tags) != NULL):
            $all_tags .= $line->post_tags.',';
        endif;
    endforeach;
    $all_tags = array_unique(explode(',', rtrim($all_tags, ",")));
    foreach ($all_tags as $line):
    	echo anchor(base_url('tag/'.str_replace(' ', '-', $line)), ucfirst($line), 'class="button tiny button-category '.$attributes.'"');
    endforeach;
}

// ------------------------------------------------------------------------

 /**
 * The Search Form
 *
 * Displays a form for conducting searches on the site.
 * 
 * @access  public
 * @param   string
 * @return  ECHO
 * @since   0.0.0
 * @modify  0.0.0
 */
function the_search_form($attributes=NULL){
	$CI =& get_instance();
	$CI->load->helper(array('form', 'url'));
	echo form_open(base_url('search', $attributes));
        echo '<div class="row collapse">';
            echo '<div class="small-7 columns">';
                echo form_input(array('name'=>'search_for', 'placeholder'=>lang('core_search')), set_value('search'));
            echo '</div>';
            echo '<div class="small-5 columns">';
                echo form_submit(array('name'=>'search', 'class'=>'small-11 button secondary tiny', 'title'=>lang('core_search')), lang('core_search'));
            echo '</div>';
        echo '</div>';
    echo form_close();
}