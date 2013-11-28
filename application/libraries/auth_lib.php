<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Esta libreria sirve para que todos los controladores puedan verificar si el usuario cuenta con los permisos necesarios
 * para accesar a las funciones
 */
 
 class Auth_lib{
     var $CI;
     public function __contruct()
     {
     }
     
     public function validate_auth(){
         $CI =& get_instance();
         $CI->load->library('Alert');
         $operation = ($CI->uri->segment(2))? $CI->uri->segment(2):'index';
         $auth = $CI->session->userdata('auth');
         if(!array_key_exists($operation, $auth)){
             $CI->alert->add_alert('You have no permissions','error');
             $CI->alert->set_alerts();
             redirect('events');
         };
     }
     
     public function have_role($role_name)
     {
         $CI =& get_instance();
         $CI->load->model('user_model');
         $user_id = $CI->session->userdata('idUsers');
         $roles = $CI->user_model->get_user_roles($user_id);
         foreach($roles as $role){
             if($role->name == $role_name) return TRUE;
         }
         return FALSE;
     }
     
     public function have_permisssion()
     {
         
     }
 }
