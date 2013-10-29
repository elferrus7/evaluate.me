<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Evaluation_criteria_model extends CI_Model
{
    private $table = 'evaluation_criteria';
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_evaluation_criterials($evs = FALSE)
    {
        if(is_array($evs)){
            foreach($evs as $ev){
                $this->db->where('idEvaluation_criteria !=', $ev->idEvaluation_criteria);
            }
        }
        $this->db->order_by('percentage',"asc");
        $ecs = $this->db->get($this->table)->result();
        $data = array();
        foreach($ecs as $ec){
            $data[$ec->idEvaluation_criteria] = $ec->description . ' - %'. $ec->percentage;
        } 
        return $data;
    }
    
    public function get_evaluation_criteria($evaluation_criteria_id)
    {
        return $this->db->get_where($this->table,array('idEvaluation_criteria'=>$evaluation_criteria_id))->row();
    }
    
    public function insert_evaluation_criteria()
    {
        $data = array(
            
            'percentage' => $this->input->post('percentage'),
            'description' => $this->input->post('description')
        );
        
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();    
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
