<?php


if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Tesview extends CI_Controller
{
    public function index()
    {


			$data = array(
        'controller' => 'view',
        'uri1' => 'tes',
        'main_view' => 'testview'
      );


        $this->load->view('testview',$data);
    }

    public function tes_permission(){
      $this->load->library('Permission');
      if ($this->permission->permission_check('tesi') <> true){
        show_error('Anda tidak punya permisi', 505, $heading = 'An Error Was Encountered');
      }else{
        show_error('Anda boleh lewat', 200, $heading = 'An Error Not Encountered');
      }
    }
}

 ?>
