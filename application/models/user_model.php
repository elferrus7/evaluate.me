<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model 
{
    private $table = 'Users';
    private $users_roles = 'users_has_roles';
    private $users_view = 'vwUsers';
    function __construct()
    {
        parent::__construct();
    }
    
    public function get_users()
    {
        return $this->db->get($this->table)->result();
    }
    
    public function get_user($user_id)
    {
        return $this->db->get_where($this->table,array('idUsers'=>$user_id))->row();
    }
    
    public function insert_user()
    {
        if($this->form_validation()){
            $this->load->library('encrypt');
            $data = array(
                'email' => $this->input->post('email'),
                'username' =>$this->input->post('email'),
                'password' => $this->encrypt->encode($this->input->post('password')),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name')
            );
            $this->db->insert($this->table, $data);
            $user_id = $this->db->insert_id();
            $roles = $this->input->post('roles');
            foreach($roles as $role){
                $data_roles = array('Users_idUsers' =>$user_id, 'Roles_idRoles' => $role);
                $this->db->insert($this->users_roles,$data_roles);
            }    
            return $user_id;
        }
        return FALSE;
    }
    
    public function update_user()
    {
        if($this->form_validation()){
            $this->load->library('encrypt');
            $data = array(
                'email' => $this->input->post('email'),
                'username' =>$this->input->post('email'),
                'password' => $this->encrypt->encode($this->input->post('password')),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name')
            );
            $user_id = $this->input->post('user_id');
            $this->db->where('idUsers',$user_id);
            $this->db->update($this->table, $data);
            $this->db->delete($this->users_roles ,array('Users_idUsers'=>$user_id));
            $roles = $this->input->post('roles');
            foreach($roles as $role){
                $data_roles = array('Users_idUsers' =>$user_id, 'Roles_idRoles' => $role);
                $this->db->insert($this->users_roles,$data_roles);
            }    
            return $user_id;
        }
        return FALSE;
    }
    
    public function delete_user($user_id)
    {
        $this->db->delete($this->users_roles ,array('Users_idUsers'=>$user_id));
        $this->db->delete($this->table,array('idUsers'=>$user_id));
        return TRUE;
    }
    
    public function get_user_roles($user_id)
    {
        return $this->db->get_where($this->users_view,array('idUsers' => $user_id))->result();
    }
    
    public function get_roles($user_id = FALSE)
    {
        if($user_id){
            $user_roles = $this->get_user_roles($user_id);
            foreach($user_roles as $user_role){
                $this->db->where('idRoles !=', $user_role->idRoles);
            }
            return $this->db->get($this->users_view)->result();
        }
        return $this->db->get($this->users_view)->result();
    }
    
    public function count_users()
    {
        return $this->db->count_all($this->table);
    }
    
    public function form_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email','email','required');
        $this->form_validation->set_rules('password','password','required');
        $this->form_validation->set_rules('first_name','first_name','required');
        $this->form_validation->set_rules('last_name','last_name','required');
        return $this->form_validation->run();
    }
}