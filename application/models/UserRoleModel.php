<?php

/**
 * Created by PhpStorm.
 * User: arifk
 * Date: 25.12.17
 * Time: 12:12 AM
 */
class UserRoleModel extends CI_Model
{

    function get($id)
    {
        $row = $this->db->select("*")->from('user_role')->where("userRoleId", $id)->get()->row();
        return $row;
    }

    function getAll()
    {
        $result = $this->db->select("*")->from('user_role')->get()->result();
        return $result;
    }

    function insert($data)
    {
        $this->db->insert("user_role", $data);
        $id = $this->db->insert_id();
        if ($id == 0) {
            return true;
        } else {
            return false;
        }
    }

    function update($data)
    {
        $res = $this->db->where("userId", $data['userId'])->update("user_role", $data);

        if ($res) {
            return true;
        } else {
            return false;
        }
    }


}