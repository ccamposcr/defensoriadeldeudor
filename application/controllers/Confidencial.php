<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Confidencial extends CI_Controller
{
    
    public function __construct(){
        parent::__construct();
        if (!$this->session->userdata('id')) {
            redirect('login');
        }
    }

    function index(){
        $data['title'] = 'Privado';
        $this->load->view('global/header', $data);
        $this->load->view('global/navigation');
        $this->load->view('global/welcome');
        $this->load->view('global/confidencial');
        $this->load->view('global/body');
        $this->load->view('global/footer');
    }
    
}

?>