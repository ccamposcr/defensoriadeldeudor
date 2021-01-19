<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
    
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('id')) {
            redirect('inicio');
        }
        $this->load->library('form_validation');
        $this->load->model('login_model');
    }
    
    function index(){
        $data['title'] = 'Login';
        $this->load->view('global/header', $data);
        $this->load->view('login');
        $this->load->view('global/footer');
    }

    function verify_hash($plain_password, $hashed_password){
        $result = password_verify($plain_password, $hashed_password);
        return $result;
    }
    
    function validation(){
        
        $this->form_validation->set_rules('user_personalID', 'ID', 'required|trim');
        $this->form_validation->set_rules('user_password', 'Password', 'required');

        if ($this->form_validation->run()) {
      
            $message = 'Cédula o Contraseña incorrecta';
            $passwordhashFromBD = $this->login_model->getUserPassword($this->input->post('user_personalID'));
            if($passwordhashFromBD){
                $passwordVerification = $this->verify_hash($this->input->post('user_password'), $passwordhashFromBD);
            
                if($passwordVerification){
                    $message = 'Logged IN';
                    $this->session->set_userdata('id', $this->input->post('user_personalID'));
                    redirect('inicio');
                    
                }
            }
  
            $this->session->set_flashdata('message', $message);
            redirect('login');
        } else {
            $this->index();
        }
    }
    
}

?>