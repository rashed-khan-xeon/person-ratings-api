<?php

require "Base_Api_Controller.php";

class RatingsCategory extends Base_Api_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model("RatingsCategoryModel", "ratingsCat");
        $this->load->model("UserModel", "user");
        $this->load->model("CategoryModel", "category");
    }

    public function addOrUpdateRatingsCategory_post()
    {
        $this->isAuth();
        $ratingsCat = $this->request->body;
        if (is_null($ratingsCat)) {
            $this->response("Invalid Request", REST_Controller::HTTP_BAD_REQUEST);
        }
        $res = false;
        $isExits = $this->ratingsCat->getByUserIdCatId($ratingsCat['userId'], $ratingsCat['catId']);
        if (!$isExits) {
            $res = $this->ratingsCat->insert($ratingsCat);
        } else {

            if ($isExits->active == 0) {
                $res = true;
                $this->ratingsCat->activeRatingsCat($isExits->ratingsCatId);
            } else {
                $this->ratingsCat->inActiveRatingsCat($isExits->ratingsCatId);
                $res = true;
            }
        }
        if ($res) {
            $this->response("Updated", REST_Controller::HTTP_CREATED);
        } else {
            $this->response("Failed", REST_Controller::HTTP_BAD_REQUEST);
        }

    }

    public function getUserRatingsCategory_get()
    {
        $this->isAuth();
        $id = $this->get("userId");
        if (is_null($id) or $id == 0) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $ratingsCategory = $this->ratingsCat->getActiveRtCatByUserId($id);
        if (!empty($ratingsCategory)) {
            foreach ($ratingsCategory as $rtcat) {
                $cat = $this->category->get($rtcat->catId);
                $user = $this->user->get($rtcat->userId);
                if ($cat) {
                    $rtcat->category = $cat;
                } else {
                    $rtcat->category = null;
                }
                if ($user) {
                    $rtcat->user = $user;
                } else {
                    $rtcat->user = null;
                }
            }

            $this->response($ratingsCategory, REST_Controller::HTTP_OK);
        } else {
            $this->response(null, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function getRatingsCategory_get()
    {
        $this->isAuth();
        $id = $this->get("ratingsCatId");
        if (is_null($id) or $id == 0) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $ratingsCategory = $this->ratingsCat->get($id);
        if (!empty($ratingsCategory)) {

            $cat = $this->category->get($ratingsCategory->catId);
            $user = $this->user->get($ratingsCategory->userId);
            if ($cat) {
                $ratingsCategory->category = $cat;
            } else {
                $ratingsCategory->category = null;
            }
            if ($user) {
                $ratingsCategory->user = $user;
            } else {
                $ratingsCategory->user = null;
            }


            $this->response($ratingsCategory, REST_Controller::HTTP_OK);
        } else {
            $this->response(null, REST_Controller::HTTP_NOT_FOUND);
        }
    }


}