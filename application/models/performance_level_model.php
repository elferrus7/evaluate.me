<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Performance_level_model extends CI_Model 
{
    var $table = 'performance_levels';
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_performance_levels($pl_ids = FALSE)
    {
        if(is_array($pl_ids))
        {
            foreach($pl_ids as $pl_id)
            {
                $this->db->where('idPerformance_levels !=',$pl_id);   
            }
        }
        $this->db->order_by('percentage',"desc");
        $pls = $this->db->get($this->table)->result();
        $data = array();
        foreach($pls  as $pl){
            $data[$pl->idPerformance_levels] = $pl->description . ' - %' . $pl->percentage;
        }
        return $data;
    }
    
    public function get_performance_level($performance_level_id)
    {
        return $this->db->get_where($this->table,array('idPerformance_levels'=>$performance_level_id))->row();
    }
    
    public function insert_performance_level()
    {
        $data = array(
            'percentage' => $this->input->post('percentage'),
            'description' => $this->input->post('description')
        );
        
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();    
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