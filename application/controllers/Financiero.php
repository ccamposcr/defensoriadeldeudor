<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Financiero extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        if (!$this->session->userdata('id')) {
            redirect('login');
        }
        $this->load->model('financiero_model');
    }
    
    function index(){
        $data['title'] = 'Financiero';
        $this->load->view('global/header', $data);
        $this->load->view('global/welcome');
        $this->load->view('global/navigation');
        $this->load->view('global/body');
        $this->load->view('financiero');
        $this->load->view('global/footer');
    }

    function addPaymentDates(){
        $legalCaseID = $this->input->post('legalCaseID');
        $dates = json_decode($this->input->post('dates'));

        foreach ($dates as $item) {
            $data = array(
                'legalCaseID' => $legalCaseID,
                'date' => $item->date,
                'status' => '1'
            );
            $this->casosLegales_model->addPaymentDates($data);
        }

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response); 
    }

    function getLegalPaymentDatesBy(){
        $data = array(
            'searchBy' => $this->input->post('searchBy'), 
            'value' => $this->input->post('value')
        );

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->casosLegales_model->getLegalPaymentDatesBy($data)
        );

        echo json_encode($response);
    }

    function getPaymentDatesByDateRange(){
        $data = array( 
            'start' => $this->input->post('start'),
            'end' => $this->input->post('end')
        );

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->casosLegales_model->getPaymentDatesByDateRange($data)
        );

        echo json_encode($response);
    }

    function deletePaymentDate(){
        $data = array(
            'status' => '0'
        );
        $id = $this->input->post('id');
        
        $this->casosLegales_model->updatePaymentDate($id, $data);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response);
    }
}

?>