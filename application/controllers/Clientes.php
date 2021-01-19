<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends CI_Controller
{
    
    public function __construct(){
        parent::__construct();
        if (!$this->session->userdata('id')) {
            redirect('login');
        }
        $this->load->library('form_validation');
        $this->load->model('clientes_model');
    }
    
    function index(){
        $data['title'] = 'Clientes';
        $this->load->view('global/header', $data);
        $this->load->view('global/navigation');
        $this->load->view('clientes');
        $this->load->view('global/footer');
    }

    function addClient(){
        $personalID = '12123123123';
        $name = 'PacCerda';
        $lastName1 = 'Leg';
        $lastName2 = 'Leg2';
        $status = 'active';
        $phone = '434234234';
        $email = 'lapaccerda@paclover.com';
        $address = 'lachancha 50 al oeste';
        
        $data = array(
        'personalID' => $personalID, 
        'name' => $name, 
        'lastName1' => $lastName1, 
        'lastName2' => $lastName2, 
        'status' => $status, 
        'phone' => $phone, 
        'email' => $email, 
        'address' => $address);
        $this->clientes_model->addClient($data);
    }
    
}

?>