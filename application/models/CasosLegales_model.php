<?php
class CasosLegales_model extends CI_Model
{

    function addLegalCase($data){       
        $this->db->insert('legalcase', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function editLegalCase($id, $data){    
        $this->db->where('id', $id);
        $results = $this->db->update('legalcase', $data);
        return $results;
    }

    function isInUse($id){
        $this->db->select('inUse');
        $this->db->where('id', $id);
        $query = $this->db->get('legalcase');
        $results = $query->result();
        return $results;
    }

    function updateIsInUse($id, $state){    
        $this->db->set('inUse', $state);    
        $this->db->where('id', $id);
        $results = $this->db->update('legalcase');
        return $results;
    }

    function addLegalCaseNote($data){       
        $results = $this->db->insert('legalcasenoteshistory', $data);
        return $results;
    }

    function addPaymentDates($data){
        $results = $this->db->insert('paymentdates', $data);
        return $results;
    }

    function getLegalCasesBy($data){
        $this->db->select('legalcase.id legalCaseID, legalcase.internalCode, legalcase.subjectID, legalcase.userID, legalcase.judicialStatusID, legalcase.administrativeStatusID, legalcase.locationID, legalcase.totalAmount, legalcase.code, legalcase.inUse, subjectlist.subject, judicialstatuslist.judicialStatus, administrativestatuslist.administrativeStatus, user.name, user.lastName1, user.lastName2');
        $this->db->from('legalcase');
        $this->db->where('legalcase.' . $data['searchBy'], $data['value']);
        $this->db->join('judicialstatuslist', 'judicialstatuslist.id = legalcase.judicialStatusID', 'left');
        $this->db->join('subjectlist', 'subjectlist.id = legalcase.subjectID', 'left');
        $this->db->join('administrativestatuslist', 'administrativestatuslist.id = legalcase.administrativeStatusID', 'left');
        $this->db->join('user', 'user.id = legalcase.locationID', 'left');
        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }

    function getLegalCaseNotesBy($data){
        $this->db->select('legalcasenoteshistory.id legalcasenoteID, legalcasenoteshistory.note, legalcasenoteshistory.date, legalcasenoteshistory.legalCaseID, legalcasenoteshistory.userID, user.name, user.lastName1, user.lastName2');
        $this->db->from('legalcasenoteshistory');
        $this->db->where($data['searchBy'], $data['value']);
        $this->db->join('user', 'user.id = legalcasenoteshistory.userID', 'left');
        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }

    function getLegalPaymentDatesBy($data){
        $this->db->select('id, date');
        $this->db->from('paymentdates');
        $this->db->where($data['searchBy'], $data['value']);
        $this->db->where('status', '1');
        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }

    function getPaymentDatesByDateRange($data){
        $this->db->select('paymentdates.legalCaseID legalCaseID, legalcase.internalCode, legalcase.userID, legalcase.totalAmount, paymentdates.date start, user.name userName, user.lastName1, user.lastName2');
        $this->db->from('paymentdates');
        $this->db->where('paymentdates.date >=', $data['start']);
        $this->db->where('paymentdates.date <=', $data['end']);
        $this->db->where('paymentdates.status', '1');
        $this->db->join('legalcase', 'paymentdates.legalCaseID = legalcase.id', 'left');
        $this->db->join('user', 'user.id = legalcase.userID', 'left');
        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }

    function updatePaymentDate($id, $data){
        $this->db->where('id', $id);
        $results = $this->db->update('paymentdates', $data);
        return $results;
    }
}

?>