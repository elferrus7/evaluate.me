<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rubric_model extends CI_Model{
    
    private $table = 'rubrics';
    private $evaluation_criteria = 'evaluation_criteria';
    private $performace_level = 'performance_levels';
    private $rubric_ec = 'rubrics_has_evaluation_criteria';
    private $ec_pl = 'evaluation_criteria_has_performance_levels';
    private $rubric_view = 'vwrubrics';
    private $ec_pl_view = 'vwevaluationcriteria';
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_rubrics($limit,$offset)
    {
        $this->db->limit($limit,$offset);
        return $this->db->get($this->table)->result();
    }
    
    public function get_rubric($rubric_id)
    {
        return $this->db->get_where($this->table,array('idRubrics'=>$rubric_id))->row();
    }
    
    public function get_vwrubric($rubric_id)
    {
        $this->db->order_by('percentage',"desc");
        return $this->db->get_where($this->rubric_view,array('idRubrics'=>$rubric_id))->result();
    }
    
    public function get_vwpl($evaluation_criteria_id,$rubric_id)
    {
        $this->db->order_by('pl_percentage',"desc");
        return $this->db->get_where($this->ec_pl_view,array('idEvaluation_criteria'=>$evaluation_criteria_id,'Rubrics_idRubrics'=>$rubric_id))->result();
    }
    
    public function insert_rubric()
    {
        if($this->form_validation()){
            $data = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description')
            );
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
        return FALSE; 
    }
    
    public function update_rubric()
    {
        if($this->form_validation())
        {
            $rubric_id = $this->input->post('rubric_id');
            $data = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description')
            );
            $this->db->where('idRubrics',$rubric_id);
            $this->db->update($this->table, $data);
            return TRUE;   
        }
        return FALSE;
    }
    
    public function delete_rubric($rubric_id)
    {
        $this->db->delete($this->rubric_ec,array('Rubrics_idRubrics'=>$rubric_id));
        $this->db->delete($this->ec_pl,array('Rubrics_idRubrics'=>$rubric_id));
        $this->db->delete($this->table,array('idRubrics'=>$rubric_id));
        
        return TRUE;    
    }
    
    /*public function get_evaluation_criteria()
    {
        return $this->db->get($this->evaluation_criteria)->result();
    }
    
    public function get_performance_levels()
    {
        return $this->db->get($this->performace_level)->result();
    }*/
    
    public function insert_ec()
    {
        //if($this->validate_ec())
        //{
            $this->db->trans_start();
            $ecs = $this->get_vwrubric($this->input->post('rubric'));
            $percentage = 0;
            if(is_array($ecs))
            {
                foreach($ecs as $key => $ev){
                    $percentage += $ev->percentage;
                }   
            }
            $ec = $this->get_ec($this->input->post('evaluation_criteria'));
            if(($percentage + $ec->percentage) > 100){
                return FALSE;
            } 
            $data = array(
                'Evaluation_criteria_idEvaluation_criteria' => $this->input->post('evaluation_criteria'),
                'Rubrics_idRubrics' => $this->input->post('rubric')
            );
            $this->db->insert($this->rubric_ec,$data);
            $pls = $this->input->post('pls');
            //echo print_r($pls);
            foreach ($pls as $pl) { 
                $data = array(
                    'Evaluation_criteria_idEvaluation_criteria' => $this->input->post('evaluation_criteria'),
                    'Performance_levels_idPerformance_levels' => $pl,
                    'Rubrics_idRubrics' => $this->input->post('rubric')
                );
                $this->db->insert($this->ec_pl,$data);    
            }
            $this->db->trans_complete();
            return TRUE;    
        //}
          
    }
    
    public function delete_ec($rubric_id, $ec_id)
    {
            //$this->db->trans_start();
            
            $this->db->where('Rubrics_idRubrics',$rubric_id);
            $this->db->where('Evaluation_criteria_idEvaluation_criteria',$ec_id);
            $this->db->delete($this->rubric_ec);
            
            $this->db->where('Rubrics_idRubrics',$rubric_id);
            $this->db->where('Evaluation_criteria_idEvaluation_criteria',$ec_id);
            $this->db->delete($this->ec_pl);
            //$this->db->trans_complete();
    }
    
    public function validate_ec()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('evaluation_criteria', 'Evaluation_criteria', 'required');
        
        for($i=1; $i<=5;$i++){
            $this->form_validation->set_rules('performance_level_'.$i, 'performance_level_'.$i, 'required');
        }
        
        return $this->form_validation->run();
    }
    
    public function get_ec($ec_id){
        return $this->db->get_where($this->evaluation_criteria,array('idEvaluation_criteria'=>$ec_id))->row();
    }
    
    public function form_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name','Name','required');
        $this->form_validation->set_rules('description','Description','required');
        return $this->form_validation->run();
    }
    
    public function count_rubrics()
    {
        return $this->db->count_all($this->table);
    }
    
}
