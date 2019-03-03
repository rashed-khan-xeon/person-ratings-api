<?php

class UserTypeModel extends CI_Model
{
    public function get($id)
    {
        $type = $this->db->select("*")->from("user_type")->where("userTypeId", $id)->get()->row();
        if (empty($type)) {
            return false;
        } else {
            return $type;
        }
    }

    public function getAll()
    {
        $type = $this->db->select("*")->from("user_type")->get()->result();
        if (empty($type)) {
            return false;
        } else {
            return $type;
        }
    }

    public function insert($data)
    {
        $res = $this->db->insert("user_type", $data);
        if ($res) {
            return true;
        } else {
            return false;
        }

    }

    public function update($data)
    {
        $res = $this->db->where("userTypeId", $data->userTypeId)->update("user_type", $data);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

}