<?php
class Validator {
        protected $db;
        protected $router;
        protected $smarty;
        protected $model;
        
        
        /**
         * Initalisiere die Datenbank-Verbindung.
         * @param object $db
         * @param object $router
         * @param object $smarty
         */
        public function __construct($db, $router, $smarty) {
                $this->db = $db;
                $this->router = $router;
                if (!empty($smarty)) {
                        $this->smarty = $smarty;

                        // Lese die globale Statistik aus
                        $globalStatsData = $this->db->query("SELECT * FROM ".DB_PRE."stats"); 
                        $this->smarty->assign('globalStats', $globalStatsData[0]);
                        $this->smarty->assign('marketPriceUSD', $globalStatsData[0]['burstBTC']*$globalStatsData[0]['btcUSD']);
                        $this->smarty->assign('marketPriceEUR', $globalStatsData[0]['burstBTC']*$globalStatsData[0]['btcEUR']);
                        $this->smarty->assign('kBurstUSD', $globalStatsData[0]['burstBTC']*1000*$globalStatsData[0]['btcUSD']);
                        $this->smarty->assign('kBurstEUR', $globalStatsData[0]['burstBTC']*1000*$globalStatsData[0]['btcEUR']);
                        $this->smarty->assign('btcUSDts', $this->convertAge(time()-$globalStatsData[0]['btcUSDts']));
                        $this->smarty->assign('btcEURts', $this->convertAge(time()-$globalStatsData[0]['btcEURts']));
                }
        }        
        
        /**
         * Prüft ob die Variable einen Wert enthält.
         * @param type $string String
         * @return boolean Gibt true oder false zurück
         */
	public function isString($string) {
                if (!isset($string) OR empty($string)) {
                        return false;
                }
                
                return true;
	}
        
        /**
         * Prüft E-Mail-Adresse auf korrekten Syntax und ggf. den DNS-Server.
         * @param string $email E-Mail-Adresse
         * @param boolean $checkdns Wenn dieser Parameter auf true gesetzt ist, wird der DNS-Server überprüft (Default: false)
         * @return boolean Gibt true oder false zurück
         */
        public function isMail($email, $checkdns = false) {
                if ($this->isString($email) === false) {
                        return false;
                }
                
                if (preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9][a-zA-Z0-9-.]+\.([a-zA-Z]{2,6})$/" , $email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $tmp = explode('@', $email);
                        $domain = $tmp[1];
                        if($checkdns && @dns_get_record($domain) === false) {
                                return false;
                        }
                        
                } else {
                        return false;
                }
                
                return true;
        }
        
        /**
         * Prüft ob es sich bei dem String um ein ausreichend langes Passwort handelt.
         * @param string $password
         * @return boolean Gibt true oder false zurück
         */
	public function isPassword($password) {
                if (!isset($password) OR strlen(trim($password)) < SECURITY_PASSWORD_MIN) {
                        return false;
                }
                
                return true;
	}
        
        /**
         * Prüft ob es sich bei dem String um eine korrekte IP-Adresse handelt.
         * @param string $ip IP-Adresse
         * @return boolean Gibt true oder false zurück
         */
        public function isIp($ip) {
                if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                        return false;
                }
                
                return true;
        }
        
        /**
         * Wandelt Sekunden in eine lesbare Zeiteinheit um.
         * @param integer $seconds Sekunden
         * @return varchar Gibt die Zeit Zurück
         */
        public function convertAge($seconds) {
                if ($seconds == 1) {
                        return '1 second';
                } elseif ($seconds < 60) {
                        return $seconds.' seconds';                        
                } elseif ($seconds < 3600) {
                        $seconds = floor($seconds/60);
                        if ($seconds == 1) {
                                return $seconds.' minute';
                        }
                        return $seconds.' minutes';                      
                } elseif ($seconds < 86400) {
                        $seconds = floor($seconds/3600);
                        if ($seconds == 1) {
                                return $seconds.' hour';
                        }
                        return $seconds.' hours';                      
                } elseif ($seconds < 2592000) {
                        $seconds = floor($seconds/86400);
                        if ($seconds == 1) {
                                return $seconds.' day';
                        }
                        return $seconds.' days';                      
                } elseif ($seconds >= 2592000) {
                        $seconds = floor($seconds/2592000);
                        if ($seconds == 1) {
                                return $seconds.' month';
                        }
                        return $seconds.' months';                      
                }
                
                return true;
        }
        
        /**
         * Wandelt einen Zahlenwert in ein Geschlecht um.
         * @param integer $gender ID des Geschlechts
         * @return string Gibt das Geschlecht bzw. die Anrede zurück ("Herr" oder "Frau")
         */
        public function isGender($gender) {
                if ($gender == 1) {
                        return "Herr";
                } elseif ($gender == 2) {
                        return "Frau";
                } else {
                        return false;
                }                
        }
        
        /**
         * Prüft ob es sich bei dem User um einen Admin handelt.
         * @return boolean Liefert true zurück oder leitet den Benutzer auf die "Zugriff verweigert" Seite weiter
         */
        public function isAdmin() {
                if ($_SESSION['userlevelid'] != 1) {
                        header("Location: ".HTTP_ROOT."access-denied");
                        exit();
                }
                
                return true;
        }
        
        /**
         * Prüft ob es sich bei dem User um einen Mitarbeiter handelt.
         * @return boolean Liefert true zurück oder leitet den Benutzer auf die "Zugriff verweigert" Seite weiter
         */
        public function isStaff() {
                if ($_SESSION['userlevelid'] < 1 || $_SESSION['userlevelid'] > 4) {
                        header("Location: ".HTTP_ROOT."access-denied");
                        exit();
                }
                
                return false;
        }
        
        /**
         * Prüft ob es sich bei dem User um ein Mitglied handelt.
         * @return boolean Liefert true zurück oder leitet den Benutzer auf die "Zugriff verweigert" Seite bzw. den AGB weiter
         */
        public function isUser() {
                if ($_SESSION['userlevelid'] < 1 || $_SESSION['userlevelid'] > 6) {
                        header("Location: ".HTTP_ROOT."access-denied");
                        exit();
                }
                
                // Prüfe ob der User noch in der Datenbank existiert
                $validateUserID = $this->db->query("SELECT userid FROM ".DB_PRE."users WHERE userid='".$_SESSION['userid']."'");
                if (!isset($validateUserID[0]) OR $validateUserID[0]['userid'] < 1) {
                        $this->load->model('User');
                        $this->model_user->destroySession();
                }
                
                return true;
        }
        
        /**
         * Prüft ob es sich bei dem User um einen Gast handelt.
         * @return boolean Liefert true zurück oder leitet den Benutzer zum Dashboard weiter
         */
        public function isGuest() {
                // Leite bereits eingelogte User zum Kunden-Bereich weiter
                if ($_SESSION['userlevelid'] > 0 && $_SESSION['userlevelid'] < 7) {
                        header("Location: ".HTTP_ROOT."admin");
                        exit();
                }
                
                return true;
        }
}