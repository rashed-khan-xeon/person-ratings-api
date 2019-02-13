<?php

/**
 * Created by PhpStorm.
 * User: arifk
 * Date: 13.2.19
 * Time: 12:06 AM
 */
class FeatureTypeModel extends CI_Model
{

    function insert($data)
    {
        $res = $this->db->insert("feature_type", $data);
        if ($res) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    function update($data)
    {
        $res = $this->db->where("featureTypeId", $data['featureTypeId'])->update("feature_type", $data);
        if ($res) {
            return $data['featureTypeId'];
        } else {
            return false;
        }
    }


    function getAll($userId)
    {
        $this->load->model("FeatureModel", "featureModel");
        $this->load->model("UserModel", "userModel");
        $res = $this->db->select("*")->from("feature_type")->where("userId", $userId)->get()->result();
        foreach ($res as $data) {
            $data->features = $this->featureModel->getAllByFeatureTypeId($data->featureTypeId);
            $data->user = $this->userModel->get($data->userId);
        }
        return $res;
    }

    function get($id)
    {
        $this->load->model("FeatureModel", "featureModel");
        $this->load->model("UserModel", "userModel");
        $data = $this->db->select("*")->from("feature_type")->where("featureTypeId", $id)->get()->row();
        $data->features = $this->featureModel->getAllByFeatureTypeId($id);
        $data->user = $this->userModel->get($data->userId);
        return $data;
    }

    function getAllActiveFeatureType($userId)
    {
        $this->load->model("FeatureModel", "featureModel");
        $res = $this->db->select("*")->from("feature_type")->where("userId", $userId)->where("active", 1)->get()->result();
        foreach ($res as $data) {
            $data->users = $this->userModel->getAllByFeatureId($data->featureId);
        }
        return $res;
    }

}