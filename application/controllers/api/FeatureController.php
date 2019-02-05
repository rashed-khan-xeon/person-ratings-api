<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: arifk
 * Date: 20.12.17
 * Time: 11:39 PM
 */
require 'Base_Api_Controller.php';

class FeatureController extends Base_Api_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model("LoginModel", "login");
        $this->load->model("UserModel", "user");
        $this->load->model("FeatureModel", "fm");
    }

    public function createFeature_post()
    {
        try {
            $body = $this->request->body;
            if (empty($body) or is_null($body)) {
                $this->response("Invalid Request", REST_Controller::HTTP_BAD_REQUEST);
            }
            unset($body["users"]);
            $rs = $this->fm->insert($body);
            if ($rs) {
                $feature = $this->fm->get($rs);
                $this->response($feature, REST_Controller::HTTP_CREATED);
            } else {
                $this->response("Failed to create", REST_Controller::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            log_message("feature create", $e->getMessage());
        }
    }
    public function getFeatureList_get()
    {
        $this->isAuth();
        $id = $this->get("userId");
        if ($id == 0 or is_null($id)) {
            $this->response("Invalid Request", REST_Controller::HTTP_BAD_REQUEST);
        }
        $featureList = $this->fm->getAll($id);
        if ($featureList) {
            $this->response($featureList, REST_Controller::HTTP_OK);
        } else {
            $this->response("Invalid Request", REST_Controller::HTTP_NOT_FOUND);
        }
    }
}