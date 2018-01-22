<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HomeController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

    }

    public function loadCommonView($data)
    {
        $data["menu"] = $this->load->view("menu", "", true);
        $this->load->view('dashboard', $data);
    }

    public function index()
    {
        $data["menu"] = $this->load->view("menu", "", true);
        $data["container"] = $this->load->view("home", $data, true);
        $this->loadCommonView($data);
    }

    public function manageCategory()
    {
        $data["container"] = $this->load->view("category_view", "", true);
        $this->loadCommonView($data);
    }
}
