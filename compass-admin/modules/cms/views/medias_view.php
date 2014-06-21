<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
    case 'insertmediasmodal':
        echo '<div id="modalimg" class="reveal-modal medium" data-reveal >';
            echo '<div class="row collapse">';
                echo '<div class="small-6 collapse columns">';
                    echo form_input(array('name'=>'search_img', 'class'=>'searchTXT'));
                echo '</div>';
                echo '<div class="small-2 columns">';
                    echo form_button(NULL, lang('cms_medias_show_images'), 'class="searchIMG button postfix small-6 columns"');
                echo '</div>';
                echo '<div class="small-2 columns">';
                    echo form_button(NULL, lang('cms_medias_clear'), 'class="limparimg button postfix secondary radius"');
                echo '</div>';
                echo '<div class="small-2 columns">';
                    echo anchor('#', lang('cms_medias_upload'), 'class="limparimg button postfix secondary radius" data-reveal-id="modalimg-upload" data-reveal title="Upload de imagens"');
                echo '</div>';
            echo '</div>';
            echo '<div class="return">&nbsp;</div>';
            echo '<a class="close-reveal-modal">&#215;</a>';
            echo '<small>'.lang('cms_medias_insertmedias_small').'</small>';
        echo '</div>';
        break;
    case 'uploadmediasmodal':
        echo '<div id="modalimg-upload" class="reveal-modal small" data-reveal >';
            echo '<div class="row">';
                echo '<iframe src="'.base_url().'cms/medias/uploadmediasmodal/" class="small-12 columns" height="250px" scrolling="no" frameborder="0"></iframe>';
            echo '</div>';
            echo '<div class="row collapse">';
                echo '<small class="small-8 columns">'.lang('cms_medias_uploadmedias_small').'</small>';
                echo anchor('#', lang('cms_medias_insert'), 'class="button tiny alert radius small-4 columns" data-reveal-id="modalimg" data-reveal');
            echo '</div>';
            echo '<a class="close-reveal-modal">&#215;</a>';
        echo '</div>';
        break;
    case 'medias':
        echo '<div class="row" id="cms-medias">';
            echo '<div class="small-12 columns">';
                echo '<div class="row">';
                    echo '<div class="small-8 columns">';
                        echo '<h3>'.lang('cms_medias').'</h3>';
                    echo '</div>';
                    echo '<div class="small-4 columns">';
                        echo form_open(current_url(), 'id="cms-medias-form-search"');
                            echo '<div class="row">';
                                echo '<div class="row collapse">';
                                    echo '<div class="small-7 columns">';
                                        echo form_input(array('name'=>'search_for', 'placeholder'=>lang('core_search')), set_value('search', ($this->uri->segment($config['filter_key_segment']) == 'search') ? $this->uri->segment($config['filter_value_segment']) : NULL));
                                    echo '</div>';
                                    echo '<div class="small-5 columns">';
                                        echo form_submit(array('name'=>'search', 'class'=>'small-11 button secondary tiny', 'title'=>lang('cms_medias_search')), lang('cms_medias_search'));
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo form_close();
                    echo '</div>';
                echo '</div>';
                echo '<div class="row">';
                    echo '<div class="small-4 columns">';
                        $actionmedia = $this->uri->segment(4);
                        $idmedia = $this->uri->segment(5);
                        if ($idmedia != NULL && $actionmedia == 'update'):
                            $query_media = $this->posts->get_by_id($idmedia)->row();
                            $idmedia = $this->uri->segment(5);
                        else:
                            $idmedia = NULL;
                        endif;
                        if ($actionmedia == 'update' && $idmedia == NULL):
                            set_msg('msgerror', lang('cms_medias_not_found_to_edit'), 'error');
                            redirect('cms/medias');
                        endif;
                        echo '<h5>';
                            echo ($idmedia != NULL && $actionmedia == 'update') ? lang('cms_medias_update') : lang('cms_medias_insert');
                        echo '</h5>';
                        echo form_open_multipart(current_url(), 'id="cms-medias-form-insert"');
                            if ($actionmedia == NULL):
                                echo '<div class="row">';
                                    echo '<div class="small-12 columns">';
                                        echo form_label(lang('cms_medias_field_file'));
                                        echo form_upload(array('name'=>'media_file'), set_value('media_file'));
                                        echo '<small><em>'.lang('cms_medias_field_file_small').'</em></small><br/><br/>';
                                    echo '</div>';
                                echo '</div>';
                            endif;
                            if ($actionmedia == 'update'):
                                echo '<div class="row">';
                                    echo '<div class="small-12 columns">';
                                        echo thumb($query_media->post_content, 300, 180);
                                    echo '</div>';
                                echo '</div>';
                            endif;
                            if ($actionmedia != 'saved'): 
                                echo '<div class="row">';
                                    echo '<div class="small-12 columns">';
                                        echo form_label(lang('cms_medias_field_name'));
                                        echo form_input(array('name'=>'media_name', 'placeholder'=>lang('cms_medias_field_name')), set_value('media_name', ($idmedia != NULL && $actionmedia == 'update')? $query_media->post_title : NULL));
                                        echo '<small><em>'.lang('cms_medias_field_name_small').'</em></small><br/><br/>';
                                    echo '</div>';
                                echo '</div>';
                                echo '<div class="row">';
                                    echo '<div class="small-12 columns">';
                                        echo form_label(lang('cms_medias_field_description'));
                                        echo form_textarea(array('name'=>'media_description', 'rows'=>'3', 'placeholder'=>lang('cms_medias_field_description')), set_value('media_description', ($idmedia != NULL && $actionmedia == 'update')? $query_media->post_excerpt : NULL));
                                        echo '<small><em>'.lang('cms_medias_field_description_small').'</em></small><br/><br/>';
                                    echo '</div>';
                                echo '</div>';
                                if ($idmedia != NULL && $actionmedia == 'update'):
                                    echo form_hidden('idmedia', $idmedia);
                                endif;
                                echo form_submit(array('name'=>($idmedia != NULL && $actionmedia == 'update') ? 'update':'save', 'class'=>'button radius tiny'), ($idmedia != NULL && $actionmedia == 'update') ? lang('cms_medias_update') : lang('cms_medias_insert'));
                                if ($idmedia != NULL && $actionmedia == 'update'):
                                    echo anchor('cms/medias', ' '.lang('core_cancel'), array('class'=>'alertlink'));
                                endif;
                            else:
                                $query_media = $this->posts->get_by_id($this->uri->segment(5))->row();
                                echo '<div class="row">';
                                    echo '<div class="small-12 columns">';
                                        echo thumb($query_media->post_content, 300, 180);
                                    echo '</div>';
                                    echo '<div class="small-12 columns">';
                                        echo '<div class="row collapse">';
                                            echo '<div class="small-10 columns">';
                                                echo '<input type="text" value="'.base_url('compass-content/uploads/medias').'/'.$query_media->post_content.'" class="select" />';
                                            echo '</div>';
                                            echo '<div class="small-2 columns">';
                                                echo anchor_popup(base_url('compass-content/uploads/medias').'/'.$query_media->post_content, '<i class="fa fa-picture-o"></i>', array('class'=>'button tiny', 'title'=>lang('core_view')));
                                            echo '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                    echo '<div class="small-12 columns">';
                                        echo '<small><em>'.lang('cms_medias_insert_small').'</em></small><br/><br/>';
                                    echo '</div>';
                                    echo '<div class="small-12 columns">';
                                        echo anchor('cms/medias', '← '.lang('cms_medias_return_medias'), array('class'=>'alertlink'));
                                    echo '</div>';
                                echo '</div>';
                            endif;
                        echo form_close();
                    echo '</div>';
                    echo '<div class="small-8 columns">';
                        echo '<table class="small-12" id="cms-medias-table-list">';
                            echo '<thead>';
                                echo '<tr class="table-order">';
                                    echo '<th class="small-2 collums">'.lang('cms_medias_field_mini').'</th>';
                                    echo '<th class="small-2 collums">'.get_th_orderby(lang('cms_medias_field_name'), 'post_title', $config).'</th>';
                                    echo '<th class="small-6 collums">'.get_th_orderby(lang('cms_medias_field_link'), 'post_link', $config).'</th>';
                                    echo '<th class="small-2 collums">'.get_th_orderby(lang('cms_medias_field_date'), 'post_date', $config).'</th>';
                                echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                                $query_numrows = 0;
                                foreach (get_query($config)->result() as $line):
                                    if ($line->post_type == 'media'):
                                        echo '<tr>';
                                            $media_ext = substr($line->post_content, strlen($line->post_content)-3, 3);
                                            $media_image = ($media_ext == 'txt' || $media_ext =='pdf' || $media_ext == 'zip' || $media_ext == 'doc') ? '<i class="fa fa-file-o large-i-image"><small>.'.$media_ext.'</small></i>' : thumb($line->post_content, 100, 100);
                                            printf('<td>%s</td>', $media_image);
                                            printf('<td class="table-operations"><strong>%s</strong><br>%s %s</td>', 
                                                anchor_popup(base_url('compass-content/uploads/medias').'/'.$line->post_content, $line->post_title, array('title'=>lang('core_view'))),
                                                anchor("cms/medias/index/update/$line->post_id", lang('core_update'), array('class'=>'table-action table-action-first update')), 
                                                anchor("cms/medias/index/delete/$line->post_id", lang('core_delete'), array('class'=>'table-action delete deletereg')));
                                            printf('<td><input type="text" value="%s" class="select" /></td>', 
                                                base_url('compass-content/uploads/medias').'/'.$line->post_content);
                                            printf('<td>%s</td>', $line->post_date);
                                        echo '</tr>';
                                        $query_numrows++;
                                    endif;
                                endforeach;
                            echo '</tbody>';
                            echo '<thead>';
                                echo '<tr class="table-order">';
                                    echo '<th class="small-2 collums">'.lang('cms_medias_field_mini').'</th>';
                                    echo '<th class="small-3 collums">'.get_th_orderby(lang('cms_medias_field_name'), 'post_title', $config).'</th>';
                                    echo '<th class="small-5 collums">'.get_th_orderby(lang('cms_medias_field_link'), 'post_link', $config).'</th>';
                                    echo '<th class="small-2 collums">'.get_th_orderby(lang('cms_medias_field_date'), 'post_date', $config).'</th>';
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
    case 'insertmedias':
        echo '<div class="small-12 small-centered columns">';
            get_msg('msgerror').get_msg('msgok').errors_validating();
            $actionmedia = $this->uri->segment(4);
            if ($actionmedia != 'saved'): echo '<h5>'.lang('cms_medias_insert').'</h5>'; endif;
            echo form_open_multipart(current_url(), 'id="cms-medias-form-insertmedias"');
                if ($actionmedia != 'saved'):
                    echo form_upload(array('name'=>'media_file'), set_value('media_file'));
                endif;
                if ($this->uri->segment(4) == NULL || $this->uri->segment(4) != 'saved'):
                    echo form_input(array('name'=>'media_name', 'placeholder'=>lang('cms_medias_field_name')), set_value('media_name'));
                    echo form_textarea(array('name'=>'media_description', 'rows'=>'2', 'placeholder'=>lang('cms_medias_field_description')), set_value('media_description'));
                    echo form_submit(array('name'=>'save', 'class'=>'button radius tiny'), lang('cms_medias_insert'));
                elseif ($this->uri->segment(4) == 'saved'):
                    $query_media = $this->posts->get_by_id($this->uri->segment(5))->row();
                    echo '<div class="small-7 small-centered columns">';
                        echo thumb($query_media->post_content, 150, 150);
                    echo '</div>';
                    echo '<div class="row collapse">';
                        echo '<div class="small-10 columns">';
                            echo '<input type="text" value="'.base_url('compass-content/uploads/medias').'/'.$query_media->post_content.'" class="select" />';
                        echo '</div>';
                        echo '<div class="small-2 columns">';
                            echo anchor_popup(base_url('compass-content/uploads/medias').'/'.$query_media->post_content, '<i class="fa fa-picture-o"></i>', array('class'=>'button tiny', 'title'=>lang('core_view')));
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="small-12 columns">';
                        echo '<small><em>'.lang('cms_medias_insert_small').'</em></small><br/><br/>';
                    echo '</div>';
                endif;
            echo form_close();
        echo '</div>';
        break;
}