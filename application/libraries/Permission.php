<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission {
  private $_CI;
  public function __construct()
  {
    $this->_CI =& get_instance();
    $this->_CI->load->model(array('permission_model'));
  }

  public function permission_check($codename){
    //cari permission id berdasarkan codemane permission
    $perm_q = $this->_CI->permission_model->get_permission_id($codename);
    if (!$perm_q){
      show_error($message = 'Permission tidak ditemukan di database',404,$heading = 'Error');
    }else{
      $perm_id = $perm_q->id;
    }

    $user_id = $this->_CI->session->userdata('user_id');
    //cek apakah user punya permisi
    $user_has_permission = $this->_CI->permission_model->cek_user_permission($user_id,$perm_id);

    if ($user_has_permission == true){
      return true;
    }else{
      //cari id grup si user berdasarkan id_user
      $group_ids = $this->_CI->permission_model->get_user_group_id($user_id);
      //cek permisi setiap grupnya user
      foreach ($group_ids as $group_id) {
        //cek apakah grupnya user punya permisi
        $group_has_permission = $this->_CI->permission_model->cek_group_permission($group_id->group_id,$perm_id);
        //jika grup tidak punya permisi cek si user sendiri apakah punya permisi
        if ($group_has_permission == true){
          return true;
        }
      }
      return false;
    }
  }
}
