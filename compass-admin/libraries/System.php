<?php defined('BASEPATH') OR exit('No direct script access allowed');

class System {

	public function __construct(){
		$this->CI =& get_instance();
/*		$this->CI->load->helper('functions');
        $this->CI->load->helper('templates');*/
	}

    public function send_email($to, $subject, $mensage, $format='html'){
        $this->CI->load->library('email');
        $config['mailtype'] = $format;
        $this->CI->email->initialize($config);
        $this->CI->email->from(get_setting('general_email_admin'), get_setting('general_title_site'));
        $this->CI->email->to($to);
        $this->CI->email->subject($subject);
        $this->CI->email->message($mensage);
        if ($this->CI->email->send()):
            return TRUE;
        else:
            return $this->CI->email->print_debugger();
        endif;
    }

}