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
        $offeset = ($event_id = $this->uri->segment(3))? $event_id = $this->uri->segment(3): 0; 
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
        
        $this->load->library('table');
        $this->config->load('table_html');
        $tmpl = $this->config->item('tmpl');
        $this->table->set_template($tmpl);
        $event = $this->event_model->get_event($event_id);
        $this->table->set_heading('','');
        $this->table->add_row('Event Name', $event->name);
        $this->table->add_row('Date', $event->date);
        $this->table->add_row('Description', $event->description);
        $data['table'] = $this->table->generate();
        $data['content'] = 'events/details_event';
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
        if($this->event_model->insert_event()){
            echo json_encode(array('stat'=>TRUE));
        } else {
            echo json_encode(array('stat'=>FALSE));
        } 
    }
    
    public function update_event()
    {
        if($this->event_model->update_event()){
            echo json_encode(array('stat'=>TRUE));
        } else {
            echo json_encode(array('stat'=>FALSE));
        }
    }
}

/* End of file events.php */
/* Location: ./application/controllers/events.php */