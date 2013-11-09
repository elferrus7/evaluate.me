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
        $ec = $this->evaluation_model->get_evaluation_criteria_rubric($rubric->idRubrics,$project_id);
        if(!$ec){redirect('evaluations/details_evaluation/'.$project_id);}
        $pls = $this->evaluation_model->get_performance_levels($ec->idEvaluation_criteria);
        $data['rubric'] = $rubric;
        $data['user'] = 1;
        $data['project'] = $project;
        $data['event'] = $event;
        $data['ec'] = $ec;
        $data['pls'] = $pls;
        $data['title'] = 'Evaluation';
        $data['content'] = 'evaluations/evaluate';
        $this->load->view('index.php',$data);
    }
    public function insert_evaluation()
    {
        $project_id = $this->input->post('project_id');
        if($this->evaluation_model->insert_evaluation()){
            redirect('evaluations/evaluate/'.$project_id);
        }
        redirect('evaluations/evaluate/'.$project_id);
    }
    
    public function details_evaluation(){
        $project_id = $this->uri->segment(3);
        $evaluations = $this->evaluation_model->get_vwevaluations($project_id);
        
        $this->load->library('table');
        $this->config->load('table_html');
        $tmpl = $this->config->item('tmpl');
        $this->table->set_template($tmpl);
        
        $this->table->set_heading('Evaluation Criteria','Points Available', 'Performance Level','Assigned Points','');
        foreach($evaluations as $evaluation)
        {
            $this->table->add_row($evaluation->ec_description,
                                  $evaluation->ec_percentage,
                                  $evaluation->pl_description,
                                  $evaluation->grade,
                                  ' '.anchor('evaluations/edit_evaluation/'.$evaluation->idEvaluations,'<i class="icon-pencil"></i>'));
        }
        $data['table'] = $this->table->generate();
        $data['project'] = $this->evaluation_model->get_project($project_id);
        $data['grade'] = $this->evaluation_model->get_sum_grade($project_id)->grade;
        $data['content'] = 'evaluations/details_evaluation';
        $data['title'] = 'Evaluation';
        $this->load->view('index.php',$data);
    }

    public function edit_evaluation()
    {
        $evaluation_id = $this->uri->segment(3);
        $this->load->helper('form');
        $evaluation = $this->evaluation_model->get_evaluation($evaluation_id);
        $project = $this->evaluation_model->get_project($evaluation->Projects_idProjects);
        $event = $this->evaluation_model->get_event($evaluation->Projects_idProjects);
        $rubric = $this->evaluation_model->get_rubric($event->idEvents);
        $ec = $this->evaluation_model->get_evaluation_criteria($evaluation->Evaluation_criteria_idEvaluation_criteria);
        $pls = $this->evaluation_model->get_performance_levels($ec->idEvaluation_criteria);
        $data['evaluation'] = $evaluation;
        $data['rubric'] = $rubric;
        $data['user'] = 1;
        $data['project'] = $project;
        $data['event'] = $event;
        $data['ec'] = $ec;
        $data['pls'] = $pls;
        $data['title'] = 'Evaluation';
        $data['content'] = 'evaluations/edit_evaluation';
        $this->load->view('index.php',$data);
    }
    
    public function update_evaluation()
    {
        
    }
}
