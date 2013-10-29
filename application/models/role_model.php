<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Role_model extends CI_Model 
{
    private $table = 'roles';
    private $role_permission = 'roles_has_permissions';
    private $role_view = 'vwroles';
    private $permissions = 'permissions';
    private $users_roles = 'users_has_roles';
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_roles()
    {
        return $this->db->get($this->table)->result();
    }
    
    public function get_role($role_id)
    {
        return $this->db->get_where($this->table,array('idRoles'=>$role_id))->row();
    }
    
    public function get_role_permissions($role_id){
        return $this->db->get_where($this->role_view,array('idRoles'=>$role_id))->result();
    }
    
    public function insert_role()
    {
        if($this->form_validation()){
            $data = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description')
            );
            $this->db->insert($this->table,$data);
            $role_id = $this->db->insert_id();
            
            $permissions = array();
            $permissions = $this->input->post('permissions');
            foreach($permissions as $permission){
                $data_role = array(
                    'Roles_idRoles' => $role_id,
                    'Permissions_idPermissions' => $permission
                ); 
                $this->db->insert($this->role_permission,$data_role);
            }
            return $role_id;
        }
        return FALSE;
    }
    
    public function update_role(){
        
        if($this->form_validation()){
            $data = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description')
            );
            $role_id = $this->input->post('role_id');
            $this->db->where('idRoles',$role_id);
            $this->db->update($this->table,$data);
            $role_id = $this->input->post('role_id');
            
            $this->db->delete($this->role_permission,array('Roles_idRoles' => $role_id));
            $permissions = array();
            $permissions = $this->input->post('permissions');
            foreach($permissions as $permission){
                $data_role = array(
                    'Roles_idRoles' => $role_id,
                    'Permissions_idPermissions' => $permission
                ); 
                $this->db->insert($this->role_permission,$data_role);
            }
            return TRUE;
        }
        return FALSE;
    }
    
    public function delete_role($role_id)
    {
        $this->db->delete($this->role_permission,array('Roles_idRoles' => $role_id));
        $this->db->delete($this->users_roles,array('Roles_idRoles' => $role_id));
        $this->db->delete($this->table,array('idRoles' => $role_id));
    }
    
    public function get_permissions($role_id = FALSE){
        if($role_id){
            $role_permissions = $this->db->get_where($this->role_permission,array('Roles_idRoles'=>$role_id))->result();
            foreach($role_permissions as $role_permission){
                $this->db->where('idPermissions !=', $role_permission->Permissions_idPermissions);
            }
            return $this->db->get($this->permissions)->result();
        }
        return $this->db->get($this->permissions)->result();
    }
    
    public function count_roles()
    {
        return $this->db->count_all($this->table);
    }
    
    public function form_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name','name','required');
        $this->form_validation->set_rules('description','description','required');
        return $this->form_validation->run();
    }
    
}