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

    function hash_password($password){
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        return $hashed_password;
    }
    
    function index(){
        $data['title'] = 'Clientes';
        $this->load->view('global/header', $data);
        $this->load->view('global/welcome');
        $this->load->view('global/navigation');
        $this->load->view('global/body');
        $this->load->view('global/footer');
    }

    function addClient(){
        $data = array(
            'personalID' => $this->input->post('personalID'), 
            'name' => $this->input->post('name'), 
            'lastName1' => $this->input->post('lastName1'), 
            'lastName2' => $this->input->post('lastName2'), 
            'status' => $this->input->post('status') != null ? $this->input->post('status') : '1' , 
            'phone' => $this->input->post('phone'), 
            'email' => $this->input->post('email'), 
            'address' => $this->input->post('address'),
            'roleID' => $this->input->post('roleID') != null ? $this->input->post('roleID') : '0',
            'phone2' => $this->input->post('phone2'),
            'phone3' => $this->input->post('phone3'),
            'email2' => $this->input->post('email2'),
            'email3' => $this->input->post('email3'),
            'job' => $this->input->post('job')
        );

        $clientID = $this->clientes_model->addClient($data);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'clientID' => $clientID
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
            'roleID' => $this->input->post('roleID'),
            'phone2' => $this->input->post('phone2'),
            'phone3' => $this->input->post('phone3'),
            'email2' => $this->input->post('email2'),
            'email3' => $this->input->post('email3'),
            'job' => $this->input->post('job')
        );
        
        $id = $this->input->post('id');
        $this->clientes_model->editClient($id, $data);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response);
    }

    function isInUse(){
        $id = $this->input->post('id');
        $isInUse = $this->clientes_model->isInUse($id);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $isInUse
        );

        echo json_encode($response);
    }

    function updateIsInUse(){
        $id = $this->input->post('id');
        $state = $this->input->post('inUse');
        $isInUse = $this->clientes_model->updateIsInUse($id, $state);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response);
    }

    function getAllClients(){
        $response = array(
            'response' => $this->clientes_model->getAllClients()
        );

        echo json_encode($response);
    }

    function getAllUsers(){
        $response = array(
            'response' => $this->clientes_model->getAllUsers()
        );

        echo json_encode($response);
    }

    function getClientBy(){
        $data = array(
            'searchBy' => $this->input->post('searchBy'), 
            'value' => $this->input->post('value')
        );

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->clientes_model->getClientBy($data)
        );

        echo json_encode($response);
    }

    function getClientByLegalCase(){
        $data = array(
            'searchBy' => $this->input->post('searchBy'), 
            'value' => $this->input->post('value')
        );

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->clientes_model->getClientByLegalCase($data)
        );

        echo json_encode($response);
    }

    function deleteUser(){
        $data = array(
            'status' => '0'
        );
        $id = $this->input->post('id');
        
        $this->clientes_model->updateUser($id, $data);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response);
    }

    function updatePassword(){
        $id = $this->input->post('id');
        $encrypted_password = $this->hash_password($this->input->post('password'));

        $data = array(
            'password' => $encrypted_password
        );

        $this->clientes_model->updateUser($id, $data);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response);
    }
    
}

?>