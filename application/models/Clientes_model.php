<?php
class Clientes_model extends CI_Model
{
    function addClient($data){       
        $results = $this->db->insert('user', $data);
        return $results;
    }

    function editClient($id, $data){    
        $this->db->where('id', $id);
        $results = $this->db->update('user', $data);
        return $results;
    }

    function editLegalCase($id, $data){    
        $this->db->where('id', $id);
        $results = $this->db->update('legalcase', $data);
        return $results;
    }
    
    function addLegalCase($data){       
        $results = $this->db->insert('legalcase', $data);
        return $results;
    }

    function getAllClients(){
        $this->db->select('id, personalID, name, lastName1, lastName2, status, phone, email, address, roleID');
        $this->db->where('roleID', '99');
        $query = $this->db->get('user');
        $results = $query->result();
        return $results;
    }

    function getRoleList(){
        $query = $this->db->get('rolelist');
        $results = $query->result();
        return $results;
    }

    function getJudicialStatusList(){
        $query = $this->db->get('judicialstatuslist');
        $results = $query->result();
        return $results;
    }

    function getAdministrativeStatusList(){
        $query = $this->db->get('administrativestatuslist');
        $results = $query->result();
        return $results;
    }

    function getSubjectList(){
        $query = $this->db->get('subjectlist');
        $results = $query->result();
        return $results;
    }

    function getClientBy($data){
        $this->db->select('id, personalID, name, lastName1, lastName2, status, phone, email, address, roleID');
        $this->db->where($data['searchBy'], $data['value']);
        $query = $this->db->get('user');
        $results = $query->result();
        return $results;
    }

    function getLegalCasesBy($data){
        $this->db->select('legalcase.id legalCaseID, legalCase.internalCode ,legalcase.subjectID, legalcase.userID, legalcase.judicialStatusID, legalcase.administrativeStatusID, legalcase.nextNotification, subjectlist.subject, judicialstatuslist.judicialStatus, administrativestatuslist.administrativeStatus');
        $this->db->from('legalcase');
        $this->db->where('legalcase.' . $data['searchBy'], $data['value']);
        $this->db->join('judicialstatuslist', 'judicialstatuslist.id = legalcase.judicialStatusID', 'left');
        $this->db->join('subjectlist', 'subjectlist.id = legalcase.subjectID', 'left');
        $this->db->join('administrativestatuslist', 'administrativestatuslist.id = legalcase.administrativeStatusID', 'left');
        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }
}

?>