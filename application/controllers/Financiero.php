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
        $financialContractID = $this->input->post('financialContractID');
        $dates = json_decode($this->input->post('dates'));

        foreach ($dates as $item) {
            $data = array(
                'financialContractID' => $financialContractID,
                'paymentDateAlert' => $item->paymentDateAlert,
                'status' => '1'
            );
            $this->financiero_model->addPaymentDates($data);
        }

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response); 
    }

    function getPaymentDatesBy(){
        $data = array(
            'searchBy' => $this->input->post('searchBy'), 
            'value' => $this->input->post('value')
        );

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->financiero_model->getPaymentDatesBy($data)
        );

        echo json_encode($response);
    }

    function addInvoice(){
        $id = $this->input->post('paymentDatesID');
        $data = array( 
            'paymentDateMade' => date("Y-m-d"),
            'referenceNumber' => $this->input->post('referenceNumber'),
            'amountPaid' => $this->input->post('amountPaid'),
            'paymentStatus' => '1'
        );

        $this->financiero_model->updatePaymentDate($id, $data);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response); 
    }

    function getCOUNTPaymentDatesBy(){
        $data = array(
            'searchBy' => $this->input->post('searchBy'), 
            'value' => $this->input->post('value')
        );

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->financiero_model->getCOUNTPaymentDatesBy($data)
        );

        echo json_encode($response);
    }

    function getSUMPaymentDatesBy(){
        $data = array(
            'searchBy' => $this->input->post('searchBy'), 
            'value' => $this->input->post('value')
        );

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->financiero_model->getSUMPaymentDatesBy($data)
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
            'response' => $this->financiero_model->getPaymentDatesByDateRange($data)
        );

        echo json_encode($response);
    }

    function getOverDuePaymentDatesByDateRange(){
        $data = array(
            'end' => $this->input->post('end')
        );

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->financiero_model->getOverDuePaymentDatesByDateRange($data)
        );

        echo json_encode($response);
    }

    function deletePaymentDate(){
        $data = array(
            'status' => '0'
        );
        $id = $this->input->post('id');
        
        $this->financiero_model->updatePaymentDate($id, $data);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response);
    }

    function addFinancialContract(){
        $data = array(
            'totalAmount' => $this->input->post('totalAmount'),
            'administrativeStatusID' => $this->input->post('administrativeStatusID'), 
            'userID' => $this->input->post('userID'),
            'propertyNumber' => $this->input->post('propertyNumber')
        );

        $financialContractID = $this->financiero_model->addFinancialContract($data);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'financialContractID' => $financialContractID
        );

        echo json_encode($response);
    }

    function getFinancialInfoBy(){
        $data = array(
            'searchBy' => $this->input->post('searchBy'), 
            'value' => $this->input->post('value')
        );

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->financiero_model->getFinancialInfoBy($data)
        );

        echo json_encode($response);
    }

    function isInUse(){
        $id = $this->input->post('id');
        $isInUse = $this->financiero_model->isInUse($id);

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
        $isInUse = $this->financiero_model->updateIsInUse($id, $state);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response);
    }

    function editFinancialContract(){
        $data = array(
            'totalAmount' => $this->input->post('totalAmount'),
            'administrativeStatusID' => $this->input->post('administrativeStatusID'), 
            'userID' => $this->input->post('userID'),
            'propertyNumber' => $this->input->post('propertyNumber')
        );
        
        $id = $this->input->post('id');
        $this->financiero_model->editFinancialContract($id, $data);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response);
    }
}

?>