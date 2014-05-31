<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
    case 'submenu':
        echo 
            make_menu('submenu_item', lang('tools'), 'tools').
            make_menu('submenu_item', lang('tools_audits'), 'tools', 'audits')
        ;
        break;
    case 'audits':
        echo '<div id="tools-audits" class="row">';
            echo '<div class="small-12 columns">';
                echo '<h3>'.lang('tools_audits').'</h3>';
                echo '<table id="tools-audits-table-list" class="small-12 data-table">';
                    echo '<thead>';
                        echo '<tr>';
                            echo '<th class="collums"></th>';
                            echo '<th class="small-2 collums">'.lang('tools_audits_user').'</th>';
                            echo '<th class="small-2 collums">'.lang('tools_audits_time').'</th>';
                            echo '<th class="small-3 collums">'.lang('tools_audits_operation').'</th>';
                            echo '<th class="small-3 collums">'.lang('tools_audits_obs').'</th>';
                            echo '<th class="small-2 collums">'.lang('tools_audits_type').'</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                        $this->db->limit(100);
                        $queryaudit = $this->audits->get_all()->result();
                        $count = 1;
                        foreach ($queryaudit as $line):
                            $queryuser = $this->users->get_by_id($line->audit_userid)->row();
                            echo '<tr>';
                            printf('<td>%s</td>', $count);
                            printf('<td>%s</td>', ($line->audit_userid != 0)?$queryuser->user_displayname:'Desconhecido');
                            printf('<td>%s</td>', date('d/m/Y H:i:s', strtotime($line->audit_date)));
                            printf('<td>%s</td>', '<span data-tooltip class="has-tip tip-top" title="'.$line->audit_query.'">'.$line->audit_process.'</span>');
                            printf('<td>%s</td>', $line->audit_description);
                            printf('<td>%s</td>', $line->audit_type);
                            echo '</tr>';
                            $count ++;
                        endforeach;
                    echo '</tbody>';
                echo '</table>';
            echo '</div>';
        echo '</div>';
        break;
	default:
		echo '<div class=""><p>A tela solicitada não existe</p></div>';
		break;
}