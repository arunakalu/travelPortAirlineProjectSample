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
$route['req1'] = "FirstRequestAirline/getOriginDestinationDetails";
$route['test1'] = "FirstRequestAirline/fetchResponse1";
$route['test2'] = "FirstRequestAirline/fetchUsingXpath";
$route['first1'] = "UAPI_airController/createFirstRequest";
$route['first2'] = "UAPI_airController/fetchFirstResponse";
$route['first3'] = "UAPI_airController/filterFlights";
$route['test3'] = "Sql_to_xmlController/index";
$route['test4'] = "Sql_to_xmlController/universalSqlXmalGenrarate";
$route['test5'] = "UAPI_airController/fetchResponse1UsingnewMethod";
$route['test6'] = "siteMapController/index";
$route['404_override'] = '';

//system
$route['response1'] = "Flight_booking_system_controller/fetch_first_response";
$route['response1option'] = "Flight_booking_system_controller/getOption_details";
$route['price'] = "Flight_booking_system_controller/priceRequestCreate";
$route['sitemapxml'] = "Sql_to_xmlController/sitemapXml";
$route['bread'] = "Sql_to_xmlController/callbreadScrum";

/* End of file routes.php */
/* Location: ./application/config/routes.php */