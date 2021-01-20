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
        $data = array(
        'personalID' => $this->input->post('personalID'), 
        'name' => $this->input->post('name'), 
        'lastName1' => $this->input->post('lastName1'), 
        'lastName2' => $this->input->post('lastName2'), 
        'status' => $this->input->post('status'), 
        'phone' => $this->input->post('phone'), 
        'email' => $this->input->post('email'), 
        'address' => $this->input->post('address'),
        'role' => $this->input->post('role'));

        $this->clientes_model->addClient($data);
    }

    function getAllClients(){
        echo json_encode($this->clientes_model->getAllClients());
    }
    
}

?>