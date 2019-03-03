<?php

class UserRatingModel extends CI_Model
{

    function get($id)
    {
        $rq = $this->db->select("*")->from("user_ratings")->where("userRatingsId", $id)->get()->row();
        if (is_null($rq)) {
            return false;
        } else {
            return $rq;
        }
    }

    function getAll()
    {
        $rq = $this->db->select("*")->from("user_ratings")->get()->result();
        if (is_null($rq)) {
            return false;
        } else {
            return $rq;
        }
    }

    function getUserAllRatingsByUserId($userId, $skip, $top)
    {
        $rq = $this->db->select("*")->from("user_ratings")->where("userId", $userId)->order_by("ratingsDate", "DESC")->Limit($top, $skip)->get()->result();
        if (is_null($rq)) {
            return false;
        } else {

            return $rq;
        }
    }

    function getUserRatingsSummaryByUserId($userId)
    {
        $rq = $this->db
            ->select_avg("ratings", "ratingsSummary")
            ->select("c.name as category,COUNT(ratings) as count")
            ->from("user_ratings ur")
            ->join("ratings_category rc", "ur.ratingsCatId=rc.ratingsCatId", "right")
            ->join("category c", "rc.catId=c.catId", "left")
            ->where("rc.userId", $userId)
            ->group_by("rc.ratingsCatId")
            ->get()->result();
        if (is_null($rq)) {
            return false;
        } else {
            return $rq;
        }
    }

    function insert($data)
    {
        $checkDuplicate = $this->db->select("*")->from("user_ratings")->where("ratedByUserId", $data['ratedByUserId'])->where("ratingsCatId", $data['ratingsCatId'])->get()->row();
        if (sizeof($checkDuplicate) > 0) {
            $data['userRatingsId'] = $checkDuplicate->userRatingsId;
            $up = $this->update($data);
            return $up == true;
        }
        $res = $this->db->insert("user_ratings", $data);
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }

    function update($data)
    {
        $res = $this->db->where("userRatingsId", $data['userRatingsId'])->update("user_ratings", $data);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }
}