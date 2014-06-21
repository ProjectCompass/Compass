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
        echo '</div>';
        break;
}