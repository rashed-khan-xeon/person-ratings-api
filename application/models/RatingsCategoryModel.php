<?php

/**
 * Created by PhpStorm.
 * User: arifk
 * Date: 27.12.17
 * Time: 02:23 AM
 */
class RatingsCategoryModel extends CI_Model
{

    function get($id)
    {
        $rq = $this->db->select("*")->from("ratings_category")->where("ratingsCatId", $id)->get()->row();
        if (is_null($rq)) {
            return false;
        } else {
            return $rq;
        }
    }

    function getByUserIdCatId($userId, $catId)
    {
        $rq = $this->db->select("*")->from("ratings_category")->where("userId", $userId)->where("catId", $catId)->get()->row();
        if (is_null($rq)) {
            return false;
        } else {
            return true;
        }
    }

    function getByUserId($userId)
    {
        $rq = $this->db->select("*")->from("ratings_category")->where("userId", $userId)->get()->result();
        if (is_null($rq)) {
            return false;
        } else {
            return $rq;
        }
    }

    function getAll()
    {
        $rq = $this->db->select("*")->from("ratings_category")->get()->result();
        if (is_null($rq)) {
            return false;
        } else {
            return $rq;
        }
    }

    function insert($data)
    {
        $insert = $this->db->insert("ratings_category", $data);
        if ($insert) {
            return true;
        } else {
            return false;
        }
    }

    function update($data)
    {
        $update = $this->db->where("ratingsCatId", $data['ratingsCatId'])->update("ratings_category", $data);
        if ($update) {
            return true;
        } else {
            return false;
        }
    }
}