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

    function getJudicialStatusList(){
        $query = $this->db->get('judicialstatuslist');
        $results = $query->result();
        return $results;
    }

    function getSubjectList(){
        $query = $this->db->get('subjectlist');
        $results = $query->result();
        return $results;
    }

    function getClientBy($data){
        $this->db->select('id, personalID, name, lastName1, lastName2, status, phone, email, address, role');
        $this->db->where($data['searchBy'], $data['value']);
        $query = $this->db->get('user');
        $results = $query->result();
        return $results;
    }

    function getLegalCasesBy($data){
        $this->db->where($data['searchBy'], $data['value']);
        $query = $this->db->get('legalcase');
        $results = $query->result();
        return $results;
    }
}

?>