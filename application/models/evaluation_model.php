<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Evaluation_model extends CI_Model{
    private $table = 'evaluations';
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
    public function get_evaluation_criteria($rubric_id,$project_id)
    {
        $this->db->select('Evaluation_criteria_idEvaluation_criteria');
        $evaluation_ecs = $this->db->get_where($this->table,array('Projects_idProjects'=>$rubric_id))->result(); //EC that had been already used to evaluate the project
        foreach($evaluation_ecs as $evaluation_ec){
            $this->db->where('idEvaluation_criteria !=', $evaluation_ec->Evaluation_criteria_idEvaluation_criteria); //Filters the ec tha had been used
        }
        $rubric_ecs = $this->db->get($this->rubric_evs)->row(); //get the first ec that had not been used from the relational table
        return $this->db->get_where($this->evaluation_criteria,array('idEvaluation_criteria'=>$rubric_ecs->Evaluation_criteria_idEvaluation_criteria))->row(); //get the first ec  
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
    
    public function get_evaluation(){
        
    }
    
    public function insert_evaluation(){
        
    }
    
    public function edit_evaluation(){
        
    }
    
}
