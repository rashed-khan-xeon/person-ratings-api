<?php

/**
 * Created by PhpStorm.
 * User: arifk
 * Date: 28.12.17
 * Time: 01:36 AM
 */
class CategoryModel extends CI_Model
{
    public function get($id)
    {
        $res = $this->db->select("*")->from('category')->where("catId", $id)->get()->row();
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }

    public function getAll()
    {
        $res = $this->db->select("*")->from('category')->get()->result();
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }

    public function getDefaultCategories($userId)
    {
        $res = $this->db->select("*")->from('category')->where("userId", $userId)->where("active", 1)->or_where("isDefault", 1)->get()->result();
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }

    public function getAllByTypeId($userTypeId)
    {
        $res = $this->db->select("*")->from('category')->where("userTypeId", $userTypeId)->get()->result();
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }

    public function getAllByUserId($userId)
    {
        $res = $this->db->select("*")->from('category')->where("userId", $userId)->where("active", 1)->or_where("isDefault", 1)->get()->result();
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }

    public function insert($data)
    {
        $in = $this->db->insert('category', $data);
        if ($in) {
            $rs = $this->get($this->db->insert_id());
            return $rs;
        } else {
            return false;
        }
    }

    public function update($data)
    {
        $up = $this->db->where("catId", $data['catId'])->update('category', $data);
        if ($up) {
            return true;
        } else {
            return false;
        }
    }

}