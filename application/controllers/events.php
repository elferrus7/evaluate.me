<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Events extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -  
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
     
     function __construct()
    {
        parent::__construct();
        $this->load->model('event_model');
        //$this->load->library('auth_lib');
        //if(!$this->auth_lib->have_auth()) redirect('auth');
    }
     
    public function index()
    {
        redirect('events/display_events');
    }
    
    public function display_events()
    {
        $this->load->library('table');
        $this->config->load('table_html');
        $tmpl = $this->config->item('tmpl');
        $this->table->set_template($tmpl);
        
        $limit = 20;
        $offeset = ($this->uri->segment(3))?  $this->uri->segment(3): 0; 
        $this->load->library('pagination');
        $this->config->load('pagination_html');
        $config = $this->config->item('tmpl');
        $config['base_url'] = base_url().'index.php/events/display_events';
        $config['total_rows'] = $this->event_model->count_events();
        $config['per_page'] = $limit; 
        $this->pagination->initialize($config); 
        
        $events = $this->event_model->get_events($limit,$offeset);
        
        $this->table->set_heading('Event Name','Event Description', 'Date','');
        $this->table->set_caption('Events ' . anchor('events/create_event','<i class="icon-plus"></i>', 'class="btn" title="New Event"'));
        foreach($events as $event){
            $this->table->add_row($event->name, $event->description, $event->date, 
                                  anchor('events/details_event/'.$event->idEvents,'<i class="icon-zoom-in"></i>').
                                  anchor('events/edit_event/'.$event->idEvents,'<i class="icon-pencil"></i>').
                                  anchor('events/delete_event/'.$event->idEvents,'<i class="icon-remove"></i>'));
        }
        $data['table'] = $this->table->generate();
        $data['content'] = 'events/display_events';
        $data['title'] = 'Evaluate.me';
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('index.php',$data);
    }
    public function details_event()
    {
        if(($event_id = $this->uri->segment(3)) === FALSE)redirect('events/display_events');
        
        $this->load->helper('form');
        $this->load->library('table');
        $this->config->load('table_html');
        $tmpl = $this->config->item('tmpl');
        $this->table->set_template($tmpl);
        $event = $this->event_model->get_event($event_id);
        $this->table->set_heading('Details','');
        $this->table->add_row('Event Name', $event->name .' ' . anchor('events/edit_event/'.$event->idEvents,'<i class="icon-pencil"></i>','title="Edit Event"'));
        $this->table->add_row('Date', $event->date);
        $this->table->add_row('Description', $event->description);
        $data['table'] = $this->table->generate();
        
        $this->table->clear();
        $judges = $this->event_model->get_judges_event($event_id);
        $this->table->set_heading('Judges','');
        foreach($judges as $judge){
            $this->table->add_row($judge->first_name . ' ' . $judge->last_name,anchor('users/details_user/'.$judge->idUsers,'<i class="icon-zoom-in"></i>'));
        }
        $data['table_judges'] = $this->table->generate();
        
        $data['students'] = $this->event_model->get_students();
        $this->table->clear();
        $tmpl['table_open'] = '<table class="table table-stripped" id="projects">';
        $this->table->set_template($tmpl);
        $this->table->set_heading('Projects','','');
        
        $projects = $this->event_model->get_event_projects($event_id);
        
        foreach($projects as $project){
            $this->table->add_row($project->team_name,$project->project_name,$project->description . ' ' . anchor('events/details_project/'.$project->idProjects,'<i class="icon-pencil"></i>','title="Project details"'));
        }
        $data['rubrics'] = $this->event_model->get_rubrics();
        $data['event_rubric'] = $this->event_model->get_event_rubrics($event_id);
        $data['table_projects'] = $this->table->generate();
        $data['event_id'] = $event_id;
        $data['chosen'] = TRUE;
        $data['content'] = 'events/details_event';
        $data['title'] = 'Evaluate.me';
        $this->load->view('index.php',$data);
    }
    
    public function details_project()
    {
        $project_id = $this->uri->segment(3);
        $project = $this->event_model->get_project($project_id);
        $students = $this->event_model->get_project_students($project_id);
        
        $this->load->library('table');
        $this->config->load('table_html');
        $tmpl = $this->config->item('tmpl');
        $this->table->set_template($tmpl);
        
        $this->table->set_heading('Project','');
        $this->table->add_row('Project name',$project->project_name);
        $this->table->add_row('Team name',$project->team_name);
        $this->table->add_row('Description',$project->team_name);
        //echo print_r($students);
        foreach($students as $student){
            $this->table->add_row('Student',$student->first_name . ' ' . $student->last_name);
        }
        $data['table'] = $this->table->generate();
        $data['content'] = 'events/details_project';
        $data['title'] = 'Evaluate.me';
        $this->load->view('index.php',$data);
    }
    
    public function create_event()
    {
        $this->load->helper('form');
        $this->load->model('user_model');
        $data['content'] = 'events/create_event';
        $data['sorteable'] = TRUE;
        $data['title'] = 'Create Event';
        $judges = $this->user_model->get_users();
        $data['judges'] = "";
        foreach ($judges as $judge){
            $data['judges'] .= "<li data-id=\"$judge->idUsers\" class=\"judge\">$judge->first_name $judge->last_name </li>";
        }
        $this->load->view('index.php',$data);
    }
    
    public function edit_event()
    {
        $this->load->helper('form');
        $this->load->model('user_model');
        $event_id = $this->uri->segment(3);
        if($event_id === FALSE)redirect('events/display_events');
        $data['content'] = 'events/edit_event';
        $data['sorteable'] = TRUE;
        $data['title'] = 'Edit Event';
        $judges = $this->event_model->get_judges($event_id);
        $data['judges'] = "";
        foreach ($judges as $judge){
            $data['judges'] .= "<li data-id=\"$judge->idUsers\" class=\"judge\">$judge->first_name $judge->last_name </li>";
        }
        $event_judges = $this->event_model->get_judges_event($event_id);
        $data['event_judges'] = "";
        foreach ($event_judges as $event_judge){
            $data['event_judges'] .= "<li data-id=\"$event_judge->idUsers\" class=\"judge\">$event_judge->first_name $event_judge->last_name </li>";
        }
        $data['event'] = $this->event_model->get_event($event_id);
        $this->load->view('index.php',$data);
    }
    public function delete_event()
    {
        if(($event_id = $this->uri->segment(3)) === FALSE)redirect('events/display_events');   
        $this->event_model->delete_event($event_id);
        redirect('events/display_events');
    }
    public function insert_event()
    {
        if($event_id = $this->event_model->insert_event()){
            echo json_encode(array('stat'=>TRUE,'event_id'=>$event_id));
        } else {
            echo json_encode(array('stat'=>FALSE));
        } 
    }
    
    public function update_event()
    {
        $event_id = $this->input->post('event_id');
        if($this->event_model->update_event()){
            echo json_encode(array('stat'=>TRUE,'event_id'=>$event_id));
        } else {
            echo json_encode(array('stat'=>FALSE));
        }
    }
    
    public function insert_project()
    {
        if($project_id = $this->event_model->insert_project()){
            echo json_encode(array('stat'=> TRUE,'project_id'=>$project_id));
        }else {
            echo json_encode(array('stat'=>FALSE));
        }
    }
    
    public function select_rubric()
    {
        if($this->event_model->select_rubric()){
            echo json_encode(array('stat'=>TRUE));
        }else{
            echo json_encode(array('stat'=>FALSE));
        }
    }
    
}

/* End of file events.php */
/* Location: ./application/controllers/events.php */