<?php
class Clientes_model extends CI_Model
{
    function addClient($data){       
        $this->db->insert('user', $data);
    }

    function editClient($id, $data){    
        $this->db->where('id', $id);
        $this->db->update('user', $data);
    }

    function editLegalCase($id, $data){    
        $this->db->where('id', $id);
        $this->db->update('legalcase', $data);
    }
    
    function addLegalCase($data){       
        $this->db->insert('legalcase', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function addLegalCaseNote($data){       
        $this->db->insert('legalcasenoteshistory', $data);
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
        $this->db->select('legalcase.id legalCaseID, legalCase.internalCode, legalcase.subjectID, legalcase.userID, legalcase.judicialStatusID, legalcase.administrativeStatusID, legalcase.nextNotification, subjectlist.subject, judicialstatuslist.judicialStatus, administrativestatuslist.administrativeStatus');
        $this->db->from('legalcase');
        $this->db->where('legalcase.' . $data['searchBy'], $data['value']);
        $this->db->join('judicialstatuslist', 'judicialstatuslist.id = legalcase.judicialStatusID', 'left');
        $this->db->join('subjectlist', 'subjectlist.id = legalcase.subjectID', 'left');
        $this->db->join('administrativestatuslist', 'administrativestatuslist.id = legalcase.administrativeStatusID', 'left');
        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }

    function getLegalCaseNotesBy($data){
        $this->db->select('legalcasenoteshistory.id legalcasenoteID, legalcasenoteshistory.note, legalcasenoteshistory.date, legalcasenoteshistory.legalCaseID, legalcasenoteshistory.userID, user.name, user.lastName1, user.lastName2');
        $this->db->from('legalcasenoteshistory');
        $this->db->where($data['searchBy'], $data['value']);
        $this->db->join('user', 'user.id = legalcasenoteshistory.userID', 'left');
        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }
}

?>