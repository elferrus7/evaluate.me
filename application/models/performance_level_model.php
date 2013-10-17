<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Performance_level_model extends CI_Model 
{
    var $table = 'performance_levels';
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_performance_levels()
    {
        return $this->db->get($this->table)->result();
    }
    
    public function get_performance_level($performance_level_id)
    {
        return $this->db->get_where($this->table,array('Performance_levels_id'=>$performance_level_id))->result();
    }
    
    public function insert_performance_level()
    {
        $data = array(
            'percentage' => $this->post('percentage'),
            'description' => $this->post('description')
        );
        
        $this->db->insert($this->table, $data);    
    }
    
    public function update_performance_level($performance_level_id)
    {
        $data = array(
            'percentage' => $this->post('percentage'),
            'description' => $this->post('description')
        );
        $this->db->where('performance_levels_id',$performance_level_id);
        $this->db->update($this->table, $data);
    }
    
    public function delete_performance_level($performance_level_id)
    {
        $this->db->where('performance_levels_id',$performance_level_id);
        $this->db->delete($this->table);
    }
}