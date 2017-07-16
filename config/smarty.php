<?php
// Smarty
if (isset($smarty)) {
        $smarty->setTemplateDir(DIR_SYSTEM.DS.'app'.DS.'view');
        $smarty->setCompileDir(DIR_SYSTEM.DS.'app'.DS.'view_c');
        $smarty->setCacheDir(DIR_SYSTEM.DS.'app'.DS.'cache');
        $smarty->error_reporting = E_ALL & ~E_NOTICE;
        $smarty->muteExpectedErrors();
        $smarty->force_compile = false;
        $smarty->debugging = false;
        $smarty->caching = false;
}
$lang = '';
if (LANGUAGE === true) {
        if (isset($_SESSION['lang'])) {
                $lang = $_SESSION['lang'].'/';    
        } else {
                $lang = LANGUAGE_DEFAULT.'/';
        }
        $i18nData = array();
        if (isset($smarty)) {
                $smarty->registerFilter('output', 'i18n_substitute_text');
        }
}
if (isset($smarty)) {
        $smarty->assign('httpUrl', HTTP_ROOT);
        $smarty->assign('httpRoot', HTTP_ROOT);
        $smarty->assign('httpMain', HTTP_ROOT);
}

// Internationalisierung: Datums-, Uhrzeit- und Zahlen-Formatierung
if (isset($_SESSION['lang']) AND $_SESSION['lang'] == "en") {
        define('i18nTime', 'h:ia');
        define('i18nDate', 'm-d-Y');
        define('i18nDatetime', 'm-d-Y - h:ia');
        define('i18nSepDecimal', '.');
        define('i18nSepThousand', ',');
        if (isset($smarty)) {
                $smarty->assign('i18nTime', i18nTime);
                $smarty->assign('i18nDate', i18nDate);
                $smarty->assign('i18nDatetime', i18nDatetime);
                $smarty->assign('i18nSepDecimal', i18nSepDecimal);
                $smarty->assign('i18nSepThousand', i18nSepThousand);                
        }
} else {
        define('i18nTime', 'H:i');
        define('i18nDate', 'd.m.Y');
        define('i18nDatetime', 'd.m.Y - H:i');
        define('i18nSepDecimal', ',');
        define('i18nSepThousand', '.');
        if (isset($smarty)) {
                $smarty->assign('i18nTime', i18nTime);
                $smarty->assign('i18nDate', i18nDate);
                $smarty->assign('i18nDatetime', i18nDatetime);
                $smarty->assign('i18nSepDecimal', i18nSepDecimal);
                $smarty->assign('i18nSepThousand', i18nSepThousand);                
        }
}