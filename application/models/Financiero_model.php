<?php
class Financiero_model extends CI_Model
{
    function addPaymentDates($data){
        $results = $this->db->insert('paymentdates', $data);
        return $results;
    }

    function getPaymentDatesBy($data){
        $this->db->select('id, financialContractID, paymentDateAlert, paymentDateMade, referenceNumber, amountPaid');
        $this->db->from('paymentdates');
        $this->db->where($data['searchBy'], $data['value']);
        $this->db->where('status', '1');
        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }

    function getCOUNTPaymentDatesBy($data){
        $this->db->where($data['searchBy'], $data['value']);
        $this->db->where('status', '1');
        $num_rows = $this->db->count_all_results('paymentdates');
        return $num_rows;
    }

    function getSUMPaymentDatesBy($data){
        $this->db->select_sum('amountPaid');
        $this->db->where($data['searchBy'], $data['value']);
        $this->db->where('status', '1');
        $query = $this->db->get('paymentdates');
        $results = $query->result();
        return $results;
    }

    function getPaymentDatesByDateRange($data){
        $this->db->select('paymentdates.financialContractID financialContractID, financialcontract.totalAmount, financialcontract.userID userID, financialcontract.administrativeStatusID, paymentdates.paymentDateAlert start, paymentdates.paymentDateMade, paymentdates.referenceNumber, paymentdates.amountPaid, paymentdates.id, user.name userName, user.lastName1, user.lastName2');
        $this->db->from('paymentdates');
        $this->db->where('paymentdates.paymentDateAlert >=', $data['start']);
        $this->db->where('paymentdates.paymentDateAlert <=', $data['end']);
        $this->db->where('paymentdates.status', '1');
        $this->db->join('financialcontract', 'paymentdates.financialContractID = financialcontract.id', 'left');
        $this->db->join('user', 'user.id = financialcontract.userID', 'left');
        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }

    function updatePaymentDate($id, $data){
        $this->db->where('id', $id);
        $results = $this->db->update('paymentdates', $data);
        return $results;
    }

    function addFinancialContract($data){       
        $this->db->insert('financialcontract', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function getFinancialInfoBy($data){
        $this->db->select('financialcontract.id financialContractID, financialcontract.totalAmount, financialcontract.administrativeStatusID, financialcontract.userID, financialcontract.dateCreated, financialcontract.propertyNumber, financialcontract.inUse, administrativestatuslist.administrativeStatus');
        $this->db->from('financialcontract');
        $this->db->where('financialcontract.' . $data['searchBy'], $data['value']);
        $this->db->join('administrativestatuslist', 'administrativestatuslist.id = financialcontract.administrativeStatusID', 'left');
        $this->db->order_by('financialcontract.dateCreated DESC');
        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }

    function isInUse($id){
        $this->db->select('inUse');
        $this->db->where('id', $id);
        $query = $this->db->get('financialcontract');
        $results = $query->result();
        return $results;
    }

    function updateIsInUse($id, $state){    
        $this->db->set('inUse', $state);    
        $this->db->where('id', $id);
        $results = $this->db->update('financialcontract');
        return $results;
    }

    function editFinancialContract($id, $data){    
        $this->db->where('id', $id);
        $results = $this->db->update('financialcontract', $data);
        return $results;
    }
}

?>