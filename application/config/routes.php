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

/*
| -------------------------------------------------------------------------
| Builder Overrides
| -------------------------------------------------------------------------
 */

// Authentication
$route['marksayshi'] = 'auth/facebookresponse';
$route['auth/saml/logout'] = 'auth/saml_slo';
$route['auth/saml/(:any)'] = 'auth/saml_$1';
$route['auth/saml'] = 'auth/saml/login';
$route['auth/(:any)'] = 'auth/$1';
$route['auth'] = 'auth/index';

$route['api/(:any)/(:any)/(:any)/(:any)'] = 'api/$1/$2/$3/$4'; // not sure we'll need this but oh well.
$route['api/(:any)/(:any)/(:any)'] = 'api/$1/$2/$3'; // not sure we'll need this but oh well.
$route['api/(:any)/(:any)'] = 'api/$1/$2'; // not sure we'll need this either, since data will come over post
$route['api/(:any)'] = 'api/$1';
$route['api'] = 'api/index';

/*
| -------------------------------------------------------------------------
| Builder App
| -------------------------------------------------------------------------
 */

$route['blog/(:any)'] = 'content/blog/$1'; // flexible post titles are nice
$route['page/(:any)'] = 'pages/view/$1'; // flexible post titles are nice

$route['field/(:any)'] = 'content/taxonomy/$1'; // flexible post titles are nice
$route['theme/(:any)'] = 'content/taxonomy/$1'; // flexible post titles are nice
$route['collection/(:any)'] = 'content/taxonomy/$1'; // flexible post titles are nice
$route['topic/(:any)'] = 'content/taxonomy/$1'; // flexible post titles are nice
$route['concept/(:any)'] = 'content/definition/$1'; // flexible post titles are nice
$route['term/(:any)'] = 'content/definition/$1'; // flexible post titles are nice
$route['thinker/(:any)'] = 'content/definition/$1'; // flexible post titles are nice

$route['feed/(:any)'] = 'content/feed/$1'; // feed listing pages by type

$route['ski/(:any)/(:any)/(:any)/(:any)'] = 'ski/$1/$2/$3/$4'; // not sure we'll need this but oh well.
$route['ski/(:any)/(:any)/(:any)'] = 'ski/$1/$2/$3'; // not sure we'll need this but oh well.
$route['ski/(:any)/(:any)'] = 'ski/$1/$2'; // not sure we'll need this either, since data will come over post
$route['ski/(:any)'] = 'ski/$1';
$route['ski'] = 'ski/index';
$route['trips/(:any)/api'] = 'trips/api/(:any)';
$route['trips/(:any)'] = 'trips/tripsingle/(:any)';
$route['trips'] = 'trips/index';

/*
| -------------------------------------------------------------------------
| Pylos
| -------------------------------------------------------------------------
 */

$route['pylos/tags'] = 'pylos/meta/tag/all';
$route['pylos/tags/(:any)'] = 'pylos/meta/tag/$1';
$route['pylos/dependencies'] = 'pylos/meta/dependency/all';
$route['pylos/dependencies/(:any)'] = 'pylos/meta/dependency/$1';
$route['pylos/projects'] = 'pylos/meta/project/all';
$route['pylos/projects/(:any)'] = 'pylos/meta/project/$1';
$route['pylos/themes'] = 'pylos/taxonomy';
$route['pylos/themes/(:any)'] = 'pylos/taxonomy/$1';

$route['pylos/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'pylos/$1/$2/$3/$4/$5/$6'; // not sure we'll need this but oh well.
$route['pylos/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'pylos/$1/$2/$3/$4/$5'; // not sure we'll need this but oh well.
$route['pylos/(:any)/(:any)/(:any)/(:any)'] = 'pylos/$1/$2/$3/$4'; // not sure we'll need this but oh well.
$route['pylos/(:any)/(:any)/(:any)'] = 'pylos/$1/$2/$3'; // not sure we'll need this but oh well.
$route['pylos/(:any)/(:any)'] = 'pylos/$1/$2'; // not sure we'll need this either, since data will come over post
$route['pylos/(:any)'] = 'pylos/$1';
$route['pylos'] = 'pylos/index';

$route['designexplorer/(:any)/(:any)/(:any)/(:any)'] = 'designexplorer/$1/$2/$3/$4'; // not sure we'll need this but oh well.
$route['designexplorer/(:any)/(:any)/(:any)'] = 'designexplorer/$1/$2/$3'; // not sure we'll need this but oh well.
$route['designexplorer/(:any)/(:any)'] = 'designexplorer/$1/$2'; // not sure we'll need this either, since data will come over post
$route['designexplorer/(:any)'] = 'designexplorer/$1';
$route['designexplorer'] = 'designexplorer/index';

$route['ppt/(:any)/(:any)/(:any)/(:any)'] = 'ppt/view/$1/$2/$3/$4'; // not sure we'll need this either, since data will come over post
$route['ppt/(:any)/(:any)/(:any)'] = 'ppt/view/$1/$2/$3'; // not sure we'll need this either, since data will come over post
$route['ppt/(:any)/(:any)'] = 'ppt/view/$1/$2'; // not sure we'll need this either, since data will come over post
$route['ppt/(:any)'] = 'ppt/view/$1';
$route['ppt'] = 'ppt/index';

/*
| -------------------------------------------------------------------------
| Builder Shared Routes
| -------------------------------------------------------------------------
 */

$route['app/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = '$1/$2/$3/$4/$5/$6/$7'; // not sure we'll need this but oh well.
$route['app/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = '$1/$2/$3/$4/$5/$6'; // not sure we'll need this but oh well.
$route['app/(:any)/(:any)/(:any)/(:any)/(:any)'] = '$1/$2/$3/$4/$5'; // not sure we'll need this but oh well.
$route['app/(:any)/(:any)/(:any)/(:any)'] = '$1/$2/$3/$4'; // not sure we'll need this but oh well.
$route['app/(:any)/(:any)/(:any)'] = '$1/$2/$3'; // not sure we'll need this but oh well.
$route['app/(:any)/(:any)'] = '$1/$2'; // not sure we'll need this either, since data will come over post
$route['app/(:any)'] = '$1/index';
$route['app'] = 'api/index';

$route['(:any)/(:any)'] = 'content/$1/$2'; // flexible post titles are nice

// Error handling and defaults
$route['default_controller'] = 'pages/view';
$route['(:any)'] = 'pages/view/$1';
$route['404_override'] = '';

//$route['default_controller'] = 'welcome';
//$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
