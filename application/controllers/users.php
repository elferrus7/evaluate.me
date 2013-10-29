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
        redirect('users/display_users');
    }
    
    public function display_users()
    {
        $this->load->library('table');
        $this->config->load('table_html');
        $tmpl = $this->config->item('tmpl');
        $this->table->set_template($tmpl);
        
        $limit = 20;
        $offeset = ($this->uri->segment(3))?  $this->uri->segment(3): 0; 
        $this->load->library('pagination');
        $this->config->load('pagination_html');
        $config = $this->config->item('tmpl');
        $config['base_url'] = base_url().'index.php/users/display_users';
        $config['total_rows'] = $this->user_model->count_users();
        $config['per_page'] = $limit; 
        $this->pagination->initialize($config);
        
        $users = $this->user_model->get_users();
        
        $this->table->set_heading('Name','Email','');
        $this->table->set_caption('Users ' . anchor('users/create_user','<i class="icon-plus"></i>', 'class="btn" title="New User"'));
        
        foreach($users as $user){
            $this->table->add_row($user->first_name .' '. $user->last_name, $user->email,
                                  anchor('users/details_user/'.$user->idUsers,'<i class="icon-zoom-in"></i>').
                                  anchor('users/edit_user/'.$user->idUsers,'<i class="icon-pencil"></i>').
                                  anchor('users/delete_user/'.$user->idUsers,'<i class="icon-remove"></i>'));
        }
        
        $data['table'] = $this->table->generate();
        $data['content'] = 'users/display_users';
        $data['title'] = 'Evaluate.me';
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('index.php',$data);
    }
    
    public function details_user()
    {
        if(($user_id = $this->uri->segment(3)) === FALSE)redirect('users/display_users');
        
        $this->load->library('table');
        $this->config->load('table_html');
        $tmpl = $this->config->item('tmpl');
        $this->table->set_template($tmpl);
        
        $user = $this->user_model->get_user($user_id);
        $roles = $this->user_model->get_user_roles($user_id);
        
        $this->table->set_heading('','');
        $this->table->add_row('Email',$user->email);
        $this->table->add_row('Name',$user->first_name .' ' .$user->last_name );
        
        foreach($roles as $role){
            $this->table->add_row('Role',$role->name);
        }
        
        $data['table'] = $this->table->generate();
        
        $data['content'] = 'users/details_user';
        $data['title'] = 'User';
        $this->load->view('index.php',$data);
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
    
    public function edit_user()
    {
        if(($user_id = $this->uri->segment(3)) === FALSE)redirect('users/display_users');
        $this->load->helper('form');
        $this->load->model('role_model');
        
        $roles = $this->user_model->get_user_roles($user_id);
        $data['roles'] = "";
        foreach($roles as $role){
            $data['roles'] .=  "<li data-id=\"$role->idRoles\" class=\"role\">$role->name</li>";
        }
        $user_roles = $this->user_model->get_roles($user_id);
        $data['user_roles'] = "";
        foreach($user_roles as $user_role){
            $data['user_roles'] = "<li data-id=\"$user_role->idRoles\" class=\"role\">$user_role->name</li>";
        }
        $data['user'] = $this->user_model->get_user($user_id);
        $data['content'] = 'users/edit_user';
        $data['sorteable'] = TRUE;
        $data['title'] = 'Edit User';
        $this->load->view('index.php',$data);
    }
    
    public function insert_user(){
        if($user_id = $this->user_model->insert_user()){
            echo json_encode(array('stat' =>TRUE,'user_id'=>$user_id));
        } else{
            echo json_encode(array('stat' =>FALSE));
        }
    }
    
    public function update_user(){
        $user_id = $this->input->post('user_id');
        if($this->user_model->update_user()){
            echo json_encode(array('stat' => TRUE,'user_id'=>$user_id));
        }else {
            echo json_encode(array('stat' => FALSE));
        }
    }
    
    public function delete_user()
    {
        if(($user_id = $this->uri->segment(3)) === FALSE) redirect('users/display_users');
        $this->user_model->delete_user($user_id);
        redirect('users/display_users');
    }
    
}
