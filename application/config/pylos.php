<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Pylos App Constants
|--------------------------------------------------------------------------
|
| Stuff we want to define before we begin. Keep the info here secret!
|
*/
$config['pylos']['ORIGIN_ACCOUNT'] = getenv('BUILDER_GITHUB_ACCOUNT');
$config['pylos']['ORIGIN_REPO'] = getenv('BUILDER_GITHUB_REPO');
$config['pylos']['API_ID'] = getenv('BUILDER_GITHUB_APPID');
$config['pylos']['API_SECRET'] = getenv('BUILDER_GITHUB_APPSECRET');
$config['pylos']['AUTH_ARGS'] = '?client_id='.$config['pylos']['API_ID'].'&client_secret='.$config['pylos']['API_SECRET'];
$config['pylos']['sitename'] = getenv('BUILDER_PYLOS_SITENAME');
$config['pylos']['app']['endpoint'] = getenv('BUILDER_PYLOS_ENDPOINT');
$config['pylos']['curl']['nexus']['fresh'] = 86400;
$config['pylos']['curl']['nexus']['baseurl'] = getenv('BUILDER_NEXUS_ENDPOINT');
$config['pylos']['curl']['nexus']['httpheader'] = array(
    "Accept: */*",
    "Authorization: Basic ".getenv('BUILDER_NEXUS_AUTH'),
    "Cache-Control: no-cache",
    "Connection: keep-alive",
    "Postman-Token: 96090561-8819-4636-ab1e-45a2ea4cd085,6d756742-3bfd-4fcc-97f3-e0b3701e45db",
    "User-Agent: PostmanRuntime/7.11.0",
    "accept-encoding: gzip, deflate",
    "cache-control: no-cache",
    "referer: ".getenv('BUILDER_NEXUS_ENDPOINT'));

/* End of file pylos.php */
/* Location: ./application/config/pylos.php */