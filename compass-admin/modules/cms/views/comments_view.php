<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
    case 'comments':
        echo '<div class="row" id="cms-comments">';
            echo '<div class="small-12 columns">';
                if(get_session('post_msg') != NULL): echo get_session('post_msg'); endif;
                echo '<div class="row">';
                    echo '<div class="small-8 columns">';
                        echo '<h3>'.lang('cms_comments').'</h3>';
                    echo '</div>';
                    echo '<div class="small-4 columns">';
                        echo form_open(current_url(), 'id="cms-comments-form-search"');
                            echo '<div class="row">';
                                echo '<div class="row collapse">';
                                    echo '<div class="small-7 columns">';
                                        echo form_input(array('name'=>'search_for', 'placeholder'=>lang('core_search')), set_value('search', ($this->uri->segment($config['filter_key_segment']) == 'search') ? $this->uri->segment($config['filter_value_segment']) : NULL));
                                    echo '</div>';
                                    echo '<div class="small-5 columns">';
                                        echo form_submit(array('name'=>'search', 'class'=>'small-11 button secondary tiny', 'title'=>lang('cms_comments_search')), lang('cms_comments_search'));
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo form_close();
                    echo '</div>';
                echo '</div>';
                echo '<div class="row">';
                    $show_updatecomment = ($this->uri->segment(4) != 'update' && $this->uri->segment(4) != 'reply') ? 'hide' : NULL;
                    $show_largecomments = ($this->uri->segment(4) != 'update' && $this->uri->segment(4) != 'reply') ? 'small-12' : 'small-8';
                    echo '<div class="small-4 '.$show_updatecomment.' columns">';
                        $query_comment = NULL;
                        if ($this->uri->segment(4) == 'update' && $this->uri->segment(5) != NULL):
                            $query_comment = $this->comments->get_by_id($this->uri->segment(5))->row();
                        endif;
                        echo '<h5>';
                            echo ($this->uri->segment(4) == 'update') ? lang('cms_comments_update') : lang('cms_comments_reply');
                        echo '</h5>';
                        echo form_open(current_url(), 'id="cms-comments-form-update"');
                            if ($this->uri->segment(4) == 'update' && $this->uri->segment(5) != NULL):
                                echo '<div class="table-operations">';
                                    echo form_radio(array('name'=>'comment_status', 'id'=>'approved'), lang('cms_comments_operation_approved'), ($query_comment->comment_status == 'approved') ? TRUE : FALSE).form_label(lang('cms_comments_operation_approved'), 'approved', array('class'=>'approve'));
                                    echo form_radio(array('name'=>'comment_status', 'id'=>'unapproved'), lang('cms_comments_operation_unapproved','cms'), ($query_comment->comment_status == 'unapproved') ? TRUE : FALSE).form_label(lang('cms_comments_operation_pendent'), 'unapproved', array('class'=>'unapprove'));
                                echo '</div>';
                                echo form_label(lang('cms_comments_field_post'));
                                echo form_input(array('name'=>'post_title', 'placeholder'=>lang('cms_comments_field_post_title'), 'disabled'=>'disabled'), set_value('post_title', $this->posts->get_by_id($query_comment->comment_postid)->row()->post_title));
                            endif;
                            echo '<div class="row">';
                                echo '<div class="small-12 columns">';
                                    echo form_label(lang('cms_comments_field_name'));
                                    echo form_input(array('name'=>'comment_author', 'placeholder'=>lang('cms_comments_field_name')), set_value('comment_author', ($this->uri->segment(4) == 'update') ? $query_comment->comment_author : get_session('user_displayname')), 'autofocus');
                                    echo form_label(lang('cms_comments_field_email'));
                                    echo form_input(array('name'=>'comment_authoremail', 'placeholder'=>lang('cms_comments_field_email')), set_value('comment_authoremail', ($this->uri->segment(4) == 'update') ? $query_comment->comment_authoremail : get_session('user_email')));
                                    echo form_label(lang('cms_comments_field_url'));
                                    echo form_input(array('name'=>'comment_authorurl', 'placeholder'=>lang('cms_comments_field_url')), set_value('comment_authorurl', ($this->uri->segment(4) == 'update') ? $query_comment->comment_authorurl : get_usermeta('user_url', get_session('user_id'))));
                                    echo form_label(lang('cms_comments_field_comment'));
                                    echo form_textarea(array('name'=>'comment_content', 'rows'=>'5', 'placeholder'=>lang('cms_comments_field_comment')), set_value('comment_content', ($this->uri->segment(4) == 'update') ? $query_comment->comment_content : NULL));
                                    echo form_label(lang('cms_comments_field_date'));
                                    echo form_input(array('name'=>'comment_date', 'class'=>'datetimepicker'), set_value('comment_date', ($this->uri->segment(4) == 'update') ? get_gmt_to_local_date($query_comment->comment_date) : get_gmt_to_local_date(date('Y-m-d H:i:s'))));
                                echo '</div>';
                            echo '</div>';
                            echo form_hidden('idcomment', $this->uri->segment(5));
                            echo form_hidden('idpost', isset($query_comment->comment_postid));
                            echo form_submit(array('name'=>($this->uri->segment(4) == 'update') ? 'update' : NULL, 'class'=>'button radius tiny'), ($this->uri->segment(4) == 'update') ? lang('cms_comments_update') : NULL);
                            echo anchor('cms/comments', ' '.lang('core_cancel'), array('class'=>'alertlink'));
                        echo form_close();
                    echo '</div>';
                    echo '<div class="'.$show_largecomments.' columns">';
                        echo '<div class="row">';
                            echo '<div class="small-12 columns links-filters">';
                                $link_filter_current = ($this->uri->segment($config['filter_value_segment']) == NULL) ? 'link-filter-current' : NULL;
                                echo "<a class='link-filter-first ".$link_filter_current."' href='".$config['pagination_url']."'>".lang('cms_all')." (".$this->comments->get_all()->num_rows().")</a>";
                                if ($this->comments->get_by_status('unmoderated')->num_rows() > 0):
                                    $link_filter_current = ($this->uri->segment($config['filter_key_segment']) == 'filter_status' && $this->uri->segment($config['filter_value_segment']) == 'unmoderated') ? 'link-filter-current' : NULL;
                                    echo "<a class='".$link_filter_current."' href='".$config['pagination_url'].'/0/orderby/'.$config['default_orderby'].'/order/'.$config['default_order']."/filter_status/unmoderated"."'>".lang('cms_comments_operation_pendent')." (".$this->comments->get_by_status('unapproved')->num_rows().")</a>";
                                endif;
                                if ($this->comments->get_by_status('approved')->num_rows() > 0):
                                    $link_filter_current = ($this->uri->segment($config['filter_key_segment']) == 'filter_status' && $this->uri->segment($config['filter_value_segment']) == 'approved') ? 'link-filter-current' : NULL;
                                    echo "<a class='".$link_filter_current."' href='".$config['pagination_url'].'/0/orderby/'.$config['default_orderby'].'/order/'.$config['default_order']."/filter_status/approved"."'>".lang('cms_comments_operation_approved')." (".$this->comments->get_by_status('approved')->num_rows().")</a>";
                                endif;
                                if ($this->comments->get_by_status('unapproved')->num_rows() > 0):
                                    $link_filter_current = ($this->uri->segment($config['filter_key_segment']) == 'filter_status' && $this->uri->segment($config['filter_value_segment']) == 'unapproved') ? 'link-filter-current' : NULL;
                                    echo "<a class='".$link_filter_current."' href='".$config['pagination_url'].'/0/orderby/'.$config['default_orderby'].'/order/'.$config['default_order']."/filter_status/unapproved"."'>".lang('cms_comments_operation_rejected')." (".$this->comments->get_by_status('unapproved')->num_rows().")</a>";
                                endif;
                            echo '</div>';
                        echo '</div>';
                        echo '<table class="small-12" id="cms-comments-table-list">';
                            echo '<thead>';
                                echo '<tr class="table-order">';
                                    echo '<th class="small-8 collums">'.get_th_orderby(lang('cms_comments_field_comment'), 'comment_content', $config).'</th>';
                                    echo '<th class="small-4 collums">'.get_th_orderby(lang('cms_comments_field_author'), 'comment_author', $config).'</th>';
                                echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                                $query_numrows = 0;
                                foreach (get_query($config)->result() as $line):
                                    $query_post = $this->posts->get_by_id($line->comment_postid)->row();
                                    if($line->comment_status == 'approved'):
                                        $comment_status = ' <small class="approve">('.lang('cms_comments_operation_approved').')</small>';
                                    elseif($line->comment_status == 'unapproved'):
                                        $comment_status = ' <small class="unapprove">('.lang('cms_comments_operation_rejected').')</small>';
                                    else:
                                        $comment_status = ' <strong class="delete">('.lang('cms_comments_operation_pendent').')</strong>';
                                    endif;
                                    echo '<tr>';
                                        printf('<td class="table-operations">'.lang('cms_comments_posted_in').': %s<br>%s '.lang('cms_comments_in_post').': %s<br>%s%s%s%s</td>',
                                            get_gmt_to_local_date($line->comment_date).$comment_status,
                                            $line->comment_content,
                                            anchor("post/$line->comment_postid", $query_post->post_title, array('target'=>'_black')),
                                            ($line->comment_status == 'approved' || $line->comment_status == 'unmoderated' || $line->comment_status == 'trash') ? anchor("cms/posts/comments/unapprove/$line->comment_id", lang('cms_comments_action_reject'), array('class'=>'table-action table-action-first unapprove')) : NULL,
                                            ($line->comment_status == 'unapproved' || $line->comment_status == 'unmoderated' || $line->comment_status == 'trash') ? anchor("cms/posts/comments/approve/$line->comment_id", lang('cms_comments_action_approve'), array('class'=>'table-action table-action-first approve')) : NULL,
                                            anchor("cms/posts/comments/update/$line->comment_id", lang('core_update'), array('class'=>'table-action update')),
                                            ($line->comment_status != 'trash') ? anchor("cms/posts/comments/delete/$line->comment_id", lang('core_delete'), array('class'=>'table-action delete deletereg')) : NULL);
                                        echo '<td class="table-operations"> '.avatar(get_usermeta("user_image", $line->comment_authorid), 40, 40);
                                        printf("<strong>%s</strong><br>%s<br>%s</td>",
                                            ($line->comment_authorid == '1') ? anchor("users/profile/$line->comment_authorid", $line->comment_author) : $line->comment_author,
                                            mailto($line->comment_authoremail),
                                            auto_link($line->comment_authorurl));
                                    echo '</tr>';
                                    $query_numrows++;
                                endforeach;
                            echo '</tbody>';
                            echo '<thead>';
                                echo '<tr class="table-order">';
                                    echo '<th class="small-8 collums">'.get_th_orderby(lang('cms_comments_field_comment'), 'comment_content', $config).'</th>';
                                    echo '<th class="small-4 collums">'.get_th_orderby(lang('cms_comments_field_author'), 'comment_author', $config).'</th>';
                                echo '</tr>';
                            echo '</thead>';
                        echo '</table>';
                        echo '<div class="row">';
                            echo '<div class="small-12 medium-6 large-4 columns">';
                                $rows = get_query($config, 'all')->num_rows();
                                echo "<small>".lang('core_showing')." ".$query_numrows." - $rows ".lang('core_registers')."</small>";
                            echo '</div>';
                            echo '<div class="small-12 medium-6 large-8 columns">';
                                get_pagination($config);
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
}