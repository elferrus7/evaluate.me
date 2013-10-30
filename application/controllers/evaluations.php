<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Evaluations extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('evaluation_model');
    }
    
    public function index()
    {
        
    }
    
    public function evaluate()
    {
        $project_id = $this->uri->segment(3);
        $this->load->helper('form');
        $project = $this->evaluation_model->get_project($project_id);
        $event = $this->evaluation_model->get_event($project_id);
        $rubric = $this->evaluation_model->get_rubric($event->idEvents);
        $ec = $this->evaluation_model->get_evaluation_criteria($rubric->idRubrics,$project_id);
        $pls = $this->evaluation_model->get_performance_levels($ec->idEvaluation_criteria);
        $data['project'] = $project;
        $data['event'] = $event;
        $data['ec'] = $ec;
        $data['pls'] = $pls;
        $data['title'] = 'Evaluation';
        $data['content'] = 'evaluations/evaluate';
        $this->load->view('index.php',$data);
    }
    public function create_evaluation()
    {
        
    }
}
