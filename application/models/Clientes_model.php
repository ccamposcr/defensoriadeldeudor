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
        $this->db->where('roleID', '0');
        $query = $this->db->get('user');
        $results = $query->result();
        return $results;
    }

    function getAllUsers(){
        $this->db->select('id, personalID, name, lastName1, lastName2, status, roleID');
        $this->db->order_by('name ASC');
        $this->db->where('roleID !=', '0');
        $this->db->where('status', '1');
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

    function deleteUser($id, $data){
        $this->db->where('id', $id);
        $results = $this->db->update('user', $data);
        return $results;
    }
}

?>