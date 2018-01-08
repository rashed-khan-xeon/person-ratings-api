<?php

/**
 * Created by PhpStorm.
 * User: arifk
 * Date: 31.12.17
 * Time: 12:59 AM
 */
require "Base_Api_Controller.php";

class UserTypeController extends Base_Api_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model("UserTypeModel", "userType");
    }

    public function addOrUpdateUserType_post()
    {
        $this->isAuth();
        $userType = $this->request->body;
        if (is_null($userType)) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $res = false;
        if ($userType->userTypeId == 0) {
            $res = $this->userType->insert($userType);
        } else {
            $res = $this->userType->update($userType);
        }
        if ($res) {
            $this->response("created", REST_Controller::HTTP_CREATED);
        } else {
            $this->response("Updated", REST_Controller::HTTP_CREATED);
        }
    }

    public function getUserType_get()
    {
        $this->isAuth();
        $utId = $this->get("userTypeId");
        if (is_null($utId) or $utId == 0) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $userType = $this->userType->get($utId);
        if (is_null($userType)) {
            $this->response(null, REST_Controller::HTTP_NOT_FOUND);
        } else {
            $this->response($userType, REST_Controller::HTTP_OK);
        }
    }

    public function getAllUserType_get()
    {
        $this->isAuth();
        $userType = $this->userType->getAll();
        if (is_null($userType)) {
            $this->response(null, REST_Controller::HTTP_NOT_FOUND);
        } else {
            $this->response($userType, REST_Controller::HTTP_OK);
        }
    }
}