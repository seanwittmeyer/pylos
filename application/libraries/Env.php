<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

// file: application/libraries/Env.php
class Env
{
	public function __construct()
	{
		$path = (ENVIRONMENT == 'testing') ? '.env.production' : '.env.'.ENVIRONMENT;
		if (file_exists(FCPATH.$path)) {
			$dotenv = Dotenv\Dotenv::create(FCPATH,$path);
		} elseif (file_exists(FCPATH.'.env.')) {
			$dotenv = Dotenv\Dotenv::create(FCPATH);
		} else {
			header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
			echo '<b>Builder Error:</b> No environment config file available. <br>If setting up Builder/Pylos for the first time, check the .env.example in the application directory for instructions.';
			exit(1); // EXIT_ERROR
		}
		$dotenv->load();
	}
}
