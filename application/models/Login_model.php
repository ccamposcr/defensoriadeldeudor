<?php
class Login_model extends CI_Model
{

    function getUserPassword($personalID){
        $this->db->where('personalID', $personalID);
        $query = $this->db->get('user');
        
        foreach ($query->result() as $row) {
            $results =  $row->password;
            break;
        }
       
        return $results;
    }
}

?>