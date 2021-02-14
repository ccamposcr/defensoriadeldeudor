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
        $this->load->view('global/body');
        //$this->load->view('clientes');
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

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response);
    }

    function editClient(){
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
        
        $id = $this->input->post('id');
        $this->clientes_model->editClient($id, $data);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response);
    }

    function getAllClients(){
        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->clientes_model->getAllClients()
        );

        echo json_encode($response);
    }

    function getRoleList(){
        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->clientes_model->getRoleList()
        );

        echo json_encode($response);
    }

    function getStatusList(){
        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->clientes_model->getStatusList()
        );

        echo json_encode($response);
    }

    function getSubjectList(){
        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->clientes_model->getSubjectList()
        );

        echo json_encode($response);
    }

    function getClientByPersonalID(){
        $data = $this->input->post('personalID');

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->clientes_model->getClientByPersonalID($data)
        );

        echo json_encode($response);
    }

    function getClientByID(){
        $data = $this->input->post('id');

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->clientes_model->getClientByID($data)
        );

        echo json_encode($response);
    }

    function getLegalCasesByUserID(){
        $data = $this->input->post('userID');

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->clientes_model->getLegalCasesByUserID($data)
        );

        echo json_encode($response);
    }
    
}

?>