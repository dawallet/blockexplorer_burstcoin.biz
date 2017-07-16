<?php
class Language {
        /**
         * Filtert den Sprachcode aus einem Pfad
         * @param string $route Pfad
         * @return string gefilteter Pfad
         */
        public static function filterLanguageCode($route) {
                $routeLangCode = substr($route, 1, 2);
                
                $langCodes = explode(',', LANGUAGES);
                foreach ($langCodes AS $langCode) {
                        if ($routeLangCode == $langCode) {
                                return substr($route, 3);
                        }
                }
                
                return $route;
        }
        
        /**
         * Lädt Sprachvariablen innerhalb des Frameworks.
         * @param string $token Name des Tokens
         * @return string Liefert Token oder Sprachvariable zurück
         */
        public function getToken($token) {
                include DIR_SYSTEM.DS.'app'.DS.'language'.DS.$_SESSION['lang'].DS.'framework.php';
                
                if (isset($i18nData[$token])) {
                        return $i18nData[$token];  
                }
                
                return $token;
        }        
}

/**
 * Ersetzt Platzhalter durch Sprachvariablen oder erstellt neuen Eintrag in der gloablen Sprachdatei falls Token nicht gefunden wurde.
 * @global array $i18nData Enthält Einträge aus den Sprachdateien
 * @param array $token Name des Tokens
 * @return string Liefert Token oder Sprachvariable zurück
 */
function i18n_substitute_text_token($token, $saveNonExistTokens = false)  {
        global $i18nData;
                
        $token = trim($token[1]);
        // Token gefunden
        if (isset($i18nData[$token])) {
                return $i18nData[$token];
        // Erstelle Eintrag in Sprachdatei wenn Token nicht gefunden wurde
        } elseif ($saveNonExistTokens === true) {
                $file = DIR_SYSTEM.DS.'app'.DS.'language'.DS.$_SESSION['lang'].DS.'global.php';
                
                if (file_exists($file) && is_writeable($file)) {
                        $s = file_get_contents($file);
                        eval($s);
                } else {
                        return $token;
                }
                
                if (isset($i18nData[$token])) {
                        return $i18nData[$token];  
                }
                
                $i18nData[$token] = $token;       
                $s = '$i18nData'."['".$token."']" . " = '".$token."'; // neuer Token".PHP_EOL;
                if ($handle = fopen($file, 'a')) {                
                        fwrite($handle, $s);
                        fclose($handle);
                }
                
                return $token;
        }
}

/**
 * Funktion für Smarty Outputfilter.
 * @global array $i18nData Enthält Einträge aus den Sprachdateien
 * @global objekt $controller Macht den Controller verfügbar um passende Sprachdatei für das Template zu laden
 * @param string $tpl_output Smarty Output
 * @param objekt $smarty Smarty-Objekt
 * @return array Übergibt Sprachvariablen an die Funktion i18n_substitute_text_token()
 */
function i18n_substitute_text($tpl_output, &$smarty) {
        global $i18nData, $controller, $router;
        
        if (!isset($_SESSION['lang'])) { $_SESSION['lang'] = LANGUAGE_DEFAULT; }
        
        // Lade globale Sprachdatei
        $file = DIR_SYSTEM.DS.'app'.DS.'language'.DS.$_SESSION['lang'].DS.'global.php';
        if (file_exists($file) && is_readable($file)) {
                $s = file_get_contents($file);
                eval($s);
        }
        
        // Lade Sprachdatei für Controller
        $file = DIR_SYSTEM.DS.'app'.DS.'language'.DS.$_SESSION['lang'].DS.$router->getController().'.php';
        if (file_exists($file) && is_readable($file)) {
                $s = file_get_contents($file);
                eval($s);
        }
        
        // Lade Sprachdatei für Template
        $file = DIR_SYSTEM.DS.'app'.DS.'language'.DS.$_SESSION['lang'].DS.$router->getController().DS.$router->getClass().'.php';
        if (file_exists($file) && is_readable($file)) {
                $s = file_get_contents($file);
                eval($s);
        }
        
        return preg_replace_callback('/@@(.+?)@@/', 'i18n_substitute_text_token', $tpl_output);       
}