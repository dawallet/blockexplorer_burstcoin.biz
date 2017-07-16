<?php
class ControllerSurfbar extends Controller {
        /**
         * Surfbar
         */
        function index() {                
                // Prüfe ob Ref gesetzt wurde
                if (isset($_GET['ref']) AND $_GET['ref'] > 0) {
                        // Prüfe ob die ID in der Datenbank existiert
                        $getRef = $this->db->query("SELECT COUNT(*) AS refFound FROM ".DB_PRE."surfbar WHERE surfbarid='".$this->db->escapeString($_GET['ref'])."'");
                        // Schreibe die Ref-ID in eine Session
                        if ($getRef[0]['refFound'] == 1) {
                                $_SESSION['surfbarRef'] = $_GET['ref'];
                                setcookie("surfbarRef", $_GET['ref'], time()+2592000);
                        }
                }
                
                // Lese die Gesamtsumme der Auszahlungen aus
                $totalPayout = $this->db->query("SELECT SUM(amount) AS totalPayout, COUNT(*) AS totalFees FROM ".DB_PRE."surfbar_payouts");
                $this->smarty->assign('totalPayouts', $totalPayout[0]['totalPayout']+$totalPayout[0]['totalFees']);
                
                $this->template = 'Surfbar/index';
        }
        
        /**
         * Surfbar - Login
         */
        function login() {
                // Zerlege die Burst Adresse
                if (isset($_POST['address']) AND !empty($_POST['address'])) {
                        $burstAddress = explode('-', $_POST['address']);
                }
                
                $buttonJS = "<script>$('#surfbarBtn').attr('disabled',false).text('Send');</script>";
                if (!isset($_POST['address']) OR empty($_POST['address'])) {
                        echo $this->bootstrap->alert('Please enter your Burst address.'.$buttonJS, 'danger');
                } elseif (count($burstAddress) != 5 OR strtolower($burstAddress[0]) != "burst" OR strlen($_POST['address']) != 26) {
                        echo $this->bootstrap->alert('Please enter a valid Burst address.'.$buttonJS, 'danger');
                } else {
                        // Prüfe ob die Burst Adresse bereits existiert
                        $getAddress = $this->db->query("SELECT * FROM ".DB_PRE."surfbar WHERE address='".$this->db->escapeString($_POST['address'])."'");
                        if (isset($getAddress[0]) AND $getAddress[0]['surfbarid'] > 0) {
                                $surfbarid = $getAddress[0]['surfbarid'];
                        // Lege einen neuen Account an
                        } else {
                                // Prüfe ob ein Referral vorhanden ist
                                $referralid = 0;
                                if ((isset($_SESSION['surfbarRef']) AND $_SESSION['surfbarRef'] > 0) OR (isset($_COOKIE['surfbarRef']) AND $_COOKIE['surfbarRef'] > 0)) {
                                        if (isset($_SESSION['surfbarRef']) AND $_SESSION['surfbarRef'] > 0) {
                                                $referralid = $_SESSION['surfbarRef'];
                                        } else {
                                                $referralid = $_COOKIE['surfbarRef'];
                                        }
                                }
                                
                                $this->db->query(
                                        "INSERT INTO ".DB_PRE."surfbar ".
                                        "(referralid, address, ts_create) ".
                                        "VALUES ".
                                        "('".$this->db->escapeString($referralid)."', '".$this->db->escapeString($_POST['address'])."', '".time()."')");
                                $surfbarid = $this->db->getId();
                        }
                        
                        // Lege eine neuen Session an und leite zum Login weiter
                        $_SESSION['surfbarID'] = $surfbarid;
                        setcookie("surfbarID", $surfbarid, time()+2592000);
                        
                        $this->bootstrap->redirect('surfbar/account');                        
                }
                
                exit();
        }
        
        /**
         * Surfbar - Account
         */
        function account() {
                // Prüfe ob Surfbar ID gesetzt ist
                if ((isset($_SESSION['surfbarID']) AND $_SESSION['surfbarID'] > 0) OR (isset($_COOKIE['surfbarID']) AND $_COOKIE['surfbarID'] > 0)) {
                        if (isset($_SESSION['surfbarID']) AND $_SESSION['surfbarID'] > 0) {
                                $surfbarid = $_SESSION['surfbarID'];
                        } else {
                                $surfbarid = $_COOKIE['surfbarID'];
                        }
                        // Überprüfe ob Surfbar ID vorhanden ist
                        $getAccount = $this->db->query("SELECT * FROM ".DB_PRE."surfbar WHERE surfbarid='".$this->db->escapeString($surfbarid)."'");
                        if (isset($getAccount[0]) AND $getAccount[0]['surfbarid'] > 0) {
                                $this->smarty->assign('account', $getAccount[0]);
                                
                                // Setze Timestamp für den letzten Login
                                $this->db->query("UPDATE ".DB_PRE."surfbar SET ts_login='".time()."' WHERE surfbarid='".$getAccount[0]['surfbarid']."'");
                                
                                // Ermittle den Ref-Verdienst                                
                                $getReferrals = $this->db->query("SELECT COUNT(*) AS referrals, SUM(surfpoints) AS surfpointsRef FROM ".DB_PRE."surfbar WHERE referralid='".$getAccount[0]['surfbarid']."'");
                                $this->smarty->assign('surfpointsRef', $getReferrals[0]['surfpointsRef']);
                                $this->smarty->assign('referrals', $getReferrals[0]['referrals']);
                                $getRefdata = $this->db->query("SELECT t1.address, t1.surfpoints, t2.account AS accountID FROM ".DB_PRE."surfbar AS t1 LEFT JOIN ".DB_PRE."chain_accounts AS t2 ON t1.address=t2.accountRS WHERE t1.referralid='".$getAccount[0]['surfbarid']."' ORDER BY t1.surfpoints DESC");
                                $this->smarty->assign('refdata', $getRefdata);
                                
                                // Ermittle die eigene numerische Account ID
                                $getNumericAccount = $this->db->query("SELECT account FROM ".DB_PRE."chain_accounts WHERE accountRS='".$this->db->escapeString($getAccount[0]['address'])."'");
                                if (isset($getNumericAccount[0]) AND $getNumericAccount[0]['account'] > 0) {
                                        $this->smarty->assign('numericAddress', $getNumericAccount[0]['account']);
                                }
                                
                                // Ermittle die Payouts
                                $totalPayouts = $this->db->query("SELECT SUM(amount) AS totalPaid FROM ".DB_PRE."surfbar_payouts WHERE surfbarid='".$getAccount[0]['surfbarid']."'");
                                $this->smarty->assign('totalPaid', $totalPayouts[0]['totalPaid']);
                                
                                $payoutData = $this->db->query("SELECT * FROM ".DB_PRE."surfbar_payouts WHERE surfbarid='".$getAccount[0]['surfbarid']."' ORDER BY payoutid DESC LIMIT 10");
                                $this->smarty->assign('payoutdata', $payoutData);
                                
                                $this->template = 'Surfbar/account';
                        } else {
                                $this->request->redirect(HTTP_ROOT."surfbar");
                        }
                } else {
                        $this->request->redirect(HTTP_ROOT."surfbar");
                }
        }
        
        /**
         * Surfbar starten
         */
        function start() {
                if (!isset($_GET['p0']) OR $_GET['p0'] < 1) {
                        $_GET['p0'] = 1;
                }
                $this->request->redirect("http://www.ebesucher.com/surfbar/.............".$_GET['p0']);
        }
}