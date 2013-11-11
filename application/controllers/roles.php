<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 */
class Roles extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('role_model');
        //$this->load->library('auth');
        //if(!$this->auth->have_auth()) redirect('auth');
    }
    
    public function index()
    {
        redirect('roles/display_roles');
    }
    
    public function display_roles()
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
        $config['base_url'] = base_url().'index.php/roles/display_roles';
        $config['total_rows'] = $this->role_model->count_roles();
        $config['per_page'] = $limit; 
        $this->pagination->initialize($config);
        
        $roles = $this->role_model->get_roles();
        
        $this->table->set_heading('Name','Description','');
        $this->table->set_caption('roles ' . anchor('roles/create_role','<i class="icon-plus"></i>', 'class="btn" title="New role"'));
        
        foreach($roles as $role){
            $this->table->add_row($role->name , $role->description,
                                  anchor('roles/details_role/'.$role->idRoles,'<i class="icon-zoom-in"></i>').
                                  anchor('roles/edit_role/'.$role->idRoles,'<i class="icon-pencil"></i>').
                                  anchor('roles/delete_role/'.$role->idRoles,'<i class="icon-remove"></i>'));
        }
        
        $data['table'] = $this->table->generate();
        $data['content'] = 'roles/display_roles';
        $data['title'] = 'Evaluate.me';
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('index.php',$data);
    }
    
    public function details_role()
    {
        if(($role_id = $this->uri->segment(3)) === FALSE)redirect('roles/display_roles');
        
        $this->load->library('table');
        $this->config->load('table_html');
        $tmpl = $this->config->item('tmpl');
        $this->table->set_template($tmpl);
        
        $role = $this->role_model->get_role($role_id);
        $permissions = array();
        $permissions = $this->role_model->get_role_permissions($role_id);
        
        $this->table->set_heading('','');
        $this->table->add_row('Name',$role->name);
        $this->table->add_row('Description',$role->description);
        foreach($permissions as $permission){
            $this->table->add_row('Permission',$permission->permission_name);
        }
        
        $data['table'] = $this->table->generate();
        
        $data['content'] = 'roles/details_role';
        $data['title'] = 'Role';
        $this->load->view('index.php',$data);
    }
    
    public function create_role()
    {
        $this->load->model('permission_model');
        $this->load->helper('form');
        $permissions = $this->permission_model->get_permissions();
        $data['permissions'] = "";
        foreach($permissions as $permission){
            $data['permissions'] .= "<li data-id=\"$permission->idPermissions\" class=\"permission\" title\"$permission->description\"> $permission->name </li>"; 
        }
        
        $data['sorteable'] = TRUE;
        $data['content'] = 'roles/create_role';
        $data['title'] = 'Create role';
        $this->load->view('index.php',$data);
    }
    
    public function edit_role()
    {
        if(($role_id = $this->uri->segment(3)) === FALSE)redirect('roles/display_roles');
        $this->load->helper('form');
        $this->load->model('permission_model');
        
        $permissions = $this->role_model->get_role_permissions($role_id); //Permissions that already have the role
        $data['permissions'] = "";
        foreach($permissions as $permission){
            $data['permissions'] .=  "<li data-id=\"$permission->idPermissions\" class=\"permission\">$permission->permission_name</li>";
        }
        $role_permissions = $this->role_model->get_permissions($role_id); //Permissions that don't have the rol
        $data['role_permissions'] = "";
        foreach($role_permissions as $role_permission){
            $data['role_permissions'] .= "<li data-id=\"$role_permission->idPermissions\" class=\"permission\">$role_permission->name</li>";
        }
        $data['role'] = $this->role_model->get_role($role_id);
        $data['content'] = 'roles/edit_role';
        $data['sorteable'] = TRUE;
        $data['title'] = 'Edit role';
        $this->load->view('index.php',$data);
    }
    
    public function insert_role(){
        if($role_id = $this->role_model->insert_role()){
            echo json_encode(array('stat' =>TRUE,'role_id'=>$role_id));
        } else{
            echo json_encode(array('stat' =>FALSE));
        }
    }
    
    public function update_role(){
        $role_id = $this->input->post('role_id');
        if($this->role_model->update_role()){
            echo json_encode(array('stat' => TRUE,'role_id'=>$role_id));
        }else {
            echo json_encode(array('stat' => FALSE));
        }
    }
    
    public function delete_role()
    {
        if(($role_id = $this->uri->segment(3)) === FALSE) redirect('roles/display_roles');
        $this->role_model->delete_role($role_id);
        redirect('roles/display_roles');
    }
    
}
