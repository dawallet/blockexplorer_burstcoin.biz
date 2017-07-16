<?php
// Konfiguration
require_once '../config/config.php';

// Error Handler
require_once DIR_SYSTEM.DS.'core'.DS.'engine'.DS.'ErrorHandler.php';

// Template Engine (Smarty)
require_once DIR_SYSTEM.DS.'core'.DS.'libs'.DS.'smarty'.DS.'libs'.DS.'Smarty.class.php';
$smarty = new Smarty();

// Autoloader
spl_autoload_register(function ($class) {
        $phpExcelClass = str_replace('_', DS, $class);
        // nbCore Autoloader
        if (file_exists(DIR_SYSTEM.DS.'core'.DS.'engine'.DS.$class.'.php')) {
                require_once DIR_SYSTEM.DS.'core'.DS.'engine'.DS.$class.'.php';
        // PHPExcel Autoloader
        } elseif (file_exists(DIR_SYSTEM.DS.'core'.DS.'libs'.DS.'phpexcel'.DS.'Classes'.DS.$phpExcelClass.'.php')) {
                require DIR_SYSTEM.DS.'core'.DS.'libs'.DS.'phpexcel'.DS.'Classes'.DS.$phpExcelClass.'.php';
        // PHPQRCode Autoloader
        } elseif ($class == "QRcode") {
                require DIR_SYSTEM.DS.'core'.DS.'libs'.DS.'phpqrcode'.DS.'qrlib.php';
        } else {
                ErrorHandler::writeLog('internal', 'spl_autoload error: can\'t find class "'.$class.'" in '.DIR_SYSTEM.DS.'core'.DS.'engine'.DS);
                return false;
        }
});  

// Registry
$registry = new Registry();
$registry->set('smarty', $smarty);

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

// Bootstrap
$bootstrap = new Bootstrap();
$registry->set('bootstrap', $bootstrap);

// Request
$request = new Request();
$registry->set('request', $request);

// Session Handler
$session = new Session();
$registry->set('session', $session);
require_once DIR_SYSTEM.DS.'config'.DS.'smarty.php';

// Internationalisierung
if (LANGUAGE === true) {
        $language = new Language();
        $registry->set('language', $language);
}

// Front
$controller = new Front($registry, $smarty);

// Router
$router = new Router($request->get, $db);
$registry->set('router', $router);

// Validator
$validate = new Validator($db, $router, $smarty);
$registry->set('validate', $validate);

// Controller
$controller->render('Controller'.$router->getController(), $router->getClass());

// Output
$controller->output();