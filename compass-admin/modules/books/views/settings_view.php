<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
    case 'settings':
		echo '<div class="row" id="cms-settings">';
            echo '<div class="small-12 columns">';
                echo '<div class="row">';
					echo '<div class="small-12 columns">';
                		echo '<h3 class="left">'.lang('books_settings').'</h3>';
                    echo '</div>';
                    echo '<div class="small-12 columns">';
                        echo form_open(current_url());
							echo '<div class="row">';
			                    echo '<div class="small-12 columns">';
			                        echo '<h5>'.lang('books_loans').'</h5>';
			                    echo '</div>';
								echo '<div class="small-4 columns">';
									echo form_label(lang('books_settings_number_days'));
								echo '</div>';
								echo '<div class="small-8 columns">';
									echo form_input(array('name'=>'books_loans_number_days'), set_value('books_loans_number_days', get_setting('books_loans_number_days')));
								echo '</div>';
								echo '<div class="small-4 columns">';
									echo form_label(lang('books_settings_number_books'));
								echo '</div>';
								echo '<div class="small-8 columns">';
									echo form_input(array('name'=>'books_loans_max_for_user'), set_value('books_loans_max_for_user', get_setting('books_loans_max_for_user')));
								echo '</div>';
								echo '<div class="small-12 columns">';
			                        echo '<h5>'.lang('books_field_reference').'</h5>';
			                    echo '</div>';
			                    echo '<div class="small-4 columns">';
									echo form_label(lang('books_settings_model_reference'));
								echo '</div>';
								echo '<div class="small-8 columns">';
									echo form_textarea(array('name'=>'books_reference_model', 'rows'=>3), set_value('books_reference_model', get_setting('books_reference_model')));
									echo lang('books_settings_reference_small');
								echo '</div>';
								echo '<div class="small-12 columns">';
			                        echo '<h5>'.lang('permissions').'</h5>';
			                    echo '</div>';
			                    echo '<div class="small-12 columns">';
				                    echo '<table id="books-settings-table-permissions">';
						                echo '<thead>';
						                    echo '<tr>';
						                        echo '<th class="small-2 collums">'.lang('permissions').'/'.lang('users_levels').'</th>';
						                            $query_userslevels = $this->userslevels->get_all()->result();
						                            foreach ($query_userslevels as $line):
						                                echo '<th class="small-1 collums">('.$line->userlevel_id.') '.$line->userlevel_name.'</th>';
						                            endforeach;
						                    echo '</tr>';
						                echo '</thead>';
						                echo '<tbody>';
						                    $alllabelspermissions = array(
						                        lang('books_settings_perm_lists'),
						                        lang('books_settings_perm_view'),
						                        lang('books_settings_perm_insert'),
						                        lang('books_settings_perm_update'),
						                        lang('books_settings_perm_delete'),
						                        lang('books_settings_perm_delete'),
						                        lang('books_settings_perm_settigns')
						                        );
						                    $allpermissions = array(
						                        'perm_bookslist_',
						                        'perm_booksview_',
						                        'perm_booksinsert_',
						                        'perm_booksupdate_',
						                        'perm_booksdelete_',
						                        'perm_booksloansgerencie_',
						                        'perm_bookssettings_'
						                    );
						                    $labelarray = 0;
						                    foreach ($allpermissions as $permission):
						                        echo '<tr>';
						                            if ($permission):
						                                echo '<td>'.$alllabelspermissions[$labelarray].'</td>';
						                                $labelarray++;
						                                $columnarray = 1;
						                                foreach ($query_userslevels as $line):
						                                    if ($permission == 'perm_ative_'):
						                                        echo get_permission($permission, $line->userlevel_id);
						                                    else:
						                                        echo get_permission($permission, $line->userlevel_id, TRUE);
						                                    endif;
						                                    $columnarray++;
						                                endforeach;
						                            endif;
						                        echo '</tr>';
						                    endforeach;
						                echo '</tbody>';
						            echo '</table>';
								echo '</div>';
							echo '</div>';
							echo form_submit(array('name'=>'save', 'class'=>'button radius tiny'), lang('core_save_settings'));
						echo form_close();
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        break;
}