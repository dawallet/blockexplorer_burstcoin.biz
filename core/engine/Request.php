<?php
class Request {
	public $get = array();
	public $post = array();
	public $cookie = array();
	public $files = array();
	public $server = array();
	
        /**
         * Ließt globale Server-Variablen und bereinigt diese.
         */
  	public function __construct() {	
                
		$this->get = $this->clean($_GET);
                $_GET = $this->get;
		$this->post = $this->clean($_POST);
                $_POST = $this->post;                
		$this->request = $this->clean($_REQUEST);
                $_REQUEST = $this->request;
		$this->cookie = $this->clean($_COOKIE);
                $_COOKIE = $this->cookie;
		$this->files = $this->clean($_FILES);
                $_FILES = $this->files;
		$this->server = $this->clean($_SERVER);
                $_SERVER = $this->server;
	}
	
        /**
         * Bereinigt Sonderzeichen aus Arrays und Strings.
         * @param array|string $data Werte die zu bereinigen sind
         * @return array|string Gibt bereinigte Werte zurück
         */
  	public function clean($data) {
                if (is_array($data)) {
	  		foreach ($data as $key => $value) {
				unset($data[$key]);
                                $data[$this->clean($key)] = $this->clean($value);
	  		}
		} else { 
	  		//$data = htmlspecialchars(trim($data), ENT_COMPAT, 'UTF-8');
                        $data = $data;
		}

		return $data;
	}
        
        /**
         * Erzeugt eine Header-Weiterleitung
         * @param string $url URL
         */
        public function redirect($url) {
                header("Location: ".$url);
                exit();
        }
}