<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('id')) {
            redirect('private_area');
        }
        $this->load->library('form_validation');
        $this->load->model('login_model');
    }
    
    function index()
    {
        $this->load->view('login');
    }

    function verify_hash($plain_password, $hashed_password){
        $result = password_verify($plain_password, $hashed_password);
        return $result;
    }
    
    function validation()
    {
        //$this->form_validation->set_rules('user_email', 'Email Address', 'required|trim|valid_email');
        $this->form_validation->set_rules('user_personalID', 'ID', 'required|trim');
        $this->form_validation->set_rules('user_password', 'Password', 'required');
        if ($this->form_validation->run()) {
            /*$isEmailVerified = $this->login_model->isEmailVerified($this->input->post('user_email'));
            var_dump( $isEmailVerified);*/
            
           // if($isEmailVerified){
                $passwordhashFromBD = $this->login_model->getUserPassword($this->input->post('user_personalID'));
                if($passwordhashFromBD){
                    $passwordVerification = $this->verify_hash($this->input->post('user_password'), $passwordhashFromBD);
                
                   
                    if($passwordVerification){
                        redirect('private_area');
                        $message = 'Logged IN';
                    }else{
                        $message = 'Cédula o Contraseña incorrecta';
                    }
                }else{
                    $message = 'Cédula o Contraseña incorrecta';
                }
            /*}else{
                $message = 'First please verify your email address';
            }*/
            $this->session->set_flashdata('message', $message);
            redirect('login');
        } else {
            $this->index();
        }
    }
    
}

?>