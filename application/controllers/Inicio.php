<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        if (!$this->session->userdata('id')) {
            redirect('login');
        }
        $this->load->model('inicio_model');
    }
    
    function index(){
        $data['title'] = 'Inicio';
        $this->load->view('global/header', $data);
        $this->load->view('global/navigation');
        $this->load->view('global/body');
        $this->load->view('inicio');
        $this->load->view('global/footer');
    }
    
    function getLegalCasesByDateRange(){
        $data = array(
            'searchBy' => $this->input->post('searchBy'), 
            'start' => $this->input->post('start'),
            'end' => $this->input->post('end')
        );

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->inicio_model->getLegalCasesByDateRange($data)
        );

        echo json_encode($response);
    }
}

?>