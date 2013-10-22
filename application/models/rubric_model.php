<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rubric_model extends CI_Model{
    
    private $table = 'rubrics';
    private $evaluation_criteria = 'evaluation_criteria';
    private $performace_level = 'performance_levels';
    private $rubric_ec = 'rubrics_has_evaluation_criteria';
    private $ec_pl = 'evaluation_criteria_has_performance_levels';
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_rubrics()
    {
        return $this->db->get($this->table)->result();
    }
    
    public function get_rubric($rubric_id)
    {
        return $this->db->get_where($this->table,array('idRubrics'=>$rubric_id))->row();
    }
    
    public function insert_rubric()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description')
        );
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();    
    }
    
    public function update_rubric($rubric_id)
    {
        $data = array(
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description')
        );
        $this->db->where('rubrics_id',$rubric_id);
        $this->db->update($this->table, $data);
    }
    
    public function delete_rubric($rubric_id)
    {
        $this->db->where('idRubrics',$rubric_id);
        $this->db->delete($this->table);
    }
    
    public function get_evaluation_criteria()
    {
        return $this->db->get($this->evaluation_criteria)->result();
    }
    
    public function get_performance_levels()
    {
        return $this->db->get($this->performace_level)->result();
    }
    
    public function insert_ec($submit = FALSE)
    {
        $this->db->trans_start();
        $data = array(
            'Evaluation_criteria_idEvaluation_criteria' => $this->input->post('evaluation_criteria'),
            'Rubrics_idRubrics' => $this->input->post('rubric')
        );
        $this->db->insert($this->rubric_ec,$data);
        $data = array(
            'Evaluation_criteria_idEvaluation_criteria' => $this->input->post('evaluation_criteria'),
            'Performance_levels_idPerformance_levels' => $this->input->post('performance_level_1')
        );
        $this->db->insert($this->ec_pl,$data);
        $data = array(
            'Evaluation_criteria_idEvaluation_criteria' => $this->input->post('evaluation_criteria'),
            'Performance_levels_idPerformance_levels' => $this->input->post('performance_level_2')
        );
        $this->db->insert($this->ec_pl,$data);
        $data = array(
            'Evaluation_criteria_idEvaluation_criteria' => $this->input->post('evaluation_criteria'),
            'Performance_levels_idPerformance_levels' => $this->input->post('performance_level_3')
        );
        $this->db->insert($this->ec_pl,$data);
        $data = array(
            'Evaluation_criteria_idEvaluation_criteria' => $this->input->post('evaluation_criteria'),
            'Performance_levels_idPerformance_levels' => $this->input->post('performance_level_4')
        );
        $this->db->insert($this->ec_pl,$data);
        $data = array(
            'Evaluation_criteria_idEvaluation_criteria' => $this->input->post('evaluation_criteria'),
            'Performance_levels_idPerformance_levels' => $this->input->post('performance_level_5')
        );
        $this->db->insert($this->ec_pl,$data);
        if($submit){
           $this->db->trans_complete();
            return  $this->db->insert_id();
        }  
    }
}
