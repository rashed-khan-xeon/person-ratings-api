<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Base_Api_Controller extends REST_Controller
{

    public function isAuth()
    {
        //TODO must be implemented next time
//        $header = $this->_head_args;
//
//        if (isset($header['accessToken'])) {
//            $cUserId = get_cookie("loginData", true);
//            if ($cUserId != $header['accessToken']) {
//                $this->response(['message' => "Authorization has been denied for this request"], REST_Controller::HTTP_UNAUTHORIZED);
//            }else{
//                return true;
//            }
//        } else {
//            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
//        }
//        return false;
    }
    function objectToArray($d) {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $d);
        }
        else {
            // Return array
            return $d;
        }
    }

}