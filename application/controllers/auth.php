<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

    /**
     * Este controlador solo maneja las funciones de login o logut pero si se quiere implementar otras funciones
     * como recuperación de password u otras este es el lugar para hacerlas
     */
     
    function __construct(){
        parent::__construct();
        $this->load->library('Alert');
    }
    public function index()
    {
        $this->load->helper('form');
        $this->load->view('auth/login');
    }
    
    public function login()
    {
        $this->load->model('user_model');
        $this->load->library('auth_lib');
        echo print_r($this->input->post());
        if($this->user_model->login()){
            if($this->auth_lib->have_role('Judge')) redirect('rubrics/display_rubrics');
            redirect('events/display_events');
        }
        $this->alert->add_alert('Invalid Username/Password','error');
        $this->alert->set_alerts();
        redirect('auth');
    }
    
    public function logout()
    {
        $this->load->library('session');
        $this->session->unset_userdata(array('idUsers','username'));
        redirect('auth');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/auths.php */