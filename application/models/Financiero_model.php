<?php
class Financiero_model extends CI_Model
{
    function addPaymentDates($data){
        $results = $this->db->insert('paymentdates', $data);
        return $results;
    }
/*
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
    }*/

    function addFinancialContract($data){       
        $this->db->insert('financialcontract', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
}

?>