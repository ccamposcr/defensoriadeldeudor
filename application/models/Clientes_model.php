<?php
class Clientes_model extends CI_Model
{
    function addClient($data){       
        return $str = $this->db->insert_string('user', $data);
    }
}

?>