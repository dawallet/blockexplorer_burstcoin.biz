<?php
class ErrorHandler {
        /**
         * Setzt den Standard PHP Error Handler auf einen eigenen Error Handler.
         */
        public function __construct() {
                set_error_handler('error_handler');
        }
        
        /**
         * Legt einen Eintrag in der Log-Datei an.
         * @param string $type Fehler-Typ: database|internal|php|request
         * @param string $message Fehlertext
         * @return boolean Gibt true zurück bei erfolgreichen Eintrag in die Log oder leitet bei internal Fehler auf die Error-Seite des Frameworks
         */
        public static function writeLog($type, $message) {
                // Ausnahme für Fehlermeldungen die nicht geloggt werden sollen
                if ($type == "database" AND strpos($message, 'Duplicate entry') !== FALSE) {                        
                } else {
                        $handle = fopen(DIR_SYSTEM.DS.'app'.DS.'log'.DS.$type.'.txt', 'a');
                        fwrite($handle, date('d.m.y-H:i:s').' '.$message."\r\n");                
                        fclose($handle);
                }
                
                if ($type == "internal") {
                        header("Location: ".HTTP_ROOT."error");
                        exit();
                }
                
                return true;
        }
}

/**
 * Ersetzt den Standard PHP Error Handler durch eigene Funktion.
 * @param type $errno Fehlernummer
 * @param type $errstr Fehlermeldung
 * @param type $errfile Datei in welcher der Fehler aufgetreten ist
 * @param integer $errline Zeile des Fehlers
 * @return boolean Gibt true zurück
 */
function error_handler($errno, $errstr, $errfile, $errline) {	
        switch ($errno) {
                case E_NOTICE:
                case E_USER_NOTICE:
                        $error = 'Notice';
                        break;
                case E_WARNING:
                case E_USER_WARNING:
                        $error = 'Warning';
                        break;
                case E_ERROR:
                case E_USER_ERROR:
                        $error = 'Fatal Error';
                        break;
                default:
                        $error = 'Unknown';
                        break;
        }
        
        // Bestimme Fehlermeldungen nicht loggen
        if (strpos($errstr, 'https://c-cex.com/t/burst-btc.json') !== false) {
                return true;
        } elseif (strpos($errstr, 'http://api.bitcoinaverage.com/ticker/global/EUR/') !== false) {
                return true;
        } elseif (strpos($errstr, 'http://api.bitcoinaverage.com/ticker/global/USD/') !== false) {
                return true;
        }
        
        // Logge den Fehler
        $errMsg = $error.': '.$errstr. ' in '.$errfile.' on line '.$errline;
        ErrorHandler::writeLog('php', $errMsg);
        
        return true;
}
