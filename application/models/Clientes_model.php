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
        $this->db->select('id, personalID, name, lastName1, lastName2, status, phone, email, address, roleID');
        $this->db->order_by('name ASC');
        $this->db->where('roleID', '99');
        $query = $this->db->get('user');
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
}

?>