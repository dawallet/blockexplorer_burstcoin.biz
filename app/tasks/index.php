<?php
// Konfiguration
require_once '/var/www/config/config.php';
set_time_limit(0);
ini_set('safe_mode', 0);
ini_set('max_execution_time', 0);
ini_set('open_basedir', DIR_SYSTEM);
require_once DIR_SYSTEM.DS.'config'.DS.'smarty.php';

// Error Handler
require_once DIR_SYSTEM.DS.'core'.DS.'engine'.DS.'ErrorHandler.php';

// Autoloader
spl_autoload_register(function ($class) {
        $phpExcelClass = str_replace('_', DS, $class);
        // nbCore Autoloader
        if (file_exists(DIR_SYSTEM.DS.'core'.DS.'engine'.DS.$class.'.php')) {
                require_once DIR_SYSTEM.DS.'core'.DS.'engine'.DS.$class.'.php';
        // PHPExcel Autoloader
        } elseif (file_exists(DIR_SYSTEM.DS.'core'.DS.'libs'.DS.'phpexcel'.DS.'Classes'.DS.$phpExcelClass.'.php')) {
                require DIR_SYSTEM.DS.'core'.DS.'libs'.DS.'phpexcel'.DS.'Classes'.DS.$phpExcelClass.'.php';
        } else {
                ErrorHandler::writeLog('internal', 'spl_autoload error: can\'t find class "'.$class.'" in '.DIR_SYSTEM.DS.'core'.DS.'engine'.DS);
                return false;
        }
});  

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Model
require_once DIR_SYSTEM.DS.'core'.DS.'engine'.DS.'Model.php';

// Library
require_once DIR_SYSTEM.DS.'core'.DS.'engine'.DS.'Library.php';

// Error Handler
$error = new ErrorHandler();
$registry->set('error', $error);

// Datenbank
$db = new Database();
$registry->set('db', $db);

// Validator
$validate = new Validator($db, null, $smarty = '');
$registry->set('validate', $validate);

// Request
$request = new Request();
$registry->set('request', $request);

// Session Handler
session_start();

// Internationalisierung
if (LANGUAGE === true) {
        $language = new Language();
        $registry->set('language', $language);
}

// CMD arguments
if (isset($argv)) {
        foreach ($argv as $arg) {
                $e = explode("=",$arg);
                if (count($e)==2) {
                        $_GET[$e[0]] = $e[1];
                } else {  
                        $_GET[$e[0]] = 0;
                }
        }
}

// Front
$controller = new Front($registry, $smarty = '');

// Controller
$controller->render('ControllerTaskManager', 'index');
