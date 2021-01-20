<?php
class Clientes_model extends CI_Model
{
    function addClient($data){       
        $str = $this->db->insert('user', $data);
        return $str;
    }

    function getAllClients(){
        $query = $this->db->get('user');
        $results = $query->result();
       
        return $results;
    }
}

?>