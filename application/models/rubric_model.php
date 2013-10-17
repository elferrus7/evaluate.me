<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rubric_model extends CI_Model{
    
    var $table = 'rubrics';
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_rubrics()
    {
        return $this->db->get($this->table)->result();
    }
    
    public function get_rubric($rubric_id)
    {
        return $this->db->get_where($this->table,array('Rubrics_id'=>$rubric_id))->result();
    }
    
    public function insert_rubric()
    {
        $data = array(
            'name' => $this->post('name'),
            'description' => $this->post('description')
        );
        
        $this->db->insert($this->table, $data);    
    }
    
    public function update_rubric($rubric_id)
    {
        $data = array(
            'name' => $this->post('name'),
            'description' => $this->post('name')
        );
        $this->db->where('rubrics_id',$rubric_id);
        $this->db->update($this->table, $data);
    }
    
    public function delete_rubric($rubric_id)
    {
        $this->db->where('Rubrics_id',$rubric_id);
        $this->db->delete($this->table);
    }
}
