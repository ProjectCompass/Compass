<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
    case 'list':
        echo '<div class="row" id="cms-posts">';
            echo '<div class="small-12 columns">';
                echo '<div class="row">';
                    echo '<div class="small-5 columns">';
                        echo '<h3 class="left">'.lang('cms_posts').'</h3>';
                        echo anchor('cms/posts/insert', lang('cms_posts_insert'), 'class="addimg button button-tiny secondary radius left space-v-medium space-h-small"');
                    echo '</div>';
                    echo '<div class="small-3 columns">';
                            $query_tags_list = $this->posts->get_all()->result();
                            $all_tags = NULL;
                            foreach ($query_tags_list as $line):
                                if ($line->post_tags != NULL):
                                    $all_tags .= $line->post_tags.',';
                                endif;
                            endforeach;
                            $all_tags = array_unique(explode(',', rtrim($all_tags, ",")));
                        $title_select = ($this->uri->segment($config['filter_key_segment']) == 'filter_tag') ? lang('cms_posts_in_tag').' '.ucfirst($this->uri->segment($config['filter_value_segment'])) : lang('cms_posts_all_tags');
                        echo '<a href="#" data-dropdown="drop1" class="tiny button secondary dropdown expand">'.$title_select.'</a>';
                        echo '<ul id="drop1" data-dropdown-content class="f-dropdown">';                            
                            foreach ($all_tags as $line):
                                echo '<li><a href="'.base_url('cms/posts/index/0/orderby/post_modified/order/desc/filter_tag').'/'.str_replace(' ', '-', $line).'">'.ucfirst($line).'</a></li>';
                            endforeach;
                        echo '</ul>';
                    echo '</div>';
                    echo '<div class="small-4 columns">';
                        echo form_open(current_url(), 'id="cms-posts-form-search"');
                            echo '<div class="row">';
                                echo '<div class="row collapse">';
                                    echo '<div class="small-7 columns">';
                                        echo form_input(array('name'=>'search_for', 'placeholder'=>lang('core_search')), set_value('search', ($this->uri->segment($config['filter_key_segment']) == 'search') ? $this->uri->segment($config['filter_value_segment']) : NULL));
                                    echo '</div>';
                                    echo '<div class="small-5 columns">';
                                        echo form_submit(array('name'=>'search', 'class'=>'small-11 button secondary tiny', 'title'=>lang('cms_posts_search')), lang('cms_posts_search'));
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo form_close();
                    echo '</div>';
                echo '</div>';
                echo '<div class="row">';
                    echo '<div class="small-12 columns links-filters">';
                        $link_filter_current = ($this->uri->segment($config['filter_value_segment']) == NULL) ? 'link-filter-current' : NULL;
                        echo "<a class='link-filter-first ".$link_filter_current."' href='".base_url().$this->uri->segment(1).'/'.$this->uri->segment(2)."'>".lang('cms_all')." (".$this->posts->get_by_type('post')->num_rows().")</a>";
                        if ($this->posts->get_by_status('publish', 'post')->num_rows() > 0):
                            ($this->uri->segment($config['filter_value_segment']) == 'publish') ? $current = 'link-filter-current' : $current = NULL;
                            echo "<a class='".$current."' href='".$config['pagination_url'].'/0/orderby/'.$config['default_orderby'].'/order/'.$config['default_order']."/filter_status/publish"."'>".lang('cms_posts_publisheds')." (".$this->posts->get_by_status('publish', 'post')->num_rows().")</a>";
                        endif;
                        if ($this->posts->get_by_status('draft', 'post')->num_rows() > 0):
                            ($this->uri->segment($config['filter_value_segment']) == 'draft') ? $current = 'link-filter-current' : $current = NULL;
                            echo "<a class='".$current."' href='".$config['pagination_url'].'/0/orderby/'.$config['default_orderby'].'/order/'.$config['default_order']."/filter_status/draft"."'>".lang('cms_posts_dafts')." (".$this->posts->get_by_status('draft', 'post')->num_rows().")</a>";
                        endif;
                    echo '</div>';
                echo '</div>';
                echo '<table class="small-12" id="cms-posts-table-list">';
                    echo '<thead>';
                        echo '<tr class="table-order">';
                            echo '<th class="small-5 collums">'.get_th_orderby(lang('cms_posts_field_title'), 'post_title', $config).'</th>';
                            echo '<th class="small-2 collums">'.lang('cms_posts_field_author').'</th>';
                            echo '<th class="small-4 collums">'.lang('cms_posts_field_tags').'</th>';
                            echo '<th class="small-mid collums">'.get_th_orderby('<i class="fa fa-comments" title="'.lang('cms_posts_field_comments').'"></i>', 'post_comment_count', $config).'</th>';
                            echo '<th class="small-mid collums">'.get_th_orderby('<i class="fa fa-bar-chart-o" title="'.lang('cms_posts_field_views').'"></i>', 'post_views', $config).'</th>';
                            echo '<th class="small-1 collums">'.get_th_orderby(lang('cms_posts_field_date'), 'post_date', $config).'</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                        if ($this->uri->segment($config['filter_key_segment']) == 'filter_tag' && $this->uri->segment($config['filter_value_segment']) != NULL):
                            $this->db->or_like('post_tags', str_replace('-', ' ', $this->uri->segment($config['filter_value_segment'])));
                        endif;
                        $query_numrows = 0;
                        foreach (get_query($config)->result() as $line):
                            if ($line->post_type == 'post'):
                                echo '<tr>';
                                    $title = ($line->post_title != NULL) ? $line->post_title : '-- ';
                                    $status = ($line->post_status == 'draft') ? ' (Rascunho)' : NULL;
                                    printf("<td class='table-operations'><strong>%s</strong><br>%s %s %s</td>",
                                        (access('perm_updateposts_', NULL, TRUE)) ? anchor("cms/posts/update/$line->post_id", $title.$status, array('title'=>lang('cms_posts_update').' \''.$title.'\'')) : $title.$status, 
                                        (access('perm_updateposts_', NULL, TRUE)) ? anchor("cms/posts/update/$line->post_id", lang('core_update'), array('class'=>'table-action table-action-first update')) : NULL,
                                        (access('perm_viewposts_', NULL, TRUE)) ? anchor("post/$line->post_slug", lang('core_view'), array('class'=>'table-action view', 'target'=>'_blanck')) : NULL,
                                        (access('perm_deleteposts_', NULL, TRUE)) ? anchor("cms/posts/delete/$line->post_id", lang('core_delete'), array('class'=>'table-action delete deletereg')) : NULL
                                        );
                                    $queryauthor = $this->users->get_by_id($line->post_author)->row();
                                    printf('<td>%s</td>',
                                        anchor("users/profile/$queryauthor->user_id", $queryauthor->user_username, array('class'=>'table-action view', 'title'=>'\''.$queryauthor->user_name.'\''))
                                    );
                                    $tags = array();
                                    if ($line->post_tags != NULL):
                                        $query_tags = explode(',', $line->post_tags);
                                        foreach ($query_tags as $line_tag):
                                            $tags[] = anchor("cms/posts/index/0/orderby/post_modified/order/desc/filter_tag/".str_replace(' ', '-', $line_tag), ucfirst($line_tag));
                                        endforeach;
                                    endif;
                                    printf('<td>%s</td>', join(", ", $tags));
                                    printf('<td>%s</td>', $line->post_comment_count);
                                    printf('<td>%s</td>', $line->post_views);
                                    printf('<td>%s</td>', get_gmt_to_local_date($line->post_date));
                                echo '</tr>';
                                $query_numrows++;
                            endif;
                        endforeach;
                    echo '</tbody>';
                    echo '<thead>';
                        echo '<tr class="table-order">';
                            echo '<th class="small-5 collums">'.get_th_orderby(lang('cms_posts_field_title'), 'post_title', $config).'</th>';
                            echo '<th class="small-2 collums">'.lang('cms_posts_field_author').'</th>';
                            echo '<th class="small-4 collums">'.lang('cms_posts_field_tags').'</th>';
                            echo '<th class="small-mid collums">'.get_th_orderby('<i class="fa fa-comments" title="'.lang('cms_posts_field_comments').'"></i>', 'post_comment_count', $config).'</th>';
                            echo '<th class="small-mid collums">'.get_th_orderby('<i class="fa fa-bar-chart-o" title="'.lang('cms_posts_field_views').'"></i>', 'post_views', $config).'</th>';
                            echo '<th class="small-1 collums">'.get_th_orderby(lang('cms_posts_field_date'), 'post_date', $config).'</th>';
                        echo '</tr>';
                    echo '</thead>';
                echo '</table>';
                echo '<div class="row">';
                    echo '<div class="small-12 medium-6 large-4 columns">';
                        if ($this->uri->segment($config['filter_key_segment']) == 'filter_tag' && $this->uri->segment($config['filter_value_segment']) != NULL):
                            $this->db->or_like('post_tags', str_replace('-', ' ', $this->uri->segment($config['filter_value_segment'])));
                        endif;
                        $rows = get_query($config, 'all')->num_rows();
                        echo "<small>".lang('core_showing')." ".$query_numrows." - $rows ".lang('core_registers')."</small>";
                    echo '</div>';
                    echo '<div class="small-12 medium-6 large-8 columns">';
                        get_pagination($config);
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
    case 'updatepost':
        echo '<div class="row" id="cms-updatepost">';
            echo '<div class="small-12 columns">';
                $idpost = $this->uri->segment(4);
                if ($idpost==NULL):
                    set_msg('msgerror', lang('cms_posts_msg_not_found'), 'error');
                    redirect('cms/posts');
                endif;
                $query = $this->posts->get_by_id($idpost)->row();
                echo '<div class="row">';
                    echo form_open(current_url(), 'id="cms-updatepost-form-update"');
                        echo '<div class="small-12 columns">';
                            echo ($query->post_title == NULL) ? '<h3>'.lang('cms_posts_insert').'</h3>' : '<h3>'.lang('cms_posts_update').'</h3>';
                            echo '<div class="row collapse">';
                                echo '<div class="small-8 columns">';
                                    echo form_input(array('name'=>'post_title', 'placeholder'=>lang('cms_posts_enter_title_here'), 'class'=>'small-12 columns', 'id'=>'title'), set_value('post_title', $query->post_title), 'autofocus');
                                echo '</div>';
                                echo '<div class="small-4 columns">';
                                    echo '<div class="row collapse">';
                                        echo form_submit(array('name'=>($query->post_title == NULL) ? 'save' : 'update', 'class'=>'small-3 button tiny right'), ($query->post_title == NULL) ? lang('cms_publish') : lang('core_save'));
                                        echo form_submit(array('name'=> 'save_draft', 'class'=>'small-6 button tiny secondary right'), lang('cms_save_as_draft'));
                                        echo anchor('post/'.$query->post_slug, lang('core_view'), array('class'=>'small-3 button tiny secondary right', 'title'=>lang('core_view'), 'target'=>'_blanck'));
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                        echo '<div class="small-12 columns">';
                            echo anchor('#', '<i class="fa fa-picture-o"></i>', 'class="addimg button button-tiny-insertimg secondary left" data-reveal-id="modalimg" data-reveal title="'.lang('cms_insert_image').'"');
                            echo form_textarea(array('name'=>'post_content', 'rows'=>'25', 'class'=>'small-12 columns tinymce'), set_value('post_content', $query->post_content));
                        echo '</div>';
                        echo '{modal}';
                        echo '<div class="small-12 columns">';
                            echo '<dl class="tabs" data-tab>';
                                echo '<dd class="active"><a href="#tab-1">'.lang('cms_posts_field_excerpt').'</a></dd>';
                                echo '<dd><a href="#tab-2">'.lang('cms_posts_field_tags').'</a></dd>';
                                echo '<dd><a href="#tab-3">'.lang('cms_posts_field_screenshort').'</a></dd>';
                                echo '<dd><a href="#tab-4">'.lang('cms_posts_field_date_for_publish').'</a></dd>';
                                echo '<dd><a href="#tab-5">'.lang('cms_posts_field_options').'</a></dd>';
                                echo '<dd><a href="#tab-6">'.lang('cms_posts_field_slug').'</a></dd>';
                            echo '</dl>';
                            echo '<div class="tabs-content">';
                                echo '<div class="content active" id="tab-1">';
                                    echo form_textarea(array('name'=>'post_excerpt', 'rows'=>'3', 'class'=>'small-12 columns'), set_value('post_excerpt', $query->post_excerpt));
                                    echo '<small><em>'.lang('cms_posts_field_excerpt_small').'</em></small>';
                                echo '</div>';
                                echo '<div class="content" id="tab-2">';
                                    echo form_input(array('name'=>'post_tags', 'id'=>'tags'), set_value('post_tags', $query->post_tags));
                                    echo '<small>'.lang('cms_posts_field_tags_small').'</small>';
                                echo '</div>';  
                                echo '<div class="content" id="tab-3">';
                                    echo '<div class="row">';
                                        if (get_postmeta('image', $query->post_id)) echo '<img src="'.get_postmeta('image', $query->post_id).'" class="small-12 medium-8 large-4 columns" />';
                                    echo '</div>';
                                    echo '<div class="row">';
                                        echo '<div class="small-12 medium-8 large-4 columns">';
                                            echo '<div class="row collapse">';
                                                echo '<div class="small-8 columns">';
                                                    echo form_input(array('name'=>'post_image', 'class'=>'img_destac'), set_value('post_image', get_postmeta('image', $query->post_id)));
                                                echo '</div>';
                                                echo '<div class="small-2 columns">';
                                                    echo anchor(base_url('cms/medias'), '<i class="fa fa-picture-o"></i>', array('class'=>'button tiny', 'title'=>lang('cms_posts_medias_manager'), 'target'=>'_blanck'));
                                                echo '</div>';
                                                echo '<div class="small-2 columns">';
                                                    echo anchor(base_url('cms/medias'), '<i class="fa fa-upload"></i>', 'class="button tiny secondary" title="'.lang('cms_posts_upload_images').'" data-reveal-id="modalimg-upload" data-reveal');
                                                echo '</div>';
                                            echo '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                    echo '<small><em>'.lang('cms_posts_field_screenshort_small').'</em></small>';
                                echo '</div>';  
                                echo '<div class="content" id="tab-4">';
                                    echo form_input(array('name'=>'post_date_publish', 'class'=>'small-5 columns datetimepicker'), set_value('post_date_publish', $query->post_date_publish));
                                    echo '<small><em>'.lang('cms_posts_field_date_small').'</em></small>';
                                echo '</div>';  
                                echo '<div class="content" id="tab-5">';
                                    $options = array(
                                        '1'=>'Permitir comentários',
                                        '0'=>'Não permitir comentários',
                                        );
                                    echo form_dropdown('post_comment_status', $options, $query->post_comment_status);
                                echo '</div>';  
                                echo '<div class="content" id="tab-6">';
                                    if ($query->post_slug == NULL):
                                        echo form_input(array('name'=>'post_slug', 'class'=>'small-5 columns slug', 'id'=>'slug'), set_value('post_slug', $query->post_slug));
                                    else:
                                        echo form_input(array('name'=>'post_slug', 'class'=>'small-5 columns', 'id'=>'slug'), set_value('post_slug', $query->post_slug));
                                    endif;
                                    echo '<small><em>'.lang('cms_posts_field_slug_small').'</em></small>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo form_close();
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
}