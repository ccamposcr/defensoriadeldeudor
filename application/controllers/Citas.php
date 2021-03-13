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
    
    function index(){
        $data['title'] = 'Citas';
        $this->load->view('global/header', $data);
        $this->load->view('global/navigation');
        $this->load->view('global/body');
        //$this->load->view('clientes');
        $this->load->view('global/footer');
    }
    
}

?>