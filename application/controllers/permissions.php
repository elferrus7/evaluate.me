<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 */
class Permissions extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('permission_model');
        //$this->load->library('auth');
        //if(!$this->auth->have_auth()) redirect('auth');
    }
    
    public function index()
    {
        redirect('permissions/display_permissions');
    }
    
    public function display_permissions()
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
        $config['base_url'] = base_url().'index.php/permissions/display_permissions';
        $config['total_rows'] = $this->permission_model->count_permissions();
        $config['per_page'] = $limit; 
        $this->pagination->initialize($config);
        
        $permissions = $this->permission_model->get_permissions();
        
        $this->table->set_heading('Name','Description','');
        $this->table->set_caption('permissions ' . anchor('permissions/create_permission','<i class="icon-plus"></i>', 'class="btn" title="New permission"'));
        
        foreach($permissions as $permission){
            $this->table->add_row($permission->name , $permission->description,
                                  anchor('permissions/details_permission/'.$permission->idPermissions,'<i class="icon-zoom-in"></i>').
                                  anchor('permissions/edit_permission/'.$permission->idPermissions,'<i class="icon-pencil"></i>').
                                  anchor('permissions/delete_permission/'.$permission->idPermissions,'<i class="icon-remove"></i>'));
        }
        
        $data['table'] = $this->table->generate();
        $data['content'] = 'permissions/display_permissions';
        $data['title'] = 'Evaluate.me';
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('index.php',$data);
    }
    
    public function details_permission()
    {
        if(($permission_id = $this->uri->segment(3)) === FALSE)redirect('permissions/display_permissions');
        
        $this->load->library('table');
        $this->config->load('table_html');
        $tmpl = $this->config->item('tmpl');
        $this->table->set_template($tmpl);
        
        $permission = $this->permission_model->get_permission($permission_id);
        $operations = array();
        $operations = $this->permission_model->get_permission_operations($permission_id);
        
        $this->table->set_heading('','');
        $this->table->add_row('Name',$permission->name);
        $this->table->add_row('Description',$permission->description);
        foreach($operations as $operation){
            $this->table->add_row('Operations',$operation->operation);
        }
        
        $data['table'] = $this->table->generate();
        
        $data['content'] = 'permissions/details_permission';
        $data['title'] = 'permission';
        $this->load->view('index.php',$data);
    }
    
    public function create_permission()
    {
        $this->load->model('operation_model');
        $this->load->helper('form');
        $operations = $this->operation_model->get_operations();
        $data['operations'] = "";
        foreach($operations as $operation){
            $data['operations'] .= "<li data-id=\"$operation->idOperations\" class=\"operation\" > $operation->operation </li>"; 
        }
        
        $data['sorteable'] = TRUE;
        $data['content'] = 'permissions/create_permission';
        $data['title'] = 'Create permission';
        $this->load->view('index.php',$data);
    }
    
    public function edit_permission()
    {
        if(($permission_id = $this->uri->segment(3)) === FALSE)redirect('permissions/display_permissions');
        $this->load->helper('form');
        $this->load->model('operation_model');
        
        $operations = $this->permission_model->get_permission_operations($permission_id); //operationss that already have the permission
        $data['operations'] = "";
        foreach($operations as $operation){
            $data['operations'] .=  "<li data-id=\"$operation->idOperations\" class=\"operation\">$operation->operation</li>";
        }
        $permission_operations = $this->permission_model->get_operations($permission_id); //operationss that don't have the rol
        $data['permission_operations'] = "";
        foreach($permission_operations as $permission_operation){
            $data['permission_operations'] .= "<li data-id=\"$permission_operation->idOperations\" class=\"operation\">$permission_operation->operation</li>";
        }
        $data['permission'] = $this->permission_model->get_permission($permission_id);
        $data['content'] = 'permissions/edit_permission';
        $data['sorteable'] = TRUE;
        $data['title'] = 'Edit permission';
        $this->load->view('index.php',$data);
    }
    
    public function insert_permission(){
        if($permission_id = $this->permission_model->insert_permission()){
            echo json_encode(array('stat' =>TRUE,'permission_id'=>$permission_id));
        } else{
            echo json_encode(array('stat' =>FALSE));
        }
    }
    
    public function update_permission(){
        $permission_id = $this->input->post('permission_id');
        if($this->permission_model->update_permission()){
            echo json_encode(array('stat' => TRUE,'permission_id'=>$permission_id));
        }else {
            echo json_encode(array('stat' => FALSE));
        }
    }
    
    public function delete_permission()
    {
        if(($permission_id = $this->uri->segment(3)) === FALSE) redirect('permissions/display_permissions');
        $this->permission_model->delete_permission($permission_id);
        redirect('permissions/display_permissions');
    }
    
}
