<?php

/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: the configuration file for the APP
 * Updates here:
 */


/** Configuration Variables * */
if ($_SERVER['HTTP_HOST'] == 'localhost') { // or any other host
    /* Development enviornment */
    define('DEVELOPMENT_ENVIRONMENT', true);
    define('DB_NAME', '360_test');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DB_HOST', 'localhost');
    define('DB_PORT', '3306');
    define('REDIRECT_USER', 'index.php');
    define('DS', DIRECTORY_SEPARATOR);
    define('DIR_BASE', DS . basename(dirname(dirname(__FILE__))) . DS);
    define('DIR_ASSET', DIR_BASE . 'asset');
    define('DIR_LIB', DIR_BASE . 'lib');
    define('DIR_FILES', DIR_BASE . 'files');
    define('DIR_REPORTS', DIR_BASE . 'reports');
    define('DIR_FUNCTIONS', dirname(dirname(__FILE__)) . DS . 'lib' . DS . 'functions.php');
    define('VIEW_HEADER', dirname(dirname(__FILE__)) . DS . 'includes' . DS . 'header.php');
    define('VIEW_FOOTER', dirname(dirname(__FILE__)) . DS . 'includes' . DS . 'footer.php');
    define('PROCESS_FORM', htmlspecialchars($_SERVER['REQUEST_URI']));
    define('DIR_ERROR', dirname(dirname(__FILE__)) . DS . 'error' . DS . 'error.txt');
} 
else 
{
    // Production enviornment
    define('DB_NAME', 'db360school');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '!@#database#@!');
    define('DB_HOST', '173.194.249.86'); 
    define('DB_PORT', 3306);
    define('DB_SOCKET', '/cloudsql/probable-axon-144107:db360school');
    define('DEVELOPMENT_ENVIRONMENT', false);

   /* define('DEVELOPMENT_ENVIRONMENT', true);
    define('DB_NAME', '360_test');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '123456');
    define('DB_HOST', 'localhost');
    define('DB_PORT', '3306');
    define('DB_SOCKET', ''); */
        
    
    define('REDIRECT_USER', 'index.php');
    define('DS', DIRECTORY_SEPARATOR);
    define('DIR_BASE', DS. basename(dirname(dirname(__FILE__))) . DS);
    define('DIR_ASSET', DIR_BASE . 'asset');
    define('DIR_LIB', DIR_BASE . 'lib');
    define('DIR_FILES', DIR_BASE . 'files');
    define('DIR_REPORTS', DIR_BASE . 'reports');
    define('DIR_FUNCTIONS', dirname(dirname(__FILE__)) . DS . 'lib' . DS . 'functions.php');
    define('VIEW_HEADER', dirname(dirname(__FILE__)) . DS . 'includes' . DS . 'header.php');
    define('VIEW_FOOTER', dirname(dirname(__FILE__)) . DS . 'includes' . DS . 'footer.php');
    define('PROCESS_FORM', htmlspecialchars($_SERVER['REQUEST_URI']));
    define('DIR_ERROR', dirname(dirname(__FILE__)) . DS . 'error' . DS . 'error.txt');
}

define('STUDENT_IMG_PATH', $_SERVER["DOCUMENT_ROOT"] . DIR_ASSET . '/images/studentpicture/');
define('STUDENT_DOC_PATH', $_SERVER["DOCUMENT_ROOT"] . DIR_ASSET . '/images/document/');
define('INST_LOGO_IMG_PATH', $_SERVER["DOCUMENT_ROOT"] . DIR_ASSET . '/images/institute-logo/');
define('VEHICLE_IMG_PATH', $_SERVER["DOCUMENT_ROOT"] . DIR_ASSET . '/images/vehicles/');
define('DRIVER_IMG_PATH', $_SERVER["DOCUMENT_ROOT"] . DIR_ASSET . '/images/drivers/');
define('COOKIE_NAME', 'CAJ360LOGIN');
define('COOKIE_TIME', (86400 * 30 * 3)); // 3 days
define('ROW_PER_PAGE', 10);

if (DEVELOPMENT_ENVIRONMENT) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 'off');
    ini_set('log_errors', 'On');
}
