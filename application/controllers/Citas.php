<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Citas extends CI_Controller
{
    
    public function __construct(){
        parent::__construct();
        if (!$this->session->userdata('id')) {
            redirect('login');
        }
        $this->load->library('form_validation');
        $this->load->model('citas_model');
    }

    function addAppointment(){
        $data = array(
            'userID' => $this->input->post('userID'), 
            'date' => $this->input->post('date')
        );

        $this->citas_model->addAppointment($data);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response);
    }
    
}

?>