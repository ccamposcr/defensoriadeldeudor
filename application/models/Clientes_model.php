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
        $this->db->select('id, personalID, name, lastName1, lastName2, status, phone, email, address, role');
        $this->db->where('role', '99');
        $query = $this->db->get('user');
        $results = $query->result();
        return $results;
    }

    function getRoleList(){
        $query = $this->db->get('rolelist');
        $results = $query->result();
        return $results;
    }

    function getStatusList(){
        $query = $this->db->get('statuslist');
        $results = $query->result();
        return $results;
    }

    function getSubjectList(){
        $query = $this->db->get('subjectlist');
        $results = $query->result();
        return $results;
    }

    function getClientByPersonalID($personalID){
        $this->db->select('id, personalID, name, lastName1, lastName2, status, phone, email, address, role');
        $this->db->where('personalID', $personalID);
        $query = $this->db->get('user');
        $results = $query->result();
        return $results;
    }

    function getClientByID($id){
        $this->db->select('id, personalID, name, lastName1, lastName2, status, phone, email, address, role');
        $this->db->where('id', $id);
        $query = $this->db->get('user');
        $results = $query->result();
        return $results;
    }

    function getLegalCaseByID($id){
        $this->db->where('id', $id);
        $query = $this->db->get('legalcase');
        $results = $query->result();
        return $results;
    }

    function getLegalCasesByUserID($userID){
        $this->db->where('userID', $userID);
        $query = $this->db->get('legalcase');
        $results = $query->result();
        return $results;
    }
}

?>