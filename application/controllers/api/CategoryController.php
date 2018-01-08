<?php

/**
 * Created by PhpStorm.
 * User: arifk
 * Date: 30.12.17
 * Time: 11:15 PM
 */
require "Base_Api_Controller.php";

class CategoryController extends Base_Api_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model("CategoryModel", "category");
        $this->load->model("UserTypeModel", "userType");
    }

    public function addOrUpdateCategory_post()
    {
        $this->isAuth();
        $cat = $this->request->body;
        if ($cat == null) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $res = false;
        if ($cat->catId == 0) {
            $res = $this->category->insert($cat);
        } else {
            $res = $this->category->update($cat);
        }
        if ($res) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response("created", REST_Controller::HTTP_CREATED);
        }
    }

    public function getCategory_get()
    {
        $this->isAuth();
        $catId = $this->get("catId");
        if (is_null($catId) or $catId == 0) {
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST);
        }
        $category = $this->category->get($catId);

        $type = $this->userType->get($catId);
        $category->type = $type;

        if ($category == null) {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        } else {
            $this->response($category, REST_Controller::HTTP_OK);
        }
    }

    public function getAllCategory_get()
    {
        $this->isAuth();
        $categories = $this->category->getAll();
        foreach ($categories as $category) {
            $type = $this->userType->get($category->catId);
            $category->type = $type;
        }

        if ($categories == null) {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        } else {
            $this->response($categories, REST_Controller::HTTP_OK);
        }
    }

    public function getCategoriesByUserTypeId_get()
    {
        $this->isAuth();
        $userTypeId = $this->get("userTypeId");
      //  $this->response($userTypeId,REST_Controller::HTTP_OK);
        if (empty($userTypeId) || is_null($userTypeId) || $userTypeId == 0) {
            $this->response("Invalid Request", REST_Controller::HTTP_BAD_REQUEST);
        }
        $categories = $this->category->getAll();
        foreach ($categories as $category) {
            $type = $this->userType->get($category->catId);
            $category->type = $type;
        }

        if ($categories == null) {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        } else {
            $this->response($categories, REST_Controller::HTTP_OK);
        }
    }

}