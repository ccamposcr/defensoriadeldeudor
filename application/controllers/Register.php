<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller
{
    
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('id')) {
            redirect('inicio');
        }
        $this->load->library('form_validation');
        $this->load->model('register_model');
    }
    
    function index(){
        $data['title'] = 'Register';
        $this->load->view('global/header', $data);
        $this->load->view('register');
        $this->load->view('global/footer');
    }

    function hash_password($password){
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        return $hashed_password;
    }
    
    function validation(){
        $this->form_validation->set_rules('user_name', 'Name', 'required|trim');
        $this->form_validation->set_rules('user_email', 'Email Address', 'required|trim|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('user_personalID', 'ID', 'required|trim|is_unique[user.personalID]');
        $this->form_validation->set_rules('user_password', 'Password', 'required');
        if ($this->form_validation->run()) {
            $verificationKey    = md5(rand());
            $encrypted_password = $this->hash_password($this->input->post('user_password'));
            $data               = array(
                'name' => $this->input->post('user_name'),
                'email' => $this->input->post('user_email'),
                'personalID' => $this->input->post('user_personalID'),
                'password' => $encrypted_password,
                'verificationKey' => $verificationKey
            );
            $id = $this->register_model->createUser($data);
            redirect('register');
        } else {
            $this->index();
        }
    }    
}

?>