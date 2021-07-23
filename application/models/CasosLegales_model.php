<?php
class CasosLegales_model extends CI_Model
{

    function addLegalCase($data){       
        $this->db->insert('legalcase', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function editLegalCase($id, $data){    
        $this->db->where('id', $id);
        $results = $this->db->update('legalcase', $data);
        return $results;
    }

    function isInUse($id){
        $this->db->select('inUse');
        $this->db->where('id', $id);
        $query = $this->db->get('legalcase');
        $results = $query->result();
        return $results;
    }

    function updateIsInUse($id, $state){    
        $this->db->set('inUse', $state);    
        $this->db->where('id', $id);
        $results = $this->db->update('legalcase');
        return $results;
    }

    function addLegalCaseNote($data){       
        $results = $this->db->insert('legalcasenoteshistory', $data);
        return $results;
    }

    function getLegalCasesBy($data){
        $this->db->select('legalcase.id legalCaseID, legalcase.internalCode, legalcase.subjectID, legalcase.userID, legalcase.judicialStatusID, legalcase.administrativeStatusID, legalcase.locationID, legalcase.code, legalcase.inUse, subjectlist.subject, judicialstatuslist.judicialStatus, administrativestatuslist.administrativeStatus, user.name, user.lastName1, user.lastName2');
        $this->db->from('legalcase');
        $this->db->where('legalcase.' . $data['searchBy'], $data['value']);
        $this->db->join('judicialstatuslist', 'judicialstatuslist.id = legalcase.judicialStatusID', 'left');
        $this->db->join('subjectlist', 'subjectlist.id = legalcase.subjectID', 'left');
        $this->db->join('administrativestatuslist', 'administrativestatuslist.id = legalcase.administrativeStatusID', 'left');
        $this->db->join('user', 'user.id = legalcase.locationID', 'left');
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