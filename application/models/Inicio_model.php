<?php
class Inicio_model extends CI_Model
{

    function getLegalCasesByDateRange($data){
        $this->db->select('legalcase.id legalCaseID, legalCase.internalCode, legalcase.userID, legalcase.nextNotification start, user.name userName, user.lastName1, user.lastName2');
        $this->db->from('legalcase');
        $this->db->where('legalcase.' . $data['searchBy'] . '>=', $data['start']);
        $this->db->where('legalcase.' . $data['searchBy'] . '<=', $data['end']);
        $this->db->join('user', 'user.id = legalcase.userID', 'left');
        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }

}

?>