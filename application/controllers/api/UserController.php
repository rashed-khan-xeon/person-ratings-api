<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: arifk
 * Date: 11.12.17
 * Time: 01:08 AM
 */

require 'Base_Api_Controller.php';

class UserController extends Base_Api_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model("UserModel", "user");
    }

    public function addOrUpdateUsers_post()
    {
        $this->isAuth();
        $user = $this->request->body;
        if (is_null($user)) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $res = false;
            if ($user->userId == 0) {
                $res = $this->user->insert($user);
            } else {
                $res = $this->user->update($user);
            }
            if ($res) {
                $this->response("Created", REST_Controller::HTTP_CREATED);
            } else {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
        }

    }

    public function addUserSettings_post()
    {

    }

    public function users_get()
    {
        $this->isAuth();
        $users = $this->user->getAll();
        if ($users == null) {
            $this->response(null, REST_Controller::HTTP_NOT_FOUND);
        } else {
            $this->response($users, REST_Controller::HTTP_OK);
        }
    }

    public function userDetails_get()
    {
        $this->isAuth();
        $userId = $this->get("userId");
        if (empty($userId) || is_null($userId) || $userId <= 0) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $user = $this->user->get($userId);
        if ($user == null) {
            $this->response(null, REST_Controller::HTTP_NOT_FOUND);
        } else {
            $this->response($user, REST_Controller::HTTP_OK);
        }
    }


    public function searchUser_get()
    {
        $this->isAuth();
        $keyWord = $this->get("keyWord");
        if (empty($keyWord) || is_null($keyWord)) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        if (strlen($keyWord) < 4) {
            $this->response(null, REST_Controller::HTTP_NOT_FOUND);
        }
        $users = $this->user->searchUser($keyWord);
        if ($users == null) {
            $this->response(null, REST_Controller::HTTP_NOT_FOUND);
        } else {
            if (is_object($users))
                $this->response($this->objectToArray($users), REST_Controller::HTTP_OK);
            else {
                $this->response($users, REST_Controller::HTTP_OK);
            }

        }
    }

}