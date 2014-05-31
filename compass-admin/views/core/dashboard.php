<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
    case 'dashboard':
        echo '<div class="row" id="dashboard-index">';
            echo '<div class="small-12 columns">';
                echo '<div class="panel white">';
                    if (get_session('user_level') == 1):
                        echo '<h3>'.lang('dashboard_welcome').'</h3>';
                        echo '<h6>'.lang('dashboard_hint').'</h6>';
                        echo '<div class="row">';
                            echo '<div class="small-4 columns">';
                                echo '<p><strong>'.lang('dashboard_hint_use').'</strong></p>';
                                echo anchor('settings', lang('dashboard_hint_customize'), array('class'=>'button'));
                                echo '<p>'.lang('dashboard_hint_or').' '.anchor('settings/aparence', lang('dashboard_hint_aparence')).'</p>';
                            echo '</div>';
                            echo '<div class="small-4 columns">';
                                echo '<p><strong>'.lang('dashboard_hint_steps').'</strong></p>';
                                echo '<p><i class="fa fa-home"></i> '.anchor('', lang('dashboard_hint_homepage')).'<br />';
                                echo '<i class="fa fa-puzzle-piece"></i> '.anchor('tools', lang('dashboard_hint_tools')).'<br />';
                                echo '<i class="fa fa-gavel"></i> '.anchor('tools/audits', lang('dashboard_hint_audits')).'</p>';
                            echo '</div>';
                            echo '<div class="small-4 columns">';
                                echo '<p><strong>'.lang('dashboard_hint_more').'</strong></p>';
                                echo '<p><i class="fa fa-compass"></i> '.anchor('', lang('dashboard_hint_compass')).'<br />';
                                echo '<i class="fa fa-map-marker"></i> '.anchor('', lang('dashboard_hint_blog_compass')).'<br />';
                                echo '<i class="fa fa-info"></i> '.anchor('', lang('dashboard_hint_compass_helper')).'</p>';
                            echo '</div>';
                        echo '</div>';
                    else:
                        echo '<h3>'.lang('dashboard_welcome_cms').' '.get_setting('general_title_site').'!</h3>';
                        echo '<h6>'.get_setting('general_description_site').'</h6>';
                    endif;
                echo '</div>';
            echo '</div>';
            if (get_setting('module_cms') == 1):
                echo '<div class="small-5 columns right">';
                    echo '<div class="panel white">';
                        echo '<div class="row">';
                            $all_views = $this->stats->get_all()->num_rows();
                            $today_unix = human_to_unix(date('Y-m-d').' 00:00:00');//converte a data atual para unix
                            $today_human_min = date('Y-m-d');
                            $yesterday_unix = $today_unix - 86400;//subtrai 86400 segundos da data de hoje
                            $yesterday_human_min = date("Y-m-d", strtotime(unix_to_human($yesterday_unix, TRUE, 'eu')));//converte a data para human e deixa no formato Y-m
                            $today_views = $this->stats->get_by_in('stat_date', $today_human_min)->num_rows();
                            $yesterday_views = $this->stats->get_by_in('stat_date', $yesterday_human_min)->num_rows();
                            $date_array_interval[] = $today_human_min;
                            $repeat = 9;
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
                                        title: {text: '<?php echo lang('dashboard_cms_hint_view'); ?>',x: -20},
                                        subtitle: {text: '',x: -20},
                                        yAxis: {
                                            title: {enabled: false},
                                            plotLines: [{value: 0, width: 1, color: '#808080'}]
                                        },
                                        tooltip: {valueSuffix: ''},
                                        plotOptions: {
                                            line: {
                                                marker: {enabled: false, symbol: 'circle', radius: 2,
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
                                            name: '<?php echo lang('dashboard_cms_hint_view'); ?>',
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
                            <div class="small-11 columns" style="height: 300px;">
                                <div id="char_large" style="width: 100%; height: 300px; position:absolute; "></div>
                            </div>
                            
                            <?php
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
                echo '<div class="small-7 columns left">';
                    echo '<div class="panel white">';
                        if (get_session('user_level') == 1):
                            echo '<h3>'.lang('core_compass').' <strong>'.lang('core_cms').'</strong></h3>';
                            echo '<h6>'.lang('dashboard_hint').'</h6>';
                            echo '<div class="row">';
                                echo '<div class="small-4 columns">';
                                    echo '<p><strong>'.lang('dashboard_hint_use').'</strong></p>';
                                    echo anchor('cms/settings', lang('dashboard_cms_hint_customize'), array('class'=>'button tiny'));
                                echo '</div>';
                                echo '<div class="small-4 columns">';
                                    echo '<p><strong>'.lang('dashboard_hint_steps').'</strong></p>';
                                    echo '<p><i class="fa fa-edit"></i> '.anchor('cms/posts/insert', lang('dashboard_hint_new_post')).'<br />';
                                    echo '<i class="fa fa-bookmark"></i> '.anchor('cms/pages/insert', lang('dashboard_hint_new_page')).'<br />';
                                    echo '<i class="fa fa-dashboard"></i> '.anchor('site', lang('dashboard_hint_view_site')).'</p>';
                                echo '</div>';
                                echo '<div class="small-4 columns">';
                                    echo '<p><strong>'.lang('dashboard_hint_more').'</strong></p>';
                                    echo '<p><i class="fa fa-comment"></i> '.anchor('cms/insertpost', lang('dashboard_hint_comments')).'<br />';
                                    echo '<i class="fa fa-picture-o "></i> '.anchor('cms/insertpage', lang('dashboard_hint_new_media')).'<br />';
                                echo '</div>';
                            echo '</div>';
                        else:
                            echo '<h3>'.get_setting('layout_site_title').'</h3>';
                            echo '<h6>'.get_setting('layout_site_description').'</h6>';
                        endif;
                    echo '</div>';
                    echo '<div class="panel white">';
                        echo '<div class="small-4 columns"><small>'.lang('dashboard_views_today').'</small> '.$today_views.'</div>';
                        echo '<div class="small-4 columns"><small>'.lang('dashboard_views_yesterday').'</small> '.$yesterday_views.'</div>';
                        echo '<div class="small-4 columns"><small>'.lang('dashboard_views_all').'</small> '.$all_views.'</div> <br>';
                    echo '</div>';
                echo '</div>';
            endif;
        echo '</div>';
        break;
}