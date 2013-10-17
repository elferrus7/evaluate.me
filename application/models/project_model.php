<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Project_model extends CI_Model 
{
    var $table = 'projects';
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_projects()
    {
        return $this->db->get($this->table)->result();
    }
    
    public function get_project($project_id)
    {
        return $this->db->get_where($this->table,array('projects_id'=>$project_id))->result();
    }
    
    public function insert_project($project)
    {
        
    }
    
    public function update_project($project_id,$project){
        
    }
    
    public function delete_project($project_id)
    {
        
    }
}