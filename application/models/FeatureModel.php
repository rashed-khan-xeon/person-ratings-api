<?php

/**
 * Created by PhpStorm.
 * User: arifk
 * Date: 5.2.19
 * Time: 10:10 PM
 */
class FeatureModel extends CI_Model
{
    function insert($data)
    {
        $res = $this->db->insert("feature", $data);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    function getAll($userId)
    {
        $this->load->model("UserModel", "userModel");
        $res = $this->db->select("*")->from("feature")->where("createdUserId", $userId)->get()->result();
        foreach ($res as $data) {
            $data->users = $this->userModel->getAllByFeatureId($data->featureId);
        }
        return $res;
    }

    function get($id)
    {
        $row = $this->db->select("*")->from("feature")->where("featureId", $id)->get()->row();
        return $row;
    }
}