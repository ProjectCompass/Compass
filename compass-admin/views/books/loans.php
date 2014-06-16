<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
	case 'loans':
		echo '<div id="books-index" class="row">';
            echo '<div class="small-12 columns">';
                echo '<h3 class="left">'.lang('books_loans').'</h3>';
                echo anchor('books/loans/insert', lang('books_loans_insert'), 'class="addimg button button-tiny secondary radius space-v-medium space-h-small"');
                echo anchor('books/loans/historic', lang('books_loans_historic'), 'class="addimg button button-tiny radius space-v-medium space-h-small"');
            echo '</div>';
        echo '</div>';
        echo '<table id="users-index-table-list" class="small-12 columns">';
            echo '<thead>';
                echo '<tr class="table-order">';
                    echo '<th class="small-1"><div>'.lang('books_loans_field_book').'</div></th>';
                    echo '<th class="small-4"></th>';
                    echo '<th class="small-3">'.lang('books_loans_field_loan').'</th>';
                    echo '<th class="small-3">'.lang('books_loans_field_user').'</th>';
                    echo '<th class="small-1"></th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
                $query_numrows = 0;
                foreach (get_query($config)->result() as $line):
                    if ($line->loan_status == 1):
                        $queryuser = $this->users->get_by_id($line->loan_user_id)->row();
                        $querybook = $this->books->get_by_id($line->loan_book_id)->row();
                        $userimage = (isset($this->usermeta->get_by_key('user_image', $line->loan_user_id)->row()->usermeta_value))?$this->usermeta->get_by_key('user_image', $line->loan_user_id)->row()->usermeta_value:NULL;
                        echo '<tr>';
                        printf('<td>%s</td>', thumb($querybook->book_cover, 100, 100));
                        printf('<td>
                                <h5><strong>%s</strong></h5><br>
                                <strong>'.lang('books_field_author').': </strong>%s<br>
                                <strong>'.lang('books_field_register').': </strong>%s<br>
                                <strong>'.lang('books_field_publication').': </strong>%s Ed., %s, %s, %s.',
                            $querybook->book_title, $querybook->book_author,$querybook->book_register, $querybook->book_edition, $querybook->book_publisher, $querybook->book_city_publisher, $querybook->book_year_publisher);
                        printf('<td><strong>'.lang('books_loans_date_of_loan').': </strong><br>%s<br>
                                <strong>'.lang('books_loans_date_for_delivery').': </strong><br>%s<br>
                                %s%s
                                </td>',
                            date(get_setting('general_date_format'), strtotime(get_gmt_to_local_date($line->loan_date))).' '.date(get_setting('general_time_format'), strtotime(get_gmt_to_local_date($line->loan_date))),
                            date(get_setting('general_date_format'), strtotime("+".get_setting('books_number_days')." days",strtotime(get_gmt_to_local_date($line->loan_date)))),
                            anchor('books/loans/finish/'.$line->loan_id, lang('books_loans_finish'), array('class'=>'button tiny small-6 collumns')).' ',
                            anchor_popup('books/loans/details/'.$line->loan_id, lang('books_loans_details'), array('class'=>'button secondary tiny small-5 collumns'))
                            );
                        printf('<td>
                                <h6><strong>%s</strong> (%s)</h6><br>
                                <strong>'.lang('books_loans_field_user_email').': </strong>%s', 
                            $queryuser->user_name, $queryuser->user_displayname, $queryuser->user_email);
                        printf('<td>%s</td>', avatar($userimage, 100, 100));
                        echo '</tr>';
                        $query_numrows++;
                    endif;
                endforeach;
            echo '</tbody>';
            echo '<thead>';
                echo '<tr class="table-order">';
                    echo '<th class="small-1"><div>'.lang('books_loans_field_book').'</div></th>';
                    echo '<th class="small-4"></th>';
                    echo '<th class="small-3">'.lang('books_loans_field_loan').'</th>';
                    echo '<th class="small-3">'.lang('books_loans_field_user').'</th>';
                    echo '<th class="small-1"></th>';
                echo '</tr>';
            echo '</thead>';
        echo '</table>';
		break;
	case 'insert_loan':
		$user_id = $this->uri->segment(5);
        $book_id = $this->uri->segment(7);
        if ($user_id != NULL && $user_id != 'no'):
            $queryuser = $this->users->get_by_id($user_id)->row();
            if ($queryuser != TRUE):
                set_msg('msgerror', lang('books_loans_msg_user_inactive'), 'error');
                redirect('books/loans/insert/user/no/book/'.$book_id.'');
            endif;
            $userimage = (isset($this->usermeta->get_by_key('user_image', $queryuser->user_id)->row()->usermeta_value))?$this->usermeta->get_by_key('user_image', $queryuser->user_id)->row()->usermeta_value:NULL;
        endif;
        if ($book_id!=NULL && $book_id!='no'):
            $querybook = $this->books->get_by_id($book_id)->row();
            if ($querybook != TRUE):
                set_msg('msgerror', lang('books_loans_msg_book_inactive'), 'error');
                redirect('loans/add/'.$user_id.'/no');
            endif;
        endif;
        echo form_open(current_url(), array('id'=>'form-books-loans-insert'));
            echo '<div class="row">';
	            echo '<div class="small-12 columns">';
	                echo '<h3 class="left">'.lang('books_loans_perform').'</h3>';
	            echo '</div>';
                echo '<div class="small-12 small-centered columns">';
                	$user_url = ($user_id != NULL && $user_id != NULL && isset($queryuser) == TRUE) ? '/user/'.$user_id : '/user/no';
	                $book_url = ($book_id != NULL && $book_id != NULL && isset($querybook) == TRUE) ? '/book/'.$book_id : '/book/no';
                    echo '<div class="small-3 columns">';
	                    if ($user_id != NULL && $user_id != 'no'):
	                        echo '<ul class="pricing-table row">';
	                            echo '<li class="title">'.lang('books_loans_field_user').'</li>';
	                            echo '<li class="bullet-item">'.avatar($userimage, 150, 150).'<br>'.$queryuser->user_name.' ('.$queryuser->user_displayname.')</li>';
	                            echo '<li class="bullet-item">'.$queryuser->user_email.'</li>';
	                        echo '</ul>';
	                        echo anchor('books/loans/user'.$user_url.$book_url, lang('books_loans_action_replace_user'), array('class'=>'button radius tiny small-12 columns'));
	                        echo anchor('books/loans/insert/user/no'.$book_url, lang('books_loans_action_remove_user'), array('class'=>'button radius alert tiny small-12 columns'));
	                    else:
	                         echo '<ul class="pricing-table row">';
	                            echo '<li class="title">'.lang('books_loans_field_user').'</li>';
	                            echo '<li class="bullet-item"><i class="fa fa-user fa-fw" style="font-size:150px; margin-left:-15px;"></i></li>';
	                        echo '</ul>';
	                        echo anchor('books/loans/user'.$user_url.$book_url, lang('books_loans_action_select_user'), array('class'=>'button radius alert tiny small-12 columns'));
	                    endif;
                    echo '</div>';
                    echo '<div class="small-6 columns center">';
                        echo '<div class="row">';
                            echo date(get_setting('general_date_format'), strtotime(get_gmt_to_local_date(date('Y-m-d H:i:s')))).' '.date(get_setting('general_time_format'), strtotime(get_gmt_to_local_date(date('Y-m-d H:i:s'))));
                            if ($user_id!=NULL && $user_id!='no' && $book_id!=NULL && $book_id!='no'):
                                echo '<div class="textcentered"><i class="fa  fa-thumbs-up fa-fw" style="font-size:100px; margin-left:-15px;"></i></div>';
                                echo '<h5 class="textcentered">'.lang('books_loans_msg_hello').' <strong>'.$this->session->userdata('user_displayname').'</strong>, '.lang('books_loans_msg_select_finish_1').' <strong>'.$querybook->book_title.'</strong> '.lang('books_loans_msg_select_finish_2').' <strong>'.$queryuser->user_displayname.'</strong>.</h5>';
                                echo form_hidden('loan_user_id', $user_id);
                                echo form_hidden('loan_book_id', $book_id);
                                echo '<div class="textcentered">'.form_submit(array('name'=>'save', 'class'=>'button radius'), lang('books_loans_make')).'</div>';
                            elseif ($user_id!=NULL && $user_id!='no'):
                                if ($book_id==NULL || $book_id=='no'):
                                    echo '<div class="textcentered"><i class="fa fa-arrow-circle-o-right fa-fw" style="font-size:100px; margin-left:-15px;"></i></div>';
                                    echo '<h5 class="textcentered">'.lang('books_loans_msg_hello').' <strong>'.$this->session->userdata('user_displayname').'</strong>, '.lang('books_loans_msg_select_book').' <strong>'.$queryuser->user_displayname.'</strong>.</h5>';
                                endif;
                            elseif ($book_id!=NULL && $book_id!='no'):
                                if ($user_id==NULL || $user_id=='no'):
                                    echo '<div class="textcentered"><i class="fa fa-arrow-circle-o-left fa-fw" style="font-size:100px; margin-left:-15px;"></i></div>';
                                    echo '<h5 class="textcentered">'.lang('books_loans_msg_hello').' <strong>'.$this->session->userdata('user_displayname').'</strong>, '.lang('books_loans_msg_select_user').' <strong>'.$querybook->book_title.'</strong>.</h5>';
                                endif;
                            else:
                                echo '<div class="textcentered"><i class="fa  fa-dot-circle-o fa-fw" style="font-size:100px; margin-left:-15px;"></i></div>';
                                    echo '<h5 class="textcentered">'.lang('books_loans_msg_hello').' <strong>'.$this->session->userdata('user_displayname').'</strong>, '.lang('books_loans_msg_select_all').'.</h5>';
                            endif;
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="small-3 columns">';
                    	if ($book_id != NULL && $book_id != 'no'):
	                        echo '<ul class="pricing-table row">';
	                            echo '<li class="title">'.lang('books_loans_field_book').'</li>';
	                            echo '<li class="bullet-item">'.thumb($querybook->book_cover, 150, 150).'<br>'.$querybook->book_title.' (Reg.:'.$querybook->book_register.')</li>';
	                            echo '<li class="bullet-item">'.$querybook->book_author.'</li>';
	                        echo '</ul>';
	                        echo anchor('books/loans/book'.$user_url.$book_url, lang('books_loans_action_replace_book'), array('class'=>'button radius tiny small-12 columns'));
	                        echo anchor('books/loans/insert'.$user_url.'/book/no', lang('books_loans_action_remove_book'), array('class'=>'button radius alert tiny small-12 columns'));
	                    else:
	                         echo '<ul class="pricing-table row">';
	                            echo '<li class="title">'.lang('books_loans_field_book').'</li>';
                            	echo '<li class="bullet-item"><i class="fa fa-book fa-fw" style="font-size:150px; margin-left:-15px;"></i></li>';
	                        echo '</ul>';
	                        echo anchor('books/loans/book'.$user_url.$book_url, lang('books_loans_action_select_book'), array('class'=>'button radius alert tiny small-12 columns'));
	                    endif;
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo form_hidden('user_id', 1);
        echo form_close();
        break;
    case 'loan_book':
		echo '<div id="users-index" class="row">';
            echo '<div class="small-8 columns">';
                echo '<h3 class="left">'.lang('books_loans_book').'</h3>';
            echo '</div>';
            echo '<div class="small-4 columns">';
                echo form_open(current_url(), 'id="users-index-form-search"');
                    echo '<div class="row">';
                        echo '<div class="row collapse">';
                            echo '<div class="small-7 columns">';
                                echo form_input(array('name'=>'search_for', 'placeholder'=>lang('core_search')), set_value('search', ($this->uri->segment($config['filter_key_segment']) == 'search') ? $this->uri->segment($config['filter_value_segment']) : NULL));
                            echo '</div>';
                            echo '<div class="small-5 columns">';
                                echo form_submit(array('name'=>'search', 'class'=>'small-11 button secondary tiny', 'title'=>lang('books_loans_search_book')), lang('books_loans_search_book'));
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo form_close();
            echo '</div>';
        echo '</div>';
        echo '<table id="users-index-table-list" class="columns">';
            echo '<thead>';
                echo '<tr class="table-order">';
                    echo '<th class="small-10 collums"><div>'.lang('books_loans_field_book').'</div></th>';
                    echo '<th class="small-2 collums">'.lang('books_loans_select').'</th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
	        	$user_url = ($this->uri->segment(3) != NULL && $this->uri->segment(3) != NULL) ? '/user/'.$this->uri->segment(5) : '/user/no';
                foreach (get_query($config)->result() as $line):
                	if ($this->loans->get_by_book_id($line->book_id)->num_rows() != 0):
                        $book_select_button = '<div class="button alert tiny active small-12 collumns">'.lang('books_loans_book_loaned').'</div>';
                    else:
                        $book_select_button = anchor('books/loans/insert'.$user_url.'/book/'.$line->book_id ,lang('books_loans_select'), array('class'=>'button tiny small-12 collumns'));
                    endif;
                    echo '<tr>';
                        printf('<td>
                        			<div class="left">%s</div>
                        			<div class="small-8 columns">
	                                    <h3>%s</h3><br>
	                                    <strong>'.lang('books_field_author').': </strong>%s<br>
	                                    <strong>'.lang('books_field_register').': </strong>%s<br>
	                                    <strong>'.lang('books_field_tags').': </strong>%s<br>
	                                    <strong>'.lang('books_field_publication').': </strong>%s Ed. %s: %s, %s, %sp.
	                                </div>
	                            </td>', thumb($line->book_cover, 100, 100),
                                $line->book_title, $line->book_author, $line->book_register, $line->book_tags, $line->book_edition, $line->book_publisher, $line->book_city_publisher, $line->book_year_publisher, $line->book_number_pages);
                    	printf('<td>%s</td>', $book_select_button);
                    echo '</tr>';
                endforeach;
            echo '</tbody>';
            echo '<thead>';
                echo '<tr class="table-order">';
                    echo '<th class="small-10 collums"><div>'.lang('books_loans_field_book').'</div></th>';
                    echo '<th class="small-2 collums">'.lang('books_loans_select').'</th>';
                echo '</tr>';
            echo '</thead>';
        echo '</table>';
        break;
    case 'loan_user':
		echo '<div id="users-index" class="row">';
            echo '<div class="small-8 columns">';
                echo '<h3 class="left">'.lang('books_loans_search_user').'</h3>';
            echo '</div>';
            echo '<div class="small-4 columns">';
                echo form_open(current_url(), 'id="users-index-form-search"');
                    echo '<div class="row">';
                        echo '<div class="row collapse">';
                            echo '<div class="small-7 columns">';
                                echo form_input(array('name'=>'search_for', 'placeholder'=>lang('core_search')), set_value('search', ($this->uri->segment($config['filter_key_segment']) == 'search') ? $this->uri->segment($config['filter_value_segment']) : NULL));
                            echo '</div>';
                            echo '<div class="small-5 columns">';
                                echo form_submit(array('name'=>'search', 'class'=>'small-11 button secondary tiny', 'title'=>lang('books_loans_search_user')), lang('books_loans_search_user'));
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo form_close();
            echo '</div>';
        echo '</div>';
        echo '<table id="users-index-table-list" class="columns">';
            echo '<thead>';
                echo '<tr class="table-order">';
                    echo '<th class="small-6 collums"><div>'.lang('books_loans_field_user').'</div></th>';
                    echo '<th class="small-3 collums">'.lang('books_loans_field_user_email').'</th>';
                    echo '<th class="small-3 collums">'.lang('books_loans_select').'</th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
	        	$book_url = ($this->uri->segment(5) != NULL && $this->uri->segment(5) != NULL) ? '/book/'.$this->uri->segment(7) : '/book/no';
                foreach (get_query($config)->result() as $line):
                	if ($line->user_status == 0):
                        $user_select_button = '<div class="button alert tiny small-12 collumns">'.lang('books_loans_inactive').'</div>';
                    elseif ($line->user_status != 0 && $this->loans->get_by_user_id($line->user_id)->num_rows() > get_setting('books_loans_max_for_user')):
                        $user_select_button = '<div class="button alert tiny small-12 collumns">'.lang('books_loans_limit').'</div>';
                    else:
                        $user_select_button = anchor('books/loans/insert/user/'.$line->user_id.$book_url , lang('books_loans_select'), array('class'=>'button tiny small-12 columns'));
                    endif;
                    if ($line->user_status != 9):
                        $query_userlevel = $this->userslevels->get_by_id($line->user_level)->row();
                        echo '<tr>';
                            echo '<td class="table-operations"> ', avatar(get_usermeta("user_image", $line->user_id), 40, 40);
                            printf("<strong>%s</strong></td>", $line->user_username);
                            printf('<td>%s</td>', $line->user_email);
                            printf('<td>%s</td>', $user_select_button);
                        echo '</tr>';
                    endif;
                endforeach;
            echo '</tbody>';
            echo '<thead>';
                echo '<tr class="table-order">';
                    echo '<th class="small-6 collums"><div>'.lang('books_loans_field_user').'</div></th>';
                    echo '<th class="small-3 collums">'.lang('books_loans_field_user_email').'</th>';
                    echo '<th class="small-3 collums">'.lang('books_loans_select').'</th>';
                echo '</tr>';
            echo '</thead>';
        echo '</table>';
        break;
    case 'details':
    	$idloan = $this->uri->segment(4);
        if ($idloan==NULL):
            set_msg('msgerror', lang('books_loans_msg_select_book_det'), 'error');
            redirect('books');
        endif;
        $query = $this->loans->get_by_id($idloan)->row();
        $queryuser = $this->users->get_by_id($query->loan_user_id)->row();
        $querybook = $this->books->get_by_id($query->loan_book_id)->row();
        echo '<div class="small-12 small-centered columns">';
            echo '<div class="row">';
            	echo '<div class="small-12 columns">';
               		echo '<h3 class="left">'.lang('books_loans_details').'</h3>';
            	echo '</div>';
                echo '<div class="small-12 columns">';
                    echo '<div class="row">';
                        echo '<div class="small-12 columns">';
                            echo '<ul class="vcard small-12 columns">';
                                echo '<div class="row">';
                                    echo '<li class="small-12 columns"><h5><strong>'.strtoupper(lang('books_loans_field_loan')).':</strong></h5></li>';
                                echo '</div>';
                                echo '<div class="row">';
                                    echo '<li class="small-6 columns"><h5><small>'.lang('books_loans_date_of_loan').': </small><br>'.date(get_setting('general_date_format'), strtotime(get_gmt_to_local_date($query->loan_date))).' '.date(get_setting('general_time_format'), strtotime(get_gmt_to_local_date($query->loan_date))).'</h5></li>';
                                    if ($query->loan_status == 1):
                                        echo '<li class="small-6 columns"><h5><small>'.lang('books_loans_date_for_delivery').': </small><br>'.date(get_setting('general_date_format'), strtotime("+".get_setting('books_loans_number_days')." days",strtotime(get_gmt_to_local_date($query->loan_date)))).'</h5></li>';
                                    else:
                                        echo '<li class="small-6 columns"><h5><small>'.lang('books_loans_date_of_delivery').': </small><br>'.date(get_setting('general_date_format'), strtotime(get_gmt_to_local_date($query->loan_date_deliver))).'</h5></li>';
                                    endif;
                                echo '</div>';
                                echo '<div class="row">';
                                    echo '<li class="small-12 columns"><h5><strong>'.strtoupper(lang('books_loans_field_user')).':</strong></h5></li>';
                                    echo '<div class="small-3 columns">'.avatar(get_usermeta('user_image', $queryuser->user_id), 150, 150).'</div>';
                                    echo '<div class="small-9 columns">';
                                        echo '<li class="small-12 columns"><h5><small>'.lang('books_loans_field_user_name').': </small>'.$queryuser->user_name.' ('.$queryuser->user_displayname.')</h5></li>';
                                        echo '<li class="small-12 columns"><h5><small>'.lang('books_loans_field_user_email').'E-mail: </small>'.$queryuser->user_email.'</h5></li>';
                                        echo '<li class="small-12 columns"><h5><small>'.lang('books_loans_field_user_address').': </small>'.get_usermeta('user_adress', $queryuser->user_id).'</h5></li>';
                                        echo '<li class="small-4 columns"><h5><small>'.lang('books_loans_field_user_doc').': </small>'.get_usermeta('user_doc', $queryuser->user_id).'</h5></li>';
                                        echo '<li class="small-8 columns"><h5><small>'.lang('books_loans_field_user_url').': </small>'.get_usermeta('user_url', $queryuser->user_id).'</h5></li>';
                                    echo '</div>';
                                    echo '<li class="small-12 columns"><h5><strong>'.strtoupper(lang('books_loans_field_book')).':</strong></h5></li>';
                                    echo '<div class="small-3 columns">'.thumb($querybook->book_cover, 150, 150).'</div>';
                                    echo '<div class="small-9 columns">';
                                        echo '<li class="small-12 columns"><h5><small>'.lang('books_field_title').': </small>'.$querybook->book_title.'</h5></li>';
                                        echo '<li class="small-12 columns"><h5><small>'.lang('books_field_author').': </small>'.$querybook->book_author.'</h5></li>';
                                        echo '<li class="small-3 columns"><h5><small>'.lang('books_field_register').': </small>'.$querybook->book_register.'</h5></li>';
                                        echo '<li class="small-9 columns"><h5><small>'.lang('books_field_tags').': </small>'.$querybook->book_tags.'</h5></li>';
                                        echo '<li class="small-4 columns"><h5><small>'.lang('books_field_edition').': </small>'.$querybook->book_edition.'</h5></li>';
                                        echo '<li class="small-4 columns"><h5><small>'.lang('books_field_publisher').': </small>'.$querybook->book_publisher.'</h5></li>';
                                        echo '<li class="small-4 columns"><h5><small>'.lang('books_field_year').': </small>'.$querybook->book_year_publisher.'</h5></li>';
                                        echo '<li class="small-4 columns"><h5><small>'.lang('books_field_city').': </small>'.$querybook->book_city_publisher.'</h5></li>';
                                        echo '<li class="small-4 columns"><h5><small>'.lang('books_field_n_pages').': </small>'.$querybook->book_number_pages.'</h5></li>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</ul>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
    case 'historic':
    	echo '<div id="books-index" class="row">';
            echo '<div class="small-12 columns">';
                echo '<h3 class="left">'.lang('books_loans_historic').'</h3>';
                echo anchor('books/loans/insert', lang('books_loans_insert'), 'class="addimg button button-tiny secondary radius space-v-medium space-h-small"');
            echo '</div>';
        echo '</div>';
        echo '<table id="users-index-table-list" class="small-12 columns">';
            echo '<thead>';
                echo '<tr class="table-order">';
                    echo '<th class="small-1"><div>'.lang('books_loans_field_book').'</div></th>';
                    echo '<th class="small-3"></th>';
                    echo '<th class="small-4">'.lang('books_loans_field_loan').'</th>';
                    echo '<th class="small-3">'.lang('books_loans_field_user').'</th>';
                    echo '<th class="small-1"></th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
                $query_numrows = 0;
                $this->db->or_where('loan_status !=', '1');
                foreach (get_query($config)->result() as $line):
                    if ($line->loan_status != 1):
                        $queryuser = $this->users->get_by_id($line->loan_user_id)->row();
                        $querybook = $this->books->get_by_id($line->loan_book_id)->row();
                        $userimage = (isset($this->usermeta->get_by_key('user_image', $line->loan_user_id)->row()->usermeta_value))?$this->usermeta->get_by_key('user_image', $line->loan_user_id)->row()->usermeta_value:NULL;
                        echo '<tr>';
                        printf('<td>%s</td>', thumb($querybook->book_cover, 50, 50));
                        printf('<td>
                                <h5><strong>%s</strong> <small>('.lang('books_field_register_abrev').': <strong>%s</strong>)</small></h5>',
                            $querybook->book_title, $querybook->book_register);
                        printf('<td class="center"><strong>'.lang('books_loans_date_of_loan').': </strong>%s<br>
                        		<strong>'.lang('books_loans_date_of_delivery').': </strong>%s<br>
                        		%s
                                </td>',
                            date(get_setting('general_date_format'), strtotime(get_gmt_to_local_date($line->loan_date))).' '.date(get_setting('general_time_format'), strtotime(get_gmt_to_local_date($line->loan_date))),
                            date(get_setting('general_date_format'), strtotime(get_gmt_to_local_date($line->loan_date_deliver))),
                            anchor_popup('books/loans/details/'.$line->loan_id, lang('books_loans_details'), array('class'=>'small-12 collumns'))
                            );
                        printf('<td>
                                <h6><strong>%s</strong> <small>('.lang('books_loans_field_user_email').': <strong>%s</strong>)</small></h6>', 
                            $queryuser->user_name, $queryuser->user_email);
                        printf('<td>%s</td>', avatar($userimage, 50, 50));
                        echo '</tr>';
                        $query_numrows++;
                    endif;
                endforeach;
            echo '</tbody>';
            echo '<thead>';
                echo '<tr class="table-order">';
                    echo '<th class="small-1"><div>'.lang('books_loans_field_book').'</div></th>';
                    echo '<th class="small-3"></th>';
                    echo '<th class="small-4">'.lang('books_loans_field_loan').'</th>';
                    echo '<th class="small-3">'.lang('books_loans_field_user').'</th>';
                    echo '<th class="small-1"></th>';
                echo '</tr>';
            echo '</thead>';
        echo '</table>';
        echo '<div class="row">';
            echo '<div class="small-12 medium-6 large-4 columns">';
            	$this->db->or_where('loan_status !=', '1');
                $rows = get_query($config, 'all')->num_rows();
                echo "<small>".lang('core_showing')." ".$query_numrows." - $rows ".lang('core_registers')."</small>";
            echo '</div>';
            echo '<div class="small-12 medium-6 large-8 columns">';
            	$this->db->where('loan_status !=', '1');
                get_pagination($config);
            echo '</div>';
        echo '</div>';
		break;
        break;
}