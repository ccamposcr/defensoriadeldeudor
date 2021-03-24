<?php
class Generic_model extends CI_Model
{

    function getRoleList(){
        $query = $this->db->get('rolelist');
        $results = $query->result();
        return $results;
    }

    function getAccessList(){
        $query = $this->db->get('accessList');
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
}

?>