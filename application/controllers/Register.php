<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('id')) {
            redirect('private_area');
        }
        $this->load->library('form_validation');
        $this->load->model('register_model');
    }
    
    function index()
    {
        $this->load->view('register');
    }

    function hash_password($password){
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        return $hashed_password;
    }
    
    function validation()
    {
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
            $id                 = $this->register_model->createUser($data);
            /*if ($id > 0) {
                $subject = "Please verify email for login";
                $message = "
    <p>Hi " . $this->input->post('user_name') . "</p>
    <p>This is email verification mail from Codeigniter Login Register system. For complete registration process and login into system. First you want to verify you email by click this <a href='" . base_url() . "register/verify_email/" . $verificationKey . "'>link</a>.</p>
    <p>Once you click this link your email will be verified and you can login into system.</p>
    <p>Thanks,</p>
    ";
                $config  = array(
                    'protocol' => 'smtp',
                    'smtp_host' => 'ssl://smtp.googlemail.com',
                    'smtp_port' => 465,
                    'smtp_user' => 'software.4.e.commerce.cr@gmail.com',
                    'smtp_pass' => 'nh-HMC09abril-nh',
                    'mailtype' => 'html',
                    'charset' => 'iso-8859-1',
                    'wordwrap' => TRUE
                );
                $this->load->library('email', $config);
                $this->email->set_newline("\r\n");
                $this->email->from('aquile@seguro.com');
                $this->email->to($this->input->post('user_email'));
                $this->email->subject($subject);
                $this->email->message($message);
                if ($this->email->send()) {
                    $this->session->set_flashdata('message', 'Check in your email for email verification mail');
                    redirect('register');
                }
            }*/
        } else {
            $this->index();
        }
    }
    
    /*function verify_email()
    {
        if ($this->uri->segment(3)) {
            $verificationKey = $this->uri->segment(3);
            if ($this->register_model->verify_email($verificationKey)) {
                $data['message'] = '<h1 align="center">Your Email has been successfully verified, now you can login from <a href="' . base_url() . 'login">here</a></h1>';
            } else {
                $data['message'] = '<h1 align="center">Invalid Link</h1>';
            }
            $this->load->view('email_verification', $data);
        }
    }*/
    
}

?>