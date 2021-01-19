<?php 
class Api_model extends CI_Model
{

   public function __construct()
   {
       parent::__construct();
       $this->db = $this->load->database('default', TRUE);
   }

   public function createUser($data){
        $dataArray = array(
            'personalID' => isset($data['personalID']) ? strip_tags($data['personalID']) : '',
            'userName' => isset($data['userName']) ? strip_tags($data['userName']) : '',
            'userLastName1' => isset($data['userLastName1']) ? strip_tags($data['userLastName1']) : '',
            'userLastName2' => isset($data['userLastName2']) ? strip_tags($data['userLastName2']) : '',
            'status' => isset($data['status']) ? strip_tags($data['status']) : '',
            'scoreAverage' => isset($data['scoreAverage']) ? strip_tags($data['scoreAverage']) : '',
            'profilePhotoURL' => isset($data['profilePhotoURL']) ? strip_tags($data['profilePhotoURL']) : '',
            'photoID1URL' => isset($data['photoID1URL']) ? strip_tags($data['photoID1URL']) : '',
            'photoID2URL' => isset($data['photoID2URL']) ? strip_tags($data['photoID2URL']) : '',
            'phone' => isset($data['phone']) ? strip_tags($data['phone']) : '',
            'email' => isset($data['email']) ? strip_tags($data['email']) : '',
            'userCountry' => isset($data['userCountry']) ? strip_tags($data['userCountry']) : '',
            'userCity' => isset($data['userCity']) ? strip_tags($data['userCity']) : '',
            'userState' => isset($data['userState']) ? strip_tags($data['userState']) : '',
            'userPassword' => isset($data['userPassword']) ? strip_tags($data['userPassword']) : ''
        );
        
        return $this->db->insert('user', $dataArray);
   }

   public function getUserByID()
   {
      $where['id'] = $id;
      $result_set = $this->db->get_where('properties', $where);
      $result_arr = $result_set->result_array();
      return $result_arr[0];
   }

   public function get($id)
   {
    $where['id'] = $id;
   	$result_set = $this->db->get_where('properties', $where);
    $result_arr = $result_set->result_array();
    return $result_arr[0];
   }

   public function update($id, $new_data)
   {
    $where['id'] = $id;
    $this->db->update('properties', $new_data, $where);
   }

   public function delete($id)
   {
    $where['id'] = $id;
    $this->db->delete('properties', $where);
   }

   public function get_version()
   {
      $result_set = $this->db->query('SELECT VERSION()');
      return $result_set;
   }

   public function all()
   {
     $result_set = $this->db->get('properties');
     return $result_set->result_array();
   }

   
}