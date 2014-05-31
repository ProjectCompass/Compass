<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
    case 'list':
        echo '<div class="row" id="cms-pages">';
            echo '<div class="small-12 columns">';
                echo '<div class="row">';
                    echo '<div class="small-8 columns">';
                        echo '<h3 class="left">'.lang('cms_pages').'</h3>';
                        echo anchor('cms/pages/insert', lang('cms_pages_insert'), 'class="addimg button button-tiny secondary radius left space-v-medium space-h-small"');
                    echo '</div>';
                    echo '<div class="small-4 columns">';
                        echo form_open(current_url(), 'id="cms-pages-form-search"');
                            echo '<div class="row">';
                                echo '<div class="row collapse">';
                                    echo '<div class="small-7 columns">';
                                        echo form_input(array('name'=>'search_for', 'placeholder'=>lang('cms_search')), set_value('search', ($this->uri->segment($config['filter_key_segment']) == 'search') ? $this->uri->segment($config['filter_value_segment']) : NULL));
                                    echo '</div>';
                                    echo '<div class="small-5 columns">';
                                        echo form_submit(array('name'=>'search', 'class'=>'small-11 button secondary tiny', 'title'=>lang('cms_pages_search')), lang('cms_pages_search'));
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo form_close();
                    echo '</div>';
                echo '</div>';
                echo '<div class="row">';
                    echo '<div class="small-12 columns links-filters">';
                       $link_filter_current = ($this->uri->segment($config['filter_value_segment']) == NULL) ? 'link-filter-current' : NULL;
                        echo "<a class='link-filter-first ".$link_filter_current."' href='".base_url().$this->uri->segment(1).'/'.$this->uri->segment(2)."'>".lang('cms_all')." (".$this->posts->get_by_type('page')->num_rows().")</a>";                if ($this->posts->get_by_status('publish', 'page')->num_rows() > 0):
                            ($this->uri->segment($config['filter_value_segment']) == 'publish') ? $current = 'link-filter-current' : $current = NULL;
                            echo "<a class='".$current."' href='".$config['pagination_url'].'/0/orderby/'.$config['default_orderby'].'/order/'.$config['default_order']."/filter_status/publish"."'>".lang('cms_posts_publisheds')." (".$this->posts->get_by_status('publish', 'page')->num_rows().")</a>";
                        endif;
                        if ($this->posts->get_by_status('draft', 'page')->num_rows() > 0):
                            ($this->uri->segment($config['filter_value_segment']) == 'draft') ? $current = 'link-filter-current' : $current = NULL;
                            echo "<a class='".$current."' href='".$config['pagination_url'].'/0/orderby/'.$config['default_orderby'].'/order/'.$config['default_order']."/filter_status/draft"."'>".lang('cms_posts_dafts')." (".$this->posts->get_by_status('draft', 'page')->num_rows().")</a>";
                        endif;
                    echo '</div>';
                echo '</div>';
                echo '<table class="small-12" id="cms-pages-table-list">';
                    echo '<thead>';
                        echo '<tr class="table-order">';
                            echo '<th class="small-7 collums">'.get_th_orderby(lang('cms_posts_field_title'), 'post_title', $config).'</th>';
                            echo '<th class="small-3 collums">'.lang('cms_posts_field_author').'</th>';
                            echo '<th class="small-mid collums">'.get_th_orderby('<i class="fa fa-comments" title="'.lang('cms_posts_field_comments').'"></i>', 'post_comment_count', $config).'</th>';
                            echo '<th class="small-mid collums">'.get_th_orderby('<i class="fa fa-bar-chart-o" title="'.lang('cms_posts_field_views').'"></i>', 'post_views', $config).'</th>';
                            echo '<th class="small-1 collums">'.get_th_orderby(lang('cms_posts_field_date'), 'post_date', $config).'</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                        $query_numrows = 0;
                        foreach (get_query($config)->result() as $line):
                            echo '<tr>';
                                $title = ($line->post_title != NULL) ? $line->post_title : '-- ';
                                $status = ($line->post_status == 'draft') ? ' (Rascunho)' : NULL;
                                printf("<td class='table-operations'><strong>%s</strong><br>%s %s %s</td>",
                                    (access('perm_updatepages_', NULL, TRUE)) ? anchor("cms/pages/update/$line->post_id", $title.$status, array('title'=>lang('cms_posts_update').' \''.$title.'\'')) : $title.$status, 
                                    (access('perm_updatepages_', NULL, TRUE)) ? anchor("cms/pages/update/$line->post_id", lang('cms_update'), array('class'=>'table-action table-action-first update', 'title'=>lang('cms_update'))) : NULL,
                                    (access('perm_viewpages_', NULL, TRUE)) ? anchor("site/page/$line->post_slug", lang('cms_visualize'), array('class'=>'table-action view', 'title'=>lang('cms_visualize').' \''.$line->post_title.'\'', 'target'=>'_blanck')) : NULL,
                                    (access('perm_deletepages_', NULL, TRUE)) ? anchor("cms/pages/delete/$line->post_id", lang('cms_delete'), array('class'=>'table-action delete deletereg', 'title'=>lang('cms_delete'))) : NULL
                                    );
                                $queryauthor = $this->users->get_by_id($line->post_author)->row();
                                printf('<td>%s</td>',
                                    anchor("users/profile/$queryauthor->user_id", $queryauthor->user_username, array('class'=>'table-action view', 'title'=>'\''.$queryauthor->user_name.'\''))
                                );
                                $query_category = $this->terms->get_by_type('category');
                                $categories = array();
                                foreach ($query_category->result() as $line_category):
                                    if (get_postmeta('category-'.$line_category->term_slug, $line->post_id)==$line_category->term_id):
                                        $categories[] = anchor("cms/posts/0/orderby/post_modified/order/desc/filter_category/category-$line_category->term_slug", $line_category->term_name, 'title="Ver posts da categoria \''.$line_category->term_name.'\'"');
                                    endif;
                                endforeach;
                                printf('<td>%s</td>', $line->post_comment_count);
                                printf('<td>%s</td>', $line->post_views);
                                printf('<td>%s</td>', get_gmt_to_local_date($line->post_date));
                            echo '</tr>';
                            $query_numrows++;
                        endforeach;
                    echo '</tbody>';
                    echo '<thead>';
                        echo '<tr class="table-order">';
                            echo '<th class="small-7 collums">'.get_th_orderby(lang('cms_posts_field_title'), 'post_title', $config).'</th>';
                            echo '<th class="small-3 collums">'.lang('cms_posts_field_author').'</th>';
                            echo '<th class="small-mid collums">'.get_th_orderby('<i class="fa fa-comments" title="'.lang('cms_posts_field_comments').'"></i>', 'post_comment_count', $config).'</th>';
                            echo '<th class="small-mid collums">'.get_th_orderby('<i class="fa fa-bar-chart-o" title="'.lang('cms_posts_field_views').'"></i>', 'post_views', $config).'</th>';
                            echo '<th class="small-1 collums">'.get_th_orderby(lang('cms_posts_field_date'), 'post_date', $config).'</th>';
                        echo '</tr>';
                    echo '</thead>';
                echo '</table>';
                echo '<div class="row">';
                    echo '<div class="small-12 medium-6 large-4 columns">';
                        $rows = get_query($config, 'all')->num_rows();
                        echo "<small>".lang('cms_show')." ".$query_numrows." ".lang('cms_of')." $rows ".lang('cms_registers')."</small>";
                    echo '</div>';
                    echo '<div class="small-12 medium-6 large-8 columns">';
                        get_pagination($config);
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
    case 'updatepage':
        echo '<div class="row" id="cms-updatepage">';
            echo '<div class="small-12 columns">';
                $idpost = $this->uri->segment(4);
                if ($idpost==NULL):
                    set_msg('msgerror', lang('cms_pages_not_found_to_edit'), 'error');
                    redirect('cms/pages');
                endif;
                $query = $this->posts->get_by_id($idpost)->row();
                echo '<div class="row">';
                    echo form_open(current_url(), 'id="cms-updatepage-form-update"');
                        echo '<div class="small-12 columns">';
                            echo ($query->post_title == NULL) ? '<h3>'.lang('cms_pages_insert').'</h3>' : '<h3>'.lang('cms_pages_update').'</h3>';
                            echo '<div class="row collapse">';
                                echo '<div class="small-8 columns">';
                                    echo form_input(array('name'=>'post_title', 'placeholder'=>lang('cms_posts_enter_title_here'), 'class'=>'small-12 columns', 'id'=>'title'), set_value('post_title', $query->post_title), 'autofocus');
                                echo '</div>';
                                echo '<div class="small-4 columns">';
                                    echo '<div class="row collapse">';
                                        echo form_submit(array('name'=>($query->post_title == NULL) ? 'save' : 'update', 'class'=>'small-3 button tiny right'), ($query->post_title == NULL) ? lang('cms_publish') : lang('cms_update'));
                                        echo form_submit(array('name'=> 'save_draft', 'class'=>'small-6 button tiny secondary right'), lang('cms_save_as_draft'));
                                        echo anchor('site/page/'.$query->post_slug, lang('cms_visualize'), array('class'=>'small-3 button tiny secondary right', 'title'=>lang('cms_visualize'), 'target'=>'_blanck'));
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
                                echo '<dd><a href="#tab-6">'.lang('cms_posts_field_slug').'</a></dd>';
                                echo '<dd><a href="#tab-4">'.lang('cms_posts_field_date_for_publish').'</a></dd>';
                            echo '</dl>';
                            echo '<div class="tabs-content">';
                                echo '<div class="content active" id="tab-6">';
                                    if ($query->post_slug == NULL):
                                        echo form_input(array('name'=>'post_slug', 'class'=>'small-5 columns slug', 'id'=>'slug'), set_value('post_slug', $query->post_slug));
                                    else:
                                        echo form_input(array('name'=>'post_slug', 'class'=>'small-5 columns', 'id'=>'slug'), set_value('post_slug', $query->post_slug));
                                    endif;
                                    echo '<small><em>'.lang('cms_posts_field_slug_small').'</em></small>';
                                echo '</div>';
                                echo '<div class="content" id="tab-4">';
                                    echo form_input(array('name'=>'post_date_publish', 'class'=>'small-5 columns datetimepicker'), set_value('post_date_publish', $query->post_date_publish));
                                    echo '<small><em>'.lang('cms_posts_field_date_small').'</em></small>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo form_close();
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
}