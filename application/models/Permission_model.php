<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class permission_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->dbp = $this->load->database('default',TRUE);
    //Codeigniter : Write Less Do More
  }

  public function get_user_group_id($user_id){
    $this->dbp->where('user_id',$user_id);
    return $this->dbp->get('users_groups')->result();
  }

  public function get_permission_id($codename){
    $this->dbp->where('codename',$codename);
    return $this->dbp->get('permissions')->row();
  }

  public function cek_group_permission($group_id, $perm_id){
    $this->dbp->where('group_id',$group_id);
    $this->dbp->where('permission_id',$perm_id);
    $num_rows = $this->dbp->count_all_results('group_permissions');

    if ($num_rows > 0){
      return true;
    }else {
      return false;
    }
  }

  public function cek_user_permission($user_id, $perm_id){
    $this->dbp->where('user_id',$user_id);
    $this->dbp->where('permission_id',$perm_id);
    $num_rows = $this->dbp->count_all_results('user_permission');

    if ($num_rows > 0){
      return true;
    }else {
      return false;
    }
  }

}
