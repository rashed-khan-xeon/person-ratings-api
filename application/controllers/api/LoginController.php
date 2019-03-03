<?php

defined('BASEPATH') OR exit('No direct script access allowed');

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

    public function changePassword_post()
    {
        $body = $this->request->body;
        if ($body != null) {
            $isValid = $this->login->checkCurrentPassword(md5($body['currentPassword']), $body['userId']);
            if ($isValid) {
                $update = $this->login->updatePassword($body['newPassword'], $body['userId']);
                if ($update)
                    $this->response("Password updated", REST_Controller::HTTP_CREATED);
                else
                    $this->response("Bad request", REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $this->response("Current password is invalid", REST_Controller::HTTP_BAD_REQUEST);
            }

        } else {
            $this->response("Bad Request", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function sendCode_get()
    {
        $userId = $this->get("userId");
        if ($userId == null || $userId == 0) {
            $this->response("Invalid Request !", REST_Controller::HTTP_BAD_REQUEST);
        }
        $data["userId"] = $userId;
        $data['code'] = $this->getRandomCode();
        $startTime = date("Y-m-d H:i:s");
        $convertedTime = date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime($startTime)));
        $data['startTime'] = $startTime;
        $data['endTime'] = $convertedTime;
        $this->login->expirePreviousCode($userId);
        $insert = $this->login->addUserVerificationCode($data);
        $user = $this->user->get($userId);

        if (!$insert) {
            $this->response("Failed", REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $sent = $this->send($data['code'], $user->phoneNumber);
            if (!$sent) {
                $this->response("Failed", REST_Controller::HTTP_BAD_REQUEST);
            }
            $this->response("Success", REST_Controller::HTTP_CREATED);
        }
    }

    private function send($code, $to)
    {
        $to = "$to";
        $token = "edfa6ec4bb54fb0d201771fa229ca3b8";
        $message = "Ratings Verification Code is : " . $code;

        $url = "http://sms.greenweb.com.bd/api.php";


        $data = array(
            'to' => "$to",
            'message' => "$message",
            'token' => "$token"
        ); // Add parameters in key value
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        return $smsresult;
    }

    private function getRandomCode()
    {
        $randomString = mt_rand(100000, 999999);
        return $randomString;
    }

    public function checkCode_post()
    {
        $body = $this->request->body;
        if ($body == null) {
            $this->response("Bad Request !", REST_Controller::HTTP_BAD_REQUEST);
        }
        $userId = $body['userId'];
        $code = $body['code'];
        $result = $this->login->checkVerificationCode($userId, $code);
        if ($result == false) {
            $this->response("Verification Failed", REST_Controller::HTTP_NOT_FOUND);
        } else {
            $this->response($result, REST_Controller::HTTP_OK);
        }
    }

    public function createFeatureUser_post()
    {
        try {
            $this->load->model("RatingsCategoryModel", "rCatModel");
            $body = $this->request->body;
            if (empty($body) or is_null($body)) {
                $this->response("Invalid Request", REST_Controller::HTTP_BAD_REQUEST);
            }
            $category = $body['categoryId'];
//            $featureId = $body['featureId'];
//            $name = $body['name'];
            unset($body['categoryId']);
            $rs = $this->login->signUp($body);

            if ($rs) {
                foreach ($category as $cat) {
                    $catData['catId'] = $cat;
                    $catData['userId'] = $rs;
                    $this->rCatModel->insert($catData);
                }
                $setting['userId'] = $rs;
                $setting['emailVisible'] = 1;
                $setting['phoneNumberVisible'] = 1;
                $setting['imageVisible'] = 0;
                $setting['addressVisible'] = 1;
                $setting['hasRating'] = 1;
                $setting['hasReview'] = 1;
                $this->user->insertUserSettings($setting);
                $this->response("Success", REST_Controller::HTTP_CREATED);
            } else {
                $this->response("Failed to create", REST_Controller::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            log_message("sign-up", $e->getMessage());
        }
    }
}