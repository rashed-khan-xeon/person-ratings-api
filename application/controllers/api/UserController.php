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
            if (array_key_exists("userType", $user)) {
                unset($user['userType']);
            }
            if (array_key_exists("userSetting", $user)) {
                unset($user['userSetting']);
            }
            if (array_key_exists("userRole", $user)) {
                unset($user['userRole']);
            }
            if ($user['userId'] == 0) {
                $res = $this->user->insert($user);
            } else {
                $res = $this->user->update($user);
            }
            if ($res) {
                $updatedUser = $this->user->get($user['userId']);
                $this->response($updatedUser, REST_Controller::HTTP_CREATED);
            } else {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
        }

    }

    public function addUserSettings_post()
    {
        $body = $this->request->body;
        if (empty($body) or is_null($body)) {
            $this->response("Invalid request !", REST_Controller::HTTP_BAD_REQUEST);
        }
        $exist = $this->user->checkUserSetting($body['userId']);
        $res = false;
        if ($exist) {
            $res = $this->user->updateUserSettings($body);
        } else {
            $res = $this->user->insertUserSettings($body);
        }
        if ($res) {
            $this->response($body, REST_Controller::HTTP_CREATED);
        } else {
            $this->response("Failed ", REST_Controller::HTTP_BAD_REQUEST);
        }

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

    public function uploadUserImage_post()
    {
        $body = $this->request->body;
        $imageEncodedSByteString = $body['userImageByteString'];
        $userId = $body['userId'];
        $imageByte = base64_decode($imageEncodedSByteString);
        $put = file_put_contents(APPPATH . '../image/' . $userId . ".png", $imageByte);
        if ($put) {
            $this->db->set("image", $userId . ".png")->where("userId", $userId)->update("user");
            $this->response("Uploaded", REST_Controller::HTTP_OK);
        } else {
            $this->response("", REST_Controller::HTTP_BAD_REQUEST);
        }

    }


}