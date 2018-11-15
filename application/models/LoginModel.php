<?php

/**
 * Created by PhpStorm.
 * User: arifk
 * Date: 20.12.17
 * Time: 11:43 PM
 */
class LoginModel extends CI_Model
{
    public function checkLoginInfo($userEmail, $password)
    {
        $query = $this->db->select("*")->from("user")->where("email", $userEmail)->where("password", $password);
        $res = $query->get();
        $data = $res->row();

        if ($data) {
            unset($data->password);
            $userRole = $this->db->select("*")->from("user_role")->where("userId", $data->userId)->get()->row();
            if (!empty($userRole)) {
                $role = $this->db->select("*")->from("role")->where("roleId", $userRole->roleId)->get()->row();
                if (!empty($role)) {
                    $userRole->role = $role;
                    $data->userRole = $userRole;
                }
            }
            $userSetting = $this->db->select("*")->from("user_setting")->where("userId", $data->userId)->get()->row();
            if (!empty($userSetting)) {
                $data->userSetting = $userSetting;
            }
            $userType = $this->db->select("*")->from("user_type")->where("userTypeId", $data->userTypeId)->get()->row();
            if (!empty($userType)) {
                $data->userType = $userType;
            }

            return $data;
        }
        return null;
    }

    public function signUp($data)
    {
        $res = $this->db->insert("user", $data);
        if ($res) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }

    public function checkCurrentPassword($currentPassword, $userId)
    {
        $res = $this->db->select("password")->from("user")->where("userId", $userId)->get()->row();
        if ($res) {
            if ($res->password == $currentPassword) {
                return true;
            }
        }
        return false;

    }

    public function updatePassword($data, $userId)
    {
        $pass = md5($data);
        $res = $this->db->set("password", $pass)->where('userId', $userId)->update("user");
        if ($res) {
            return true;
        }
        return false;

    }

    public function addUserVerificationCode($data)
    {
        $insert = $this->db->insert("verify", $data);
        if ($insert) {
            return true;
        }
        return false;
    }

    public function checkVerificationCode($userId, $code)
    {
        $date = date("Y-m-d H:i:s");
        $qr = "select * from verify WHERE userId={$userId}  and code= {$code} and endTime > '{$date}' and expired=0";
        //$this->db->select("*")->from("verify")->where("userId", $userId)->where("code", $code)->get()->row();
        $verification = $this->db->query($qr)->row();
        if (!empty($verification)) {
            $this->db->set("hasVerified", 1)->where("userId", $userId)->update("user");
            return true;
        }
        return false;
    }

    public function expirePreviousCode($userId)
    {
        $this->db->set("expired", 1)->where("userId", $userId)->update("verify");
    }
}