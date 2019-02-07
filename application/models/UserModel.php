<?php

/**
 * Created by PhpStorm.
 * User: arifk
 * Date: 11.12.17
 * Time: 01:25 AM
 */
class UserModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function get($id)
    {
        $query = $this->db->select("*")->from("user")->where("userId", $id);
        $data = $query->get()->row();
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

            $userType = $this->db->select("*")->from("user_type")->where("userTypeId", $data->userTypeId)->get()->row();
            if (!empty($userType)) {
                $data->userType = $userType;
            }
            $userSetting = $this->db->select("*")->from("user_setting")->where("userId", $data->userId)->get()->row();
            if (!empty($userSetting)) {
                $data->userSetting = $userSetting;
            }
            return $data;
        }
        return null;
    }

    function getAll()
    {
        $datas = $this->db->select("*")->from("user")
            ->get()->result();

        if (isset($datas)) {
            foreach ($datas as $data) {
                unset($data->password);
                $userRole = $this->db->select("*")->from("user_role")->where("userId", $data->userId)->get()->row();
                if (!empty($userRole)) {
                    $role = $this->db->select("*")->from("role")->where("roleId", $userRole->roleId)->get()->row();
                    if (!empty($role)) {
                        $userRole->role = $role;
                        $data->userRole = $userRole;
                    }
                }

                $userType = $this->db->select("*")->from("user_type")->where("userTypeId", $data->userTypeId)->get()->row();
                if (!empty($userType)) {
                    $data->userType = $userType;
                }
                $userSetting = $this->db->select("*")->from("user_setting")->where("userId", $data->userId)->get()->row();
                if (!empty($userSetting)) {
                    $data->userSetting = $userSetting;
                }
            }
            return $datas;
        } else {
            return null;
        }

    }

    function getAllByFeatureId($featureId)
    {
        $datas = $this->db->select("*")->from("user")->where("featureId", $featureId)
            ->get()->result();

        if (isset($datas)) {
            foreach ($datas as $data) {
                unset($data->password);
                $userRole = $this->db->select("*")->from("user_role")->where("userId", $data->userId)->get()->row();
                if (!empty($userRole)) {
                    $role = $this->db->select("*")->from("role")->where("roleId", $userRole->roleId)->get()->row();
                    if (!empty($role)) {
                        $userRole->role = $role;
                        $data->userRole = $userRole;
                    }
                }

                $userType = $this->db->select("*")->from("user_type")->where("userTypeId", $data->userTypeId)->get()->row();
                if (!empty($userType)) {
                    $data->userType = $userType;
                }
                $userSetting = $this->db->select("*")->from("user_setting")->where("userId", $data->userId)->get()->row();
                if (!empty($userSetting)) {
                    $data->userSetting = $userSetting;
                }
            }
            return $datas;
        } else {
            return null;
        }
    }

    function getAllActiveUserByFeatureId($featureId)
    {
        $datas = $this->db->select("*")->from("user")->where("featureId", $featureId)
            ->get()->result();

        if (isset($datas)) {
            foreach ($datas as $data) {
                unset($data->password);
                $userRole = $this->db->select("*")->from("user_role")->where("userId", $data->userId)->get()->row();
                if (!empty($userRole)) {
                    $role = $this->db->select("*")->from("role")->where("roleId", $userRole->roleId)->get()->row();
                    if (!empty($role)) {
                        $userRole->role = $role;
                        $data->userRole = $userRole;
                    }
                }

                $userType = $this->db->select("*")->from("user_type")->where("userTypeId", $data->userTypeId)->get()->row();
                if (!empty($userType)) {
                    $data->userType = $userType;
                }
                $userSetting = $this->db->select("*")->from("user_setting")->where("userId", $data->userId)->get()->row();
                if (!empty($userSetting)) {
                    $data->userSetting = $userSetting;
                }
            }
            return $datas;
        } else {
            return null;
        }
    }

    function insert($data)
    {
        $res = $this->db->insert("user", $data);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    function update($data)
    {
        $res = $this->db->where("userId", $data['userId'])->update("user", $data);
        if ($res) {
            return true;
        } else {
            return false;
        }

    }

    function insertUserSettings($data)
    {
        $res = $this->db->insert("user_setting", $data);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    function updateUserSettings($data)
    {
        $res = $this->db->where("userId", $data['userId'])->update("user_setting", $data);
        if ($res) {
            return true;
        } else {
            return false;
        }

    }

    function checkUserSetting($userId)
    {
        $row = $this->db->select("*")->from("user_setting")->where("userId", $userId)->get()->row();
        return $row;
    }

    function searchUser($keyWord)
    {
        $qr = "SELECT * from user WHERE fullName LIKE '%{$keyWord}%' or email LIKE '%{$keyWord}%' or phoneNumber LIKE '%{$keyWord}%' AND active=1 AND hasVerified=1";
        $datas = $this->db->query($qr)->result();
//        $datas = $this->db->select("*")->from("user")
//            ->like("fullName", $keyWord)
//            ->or_like("email", $keyWord, "before")
//            ->or_like("phoneNumber", $keyWord)
//            ->get()->result();

        if (isset($datas)) {
            foreach ($datas as $data) {
                unset($data->password);
                $userRole = $this->db->select("*")->from("user_role")->where("userId", $data->userId)->get()->row();
                if (!empty($userRole)) {
                    $role = $this->db->select("*")->from("role")->where("roleId", $userRole->roleId)->get()->row();
                    if (!empty($role)) {
                        $userRole->role = $role;
                        $data->userRole = $userRole;
                    }
                }

                $userType = $this->db->select("*")->from("user_type")->where("userTypeId", $data->userTypeId)->get()->row();
                if (!empty($userType)) {
                    $data->userType = $userType;
                }
                $userSetting = $this->db->select("*")->from("user_setting")->where("userId", $data->userId)->get()->row();
                if (!empty($userSetting)) {
                    $data->userSetting = $userSetting;
                }
            }
            return $datas;
        } else {
            return null;
        }
    }
}