<?php
class Clientes_model extends CI_Model
{
    function addClient($data){       
        $results = $this->db->insert('user', $data);
        return $results;
    }

    function getAllClients(){
        $query = $this->db->get('user');
        $results = $query->result();
       
        return $results;
    }

    function getClientByPersonalID($personalID){
        $this->db->where('personalID', $personalID);
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