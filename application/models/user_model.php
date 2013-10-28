<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model 
{
    var $table = 'Users';
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
        return $this->db->get_where($this->table,array('Users_id'=>$user_id))->result();
    }
    
    public function insert_user()
    {
        $this->load->library('encrypt');
        $data = array(
            'email' => $this->post('email'),
            'username' =>$this->post('email'),
            'password' => $this->encrypt->encode($this->post('password')),
            'first_name' => $this->post('first_name'),
            'last_name' => $this->post('last_name')
        );
        $this->db->insert($this->table, $data);
        $roles = $this->input->post('roles');
            
    }
    
    public function update_user($user_id)
    {
        $data = array(
            'percentage' => $this->post('percentage'),
            'description' => $this->post('description')
        );
        $this->db->where('users_id',$user_id);
        $this->db->update($this->table, $data);
    }
    
    public function delete_user($user_id)
    {
        $this->db->where('users_id',$user_id);
        $this->db->delete($this->table);
    }
    
    public function form_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules();
        return $this->form_validation->run();
    }
}