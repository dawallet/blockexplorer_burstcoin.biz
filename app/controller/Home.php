<?php
class ControllerHome extends Controller {
        /**
         * Startseite
         */
        function index() {
                // Prüfe auf Suchbegriff
                if ($this->router->getRoute() == "/search") {                        
                        $this->smarty->assign('errorMsg', $this->bootstrap->alert('Unrecognized search pattern', 'danger', '', 'margin: 15px 0 0 0;'));
                        if (!empty($_POST['search'])) {
                                $_POST['search'] = trim($_POST['search']);
                                // Suche nach Burst Adresse oder nummerische Account ID
                                $addressSearch = $this->db->query("SELECT account FROM ".DB_PRE."chain_accounts WHERE account='".$this->db->escapeString($_POST['search'])."' OR accountRS='".$this->db->escapeString($_POST['search'])."' OR name='".$this->db->escapeString($_POST['search'])."'");
                                if (isset($addressSearch[0])) {
                                        $this->request->redirect(HTTP_ROOT.'address/'.$addressSearch[0]['account']);
                                }
                                
                                // Suche nach Transaction ID
                                $transactionSearch = $this->db->query("SELECT transaction FROM ".DB_PRE."chain_transactions WHERE transaction='".$this->db->escapeString($_POST['search'])."'");
                                if (isset($transactionSearch[0])) {
                                        $this->request->redirect(HTTP_ROOT.'transaction/'.$transactionSearch[0]['transaction']);
                                }
                                
                                // Suche nach Block Height oder ID
                                $blockSearch = $this->db->query("SELECT block FROM ".DB_PRE."chain_blocks WHERE (height='".$this->db->escapeString($_POST['search'])."' OR block='".$this->db->escapeString($_POST['search'])."') AND height<>'0'");
                                if (isset($blockSearch[0])) {
                                        $this->request->redirect(HTTP_ROOT.'block/'.$blockSearch[0]['block']);
                                }                                
                        }
                }
                
                // Auslesen der 30 neusten Blocks
                $blockData = $this->db->query("SELECT * FROM ".DB_PRE."chain_blocks ORDER BY height DESC LIMIT 30");
                $parsedBlockData = '';
                foreach ($blockData AS $blockData) {
                        // Berechnen der Blockgröße
                        $payloadLength = 0;
                        if ($blockData['payloadLength'] > 0) {
                                $payloadLength = $blockData['payloadLength']/1024;
                        }
                        $parsedBlockData[] = array(
                            'height'                    => $blockData['height'], 
                            'block'                     => $blockData['block'], 
                            'age'                       => $this->validate->convertAge(time()-BURST_EXIST-$blockData['timestamp']),
                            'numberOfTransactions'      => $blockData['numberOfTransactions'],
                            'totalAmountNQT'            => $blockData['totalAmountNQT'],
                            'totalFeeNQT'               => $blockData['totalFeeNQT'],
                            'payloadLength'             => $payloadLength);
                }
                $this->smarty->assign('blockdata', $parsedBlockData);
	    
                // Lade Module                
                $this->load->model('Stats');
                
                // Ermittle Anzahl aller im Umlauf befindlichen Burstcoins
                $this->model_stats->totalSupply();
		
                // Ermittle die Anzahl an Blöcken            
                $blockData = $this->db->query("SELECT COUNT(*) AS blocks FROM ".DB_PRE."chain_blocks WHERE height<>'0'");
                $this->smarty->assign('blocks', $blockData[0]['blocks']);
                
                // Ermittle die Anzahl an Transaktionen
                $this->model_stats->totalTransactions();
                
                // Ermittle die Anzahl an Wallets
                $this->model_stats->totalWallets();
		
                // Ermittle die Hashrate
                $this->model_stats->networkSize();
		
                $this->template = 'Home/index';
        }
        
        /**
         * Wechseln der Sprache
         */
        function language() {
                if (LANGUAGE === true) {
                        if (isset($_GET['p0'])) {
                                $this->getBrowserLang = $_GET['p0'];
                        } else {
                                $this->getBrowserLang = LANGUAGE_DEFAULT;
                        }

                        // Prüfe ob Sprachcode existiert, ansonsten falle zurück zur Standardsprache
                        $this->acceptedLang = explode(',', LANGUAGES);
                        if (!in_array($this->getBrowserLang, $this->acceptedLang)) {
                                $this->getBrowserLang = LANGUAGE_DEFAULT;
                        }
                        $this->referer = HTTP_ROOT;
                        
                        // Prüfe ob Referer übergeben wurde und leite ggf. auf die vorherige Seite weiter
                        if (isset($_SERVER['HTTP_REFERER']) AND !empty($_SERVER['HTTP_REFERER'])) {
                                $this->httpRoot = str_replace(array('http://', 'https://', 'www.'), '', HTTP_ROOT);
                                $this->httpReferer = str_replace(array('http://', 'https://', 'www.'), '', $_SERVER['HTTP_REFERER']);

                                if (substr($this->httpReferer, 0, strlen($this->httpRoot)) == $this->httpRoot) {
                                        $this->getRoute = substr($this->httpReferer, strlen($this->httpRoot));
                                        $this->referer = HTTP_ROOT.$this->getRoute;
                                }
                        }
                        
                        $_SESSION['lang'] = $this->getBrowserLang;                        
                        
                        $this->request->redirect($this->referer);
                }
        }
}