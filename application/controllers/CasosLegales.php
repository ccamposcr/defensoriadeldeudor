<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CasosLegales extends CI_Controller
{
    
    public function __construct(){
        parent::__construct();
        if (!$this->session->userdata('id')) {
            redirect('login');
        }
        $this->load->library('form_validation');
        $this->load->model('casosLegales_model');
    }
    
    function addLegalCase(){
        $data = array(
            'internalCode' => $this->input->post('internalCode'),
            'subjectID' => $this->input->post('subjectID'), 
            'userID' => $this->input->post('userID'), 
            'judicialStatusID' => $this->input->post('judicialStatusID'),
            'administrativeStatusID' => $this->input->post('administrativeStatusID'), 
            'totalAmount' => $this->input->post('totalAmount'),
            'locationID' => $this->input->post('locationID')
        );

        $legalCaseID = $this->casosLegales_model->addLegalCase($data);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'legalCaseID' => $legalCaseID
        );

        echo json_encode($response);
    }

    function addLegalCaseNote(){
        $data = array(
            'note' => $this->input->post('note'),
            'legalCaseID' => $this->input->post('legalCaseID'),
            'userID' => $this->input->post('userID')
        );

        $this->casosLegales_model->addLegalCaseNote($data);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response);
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

    function editLegalCase(){
        $data = array(
            'internalCode' => $this->input->post('internalCode'),
            'subjectID' => $this->input->post('subjectID'), 
            'userID' => $this->input->post('userID'), 
            'judicialStatusID' => $this->input->post('judicialStatusID'),
            'administrativeStatusID' => $this->input->post('administrativeStatusID'),
            'totalAmount' => $this->input->post('totalAmount'),
            'locationID' => $this->input->post('locationID')
        );
        
        $id = $this->input->post('id');
        $this->casosLegales_model->editLegalCase($id, $data);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        );

        echo json_encode($response);
    }

    function isInUse(){
        $id = $this->input->post('id');
        $isInUse = $this->casosLegales_model->isInUse($id);

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
        $isInUse = $this->casosLegales_model->updateIsInUse($id, $state);

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
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
            'response' => $this->casosLegales_model->getLegalCasesBy($data)
        );

        echo json_encode($response);
    }

    function getLegalCaseNotesBy(){
        $data = array(
            'searchBy' => $this->input->post('searchBy'), 
            'value' => $this->input->post('value')
        );

        $response = array(
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash(),
            'response' => $this->casosLegales_model->getLegalCaseNotesBy($data)
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