<?php
class Session {
	public $data = array();
	
        /**
         * Setzt Session-Einstellungen, startet Session und prüft die Spracheinstellung des Clients.
         */
  	public function __construct() {		
		if (!session_id()) {
			ini_set('session.use_cookies', 'On');
			ini_set('session.use_trans_sid', 'Off');
                        ini_set('session.gc_maxlifetime', 28800);
			
                        if ($_SERVER['HTTP_HOST'] == ROUTING_MAIN) {
                                session_set_cookie_params(28800, '/', $_SERVER['HTTP_HOST'], false, true);
                        } else {
                                session_set_cookie_params(28800, '/', $_SERVER['HTTP_HOST'], true, true);
                        }
			session_start();
                        
                        if (!isset($_SESSION['userlevelid'])) {
                                $_SESSION['userlevelid'] = 7;
                        }
		}
                
                // Prüfe Spracheinstellung des Clients
                if (LANGUAGE === true && !isset($_SESSION['lang'])) {

                        // Falls Client keine Cookies aktiviert hat, lese den Sprachcode aus der URL
                        $this->route = preg_replace("![^A-Za-z0-9-/]!is", '', $_GET['p']);
                        $this->routeLangCode = substr($this->route, 1, 2);
                        $this->langCodes = explode(',', LANGUAGES);
                        foreach ($this->langCodes AS $this->langCode) {
                                if ($this->routeLangCode == $this->langCode) {
                                        $this->getBrowserLang = $this->langCode;
                                }
                        }
                        
                        // Ermittle die Browsersprache des Clients, falls URL keinen Sprachcode enthält
                        if (!isset($this->getBrowserLang) AND isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                                $this->getBrowserLang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));

                                $this->acceptedLang = explode(',', LANGUAGES);
                                if (!in_array($this->getBrowserLang, $this->acceptedLang)) {
                                        $this->getBrowserLang = LANGUAGE_DEFAULT;
                                }
                        // Wenn keine Browsersprache ermittelt werden konnte, nehme die Standardsprache
                        } else {
                                $this->getBrowserLang = LANGUAGE_DEFAULT;
                        }
                        
                        // Setze Sprache
                        $_SESSION['lang'] = $this->getBrowserLang;
                }
                
		$this->data =& $_SESSION;
	}
	
        /**
         * Liefert die Session-ID zurück.
         * @return var Session-ID
         */
	function getId() {
		return session_id();
	}
}