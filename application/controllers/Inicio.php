<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        if (!$this->session->userdata('id')) {
            redirect('login');
        }
    }
    
    function index(){
        $data['title'] = 'Inicio';
        $this->load->view('global/header', $data);
        $this->load->view('inicio');
        $this->load->view('global/footer');
    }
    
}

?>