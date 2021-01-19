<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model("Api_model");
    }

    public function createUser(){
        $this->Api_M->createUser($_POST);
    }

}
