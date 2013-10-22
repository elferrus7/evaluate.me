<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rubrics extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('rubric_model');
    }
    
    public function index()
    {
        
    }
    
    public function display_rubrics()
    {
        
    }
    
    public function create_rubric()
    {
        $this->load->helper('form');
        $data['content'] = 'rubrics/create_rubric';
        $data['title'] = 'Create Rubric';
        $this->load->view('index.php',$data);
    }
    
    public function insert_rubric()
    {
        $rubric_id = $this->rubric_model->insert_rubric();
        redirect('rubrics/create_evaluation_criteria/' . $rubric_id);
    }
    
    public function create_evaluation_criteria()
    {
        $this->load->helper('form');
        $this->load->model('evaluation_criteria_model');
        $this->load->model('performance_level_model');
        $data['rubric_id'] = $this->uri->segment(3);
        $data['rubric'] = $this->rubric_model->get_rubric($data['rubric_id']);
        $data['evaluation_criteria'] =  $this->evaluation_criteria_model->get_evaluation_criterials();
        $data['performance_levels'] = $this->performance_level_model->get_performance_levels();
        $data['content'] = 'rubrics/add_evaluation_criteria';
        $data['title'] = 'New Evaluation Criteria';
        $this->load->view('index.php',$data);
    }
    
    public function insert_evaluation_criteria()
    {
        //echo print_r($this->input->post());
        if($this->input->post('submit') == 'Next'){
            $this->rubric_model->insert_ec();
            redirect('rubrics/add_evaluation_criteria');
        }
        
        if($this->input->post('submit') == 'Submit'){
            $this->rubric_model->insert_ec(TRUE);
            redirect('rubrics/add_evaluation_criteria');
        }
    }
    
    public function insert_ev()
    {
        $this->load->model('evaluation_criteria_model');
        $ev_id = $this->evaluation_criteria_model->insert_evaluation_criteria();
        $ev = $this->evaluation_criteria_model->get_evaluation_criteria($ev_id);
        echo json_encode(array('ev_id' => $ev->idEvaluation_criteria, 'description' => $ev->description . ' - %' . $ev->percentage));
    }
    
    public function insert_pl()
    {
        $this->load->model('performance_level_model');
        $pl_id = $this->performance_level_model->insert_performance_level();
        $pl = $this->performance_level_model->get_performance_level($pl_id);
        echo json_encode(array('pl_id' => $pl->idPerformance_levels, 'description' => $pl->description . ' - %' . $pl->percentage));
    }
    
    public function edit_rubric()
    {
        
    }
    
    public function delete_rubric()
    {
        
    }
    
}
