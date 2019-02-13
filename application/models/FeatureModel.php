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
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    function update($data)
    {
        $res = $this->db->where("featureId", $data['featureId'])->update("feature", $data);
        if ($res) {
            return $data['featureId'];
        } else {
            return false;
        }
    }

    function updateFeatureUser($data)
    {
        $res = $this->db->set("active", $data['active'])->where("userId", $data['userId'])->update("user");
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

    function getAllByFeatureTypeId($featureTypeId)
    {
        $this->load->model("UserModel", "userModel");
        $res = $this->db->select("*")->from("feature")->where("featureTypeId", $featureTypeId)->get()->result();
        foreach ($res as $data) {
            $data->users = $this->userModel->getAllByFeatureId($data->featureId);
        }
        return $res;
    }

    function getAllByActiveFeatureTypeId($featureTypeId)
    {
        $this->load->model("UserModel", "userModel");
        $res = $this->db->select("*")->from("feature")->where("featureTypeId", $featureTypeId)->where("active", 1)->get()->result();
        foreach ($res as $data) {
            $data->users = $this->userModel->getAllByFeatureId($data->featureId);
        }
        return $res;
    }

    function getAllActiveFeature($userId)
    {
        $this->load->model("UserModel", "userModel");
        $res = $this->db->select("*")->from("feature")->where("createdUserId", $userId)->where("active", 1)->get()->result();
        foreach ($res as $data) {
            $data->users = $this->userModel->getAllByFeatureId($data->featureId);
        }
        return $res;
    }

    function getAllActiveFeatureForUser()
    {
        $this->load->model("UserModel", "userModel");
        $res = $this->db->select("*")->from("feature")->where("active", 1)->get()->result();
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