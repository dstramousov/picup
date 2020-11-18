<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller']	= "defaultfrontend";
$route['404_override']			= 'pagenotfound';

$route['home']	= "defaultfrontend/home";
$route['index']	= "defaultfrontend/index";

$route['about']		= "defaultfrontend/about";
$route['rules']		= "defaultfrontend/rules";
$route['contacts']	= "defaultfrontend/contacts";
$route['linkus']	= "defaultfrontend/linkus";
$route['abuse']		= "defaultfrontend/abuse";

$route['downloadphoto/(.*)']	= "defaultfrontend/downloadPhoto/$1";
$route['downloadgalallery/(.*)']	= "defaultfrontend/downloadGallery/$1";

$route['image/(.*)']	= "defaultfrontend/preShowLogic/$1";
$route['image']			= "defaultfrontend/preShowLogic";
$route['tryupload']		= "defaultfrontend/tryupload";

$route['deletefile/(.*)']		= "defaultfrontend/deleteFile/$1";
$route['deletefile']		= "defaultfrontend/index";

$route['trylogin']		= "defaultfrontend/trylogin";
$route['logout']		= "defaultfrontend/logout";

$route['register']		= "defaultfrontend/register";
$route['tryregister']	= "defaultfrontend/tryregister";


################  PRIVATE CONTROLLER ##################
#$route['proom']			    = "privatefrontend/proom";
$route['myprofile']		    = "privatefrontend/myProfile";
$route['myprofile/settings'] = "privatefrontend/profileSettings";
$route['myprofile/geosettings'] = "privatefrontend/geoSettings";

$route['myplaces']		    = "privatefrontend/myplaces";
$route['myphoto']		    = "privatefrontend/myphoto";
$route['myfriends']		    = "privatefrontend/myfriends";
$route['mynews']		    = "privatefrontend/mynews";
$route['mymessages']		= "privatefrontend/mymessages";

$route['managegallery']		= "c/managegallery";
$route['tryaddgallery']		= "privatefrontend/tryaddgallery";
$route['pdata']				= "privatefrontend/pdata";
$route['addgallery']		= "privatefrontend/addgallery";

$route['managephoto']		= "privatefrontend/manageUserPhoto";

$route['managesocial']		= "privatefrontend/manageSocialAccounts";

$route['showstatistics']	= "privatefrontend/usersStatistics";


$route['gallery/(.*)']	= "defaultfrontend/preShowLogicGallery/$1";
$route['gallery(.*)']	= "defaultfrontend/preShowLogicGallery/$1";

$route['editgallery/(.*)']	= "privatefrontend/editGallery/$1";
$route['editgallery(.*)']	= "privatefrontend/editGallery/$1";

$route['deletegallery/(.*)']	= "privatefrontend/deleteGallery/$1";
$route['deletegallery(.*)']		= "privatefrontend/deleteGallery/$1";

$route['savegallery']		= "privatefrontend/saveGallery";

$route['editphoto/(.*)']		= "privatefrontend/editPhoto/$1";
$route['editphoto(.*)']			= "privatefrontend/editPhoto/$1";
$route['savephoto']				= "privatefrontend/savePhotoInformation";

$route['addphoto/(.*)']			= "privatefrontend/addPhotoPage/$1";
$route['addphoto(.*)']			= "privatefrontend/addPhotoPage/$1";
$route['addphoto']			= "privatefrontend/addPhotoPage";

$route['deletephoto/(.*)']		= "privatefrontend/deletePhoto/$1";
$route['deletephoto(.*)']		= "privatefrontend/deletePhoto/$1";

$route['deletecomment/(.*)']		= "privatefrontend/deleteComment/$1";

$route['user/(.*)']	= "defaultfrontend/getUsersGallery/$1";
$route['user(.*)']	= "defaultfrontend/getUsersGallery/$1";

// ajax methods 
$route['getgallerylist']	= "privatefrontend/getGalleryList";


// static pages
$route['aboutus']		= "staticfrontend/aboutUs";
$route['privacy']		= "staticfrontend/privacyPolicy";
$route['blog']			= "staticfrontend/blog";
$route['adverstising']	= "staticfrontend/adverstising";
$route['contact']		= "staticfrontend/contactUS";
$route['sitetour']		= "staticfrontend/siteTour";


$route['code/(.*)/(.*)']	= "defaultfrontend/bb/$1/$2";
$route['code/(.*)']	= "defaultfrontend/bb/$1/small";


/////////////////////////////////// ADMIN PART ///////////////////////////////////
$route['admin/googlemapapi']			= "admin/googleMapApi";
$route['admin/googlemapapi/search']		= "admin/googleMapApiSearch";
$route['admin/gpsadd']					= "admin/gpsAddForm";
$route['admin/gpsadd2']					= "admin/gpsAddFormApi";
$route['admin/gpsaddsubmit']			= "admin/gpsAddFormSubmit";
$route['admin/showcoord/(.*)/(.*)']		= "admin/showCoordinatesInToMap/$1/$2";
$route['admin/']						= "admin/";
$route['admin/usermapcheckout']			= "admin/userMapCheckout";
$route['admin/getuserscheckoutbyinterval/(.*)']			= "admin/userMapCheckoutByInterval/$1";

// admin places 
$route['admin/savenewplace(.*)']			= "admin/savePlace/$1";
$route['admin/check/placemap']				= "admin/ShowPlacesOnTheMap";
$route['admin/getplacesbytype/(.*)']		= "admin/getPlacesByType/$1";
$route['admin/place/addfrommap']			= "admin/addPlaceFromMap";

// admin news
$route['admin/news/inmap']			= "admin/getNewsInMap";

$route['admin/view']					= "admin/mainViewer";
$route['admin/view/(.*)']	= "admin/mainViewer/$1";
$route['admin/view/(.*)/(.*)']			= "admin/mainViewer/$1/$2";

$route['admin/edit/(.*)']		= "admin/mainEditor/$1";
$route['admin/edit/(.*)/(.*)']	= "admin/mainEditor/$1";

//////////////////////////////////////////////////////////////////////////////////

/* End of file routes.php */
/* Location: ./application/config/routes.php */