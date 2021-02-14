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

    function getAllClients(){
        $this->db->select('id, personalID, name, lastName1, lastName2, status, phone, email, address, role');
        $query = $this->db->get('user');
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

    function getLegalCasesByID($userID){
        $this->db->where('userID', $userID);
        $query = $this->db->get('legalcase');
        $results = $query->result();
        return $results;
    }
}

?>