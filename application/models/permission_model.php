<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class permission_model extends CI_Model 
{
    private $table = 'permissions';
    private $permission_operations = 'permissions_has_operations';
    private $permissions_view = 'vwpermissions';
    private $role_permission = 'roles_has_permissions';
    private $operations = 'operations';
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_permissions()
    {
        return $this->db->get($this->table)->result();
    }
    
    public function get_permission($permission_id)
    {
        return $this->db->get_where($this->table,array('idpermissions'=>$permission_id))->row();
    }
    
    public function insert_permission()
    {
        if($this->form_validation()){
            $data = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description')
            );
            $this->db->insert($this->table, $data);
            $permission_id = $this->db->insert_id();
            $operations = $this->input->post('operations');
            foreach($operations as $operation){
                $data_operations = array('Permissions_idPermissions' =>$permission_id, 'Operations_idOperations' => $operation);
                $this->db->insert($this->permission_operations,$data_operations);
            }    
            return $permission_id;
        }
        return FALSE;
    }
    
    public function update_permission()
    {
        if($this->form_validation()){
            $this->load->library('encrypt');
            $data = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description')
            );
            $permission_id = $this->input->post('permission_id');
            $this->db->where('idPermissions',$permission_id);
            $this->db->update($this->table, $data);
            $this->db->delete($this->permission_operations ,array('Permissions_idPermissions'=>$permission_id));
            $operations = $this->input->post('operations');
            foreach($operations as $operation){
                $data_operations = array('Permissions_idPermissions' =>$permission_id, 'Operations_idOperations' => $operation);
                $this->db->insert($this->permission_operations,$data_operations);
            }    
            return $permission_id;
        }
        return FALSE;
    }
    
    public function delete_permission($permission_id)
    {
        $this->db->delete($this->permission_operations, array('Permissions_idPermissions'=>$permission_id));
        $this->db->delete($this->role_permission, array('Permissions_idPermissions'=>$permission_id));
        $this->db->delete($this->table,array('idPermissions'=>$permission_id));
        return TRUE;
    }
    
    public function get_permission_operations($permission_id)
    {
        return $this->db->get_where($this->permissions_view,array('idPermissions' => $permission_id))->result();
    }
    
    public function get_operations($permission_id = FALSE)
    {
        if($permission_id){
            $permission_operations = $this->db->get_where($this->permission_operations,array('Permissions_idPermissions'=>$permission_id))->result();
            foreach($permission_operations as $permission_operation){
                $this->db->where('idOperations !=', $permission_operation->Operations_idOperations);
            }
            return $this->db->get($this->operations)->result();
        }
        return $this->db->get($this->operations)->result();
    }
    
    public function count_permissions()
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