<?php

/**
 * Created by PhpStorm.
 * User: arifk
 * Date: 30.12.17
 * Time: 12:06 AM
 */
require "Base_Api_Controller.php";

class UserReviewController extends Base_Api_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model('UserReviewModel', "review");
        $this->load->model('UserModel', "user");
    }

    public function addOrUpdateUserReview_post()
    {
        try {
            $this->isAuth();
            $userReview = $this->request->body;
            if (is_null($userReview) or empty($userReview)) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            $insert = $this->review->insert($userReview);
            if (!$insert) {
                $this->response("Failed", REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $this->response("Review Added", REST_Controller::HTTP_CREATED);
            }
        } catch (Exception $e) {
            $this->response($e, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            log_message("Raings", $e);
        }
    }

    public function getUserReview_get()
    {
        $this->isAuth();
        $userReviewId = $this->get("userReviewId");
        if (is_null($userReviewId) or $userReviewId == 0) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $userReview = $this->review->get($userReviewId);
        if (!is_null($userReview)) {
            $user = $this->user->get($userReview->userId);
            $ratedByUser = $this->user->get($userReview->ratedByUserId);
            if (!is_null($user)) {
                $userReview->user = $user;
            } else {
                $userReview->user = null;
            }
            if (!is_null($ratedByUser)) {
                $userReview->ratedByuser = $ratedByUser;
            } else {
                $userReview->ratedByuser = null;
            }

        }
        if (is_null($userReview)) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($userReview, REST_Controller::HTTP_OK);
        }
    }

    public function getAllUserReview_get()
    {
        $this->isAuth();
        $userReviews = $this->review->getAll();
        if (!is_null($userReviews)) {
            foreach ($userReviews as $userReview) {
                $user = $this->user->get($userReview->userId);
                $ratedByUser = $this->user->get($userReview->ratedByUserId);
                if (!is_null($user)) {
                    $userReview->user = $user;
                } else {
                    $userReview->user = null;
                }
                if (!is_null($ratedByUser)) {
                    $userReview->ratedByuser = $ratedByUser;
                } else {
                    $userReview->ratedByuser = null;
                }
            }
        }
        if (is_null($userReviews)) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($userReviews, REST_Controller::HTTP_OK);
        }
    }

    public function getUserReviewByUserId_get()
    {
        $this->isAuth();
        $userId = $this->get("userId");
        if (is_null($userId) or $userId == 0) {
            $this->response("Invalid Request", REST_Controller::HTTP_BAD_REQUEST);
        }
        $userReviews = $this->review->getAllByUserId($userId);
        if (!is_null($userReviews)) {
            foreach ($userReviews as $userReview) {
                $user = $this->user->get($userReview->userId);
                $ratedByUser = $this->user->get($userReview->reviewedByUserId);
                if (!is_null($user)) {
                    $userReview->user = $user;
                } else {
                    $userReview->user = null;
                }
                if (!is_null($ratedByUser)) {
                    $userReview->ratedByuser = $ratedByUser;
                } else {
                    $userReview->ratedByuser = null;
                }
            }
        }
        if (is_null($userReviews)) {
            $this->response("No Content Found", REST_Controller::HTTP_NOT_FOUND);
        } else {
            $this->response($userReviews, REST_Controller::HTTP_OK);
        }
    }


}