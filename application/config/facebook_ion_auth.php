<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Settings.
| -------------------------------------------------------------------------
*/
$config['app_id'] 		= getenv('BUILDER_FB_APPID'); 		// Your app id
$config['app_secret'] 	= getenv('BUILDER_FB_APPSECRET'); 		// Your app secret key
$config['app_salt'] 	= getenv('BUILDER_FB_SALT'); 		// Dummy login salt
$config['scope'] 		= 'email, public_profile'; 	// custom permissions check - http://developers.facebook.com/docs/reference/login/#permissions
$config['redirect_uri'] = site_url('marksayshi'); 		// url to redirect back from facebook. our router maps to this
$config['fields']       = 'id,first_name,last_name,email'; // fields to retrieve; if set to '', default is "id,first_name,last_name"