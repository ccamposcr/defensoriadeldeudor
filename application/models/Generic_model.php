<?php
class Generic_model extends CI_Model
{

    function getRoleList(){
        $query = $this->db->get('rolelist');
        $results = $query->result();
        return $results;
    }

    function getAccessList(){
        $query = $this->db->get('accesslist');
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

    function getAppointmentTypeList(){
        $query = $this->db->get('appointmenttypelist');
        $results = $query->result();
        return $results;
    }

    function setRolePrivilegeAccess($data){
        $this->db->empty_table('roleprivilegeaccess');
        $this->db->query('ALTER TABLE roleprivilegeaccess AUTO_INCREMENT 1');
        if( !empty($data) ){
            $results = $this->db->insert_batch('roleprivilegeaccess', $data);
            return $results;
        }
    }

    function getRolePrivilegeAccess(){
        $query = $this->db->get('roleprivilegeaccess');
        $results = $query->result();
        return $results;
    }

    function getPrivilegeAccessByRole($data){
        $this->db->where('roleprivilegeaccess.' . $data['searchBy'], $data['value']);
        $query = $this->db->get('roleprivilegeaccess');
        $results = $query->result();
        return $results;
    }

    function addAdministrativeStatus($data){       
        $results = $this->db->insert('administrativestatuslist', $data);
        return $results;
    }

    function addAppointmentType($data){       
        $results = $this->db->insert('appointmenttypelist', $data);
        return $results;
    }

    function addJudicialStatus($data){       
        $results = $this->db->insert('judicialstatuslist', $data);
        return $results;
    }

    function addSubject($data){       
        $results = $this->db->insert('subjectlist', $data);
        return $results;
    }
}

?>