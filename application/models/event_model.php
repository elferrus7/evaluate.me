<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Event_model extends CI_Model {
    
    var $table = 'events';
        
    function __construct()
    {
        parent::__construct();
    }

    public function get_events()
    {
        return $this->db->get($this->table)->result();  
    }
    
    public function get_event($event_id)
    {
        return $this->db->get_where($this->table,array('Events_id'=>$event_id))->result();
    }
    
    public function insert_event()
    {
        $data = array(
            'name' => $this->input->post('name'),
            'date' => $this->input->post('date'),
            'description' => $this->input->post('description')
        );
        
        $this->db->insert($this->table, $data);    
    }
    
    public function update_event($event_id)
    {
        $data = array(
            'name' => $this->input->post('name'),
            'date' => $this->input->post('date'),
            'description' => $this->input->post('description')
        );
        $this->db->where('Events_id',$event_id);
        $this->db->update($this->table, $data);
    }
    
    public function delete_event($event_id)
    {
        $this->db->where('Events_id',$event_id);
        $this->db->delete($this->table);
    }

}
