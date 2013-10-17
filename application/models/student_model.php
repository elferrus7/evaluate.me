<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Permission_model extends CI_Model 
{
    var $table = 'permissions';
    
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
        return $this->db->get_where($this->table,array('permissions_id'=>$permission_id))->result();
    }
    
    public function insert_permission($permission)
    {
        
    }
    
    public function update_permission($permission_id,$permission){
        
    }
    
    public function delete_permission($permission_id)
    {
        
    }
}