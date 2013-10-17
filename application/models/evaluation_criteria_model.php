<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Evaluation_criteria_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_evaluation_criterials()
    {
        return $this->db->get($this->table)->result();
    }
    
    public function get_evaluation_criterial($evaluation_criteria_id)
    {
        return $this->db->get_where($this->table,array('evaluation_criteria_id'=>$evaluation_criteria_id))->result();
    }
    
    public function insert_evaluation_criteria()
    {
        $data = array(
            'percentage' => $this->post('percentage'),
            'description' => $this->post('description')
        );
        
        $this->db->insert($this->table, $data);    
    }
    
    public function update_evaluation_criteria($evaluation_criteria_id)
    {
        $data = array(
            'percentage' => $this->post('percentage'),
            'description' => $this->post('description')
        );
        $this->db->where('evaluation_criteria_id',$evaluation_criteria_id);
        $this->db->update($this->table, $data);
    }
    
    public function delete_evaluation_criteria($evaluation_criteria_id)
    {
        $this->db->where('evaluation_criteria_id',$evaluation_criteria_id);
        $this->db->delete($this->table);
    }
}
