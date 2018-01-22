<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: arifk
 * Date: 20.12.17
 * Time: 11:39 PM
 */
require 'Base_Api_Controller.php';

class LoginController extends Base_Api_Controller
{

    /**
     * Login constructor.
     */
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model("LoginModel", "login");
        $this->load->model("UserModel", "user");
    }

    public function index_get()
    {
        $this->response("Login controller", REST_Controller::HTTP_BAD_GATEWAY);
    }

    public function authorization_post()
    {
        $data = $this->request->body;

        try {
            if ((!isset($data['email'])) || (!isset($data['password']))) {
                $this->response('Invalid username or password', REST_Controller::HTTP_BAD_REQUEST);
            }
            $isLogin = $this->login->checkLoginInfo($data['email'], md5($data['password']));

            if ($isLogin) {

                set_cookie("loginData", $isLogin->userId, 3600, COOKIE_DOMAIN, "/", null, false, true);
                $this->response($isLogin, REST_Controller::HTTP_OK);
            } else {

                $this->response('Incorrect Username or Password', REST_Controller::HTTP_NOT_FOUND);
            }

        } catch (Exception $e) {
            $this->response($e->getMessage(), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function accountCreate_post()
    {
        try {

            $body = $this->request->body;
            if (empty($body) or is_null($body)) {
                $this->response("Invalid Request", REST_Controller::HTTP_BAD_REQUEST);
            }
            $rs = $this->login->signUp($body);
            if ($rs) {
                $user = $this->user->get($rs);
                $this->response($user, REST_Controller::HTTP_CREATED);
            } else {
                $this->response("Failed to create", REST_Controller::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            log_message("sign-up", $e->getMessage());
        }
    }

}