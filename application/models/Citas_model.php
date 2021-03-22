<?php
class Citas_model extends CI_Model
{
    function addAppointment($data){       
        $results = $this->db->insert('userappointment', $data);
        return $results;
    }
}

?>