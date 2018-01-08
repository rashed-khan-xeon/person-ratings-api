<?php

/**
 * Created by PhpStorm.
 * User: arifk
 * Date: 30.12.17
 * Time: 12:11 AM
 */
class UserReviewModel extends CI_Model
{
    public function insert($data)
    {
        $checkDup = $this->db->select("*")->from("user_review")->where("reviewedByUserId", $data['reviewedByUserId'])->where("ratingsCatId", $data['ratingsCatId'])->get()->row();
        if (sizeof($checkDup) > 0) {
            $data['userReviewId'] = $checkDup->userReviewId;
            $up = $this->update($data);
            return $up == true;
        }

        $res = $this->db->insert("user_review", $data);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {
        $res = $this->db->where("userReviewId", $data['userReviewId'])->update("user_review", $data);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    public function get($id)
    {
        $review = $this->db->select("*")->from("user_review")->where("userReviewId", $id)->get()->row();
        if (!is_null($review)) {
            return $review;
        }
        return null;
    }

    public function getAll()
    {
        $reviews = $this->db->select("*")->from("user_review")->get()->result();
        if (!is_null($reviews)) {
            return $reviews;
        }
        return null;
    }

    public function getAllByUserId($userId)
    {
        $reviews = $this->db->select("*")->from("user_review")->where("userId", $userId)->get()->result();
        if (!is_null($reviews)) {
            return $reviews;
        }
        return null;
    }
}