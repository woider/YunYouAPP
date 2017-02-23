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
$route['default_controller'] = 'main';
$route['404_override'] = 'main';
$route['translate_uri_dashes'] = FALSE;

/* 登录页面 */
$route['login'] = 'login/index';
$route['login/auth/(:num)']['GET'] = 'login/authenticate/$1';
$route['login/sms/(:num)']['GET'] = 'login/send_validate_code/$1';

/* 引导页面 */
$route['guide'] = 'guide/index';
$route['guide/submit']['POST'] = 'guide/submit';

/* 导航工具 */
$route['navbar'] = 'navbar/index';
$route['navbar/search']['GET'] = 'navbar/search';

/* 云游主页 */
$route['main'] = 'main/index';
$route['main/random'] = 'main/random';

/* 景点详情 */
$route['detail/(:num)'] = 'detail/index/$1';
$route['detail/reviews']['GET'] = 'detail/reviews';
$route['detail/commend']['POST'] = 'detail/commend';

/* 评价讨论 */
$route['discuss/(:num)'] = 'discuss/index/$1';
$route['discuss/submit']['POST'] = 'discuss/submit';
$route['discuss/comment']['GET'] = 'discuss/comment';
$route['discuss/approve']['POST'] = 'discuss/approve';
$route['discuss/opinion']['POST'] = 'discuss/opinion';

/* 用户主页 */
$route['home/(:num)'] = 'home/index/$1';
$route['home/scenery']['GET'] = 'home/scenery';

/* 资料设置 */
$route['setup'] = 'setup/index';
$route['setup/quit']['POST'] = 'setup/sign_out';
$route['setup/upload']['POST'] = 'setup/upload';
$route['setup/submit']['POST'] = 'setup/submit';

/* 编辑景点 */
$route['scenic/(:num)'] = 'scenic/index/$1';
$route['scenic/upload']['POST'] = 'scenic/upload';
$route['scenic/submit/(:num)']['POST'] = 'scenic/submit/$1';

/* 评价景点 */
$route['assess/(:num)'] = 'assess/index/$1';
$route['assess/upload']['POST'] = 'assess/upload';
$route['assess/submit/(:num)']['POST'] = 'assess/submit/$1';
