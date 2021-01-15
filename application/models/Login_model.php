<?php
class Login_model extends CI_Model
{

    /*function isEmailVerified($email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('user');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                return $row->isEmailVerified;
            }
        } else {
            return false;
        }
    }*/

    function getUserPassword($personalID)
    {
        $results = '';
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