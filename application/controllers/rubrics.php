<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rubrics extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('rubric_model');
        //$this->load->library('auth');
        //if(!$this->auth->have_auth()) redirect('auth');
    }
    
    public function index()
    {
        redirect('rubrics/display_rubrics');
    }
    
    public function display_rubrics()
    {
        $this->load->library('table');
        $this->config->load('table_html');
        $tmpl = $this->config->item('tmpl');
        $this->table->set_template($tmpl);
        
        $limit = 20;
        $offeset = ($event_id = $this->uri->segment(3))? $event_id = $this->uri->segment(3): 0; 
        $this->load->library('pagination');
        $this->config->load('pagination_html');
        $config = $this->config->item('tmpl');
        $config['base_url'] = base_url().'index.php/rubrics/display_rubrics';
        $config['total_rows'] = $this->rubric_model->count_rubrics();
        $config['per_page'] = $limit; 
        $this->pagination->initialize($config);
        
        $rubrics = $this->rubric_model->get_rubrics($limit,$offeset);
        
        $this->table->set_heading('Rubric Name','Rubric Description', '');
        $this->table->set_caption('Rubrics ' . anchor('rubrics/create_rubric','<i class="icon-plus"></i>', 'class="btn" title="New Rubric"'));
        
        foreach($rubrics as $rubric)
        {
            $this->table->add_row($rubric->name, $rubric->description,
                                  anchor('rubrics/details_rubric/'.$rubric->idRubrics,'<i class="icon-zoom-in"></i>').
                                  anchor('rubrics/edit_rubric/'.$rubric->idRubrics,'<i class="icon-pencil"></i>').
                                  anchor('rubrics/delete_rubric/'.$rubric->idRubrics,'<i class="icon-remove"></i>'));
        }
        
        $data['table'] = $this->table->generate();
        $data['content'] = 'rubrics/display_rubrics';
        $data['title'] = 'Rubrics';
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('index.php',$data);
    }
    
    public function details_rubric()
    {
        $this->load->library('table');
        $this->config->load('table_html');
        $tmpl = $this->config->item('tmpl');
        $this->table->set_template($tmpl);
        
        $rubric_id = $this->uri->segment(3);
        $rubric = $this->rubric_model->get_rubric($rubric_id);
        $rubric_ev = $this->rubric_model->get_vwrubric($rubric_id);
        $this->table->set_heading('Evaluation Criteria','Performance Levels','','','','');
        foreach($rubric_ev as $rubric_ev){
            $ev_pls = $this->rubric_model->get_vwpl($rubric_ev->idEvaluation_criteria,$rubric_ev->idRubrics);
            $row = array('<p>'.$rubric_ev->ec_description. '</p> <p>%'. $rubric_ev->percentage.'</p>');
            foreach($ev_pls as $ev_pl){
                $row[] = '<p>' . $ev_pl->pl_description . '</p> <p>%' . $ev_pl->pl_percentage. '</p>';
            }
            $row[] = anchor('rubrics/edit_evaluation_criteria/'.$rubric_id.'/'.$rubric_ev->idEvaluation_criteria,'<i class="icon-pencil"></i>');
            $this->table->add_row($row);
            
        }
        $data['table'] = $this->table->generate();
        $data['content'] = 'rubrics/details_rubrics';
        $data['title'] = 'Rubric';
        $this->load->view('index.php',$data);
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
        if(!$rubric_id) redirect('rubrics/create_rubric');
        redirect('rubrics/create_evaluation_criteria/' . $rubric_id);
    }
    
    public function create_evaluation_criteria()
    {
        $this->load->helper('form');
        $this->load->model('evaluation_criteria_model');
        $this->load->model('performance_level_model');
        $data['rubric_id'] = $this->uri->segment(3);
        $rubric = $this->rubric_model->get_rubric($data['rubric_id']);
        $data['rubric'] = $rubric;
        $evs = $this->rubric_model->get_vwrubric($rubric->idRubrics);
        //echo print_r($evs);
        $data['percentage'] = 0;
        if(is_array($evs))
        {
            foreach($evs as $ev){
                $data['percentage'] += $ev->percentage; 
            }   
        }
        $data['evaluation_criteria'] =  $this->evaluation_criteria_model->get_evaluation_criterials($evs);
        $data['performance_levels'] = $this->performance_level_model->get_performance_levels();
        $data['content'] = 'rubrics/create_evaluation_criteria';
        $data['title'] = 'New Evaluation Criteria';
        $this->load->view('index.php',$data);
    }
    
    public function edit_evaluation_criteria()
    {
        $rubric_id = $this->uri->segment(3);
        $ec_id = $this->uri->segment(4);
        
        $this->load->helper('form');
        $this->load->model('evaluation_criteria_model');
        $this->load->model('performance_level_model');
        
        $data['rubric_id'] = $rubric_id;
        $data['ec_id'] = $ec_id;
        $rubric = $this->rubric_model->get_rubric($data['rubric_id']);
        $data['rubric'] = $rubric;
        $evs = $this->rubric_model->get_vwrubric($rubric->idRubrics);
        $data['percentage'] = 0;
        if(is_array($evs))
        {
            foreach($evs as $key => $ev){
                $data['percentage'] += $ev->percentage;
                if($ec_id == $ev->idEvaluation_criteria) unset($evs[$key]);
            }   
        }
        $data['evaluation_criteria'] =  $this->evaluation_criteria_model->get_evaluation_criterials($evs);
        $data['performance_levels'] = $this->performance_level_model->get_performance_levels();
        $data['content'] = 'rubrics/edit_evaluation_criteria';
        $data['title'] = 'New Evaluation Criteria';
        $this->load->view('index.php',$data);
    }
    
    public function insert_evaluation_criteria()
    {
        //echo print_r($this->input->post());
        $rubric_id = $this->input->post('rubric');
        if($this->input->post('submit') == 'Next'){
            $this->rubric_model->insert_ec();
            redirect('rubrics/create_evaluation_criteria/'.$rubric_id);
        }
        
        if($this->input->post('submit') == 'Submit'){
            $this->rubric_model->insert_ec();
            redirect('rubrics/details_rubric/'.$rubric_id);
        }
    }
    
    public function update_evaluation_criteria()
    {
        $rubric_id = $this->input->post('rubric');
        $this->rubric_model->delete_ec();
        $this->rubric_model->insert_ec();
        redirect('rubrics/details_rubric/'.$rubric_id);
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
        $rubric_id = $this->uri->segment(3);
        if($rubric_id === FALSE)redirect('rubrics/display_rubrics');
        $this->load->helper('form');
        $data['rubric'] = $this->rubric_model->get_rubric($rubric_id);
        $data['content'] = 'rubrics/edit_rubric';
        $data['title'] = 'New Evaluation Criteria';
        $this->load->view('index.php',$data);   
    }
    
    public function update_rubric()
    {
        $rubric_id = $this->input->post('rubric_id');
        if(!$this->rubric_model->update_rubric()) redirect('rubrics/edit_rubric/'.$rubric_id);
        redirect('rubrics/details_rubric/'.$rubric_id);
    }
    
    public function delete_rubric()
    {
        $rubric_id = $this->uri->segment(3);
        $this->rubric_model->delete_rubric($rubric_id);
        redirect('rubrics/display_rubrics');
    }
    
    public function get_pl()
    {
        $this->load->model('performance_level_model');
        
        $pl_ids = $this->input->post('pl_ids');
        $pls = $this->performance_level_model->get_performance_levels($pl_ids);
        echo json_encode(array('pl_ids' => $pls));
    }
}
