<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Operation_model extends CI_Model
{
    private $table = 'operations';
    
    function __construct()
    {
        parent::__construct();
    }
    
     public function get_operations()
    {
        return $this->db->get($this->table)->result();
    }
    
    public function get_operation($operation_id)
    {
        return $this->db->get_where($this->table,array('idOperations'=>$operation_id))->row();
    }
}
