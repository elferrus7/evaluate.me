<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

    /**
     * Este controlador solo maneja las funciones de login o logut pero si se quiere implementar otras funciones
     * como recuperación de password u otras este es el lugar para hacerlas
     */
    public function index()
    {
        $this->load->helper('form');
        $this->load->view('auth/login');
    }
    
    public function login()
    {
        $this->load->model('user_model');
        if($this->user_model->login()){
            redirect('events/display_events');
        }
         //$this->session->set_flashdata('error','');
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