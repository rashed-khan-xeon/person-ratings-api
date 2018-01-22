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
        $query = $this->db->select("*")->from("user")->where("email", $userEmail)->where("password", $password)->where("active", 1);
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

}