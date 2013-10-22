<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Event_model extends CI_Model {
    
    private $table = 'events';
    private $event_users = 'events_has_users';
    private $event_view = 'vwevents';
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function get_events($limit,$offset)
    {
        $this->db->limit($limit,$offset);
        return $this->db->get($this->table)->result();  
    }
    
    public function get_event($event_id)
    {
        return $this->db->get_where($this->table,array('idEvents'=>$event_id))->row();
    }
    
    public function insert_event()
    {
        if($this->form_validation()){
            $data = array(
                'name' => $this->input->post('name'),
                'date' => $this->input->post('date'),
                'description' => $this->input->post('description')
            );
            $this->db->insert($this->table, $data);
            $event_id = $this->db->insert_id();
            $judges = $this->input->post('judges');
            foreach($judges as $judge){
                $data_judges = array('Events_idEvents' => $event_id, 'Users_idUsers' => $judge);
                $this->db->insert($this->event_users, $data_judges);
            }
            return TRUE;
        }
    }
    
    public function update_event()
    {
        if($this->form_validation()){
            $event_id = $this->input->post('event_id');
            $data = array(
                'name' => $this->input->post('name'),
                'date' => $this->input->post('date'),
                'description' => $this->input->post('description')
            );
            $this->db->where('idEvents',$event_id);
            $this->db->update($this->table, $data);
            $this->db->delete($this->event_users, array('Events_idEvents' => $event_id));
            $judges = $this->input->post('judges');
            foreach($judges as $judge){
                    $data_judges = array('Events_idEvents' => $event_id, 'Users_idUsers' => $judge);
                    $this->db->insert($this->event_users, $data_judges);
            }
            return TRUE;   
        }
    }
    
    public function delete_event($event_id)
    {
        $this->db->where('Events_idEvents',$event_id);
        $this->db->delete($this->event_users);
        $this->db->where('idEvents',$event_id);
        $this->db->delete($this->table);
    }

    public function get_judges_event($event_id)
    {
        return $this->db->get_where($this->event_view, array('idEvents' => $event_id))->result();
    }
    
    public function get_judges($event_id = FALSE)
    {
            if($event_id){
               $event_judges = $this->db->get_where($this->event_view, array('idEvents' => $event_id))->result();
                foreach($event_judges as $event_judge){
                    $this->db->where('idUsers !=', $event_judge->Users_idUsers);
                }
                return $this->db->get('Users')->result(); 
            }
            
        return $this->db->get('Users')->result();
    }
    
    public function count_events()
    {
        return $this->db->count_all($this->table);
    }
    
    public function form_validation()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required'); 
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('judges', 'Judges', 'required');
        return $this->form_validation->run();
    }
}
