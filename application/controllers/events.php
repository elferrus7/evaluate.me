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
        
        $events = $this->event_model->get_events();
        
        $this->table->set_heading('Event Name','Event Description', 'Date','');
        $this->table->set_caption('Events ' . anchor('events/create_event','<i class="icon-plus"></i>', 'class="btn" title="New Event"'));
        foreach($events as $event){
            $this->table->add_row($event->name, $event->description, $event->date, '<a href="#"><i class="icon-zoom-in"></i></a><a href="#"><i class="icon-pencil"></i></a><a href="#"><i class="icon-remove"></i></a>');
        }
        $data['table'] = $this->table->generate();
        $data['content'] = 'events/display_events';
        $data['title'] = 'Evaluate.me';
        $this->load->view('index.php',$data);
    }
    
    public function create_event()
    {
        $this->load->helper('form');
        $data['content'] = 'events/create_event';
        $data['sorteable'] = TRUE;
        $data['title'] = 'Evaluate.me';
        $this->event_model->insert_event();
        $this->load->view('index.php',$data);
    }
    
    public function edit_event()
    {
        
    }
    public function delete_event()
    {
        
    }
    public function test_ajax()
    {
        $name = $this->input->post('name');
        $judges = $this->input->post('judges');
        $resp = "";
        foreach($judges as $judge){
            $resp .= " " . $judge;
        }
        echo "Name $name , $resp";
    }
}

/* End of file events.php */
/* Location: ./application/controllers/events.php */