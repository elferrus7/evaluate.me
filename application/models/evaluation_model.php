<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Evaluation_model extends CI_Model{
    private $table = 'evaluations';
    private $evaluation_vw = 'vwEvaluations';
    private $rubrics = 'rubrics';
    private $events = 'events';
    private $users = 'users';
    private $projects = 'projects';
    private $evaluation_criteria = 'evaluation_criteria';
    private $performance_leves = 'performance_levels';
    private $event_rubrics = 'events_has_rubrics';
    private $rubric_evs = 'rubrics_has_evaluation_criteria';
    private $ec_pls = 'evaluation_criteria_has_performance_levels';
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_event($project_id)
    {
        $project = $this->db->get_where($this->projects,array('idProjects'=>$project_id))->row();
        return $this->db->get_where($this->events,array('idEvents'=>$project->Events_idEvents))->row();
    }
    public function get_rubric($event_id){
        $event = $this->db->get_where($this->event_rubrics,array('Events_idEvents'=>$event_id))->row();
        return $this->db->get_where($this->rubrics,array('idRubrics'=>$event->Rubrics_idRubrics))->row(); 
    }
    public function get_evaluation_criteria_rubric($rubric_id,$project_id)
    {
        $this->db->select('Evaluation_criteria_idEvaluation_criteria');
        $evaluation_ecs = $this->db->get_where($this->table,array('Projects_idProjects'=>$project_id))->result(); //EC that had been already used to evaluate the project
        foreach($evaluation_ecs as $evaluation_ec){
            $this->db->where('Evaluation_criteria_idEvaluation_criteria !=', $evaluation_ec->Evaluation_criteria_idEvaluation_criteria); //Filters the ec tha had been used
        }
        $query = $this->db->get($this->rubric_evs);
        if($query->num_rows() > 0){
            $rubric_ecs = $query->row();  //get the first ec that had not been used from the relational table
            return $this->db->get_where($this->evaluation_criteria,array('idEvaluation_criteria'=>$rubric_ecs->Evaluation_criteria_idEvaluation_criteria))->row(); //get the first ec
        } else {
            return FALSE;
        }
          
    }
    
    public function get_evaluation_criteria($ec_id){
        return $this->db->get($this->evaluation_criteria,array('idEvaluation_criteria'=>$ec_id))->row();
    }
    
    public function get_performance_levels($ec_id)
    {
        $ec_pls = $this->db->get_where($this->ec_pls,array('Evaluation_criteria_idEvaluation_criteria'=> $ec_id))->result();
        $data = array();
        foreach($ec_pls as $ec_pl){
            //echo print_r($ec_pl);
            $this->db->order_by('percentage',"desc");
            $this->db->where('idPerformance_levels',$ec_pl->Performance_levels_idPerformance_levels);
            $data[] = $this->db->get($this->performance_leves)->row();
        }
        return $data;
    }
    
    public function get_project($project_id)
    {
        return $this->db->get_where($this->projects,array('idProjects'=>$project_id))->row();
    }
    
    public function get_evaluation($evaluation_id){
        return $this->db->get_where($this->table,array('idEvaluations'=>$evaluation_id))->row();
    }
    
    public function insert_evaluation()
    {
        $this->load->model('performance_level_model');
        $this->load->model('evaluation_criteria_model');
        $pl = $this->performance_level_model->get_performance_level($this->input->post('pls'));
        $ec = $this->evaluation_criteria_model->get_evaluation_criteria($this->input->post('evaluation_criteria_id'));
        $data= array(
            'Rubrics_idRubrics'=>$this->input->post('rubric_id'),
            'Users_idUsers'=>$this->input->post('user_id'),
            'Projects_idProjects'=>$this->input->post('project_id'),
            'Evaluation_criteria_idEvaluation_criteria'=>$this->input->post('evaluation_criteria_id'),
            'Performance_levels_idPerformance_levels'=>$this->input->post('pls'),
            'grade' => $ec->percentage * ($pl->percentage /100)
        );
        $this->db->insert($this->table,$data);
        return $this->db->insert_id();    
    }
    
    public function update_evaluation(){
        $evaluation_id = $this->input->post('evaluation_id');
        $pl = $this->performance_level_model->get_performance_level($this->input->post('pls'));
        $ec = $this->evaluation_criteria_model->get_evaluation_criteria($this->input->post('evaluation_criteria_id'));
        $data = array('Performance_levels_idPerformance_levels'=>$this->input->post('pls'),'grade' =>$ec->percentage * ($pl->percentage /100));
        $this->db->where('idEvaluations',$evaluation_id);
        $this->db->update($this->table,$data);
        return TRUE;
    }
    
    public function get_vwevaluations($project_id)
    {
      return $this->db->get_where($this->evaluation_vw, array('idProjects'=>$project_id))->result();  
    }
    public function get_sum_grade($project_id)
    {
        $this->db->select_sum('grade');
        return $this->db->get_where($this->evaluation_vw, array('idProjects'=>$project_id))->row();
    }
}
