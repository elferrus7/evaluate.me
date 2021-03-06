<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Event_model extends CI_Model {
    
    private $table = 'events';
    private $event_users = 'events_has_users';
    private $event_view = 'vwevents';
    private $students = 'students';
    private $projects = 'projects';
    private $project_students = 'projects_has_students';
    private $rubrics = 'rubrics';
    private $event_rubrics = 'events_has_rubrics';
    private $users = 'vwusers';
    function __construct()
    {
        parent::__construct();
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
                'close_date' => $this->input->post('close_date'),
                'description' => $this->input->post('description')
            );
            $this->db->insert($this->table, $data);
            $event_id = $this->db->insert_id();
            $judges = $this->input->post('judges');
            foreach($judges as $judge){
                $data_judges = array('Events_idEvents' => $event_id, 'Users_idUsers' => $judge);
                $this->db->insert($this->event_users, $data_judges);
            }
            return $event_id;
        }
        return FALSE;
    }
    
    public function update_event()
    {
        if($this->form_validation()){
            $event_id = $this->input->post('event_id');
            $data = array(
                'name' => $this->input->post('name'),
                'date' => $this->input->post('date'),
                'close_date' => $this->input->post('close_date'),
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
        return FALSE;
    }
    
    public function delete_event($event_id)
    {
        //Delete ralations with the users    
        $this->db->where('Events_idEvents',$event_id);
        $this->db->delete($this->event_users);
        //Delete relation with the Rubric
        $this->db->where('Events_idEvents',$event_id);
        $this->db->delete($this->event_rubrics);
        //Delte Project Student relations
        $projects = $this->db->get_where($this->projects, array('Events_idEvents'=>$event_id))->result();
        foreach($projects as $project){
            $this->db->where('Projects_idProjects',$project->idProjects);
            $this->db->delete($this->project_students);
        }
        //Delete projects releted
        $this->db->where('Events_idEvents',$event_id);
        $this->db->delete($this->projects);
        //Delete Event
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
                    $this->db->where(array('idUsers !='=> $event_judge->Users_idUsers ,'name'=>'Judge'));
                }
                $r = $this->db->get($this->users)->result();
                //echo $this->db->last_query();
                return $r; 
            }
            
        return $this->db->get($this->users)->result();
    }
    
    public function get_students()
    {
        $students = $this->db->get($this->students)->result();
        $data = array();
        foreach($students as $student){
            $data[$student->idStudents] = $student->first_name . ' ' .$student->last_name;
        } 
        return $data;
    }
    
    public function insert_project()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('team_name', 'Team Name', 'required');
        $this->form_validation->set_rules('project_name', 'Project Name', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('students', 'Description', 'required');
        if($this->form_validation->run()){
            $data = array(
                'team_name' => $this->input->post('team_name'),
                'project_name' => $this->input->post('project_name'),
                'description' => $this->input->post('description'),
                'Events_idEvents' => $this->input->post('event_id')
            );
            $this->db->insert($this->projects,$data);
            $project_id = $this->db->insert_id();
            $students = $this->input->post('students');
            foreach($students as $student){
                $data_project = array('Projects_idProjects'=>$project_id,'Students_idStudents' =>$student);
                $this->db->insert($this->project_students, $data_project);
            }
            
            return $project_id;
        }
        return FALSE;
    }
    
    public function get_event_projects($event_id)
    {
        return $this->db->get_where($this->projects,array('Events_idEvents'=>$event_id))->result();
    }
    
    public function get_rubrics()
    {
        $rubrics =$this->db->get($this->rubrics)->result();
        $data = array();
        foreach($rubrics as $rubric)
        {
         $data[$rubric->idRubrics] = $rubric->name;
        }
        return $data;    
    }
    public function get_event_rubrics($event_id)
    {
        $query = $this->db->get_where($this->event_rubrics,array('Events_idEvents' => $event_id));
        if($query->num_rows() > 0){
            $rubric_id = $query->row()->Rubrics_idRubrics;
            $rubric = $this->db->get_where($this->rubrics,array('idRubrics'=>$rubric_id))->row();
            return $rubric;
        }
        return FALSE;
    }
    
    public function select_rubric(){
        $data = array(
            'Rubrics_idRubrics' => $this->input->post('rubric_id'),
            'Events_idEvents' => $this->input->post('event_id')
        );
        $this->db->insert($this->event_rubrics,$data);
        return TRUE;
    }
    
    public function delete_select_rubric($event_id){
        $this->db->delete($this->event_rubrics,array('Events_idEvents'=>$event_id));
    }
    
    public function count_events()
    {
        return $this->db->count_all($this->table);
    }
    
    public function get_project($project_id){
        return $this->db->get_where($this->projects,array('idProjects'=>$project_id))->row();
    }
    
    public function get_project_students($project_id){
        $students = $this->db->get_where($this->project_students,array('Projects_idProjects'=>$project_id))->result();
        $data = array();
        foreach($students as $student){
            $data[] = $this->db->get_where($this->students,array('idStudents'=>$student->Students_idStudents))->row();
        }
        return $data;
    }
    
    public function count_projects($event_id)
    {
        $this->db->where(array('Events_idEvents'=>$event_id));
        return $this->db->count_all_results($this->projects);    
    }
    
    public function count_students($event_id)
    {
        $projects = $this->db->get_where($this->projects,array('Events_idEvents'=>$event_id))->result();
        $count = 0;
        foreach($projects as $project){
            $this->db->where(array('Projects_idProjects'=>$project->idProjects));
            $count += $this->db->count_all_results($this->project_students);
        }
        return $count;    
    }
    
    public function get_all_judges(){
        return $this->db->get_where($this->users,array('name'=>'Judge'))->result();
    }
    
    public function form_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required'); 
        $this->form_validation->set_rules('close_date', 'Close Date', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('judges', 'Judges', 'required');
        return $this->form_validation->run();
    }
}
