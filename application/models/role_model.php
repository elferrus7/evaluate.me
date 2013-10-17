<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Role_model extends CI_Model 
{
    var $table = 'roles';
    
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
        return $this->db->get_where($this->table,array('Roles_id'=>$role_id))->result();
    }
    
    public function insert_role($role)
    {
        
    }
    
    public function update_role($role_id,$role){
        
    }
    
    public function delete_role($role_id)
    {
        
    }
}