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

    function getClientByID($data){
        $this->db->where('personalID', $data);
        $query = $this->db->get('user');
        $results = $query->result();
       
        return $results;
    }

    function getLegalCasesByID($data){
        $this->db->where('userID', $data);
        $query = $this->db->get('legalcase');
        $results = $query->result();
       
        return $results;
    }
}

?>