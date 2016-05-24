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

$route['default_controller'] = "welcome";
$route['404_override'] = '';


$route['api_is_live'] = 'api/is_live/index';
$route['api_get_user_notifications'] = 'api/get_user_notifications/index';


$route['api_register'] = 'api/register/index';
$route['api_register_validation'] = 'api/register/validation';

$route['api_set_location'] = 'api/set_location/index';
$route['api_set_location_test'] = 'api/set_location_test/index';

$route['api_ask_match'] = 'api/match_request/index';
$route['api_set_user_profile'] = 'api/set_user_profile/index';


$route['api_set_contacts'] = 'api/set_contacts/index';

$route['api_get_all_users_locations'] = 'api/get_all_users_locations/index';
$route['api_get_all_users_locations_plus'] = 'api/get_all_users_locations_plus/index';
$route['api_get_all_users_routes_plus'] = 'api/get_all_users_routes_plus/index';
$route['api_get_users_distanse_and_zooz_sum'] = 'api/get_users_distanse_and_zooz_sum/index';


$route['api_set_friend_recommend'] = 'api/set_friend_recommend/index';
$route['api_set_friend_recommend_validation'] = 'api/set_friend_recommend/validation';
$route['api_get_screen_texts'] = 'api/get_general_info/get_screen_texts/get_screen_texts';
$route['api_get_user_key_data'] = 'api/get_user_info/get_user_key_data';
$route['api_set_user_pk'] = 'api/set_user_pk/index';


$route['api_get_user_stat_data'] = 'api/get_user_info/get_user_stat_data';
$route['api_get_user_stat_data_miners'] = 'api/get_user_info/get_user_stat_data_miners';
$route['api_get_user_stat_data_mined_distance'] = 'api/get_user_info/get_user_stat_data_mined_distance';





$route['api_get_user_contact_data'] = 'api/get_user_info/get_contacts_data';
$route['api_get_users_location_near_me'] = 'api/get_user_info/get_users_location_near_me';

$route['api_get_recommendation_data'] = 'api/get_user_info/get_recommendation_data';

$route['api_set_client_exception'] = 'api/set_client_exception/index';




$route['download'] = 'download';
$route['download_android'] = 'lbm-debug.apk';

$route['legal_and_privacy'] = 'web_app/legal_and_privacy';




$route['api_get_blockchain_transactions'] = 'api/blockchain/get_blockchain_transactions';


$route['default_controller'] = 'web_app/home/index';

$route['batch_calc_stat_users/(:any)'] = 'batch/calc_stat_users/stat_users/$1';


$route['admin_login'] = 'admin/admin_login';
$route['admin_menu'] = 'admin/admin_menu';

$route['admin_edit_client_texts'] = 'admin/admin_edit_client_texts';
$route['ajax_admin_edit_client_texts'] = 'admin/admin_edit_client_texts/ajax_set_texts';

$route['admin_edit_client_general_parms'] = 'admin/admin_edit_client_general_parms';
$route['ajax_admin_edit_client_general_parms'] = 'admin/admin_edit_client_general_parms/ajax_set_texts';



$route['admin_show_client_push_messages'] = 'admin/admin_show_client_push_messages';
$route['ajax_admin_show_client_push_messages'] = 'admin/admin_show_client_push_messages/ajax_edit_messages';
$route['ajax_admin_number_of_users_per_country'] = 'admin/admin_number_of_users_per_country/ajax_edit_messages';



$route['admin_show_client_issues'] = 'admin/admin_show_client_issues';
$route['admin_show_suspicious_users'] = 'admin/admin_show_suspicious_users';

$route['admin_show_client_locations'] = 'admin/admin_show_client_locations';


$route['admin_logoff'] = 'admin/admin_logoff';

$route['admin_fix_duplicate_payload'] = 'admin/admin_fix_duplicate_payload';
$route['admin_number_of_users_per_country'] = 'admin/admin_number_of_users_per_country';




$route['client_report_issue/(:num)'] = 'web_app/client_report_issue/index/$1';
$route['client_report_issue'] = 'web_app/client_report_issue/index/0';
$route['ajax_client_report_issue'] = 'web_app/client_report_issue/ajax_client_report_issue';

$route['twilio_callback'] = 'api/twilio_callback';

$route['asdfasdfasdfa32hgjhhgjssaj677jn'] = 'asdfasdfasdfa32hgjhhgjssaj677jn';




/* End of file routes.php */
/* Location: ./application/config/routes.php */
