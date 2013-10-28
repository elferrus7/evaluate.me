<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 */
class Users extends CI_Controller {
	
	function __construct() {
		parent::__construct();
        $this->load->model('user_model');
	}
    
    public function index()
    {
        
    }
    
    public function create_user()
    {
        $this->load->model('role_model');
        $this->load->helper('form');
        $roles = $this->role_model->get_roles();
        $data['roles'] = "";
        foreach($roles as $role){
            $data['roles'] .= "<li data-id=\"$role->idRoles\" class=\"role\" title\"$role->description\"> $role->name </li>"; 
        }
        
        $data['sorteable'] = TRUE;
        $data['content'] = 'users/create_user';
        $data['title'] = 'Create User';
        $this->load->view('index.php',$data);
    }
    
    public function insert_user(){
        
    }
    
}
