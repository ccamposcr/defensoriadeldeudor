<?php
class Citas_model extends CI_Model
{
    function addAppointment($data){       
        $results = $this->db->insert('userappointment', $data);
        return $results;
    }

    function getAppointmentsByDateRange($data){
        $this->db->select('userappointment.id appointmentID, userappointment.userID, userappointment.internalUserID, userappointment.madeByUserID, userappointment.alertColor, userappointment.date, client.name clientUserName, client.lastName1 clientLastName1, client.lastName2 clientLastName2, internalUser.name internalUserUserName, internalUser.lastName1 internalUserLastName1, internalUser.lastName2 internalUserLastName2, madeByUser.name madeByUserUserName, madeByUser.lastName1 madeByUserLastName1, madeByUser.lastName2 madeByUserLastName2, appointmenttypelist.type');
        $this->db->from('userappointment');
        $this->db->where('userappointment.' . $data['searchBy'] . '>=', $data['start']);
        $this->db->where('userappointment.' . $data['searchBy'] . '<=', $data['end']);
        $this->db->join('user as client', 'client.id = userappointment.userID', 'left');
        $this->db->join('user as internalUser', 'internalUser.id = userappointment.internalUserID', 'left');
        $this->db->join('user as madeByUser', 'madeByUser.id = userappointment.madeByUserID', 'left');
        $this->db->join('appointmenttypelist', 'appointmenttypelist.id = userappointment.appointmentTypeID', 'left');
        
        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }

    function cancelAppointment($data){
        $results = $this->db->delete('userappointment', $data);
        return $results;
    }
}

?>