<?php
class Clientes_model extends CI_Model
{
    function addClient($data){       
        $this->db->insert('user', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function editClient($id, $data){    
        $this->db->where('id', $id);
        $results = $this->db->update('user', $data);
        return $results;
    }

    function isInUse($id){
        $this->db->select('inUse');
        $this->db->where('id', $id);
        $query = $this->db->get('user');
        $results = $query->result();
        return $results;
    }

    function updateIsInUse($id, $state){    
        $this->db->set('inUse', $state);    
        $this->db->where('id', $id);
        $results = $this->db->update('user');
        return $results;
    }

    function getAllClients(){
        $this->db->select('id, personalID, name, lastName1, lastName2, status, phone, email, address, roleID, phone2, phone3, email2, email3, job, inUse');
        $this->db->order_by('name ASC');
        $this->db->where('roleID', '0');
        $query = $this->db->get('user');
        $results = $query->result();
        return $results;
    }

    function getAllUsers(){
        $this->db->select('user.id id, user.personalID, user.name, user.lastName1, user.lastName2, user.status, user.roleID, rolelist.role');
        $this->db->order_by('user.name ASC');
        $this->db->where('user.roleID !=', '0');
        $this->db->where('user.status', '1');
        $this->db->join('rolelist', 'rolelist.id = user.roleID', 'left');
        $query = $this->db->get('user');
        $results = $query->result();
        return $results;
    }

    function getClientBy($data){
        $this->db->select('id, personalID, name, lastName1, lastName2, status, phone, email, address, roleID, phone2, phone3, email2, email3, job, inUse');
        $this->db->where($data['searchBy'], $data['value']);
        $query = $this->db->get('user');
        $results = $query->result();
        return $results;
    }

    function getClientByLegalCase($data){
        $this->db->select('user.id, user.personalID, user.name, user.lastName1, user.lastName2, user.status, user.phone, user.email, user.address, user.roleID, legalcase.id legalCaseID, , user.phone2, user.phone3, user.email2, user.email3, user.job, user.inUse');
        $this->db->where('legalcase.' . $data['searchBy'], $data['value']);
        $this->db->join('legalcase', 'user.id = legalcase.userID', 'left');
        $query = $this->db->get('user');
        $results = $query->result();
        return $results;
    }

    function updateUser($id, $data){
        $this->db->where('id', $id);
        $results = $this->db->update('user', $data);
        return $results;
    }
}

?>