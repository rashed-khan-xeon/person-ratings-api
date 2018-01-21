<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'HomeController';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
//For version- v1
$route['api/v1/user-signup'] = 'api/LoginController/accountCreate';
$route['api/v1/user-login'] = 'api/LoginController/authorization';
$route['api/v1/users'] = 'api/UserController/users';
$route['api/v1/user-details'] = 'api/UserController/userDetails';
$route['api/v1/search-users'] = 'api/UserController/searchUser';
$route['api/v1/user-add-or-update'] = 'api/UserController/addOrUpdateUsers';
$route['api/v1/user-upload-image'] = 'api/UserController/uploadUserImage';
$route['api/v1/user-settings-update'] = 'api/UserController/addUserSettings';
$route['api/v1/user-all-ratings'] = 'api/UserRatingsController/getUserAllRatings';
$route['api/v1/user-average-ratings-by-cat'] = 'api/UserRatingsController/getUserRatingsSummaryByCat';
$route['api/v1/user-ratings-add-or-update'] = 'api/UserRatingsController/addOrUpdateUserRatings';
$route['api/v1/ratings-category-add-or-update'] = 'api/RatingsCategory/addOrUpdateRatingsCategory';
$route['api/v1/user-ratings-category'] = 'api/RatingsCategory/getUserRatingsCategory';
$route['api/v1/user-review'] = 'api/UserReviewController/getUserReview';
$route['api/v1/user-reviews'] = 'api/UserReviewController/getAllUserReview';
$route['api/v1/user-reviews-by-user-id'] = 'api/UserReviewController/getUserReviewByUserId';
$route['api/v1/add-or-update-user-review'] = 'api/UserReviewController/addOrUpdateUserReview';
$route['api/v1/add-or-update-category'] = 'api/CategoryController/addOrUpdateCategory';
$route['api/v1/category'] = 'api/CategoryController/getCategory';
$route['api/v1/categories'] = 'api/CategoryController/getAllCategory';
$route['api/v1/categories-by-user-type-id'] = 'api/CategoryController/getCategoriesByUserTypeId';
$route['api/v1/add-or-update-user-type'] = 'api/UserTypeController/addOrUpdateUserType';
$route['api/v1/user-type'] = 'api/UserTypeController/getUserType';
$route['api/v1/user-types'] = 'api/UserTypeController/getAllUserType';