<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generic extends CI_Controller
{
    
    public function __construct(){
        parent::__construct();
        if (!$this->session->userdata('id')) {
            redirect('login');
        }
        $this->load->library('form_validation');
        $this->load->model('generic_model');
    }

    function getRoleList(){
        $response = array(
            'response' => $this->generic_model->getRoleList()
        );

        echo json_encode($response);
    }

    function getAccessList(){
        $response = array(
            'response' => $this->generic_model->getAccessList()
        );

        echo json_encode($response);
    }

    function getJudicialStatusList(){
        $response = array(
            'response' => $this->generic_model->getJudicialStatusList()
        );

        echo json_encode($response);
    }

    function getAdministrativeStatusList(){
        $response = array(
            'response' => $this->generic_model->getAdministrativeStatusList()
        );

        echo json_encode($response);
    }

    function getSubjectList(){
        $response = array(
            'response' => $this->generic_model->getSubjectList()
        );

        echo json_encode($response);
    }

    function setRolePrivilegeAccess(){
        $data = $this->input->post('data');

        $this->generic_model->setRolePrivilegeAccess(json_decode($data, true));

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response);
    }
    
}

?>