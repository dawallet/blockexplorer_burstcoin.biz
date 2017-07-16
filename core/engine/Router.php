<?php
class Router {
        protected $route = '/';
        protected $controller = 'Home';
        protected $class = 'index';        
	protected $db;
        
        /**
         * Überprüft ob der aufgerufene Pfad gültig ist und ließt den zugehörigen Controller/Klasse.
         * @param string $route Aufgerufener Pfad
         */
  	public function __construct($route, $db) {
                $this->db = $db;
                
                require_once DIR_SYSTEM.DS.'config'.DS.'routing.php';

                if (isset($route['p']) AND !empty($route['p'])) {
                        $this->route = preg_replace("![^A-Za-z0-9-/]!is", '', $route['p']);
                        
                        // Setze Standard-Pfad
                        if (empty($this->route)) {
                                $this->route = '/';
                        }
                        
                        // Entfernt den letzten Backslash ('/')
                        if (substr($this->route, -1) == '/' AND strlen($this->route) != 1) {
                                $this->route = substr($this->route, 0, -1);                                 
                        }

                        // Prüfe ob der Pfad existiert
                        if (array_key_exists($this->route, $routing) AND (!isset($routing[$this->route][2]) OR (isset($routing[$this->route][2]) AND $routing[$this->route][2] == $_SERVER['HTTP_HOST']))) {
                                $this->controller = $routing[$this->route][0];
                                
                                if (!empty($routing[$this->route][1])) {
                                    $this->class = $routing[$this->route][1];
                                            
                                }
                                
                                return true;
                        // Prüfe auf alternativen Pfad
                        } else {
                                $this->route = substr($this->route, 0, strrpos($this->route, '/')).'/(*)';

                                // Prüfe ob Parameter für diesen Pfad zulässig sind
                                if (array_key_exists($this->route, $routing) AND (!isset($routing[$this->route][2]) OR (isset($routing[$this->route][2]) AND $routing[$this->route][2] == $_SERVER['HTTP_HOST']))) {

                                        $this->controller = $routing[$this->route][0];

                                        if (!empty($routing[$this->route][1])) {
                                            $this->class = $routing[$this->route][1];

                                        }

                                        $getParameters = substr($route['p'], strrpos($route['p'], '/')+1);
                                        // Lese und setze Parameter
                                        if (strpos($getParameters, '-') === false) {
                                                $_GET['p0'] = $getParameters;
                                        } else {
                                                $getParameters = explode('-', $getParameters);
                                                $i = 0;
                                                foreach ($getParameters AS $getParams) {
                                                        $_GET['p'.$i] = $getParams;
                                                        $i++;
                                                }
                                                unset($i);
                                        }

                                        return true;
                                }
                                        
                                // Seite nicht gefunden
                                $this->notFound($route);
                        
                        }
                        
                        // Seite nicht gefunden
                        $this->notFound($route);
                        
                }
                
	}
	
        /**
         * Gibt den durch Client aufgerufen Pfad zurück.
         * @return string Name des Pfads
         */
  	public function getRoute() {
		return $this->route;
                
	}
	
        /**
         * Gibt den angesteuerten Controller zurück.
         * @return string Name des Controllers
         */
  	public function getController() {
		return $this->controller;
                
	}
	
        /**
         * Gibt die angesteuerte Klasse zurück.
         * @return string Name der Klasse
         */
  	public function getClass() {
		return $this->class;
                
	}
        
        /**
         * Leitet den Client weiter, wenn Seite/Pfad nicht gefunden wurde.
         */
        private function notFound($route) {                
                $this->ref = '';
                if (isset($_SERVER['HTTP_REFERER']) AND !empty($_SERVER['HTTP_REFERER'])) {
                        $this->ref = 'referer: '.$_SERVER['HTTP_REFERER'].' / ';
                }
                if (isset($_SERVER['HTTP_USER_AGENT'])) {
                        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
                } else {
                        $this->userAgent = '-';
                }
                // Ignoriere Aufrufe von Bots und ähnlichem
                if (strpos($_SERVER['REQUEST_URI'], 'adform') === false AND strpos($_SERVER['REQUEST_URI'], 'eyeblaster') === false AND strpos($_SERVER['REQUEST_URI'], 'wp-admin') === false AND strpos($_SERVER['REQUEST_URI'], 'xmlrpc') === false) {
                        ErrorHandler::writeLog('request', 'page '.$_SERVER['REQUEST_URI'].' not found ('.$this->ref.'remote addr: '.$_SERVER['REMOTE_ADDR'].' / user agent: '.$this->userAgent.')');
                }
                header('Location: '.HTTP_ROOT.'page-not-found');
                exit();
        }
}