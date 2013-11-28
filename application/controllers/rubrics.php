<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rubrics extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('rubric_model');
        
        $this->load->library('Alert');
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
        $this->table->set_heading('Evaluation Criteria '. anchor('rubrics/create_evaluation_criteria/'.$rubric_id,'<i class="icon-plus"></i>',' class="btn" title="Add new Evaluation Criteria"'),'Performance Levels','','','','');
        foreach($rubric_ev as $rubric_ev){
            $ev_pls = $this->rubric_model->get_vwpl($rubric_ev->idEvaluation_criteria,$rubric_ev->idRubrics);
            $row = array('<p>'.$rubric_ev->ec_description. '</p> <p>%'. $rubric_ev->percentage.'</p>');
            foreach($ev_pls as $ev_pl){
                $row[] = '<p>' . $ev_pl->pl_description . '</p> <p>%' . $ev_pl->pl_percentage. '</p>';
            }
            $row[] = anchor('rubrics/edit_evaluation_criteria/'.$rubric_id.'/'.$rubric_ev->idEvaluation_criteria,'<i class="icon-pencil"></i>') .  
                     anchor('rubrics/delete_evaluation_criteria/'.$rubric_id.'/'.$rubric_ev->idEvaluation_criteria,'<i class="icon-remove"></i>');
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
        if(!$rubric_id){
          $this->alert->add_alert('Please fill all the fields','error');
          $this->alert->set_alerts();
          redirect('rubrics/create_rubric');
        }
        $this->alert->add_alert('Rubric Created','success');
        $this->alert->set_alerts(); 
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
        if($data['percentage'] == 100) redirect('rubrics/details_rubric/'.$data['rubric_id']);
        $data['chosen'] = TRUE;
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
        $data['chosen'] = TRUE;
        $data['evaluation_criteria'] =  $this->evaluation_criteria_model->get_evaluation_criterials($evs);
        $data['performance_levels'] = $this->performance_level_model->get_performance_levels();
        $data['content'] = 'rubrics/edit_evaluation_criteria';
        $data['title'] = 'New Evaluation Criteria';
        $this->load->view('index.php',$data);
    }
    
    public function insert_evaluation_criteria()
    {
        if($this->rubric_model->insert_ec()){
             $jason = array('stat'=>TRUE);   
        } else{
             $jason = array('stat'=> FALSE);
        }
        if($this->input->post('submit') == 'next'){
            $rubric_id = $this->input->post('rubric');
            $evs = $this->rubric_model->get_vwrubric($rubric_id);
            $percentage = 0;
            if(is_array($evs))
            {
                foreach($evs as $key => $ev){
                    $percentage += $ev->percentage;
                }   
            }
            $jason['redirect']= ($percentage == 100)? 'submit':'next';
            echo json_encode($jason);
        }
        if($this->input->post('submit') == 'submit'){
            $jason['redirect']= 'submit';
            echo json_encode($jason);
        }
    }
    
    public function update_evaluation_criteria()
    {
        $rubric_id = $this->input->post('rubric');
        $ec_id = $this->input->post('ec_id');
        $this->rubric_model->delete_ec($rubric_id,$ec_id);
        if($this->rubric_model->insert_ec()){
             $jason = array('stat'=>TRUE);   
        } else{
             $jason = array('stat'=> FALSE);
        }
        $jason['redirect']= 'submit';
        echo json_encode($jason);
        //redirect('rubrics/details_rubric/'.$rubric_id);
    }
    public function delete_evaluation_criteria()
    {
      $rubric_id = $this->uri->segment(3);
      $ec_id = $this->uri->segment(4);
      
      $this->alert->add_alert('Evaluation Criteria deleted','success');
      $this->alert->set_alerts();
      $this->rubric_model->delete_ec($rubric_id,$ec_id);
      redirect('rubrics/details_rubric/'.$rubric_id);
    }
    public function insert_ec()
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
        if(!$this->rubric_model->update_rubric()){
            $this->alert->add_alert('Please fill all the fields','error');
            $this->alert->set_alerts();
            redirect('rubrics/edit_rubric/'.$rubric_id);
        } 
        $this->alert->add_alert('Rubric Updated','success');
        $this->alert->set_alerts();
        redirect('rubrics/details_rubric/'.$rubric_id);
    }
    
    public function delete_rubric()
    {
        $rubric_id = $this->uri->segment(3);
        if($rubric_id === FALSE)redirect('rubrics/display_rubrics');
        $this->rubric_model->delete_rubric($rubric_id);
        $this->alert->add_alert('Rubric Deleted','success');
        $this->alert->set_alerts();
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
