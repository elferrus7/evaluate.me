<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /**
     * Name: Alert
     * Author: Fernando Isaac Mendoza Quinones
     *         elferrus7@gmail.com
     *         @elferrus7
     * 
     * 
     * Description :
     * This library works to send messages through the session of the user
     * this messages are Alerts to confirm the user about some function success or errors.
     * The html code that is displayed is based in the bootstrap css.
     * 
     * Requirements: Bootstrap CSS, PHP 4.2 or superior
     * 
     **/
     class Alert 
     {
         /**
         * Store all the alerts
         *
         * @var array
         **/
         
         var $data = array('error' => array(), 'success' => array());
         
         /**
          * Code Igniter
          * 
          **/
         
         var $CI;
         
         function __construct() 
         {
             $this->CI =& get_instance();
             $this->CI->load->library('session');
         }
         public function  add_alert($string, $type)
         {
             if ($type == 'success')
                array_push($this->data['success'], $string);
             if ($type == 'error')
                array_push($this->data['error'], $string);
         }
         
         public function set_alerts()
         {
             $this->CI->session->set_userdata($this->data);
         }
         
         public function display_alerts()
         {
             
             if($errors = $this->CI->session->userdata('error'))
             {
            //echo print_r($errors);
                foreach($errors as $error)
                {
                    echo "<div class='alert alert-error'>$error</div>";
                }
            }
            if($success = $this->CI->session->userdata('success'))
            {
                //echo print_r($errors);
                foreach($success as $ok)
                {
                    echo "<div class='alert alert-success'>$ok</div>";
                }
            }
            $array_items = array('error' => '', 'success' => '');
            $this->CI->session->unset_userdata($array_items); 
         }
     }

/* End of file Alert.php */