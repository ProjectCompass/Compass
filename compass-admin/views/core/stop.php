<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
switch ($screen) {
	case 'stop':
        echo '<div id="stop-index">';
            echo '<div class="row">';
                echo '<div class="small-12 columns center">';
                    echo '<h5>404: '.lang('stop_page_not_foung').'</h5>';
                    echo '<h1 class="oversized">'.lang('stop_keep_looking').'</h1>';
                    echo '<p class="lead bottom40">'.lang('stop_double').' <a href="'.base_url('').'">'.lang('core_homepage').'</a> <br> '.lang('stop_double_2').'</a></p>';
                    echo '<h3>'.lang('stop_or_try').'</h3>';
                    echo '<ul class="no-bullet">';
                        echo '<li><a href="'.base_url('login').'">'.lang('stop_login').'</a></li>';
                        echo '<li><a href="'.base_url('dashboard').'">'.lang('stop_dashboard').'</a></li>';
                        echo '<li><a href="'.base_url('users/profile/'.get_session('user_id')).'">'.lang('stop_profile').'</a></li>';
                    echo '</ul>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
	    break;
}