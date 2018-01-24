<?php

/**
 * Created by PhpStorm.
 * User: arifk
 * Date: 27.12.17
 * Time: 02:10 AM
 */
require_once "Base_Api_Controller.php";

class UserRatingsController extends Base_Api_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model("UserRatingModel", "userRatings");
        $this->load->model("UserModel", "user");
        $this->load->model("RatingsCategoryModel", "ratingsCat");
        $this->load->model("CategoryModel", "category");
    }

    public function getUserRatings_get()
    {
        $this->isAuth();
        $id = $this->db->get("userRatingsId");
        if ($id == 0 or is_null($id)) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $userRatings = $this->userRatings->get($id);
        if ($userRatings) {
            $user = $this->user->get($userRatings->userId);
            $ratedUser = $this->user->get($userRatings->ratedByUserId);
            $ratingsCategory = $this->ratingsCat->get($userRatings->ratingsCatId);
            if ($user) {
                $userRatings->user = $user;
            }
            if ($ratedUser) {
                $userRatings->ratedByUser = $ratedUser;
            }
            if ($ratingsCategory) {
                $userRatings->ratingsCategory = $ratingsCategory;
            }
            $this->response($userRatings, REST_Controller::HTTP_OK);
        } else {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function getUserAllRatings_get()
    {
        $this->isAuth();
        $id = $this->get("userId");
        $skip = $this->get("skip");
        $top = $this->get("top");
        if ($id == 0 or is_null($id)) {
            $this->response("Invalid Request", REST_Controller::HTTP_BAD_REQUEST);
        }

        $userRatings = $this->userRatings->getUserAllRatingsByUserId($id, $skip, $top);

        if ($userRatings) {
            foreach ($userRatings as $ur) {
                $user = $this->user->get($ur->userId);
                $ratedUser = $this->user->get($ur->ratedByUserId);
                $ratingsCategory = $this->ratingsCat->get($ur->ratingsCatId);


                if ($user) {
                    $ur->user = $user;
                }
                if ($ratedUser) {
                    $ur->ratedByUser = $ratedUser;
                }
                if ($ratingsCategory) {
                    $ur->ratingsCategory = $ratingsCategory;
                    $rtCat = $this->category->get($ratingsCategory->catId);
                    if ($rtCat) {
                        $ratingsCategory->category = $rtCat;
                    }
                } else {

                    $ur->ratingsCategory = null;
                }

            }

            $this->response($userRatings, REST_Controller::HTTP_OK);
        } else {
            $this->response("No Content Found", REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function getUserRatingsSummaryByCat_get()
    {
        $this->isAuth();
        $id = $this->get("userId");
        if ($id == 0 or is_null($id)) {
            $this->response("Invalid Request", REST_Controller::HTTP_BAD_REQUEST);
        }
        $userRatings = $this->userRatings->getUserRatingsSummaryByUserId($id);
        if ($userRatings) {
            $this->response($userRatings, REST_Controller::HTTP_OK);
        } else {
            $this->response("Invalid Request", REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function addOrUpdateUserRatings_post()
    {
        $this->isAuth();
        try {

            $userRatings = $this->request->body;
            if (is_null($userRatings)) {
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
            }
            if (!array_key_exists("ratings", $userRatings)) {
                $this->response("Please Provide rating", REST_Controller::HTTP_BAD_REQUEST);
            }
            $res = false;
            if ($userRatings['userRatingsId'] == 0) {
                $res = $this->userRatings->insert($userRatings);
            } else {
                $res = $this->userRatings->update($userRatings);
            }
            if ($res) {
                $this->response("Ratings Added ", REST_Controller::HTTP_CREATED);
            } else {
                $this->response("Failed", REST_Controller::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            $this->response($e, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            log_message("Ratings", $e);
        }
    }


}