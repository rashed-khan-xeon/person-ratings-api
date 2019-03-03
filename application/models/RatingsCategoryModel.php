<?php

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
        return $rq;
    }

    function inActiveRatingsCat($rtCatId)
    {
        $rq = $this->db->set("active", 0)->where("ratingsCatId", $rtCatId)->update("ratings_category");
        if ($rq) {
            return true;
        } else {
            return false;
        }
    }

    function activeRatingsCat($rtCatId)
    {
        $rq = $this->db->set("active", 1)->where("ratingsCatId", $rtCatId)->update("ratings_category");
        if ($rq) {
            return true;
        } else {
            return false;
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

    function getInActiveRtCatByUserId($userId)
    {
        $rq = $this->db->select("*")->from("ratings_category")->where("userId", $userId)->where("active", 0)->get()->result();
        if (is_null($rq)) {
            return false;
        } else {
            return $rq;
        }
    }

    function getActiveRtCatByUserId($userId)
    {
        $rq = $this->db->select("*")->from("ratings_category")->where("userId", $userId)->where("active", 1)->get()->result();
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

    function getAllActive()
    {
        $rq = $this->db->select("*")->from("ratings_category")->where("active", 1)->get()->result();
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