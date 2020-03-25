<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Create an __autoload function
// (can conflicts other autoloaders)
// http://php.net/manual/en/language.oop5.autoload.php

$libDir = __DIR__ . '/lib/Saml2/';
$folderInfo = scandir($libDir);

foreach ($folderInfo as $element) {
    if (is_file($libDir.$element) && (substr($element, -4) === '.php')) {
        include_once $libDir.$element;
        //break;
    }
}
