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
            'status' => $this->input->post('status') != null ? $this->input->post('status') : '1' , 
            'phone' => $this->input->post('phone'), 
            'email' => $this->input->post('email'), 
            'address' => $this->input->post('address'),
            'role' => $this->input->post('role') != null ? $this->input->post('role') : '99'
        );

        $this->clientes_model->addClient($data);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response);
    }

    function addLegalCase(){
        $data = array(
            'subject' => $this->input->post('subject'), 
            'userID' => $this->input->post('userID'), 
            'judicialStatus' => $this->input->post('judicialStatus'), 
            'detail' => $this->input->post('detail'),
            'nextNotification' => $this->input->post('nextNotification')
        );

        $this->clientes_model->addLegalCase($data);

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
            'role' => $this->input->post('role')
        );
        
        $id = $this->input->post('id');
        $this->clientes_model->editClient($id, $data);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response);
    }

    function editLegalCase(){
        $data = array(
            'subject' => $this->input->post('subject'), 
            'userID' => $this->input->post('userID'), 
            'judicialStatus' => $this->input->post('judicialStatus'), 
            'detail' => $this->input->post('detail'),
            'nextNotification' => $this->input->post('nextNotification')
        );
        
        $id = $this->input->post('id');
        $this->clientes_model->editLegalCase($id, $data);

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

    function getJudicialStatusList(){
        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->clientes_model->getJudicialStatusList()
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

    function getLegalCasesBy(){
        $data = array(
            'searchBy' => $this->input->post('searchBy'), 
            'value' => $this->input->post('value')
        );

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->clientes_model->getLegalCasesBy($data)
        );

        echo json_encode($response);
    }
    
}

?>