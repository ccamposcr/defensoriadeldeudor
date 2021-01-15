<?php
class Register_model extends CI_Model
{
    function createUser($data)
    {
        $this->db->insert('user', $data);
        return $this->db->insert_id();
    }
    
    /*function verify_email($key)
    {
        $this->db->where('verificationKey', $key);
        $this->db->where('isEmailVerified', false);
        $query = $this->db->get('user');
        if ($query->num_rows() > 0) {
            $data = array(
                'isEmailVerified' => true
            );
            $this->db->where('verificationKey', $key);
            $this->db->update('user', $data);
            return true;
        } else {
            return false;
        }
    }*/
}

?>
