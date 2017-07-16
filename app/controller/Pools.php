<?php
class ControllerPools extends Controller {
        /**
         * Pool-Vergleich
         */
        function index() {                 
                // Prüfe Parameter
                if (isset($_GET['p0']) AND $_GET['p0'] >= 2014 AND isset($_GET['p1']) AND $_GET['p1'] >= 1 AND $_GET['p1'] <= 12 AND isset($_GET['p2']) AND $_GET['p2'] >= 1 AND $_GET['p2'] <= 31 AND checkdate($_GET['p1'], $_GET['p2'], $_GET['p0']) AND strtotime($_GET['p0'].'-'.$_GET['p1'].'-'.$_GET['p2']) >= strtotime("2014-08-11") AND strtotime($_GET['p0'].'-'.$_GET['p1'].'-'.$_GET['p2']) <= strtotime(date("Y-m-d"))) {
                        $blockdate = $_GET['p0'].'-'.$_GET['p1'].'-'.$_GET['p2'];
                } else {
                        $yesterday = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
                        $blockdate = date("Y-m-d", $yesterday);
                        $_GET['p0'] = date("Y", $yesterday);
                        $_GET['p1'] = date("m", $yesterday);
                        $_GET['p2'] = date("d", $yesterday);
                }
                $blockdatePrev = date("Y-m-d", mktime(0, 0, 0, $_GET['p1'], $_GET['p2']-1, $_GET['p0']));
                $blockdateNext = date("Y-m-d", mktime(0, 0, 0, $_GET['p1'], $_GET['p2']+1, $_GET['p0']));
                $this->smarty->assign('blockdate', $blockdate);
                if (strtotime($blockdatePrev) >= strtotime("2014-08-11")) {
                        $this->smarty->assign('blockdatePrev', $blockdatePrev);
                }
                if (strtotime($blockdateNext) < strtotime(date("Y-m-d"))) {
                        $this->smarty->assign('blockdateNext', $blockdateNext);
                }
                
                // Auslesen der Pool Statistik für den ausgewählten Tag
                require_once DIR_SYSTEM.DS.'config'.DS.'site.php';
                $parsedPoolData = '';
                foreach ($conf['pools'] AS $pool) {
                        $countMiner = $this->db->query("SELECT COUNT(*) AS miners FROM ".DB_PRE."chain_transactions WHERE DATE(transactiondate)='".$blockdate."' AND sender='".$pool['addr']."' GROUP BY recipient");
                        $countPayouts = $this->db->query("SELECT SUM(amountNQT) AS payouts FROM ".DB_PRE."chain_transactions WHERE DATE(transactiondate)='".$blockdate."' AND sender='".$pool['addr']."'");
                        $poolBalance = $this->db->query("SELECT unconfirmedBalanceNQT FROM ".DB_PRE."chain_accounts WHERE account='".$pool['addr']."'");
                        $parsedPoolData[] = array(
                            'name'      => $pool['name'], 
                            'addr'      => $pool['addr'], 
                            'url'       => rtrim(strtr(base64_encode($pool['url']), '+/', '-_'), '='),
                            'miner'     => count($countMiner),
                            'payout'    => $countPayouts[0]['payouts'],
                            'balance'   => $poolBalance[0]['unconfirmedBalanceNQT']);
                }
                $this->smarty->assign('pooldata', $parsedPoolData);
                
                $this->template = 'Pools/index';
        }
}