<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
    case 'stats':
        echo '<div class="row" id="cms-stats">';
            echo '<div class="small-12 columns">';
                echo '<h3 class="left">'.lang('cms_stats').'</h3>';
            echo '</div>';
            $all_views = $this->stats->get_all()->num_rows();
            $today_unix = human_to_unix(date('Y-m-d').' 00:00:00');//converte a data atual para unix
            $today_human_min = date('Y-m-d');
            $yesterday_unix = $today_unix - 86400;//subtrai 86400 segundos da data de hoje
            $yesterday_human_min = date("Y-m-d", strtotime(unix_to_human($yesterday_unix, TRUE, 'eu')));//converte a data para human e deixa no formato Y-m
            $today_views = $this->stats->get_by_in('stat_date', $today_human_min)->num_rows();
            $yesterday_views = $this->stats->get_by_in('stat_date', $yesterday_human_min)->num_rows();
            $date_array_interval[] = $today_human_min;
            $repeat = 29;
            $count = 0;
            $prev = 1;
            $query_data = $this->stats->get_all()->result();
            foreach ($query_data as $line):
                if($count <= $repeat):
                    $date_last = human_to_unix(date('Y-m-d').' 00:00:00');//data final que está como a data atual em unix
                    $date_first = human_to_unix(date('Y-m-d').' 00:00:00');//data inicial que está como a data atual em unix
                    $date_first_month = $date_last-86400*$repeat;//data inicial em unix resultado da data atual -30 dias
                    $date_prev_unix = $date_last-86400*$prev;//data atual subtraida da data anterior em unix 
                    $date_prev_human_min = date("Y-m-d", strtotime(unix_to_human($date_prev_unix, TRUE, 'eu')));//data atual em human Y-m-d
                    $date_array_interval[] = $date_prev_human_min;//criação da array de datas
                    ++$prev;//acrescimo do prev
                    ++$count;//acrescimo do count
                endif;
            endforeach;
            foreach ($date_array_interval as $line):
                $date_array_views[] = $this->stats->get_by_in('stat_date', $line)->num_rows();
            endforeach;
            ?>
            <script type="text/javascript">
                $(function () {
                    $('#char_large').highcharts({
                        chart: {
                            type: 'line'
                        },
                        title: {text: '<?php echo lang("cms_stats_pages_views"); ?>',x: -20},
                        subtitle: {text: '',x: -20},
                        yAxis: {
                            title: {enabled: false},
                            plotLines: [{value: 0, width: 1, color: '#808080'}]
                        },
                        tooltip: {valueSuffix: ''},
                        plotOptions: {
                            line: {
                                marker: {
                                    enabled: false,
                                    symbol: 'circle',
                                    radius: 2,
                                    states: {
                                        hover: {
                                            enabled: true
                                        }
                                    }
                                }
                            }
                        },
                        legend: {enabled: false},
                        xAxis: {
                            categories: [
                                <?php 
                                    foreach(array_reverse($date_array_interval) as $line):
                                        echo '"'.date("d/m", strtotime($line)).'",';
                                    endforeach;
                                ?>
                            ]
                        },
                        series: [{
                            name: '<?php echo lang("cms_stats_pages_views"); ?>',
                            data: [
                                <?php 
                                    foreach(array_reverse($date_array_views) as $line):
                                        echo $line.',';
                                    endforeach;
                                ?>
                            ]
                        }]
                    });
                });
            </script>
            <?php
            echo '<div class="small-12 columns" style="height: 300px;">';
                echo '<div id="char_large" style="width: 100%; height: 300px; position:absolute; "></div>';
            echo '</div>';
            echo '<div class="small-12 columns">';
                echo '<table class="small-12">';
                    echo '<tbody>';
                        echo '<tr>';
                            echo '<td class="small-3">'.lang('cms_stats_pages_views_today').'</td>';
                            echo '<td class="small-1">'.$today_views.'</td>';
                            echo '<td class="small-3">'.lang('cms_stats_pages_views_yesterday').'</td>';
                            echo '<td class="small-1">'.$yesterday_views.'</td>';
                            echo '<td class="small-3">'.lang('cms_stats_pages_views_all').'</td>';
                            echo '<td class="small-1">'.$all_views.'</td>';
                        echo '</tr>';
                    echo '</tbody>';
                echo '</table>';
            echo '</div>';
            echo '<div class="small-12 medium-6 large-6 columns">';
                echo '<h5>'.lang('cms_posts').'</h5>';
                echo '<table class="small-12">';
                    echo '<thead>';
                        echo '<th class="small-9">'.lang('cms_stats_entry').'</th>';
                        echo '<th class="small-3">'.lang('cms_stats_views').'</th>';
                    echo '</thead>';
                    echo '<tbody>';
                        $query_data = $this->posts->return_list('5','0','post_views', 'desc', NULL, NULL)->result();
                        foreach ($query_data as $line):
                            echo '<tr>';
                                echo '<td>'.anchor('post/'.$line->post_slug, $line->post_title, array('target'=>'_blanck')).'</td>';
                                echo '<td>'.$line->post_views.'</td>';
                            echo '</tr>';
                        endforeach;
                    echo '</tbody>';
                echo '</table>';
                echo '<h5>'.lang('cms_pages').'</h5>';
                echo '<table class="small-12">';
                    echo '<thead>';
                        echo '<th class="small-9">'.lang('cms_stats_entry').'</th>';
                        echo '<th class="small-3">'.lang('cms_stats_views').'</th>';
                    echo '</thead>';
                    echo '<tbody>';
                        $query_data = $this->posts->return_list_pages('5','0','post_views', 'desc', NULL, NULL)->result();
                        foreach ($query_data as $line):
                            echo '<tr>';
                                echo '<td>'.anchor('page/'.$line->post_slug, $line->post_title, array('target'=>'_blanck')).'</td>';
                                echo '<td>'.$line->post_views.'</td>';
                            echo '</tr>';
                        endforeach;
                    echo '</tbody>';
                echo '</table>';
            echo '</div>';
            echo '<div class="small-12 medium-6 large-6 columns">';
                $query_data = $this->stats->get_all()->result();
                $browser_ie = 0;
                $browser_opera= 0;
                $browser_firefox = 0;
                $browser_chrome = 0;
                $browser_safari = 0;
                $browser_other = 0;
                $so_windowns = 0;
                $so_iphone = 0;
                $so_mac = 0;
                $so_linux = 0;
                $so_other = 0;
                foreach ($query_data as $line):
                    $useragent = $line->stat_browser;
                    if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)):
                        ++$browser_ie;
                    elseif (preg_match( '|Opera/([0-9].[0-9]{1,2})|',$useragent,$matched)):
                        ++$browser_opera;
                    elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)):
                        ++$browser_firefox;
                    elseif(preg_match('|Chrome/([0-9\.]+)|',$useragent,$matched)):
                        ++$browser_chrome;
                    elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)):
                        ++$browser_safari;
                    else:
                        ++$browser_other;
                    endif;
                    $useragent = strtolower($useragent);
                    if (strpos("$useragent","windows nt 6.2") !== false || strpos("$useragent","windows nt 5.1") !== false || strpos("$useragent","windows nt 6.0") !== false || strpos("$useragent","windows nt 6.1") !== false || strpos("$useragent","windows 98") !== false || strpos("$useragent","windows nt 5.0") !== false || strpos("$useragent","windows nt 5.2") !== false || strpos("$useragent","windows nt 6.0") !== false || strpos("$useragent","windows nt") !== false):
                        ++$so_windowns;
                    elseif (strpos("$useragent","iphone") !== false):
                        ++$so_iphone;
                    elseif (strpos("$useragent","mac os x") !== false || strpos("$useragent","macintosh")):
                        ++$so_mac;
                    elseif (strpos("$useragent","linux") !== false):
                        ++$so_linux;
                    else:
                        ++$so_other;
                    endif;
                endforeach;
                echo '<h5>'.lang('cms_stats_views_browser').'</h5>';
                echo '<table class="small-12">';
                    echo '<thead>';
                        echo '<th class="small-9">'.lang('cms_stats_entry').'</th>';
                        echo '<th class="small-3">'.lang('cms_stats_views').'</th>';
                    echo '</thead>';
                    echo '<tbody>';
                        echo '<tr>';
                            echo '<td>Chrome</td>';
                            echo '<td>'.$browser_chrome.'</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td>Firefox</td>';
                            echo '<td>'.$browser_firefox.'</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td>Opera</td>';
                            echo '<td>'.$browser_opera.'</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td>Internet Explorer</td>';
                            echo '<td>'.$browser_ie.'</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td>Safari</td>';
                            echo '<td>'.$browser_safari.'</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td>Outros</td>';
                            echo '<td>'.$browser_other.'</td>';
                        echo '</tr>';
                    echo '</tbody>';
                echo '</table>';
                echo '<h5>'.lang('cms_stats_views_os').'</h5>';
                echo '<table class="small-12">';
                    echo '<thead>';
                        echo '<th class="small-9">'.lang('cms_stats_entry').'</th>';
                        echo '<th class="small-3">'.lang('cms_stats_views').'</th>';
                    echo '</thead>';
                    echo '<tbody>';
                        echo '<tr>';
                            echo '<td>Windowns</td>';
                            echo '<td>'.$so_windowns.'</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td>Linux</td>';
                            echo '<td>'.$so_linux.'</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td>Iphone</td>';
                            echo '<td>'.$so_iphone.'</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td>Mac</td>';
                            echo '<td>'.$so_mac.'</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td>Outros</td>';
                            echo '<td>'.$so_other.'</td>';
                        echo '</tr>';
                    echo '</tbody>';
                echo '</table>';
            echo '</div>';
        echo '</div>';
		break;
}