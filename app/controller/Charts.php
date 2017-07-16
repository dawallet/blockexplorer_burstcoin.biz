<?php
class ControllerCharts extends Controller {
        /**
         * Constructor
         */
        function construct() {
                // Prüfe auf Parameter
                $this->statsPeriod = 'all';
                if (isset($_GET['p0']) AND ($_GET['p0'] == "day" OR $_GET['p0'] == "week" OR $_GET['p0'] == "month" OR $_GET['p0'] == "all")) {
                        $this->statsPeriod = $_GET['p0'];
                }
                $this->smarty->assign('statsPeriod', $this->statsPeriod);
                
                // Berechnen der Filter
                $this->tsDay = date("Y-m-d H:i:s", time()-86400);
                $this->tsWeek = date("Y-m-d H:i:s", time()-604800);
                $this->tsMonth = date("Y-m-d H:i:s", time()-2592000);
        }
        
        /**
         * Charts
         */
        function index() {
                $this->template = 'Charts/index';
        }
        
        /**
         * Gesamte Anzahl an Burstcoins im Umlauf
         */
        function totalBurstcoins() {
                // Lade JS-Charts
                $this->smarty->assign('jsStocks', true);
                
                // Auslesen der vorhandenen Burstcoins
                $blockData = $this->db->query("SELECT SUM(blockReward) AS burstcoins, DATE_FORMAT(blockdate,'%Y') AS blockyear, DATE_FORMAT(blockdate,'%m') AS blockmonth, DATE_FORMAT(blockdate,'%e') AS blockday FROM ".DB_PRE."chain_blocks GROUP BY DATE(blockdate) ORDER BY blockdate ASC");
                $burstcoins = 0;
                foreach ($blockData AS $blockdata) {
                        $burstcoins = $burstcoins+$blockdata['burstcoins'];
                        $parsedBlockData[] = array(
                            'burstcoins' => $burstcoins,
                            'blockyear' => $blockdata['blockyear'],
                            'blockmonth' => $blockdata['blockmonth']-1,
                            'blockday' => $blockdata['blockday']
                            );
                }
                $this->smarty->assign('blockdata', $parsedBlockData);
                
                $this->template = 'Charts/total-burstcoins';
        }
        
        /**
         * Anzahl an gefundenen Burstcoins pro Tag
         */
        function minedPerDay() {
                // Lade JS-Charts
                $this->smarty->assign('jsStocks', true);
                
                // Auslesen der neu generierten Burstcoins pro Tag
                $blockData = $this->db->query("SELECT SUM(blockReward) AS burstcoins, DATE_FORMAT(blockdate,'%Y') AS blockyear, DATE_FORMAT(blockdate,'%m') AS blockmonth, DATE_FORMAT(blockdate,'%e') AS blockday FROM ".DB_PRE."chain_blocks GROUP BY DATE(blockdate) ORDER BY blockdate ASC");
                foreach ($blockData AS $blockdata) {
                        $parsedBlockData[] = array(
                            'burstcoins' => $blockdata['burstcoins'],
                            'blockyear' => $blockdata['blockyear'],
                            'blockmonth' => $blockdata['blockmonth']-1,
                            'blockday' => $blockdata['blockday']
                            );
                }
                $this->smarty->assign('blockdata', $parsedBlockData);
                
                $this->template = 'Charts/mined-per-day';
        }
        
        /**
         * Anzahl an erzeugten Burstcoins
         */
        function minedBurstcoins() {
                // Lade JS-Charts
                $this->smarty->assign('jsCharts', true);
                
                // Auslesen der erzeugten Burstcoins
                $totalBlockReward = $this->db->query("SELECT SUM(blockReward) AS blockrewards FROM ".DB_PRE."chain_blocks WHERE height<>'0'");
                $this->smarty->assign('minedBurstcoins', $totalBlockReward[0]['blockrewards']);
                $this->smarty->assign('unminedBurstcoins', 2158812800-$totalBlockReward[0]['blockrewards']);
                
                $this->template = 'Charts/mined-burstcoins';
        }
        
        /**
         * Blockchain Größe
         */
        function blockchainSize() {
                // Lade JS-Charts
                $this->smarty->assign('jsStocks', true);
                
                // Auslesen der vorhandenen Burstcoins
                $blockData = $this->db->query("SELECT SUM(payloadLength) AS size, COUNT(*) AS blocks, DATE_FORMAT(blockdate,'%Y') AS blockyear, DATE_FORMAT(blockdate,'%m') AS blockmonth, DATE_FORMAT(blockdate,'%e') AS blockday FROM ".DB_PRE."chain_blocks GROUP BY DATE(blockdate) ORDER BY blockdate ASC");
                $size = 0;
                foreach ($blockData AS $blockdata) {
                        $zwsize = $blockdata['size']/1024/1024;
                        $blocksize = $blockdata['blocks']*0.01246; 
                        $size = $size+$zwsize+$blocksize;
                        $parsedBlockData[] = array(
                            'size' => round($size),
                            'blockyear' => $blockdata['blockyear'],
                            'blockmonth' => $blockdata['blockmonth']-1,
                            'blockday' => $blockdata['blockday']
                            );
                }
                $this->smarty->assign('blockdata', $parsedBlockData);
                
                $this->template = 'Charts/blockchain-size';
        }        
        
        /**
         * Anzahl der Transaktionen pro Tag
         */
        function transactionsPerDay() {
                // Lade JS-Charts
                $this->smarty->assign('jsStocks', true);
                
                // Auslesen der vorhandenen Burstcoins
                $transactionData = $this->db->query("SELECT COUNT(*) AS transactions, DATE_FORMAT(transactiondate,'%Y') AS transactionyear, DATE_FORMAT(transactiondate,'%m') AS transactionmonth, DATE_FORMAT(transactiondate,'%e') AS transactionday FROM ".DB_PRE."chain_transactions GROUP BY DATE(transactiondate) ORDER BY transactiondate ASC");
                foreach ($transactionData AS $transactiondata) {
                        $parsedTransactionData[] = array(
                            'transactions' => $transactiondata['transactions'],
                            'transactionyear' => $transactiondata['transactionyear'],
                            'transactionmonth' => $transactiondata['transactionmonth']-1,
                            'transactionday' => $transactiondata['transactionday']
                            );
                }
                $this->smarty->assign('transactiondata', $parsedTransactionData);
                
                $this->template = 'Charts/transactions-per-day';
        }
        
        /**
         * Gesamtanzahl an Transaktionen
         */
        function totalTransactions() {
                // Lade JS-Charts
                $this->smarty->assign('jsStocks', true);
                
                // Auslesen der vorhandenen Burstcoins
                $transactionData = $this->db->query("SELECT COUNT(*) AS transactions, DATE_FORMAT(transactiondate,'%Y') AS transactionyear, DATE_FORMAT(transactiondate,'%m') AS transactionmonth, DATE_FORMAT(transactiondate,'%e') AS transactionday FROM ".DB_PRE."chain_transactions GROUP BY DATE(transactiondate) ORDER BY transactiondate ASC");
                $transactions = 0;
                foreach ($transactionData AS $transactiondata) {
                        $transactions = $transactions+$transactiondata['transactions'];
                        $parsedTransactionData[] = array(                            
                            'transactions' => $transactions,
                            'transactionyear' => $transactiondata['transactionyear'],
                            'transactionmonth' => $transactiondata['transactionmonth']-1,
                            'transactionday' => $transactiondata['transactionday']
                            );
                }
                $this->smarty->assign('transactiondata', $parsedTransactionData);
                
                $this->template = 'Charts/total-transactions';
        }
        
        /**
         * Betrag der Transaktionen pro Tag
         */
        function transactionsAmountPerDay() {
                // Lade JS-Charts
                $this->smarty->assign('jsStocks', true);
                
                // Auslesen der Transaktionen
                $transactionData = $this->db->query("SELECT SUM(amountNQT) AS burstvolume, DATE_FORMAT(transactiondate,'%Y') AS transactionyear, DATE_FORMAT(transactiondate,'%m') AS transactionmonth, DATE_FORMAT(transactiondate,'%e') AS transactionday FROM ".DB_PRE."chain_transactions GROUP BY DATE(transactiondate) ORDER BY transactiondate ASC");
                foreach ($transactionData AS $transactiondata) {
                        $parsedTransactionData[] = array(                            
                            'burstvolume' => number_format($transactiondata['burstvolume'], 0, '', ''),
                            'transactionyear' => $transactiondata['transactionyear'],
                            'transactionmonth' => $transactiondata['transactionmonth']-1,
                            'transactionday' => $transactiondata['transactionday']
                            );
                }
                $this->smarty->assign('transactiondata', $parsedTransactionData);
                
                $this->template = 'Charts/transactions-amount-per-day';
        }
        
        /**
         * Verteilung der Transaktionen nach Umfang
         */
        /**function transactionDistribution() {
                // Lade JS-Charts
                $this->smarty->assign('jsCharts', true);
                
                // Auslesen der Transaktionsverteilung
                $blockData = $this->db->query("SELECT COUNT(*) AS transactions FROM ".DB_PRE."chain_transactions WHERE amountNQT<'1'");
                $this->smarty->assign('blockData1', $blockData[0]['transactions']);
                $blockData = $this->db->query("SELECT COUNT(*) AS transactions FROM ".DB_PRE."chain_transactions WHERE amountNQT>='1' AND amountNQT<'10'");
                $this->smarty->assign('blockData2', $blockData[0]['transactions']);
                $blockData = $this->db->query("SELECT COUNT(*) AS transactions FROM ".DB_PRE."chain_transactions WHERE amountNQT>='10' AND amountNQT<'100'");
                $this->smarty->assign('blockData3', $blockData[0]['transactions']);
                $blockData = $this->db->query("SELECT COUNT(*) AS transactions FROM ".DB_PRE."chain_transactions WHERE amountNQT>='100' AND amountNQT<'1000'");
                $this->smarty->assign('blockData4', $blockData[0]['transactions']);
                $blockData = $this->db->query("SELECT COUNT(*) AS transactions FROM ".DB_PRE."chain_transactions WHERE amountNQT>='1000' AND amountNQT<'10000'");
                $this->smarty->assign('blockData5', $blockData[0]['transactions']);
                $blockData = $this->db->query("SELECT COUNT(*) AS transactions FROM ".DB_PRE."chain_transactions WHERE amountNQT>='10000' AND amountNQT<'100000'");
                $this->smarty->assign('blockData6', $blockData[0]['transactions']);
                $blockData = $this->db->query("SELECT COUNT(*) AS transactions FROM ".DB_PRE."chain_transactions WHERE amountNQT>='100000' AND amountNQT<'1000000'");
                $this->smarty->assign('blockData7', $blockData[0]['transactions']);
                $blockData = $this->db->query("SELECT COUNT(*) AS transactions FROM ".DB_PRE."chain_transactions WHERE amountNQT>='1000000' AND amountNQT<'10000000'");
                $this->smarty->assign('blockData8', $blockData[0]['transactions']);
                $blockData = $this->db->query("SELECT COUNT(*) AS transactions FROM ".DB_PRE."chain_transactions WHERE amountNQT>='10000000'");
                $this->smarty->assign('blockData9', $blockData[0]['transactions']);
                              
                $this->template = 'Charts/transaction-distribution';
        }**/
        
        /**
         * Durchschnittliche Anzahl der Transaktionen pro Block
         */
        function transactionsAveragePerBlock() {
                // Lade JS-Charts
                $this->smarty->assign('jsStocks', true);
                
                // Auslesen der vorhandenen Burstcoins
                $blockData = $this->db->query("SELECT COUNT(*) AS blocks, SUM(numberOfTransactions) AS transactions, DATE_FORMAT(blockdate,'%Y') AS blockyear, DATE_FORMAT(blockdate,'%m') AS blockmonth, DATE_FORMAT(blockdate,'%e') AS blockday FROM ".DB_PRE."chain_blocks GROUP BY DATE(blockdate) ORDER BY blockdate ASC");
                foreach ($blockData AS $blockdata) {
                        $parsedBlockData[] = array(
                            'transactions' => round($blockdata['transactions']/$blockdata['blocks']),
                            'blockyear' => $blockdata['blockyear'],
                            'blockmonth' => $blockdata['blockmonth']-1,
                            'blockday' => $blockdata['blockday']
                            );
                }
                $this->smarty->assign('blockdata', $parsedBlockData);
                
                $this->template = 'Charts/transactions-average-per-block';
        }
        
        /**
         * Anzahl der Wallets
         */
        function totalWallets() {
                // Lade JS-Charts
                $this->smarty->assign('jsStocks', true);
                
                // Auslesen der Accounts
                $walletData = $this->db->query("SELECT COUNT(*) AS wallets, DATE_FORMAT(accountFirstseen,'%Y') AS walletyear, DATE_FORMAT(accountFirstseen,'%m') AS walletmonth, DATE_FORMAT(accountFirstseen,'%e') AS walletday FROM ".DB_PRE."chain_accounts GROUP BY DATE(accountFirstseen) ORDER BY accountFirstseen ASC");
                $wallets = 0;
                foreach ($walletData AS $walletdata) {
                        $wallets = $wallets+$walletdata['wallets'];
                        $parsedWalletsData[] = array(                            
                            'wallets' => $wallets,
                            'walletyear' => $walletdata['walletyear'],
                            'walletmonth' => $walletdata['walletmonth']-1,
                            'walletday' => $walletdata['walletday']
                            );
                }
                $this->smarty->assign('walletdata', $parsedWalletsData);
                
                $this->template = 'Charts/total-wallets';
        }
        
        /**
         * Neue Wallets pr Tag
         */
        function walletsPerDay() {
                // Lade JS-Charts
                $this->smarty->assign('jsStocks', true);
                
                // Auslesen der neuen Wallets pro Tag
                $walletData = $this->db->query("SELECT COUNT(*) AS wallets, DATE_FORMAT(accountFirstseen,'%Y') AS walletyear, DATE_FORMAT(accountFirstseen,'%m') AS walletmonth, DATE_FORMAT(accountFirstseen,'%e') AS walletday FROM ".DB_PRE."chain_accounts GROUP BY DATE(accountFirstseen) ORDER BY accountFirstseen ASC");
                foreach ($walletData AS $walletdata) {
                        $parsedWalletsData[] = array(                            
                            'wallets' => $walletdata['wallets'],
                            'walletyear' => $walletdata['walletyear'],
                            'walletmonth' => $walletdata['walletmonth']-1,
                            'walletday' => $walletdata['walletday']
                            );
                }
                $this->smarty->assign('walletdata', $parsedWalletsData);
                
                // Auslesen der neuen Wallets pro Monat
                $walletData = $this->db->query("SELECT COUNT(*) AS wallets, DATE_FORMAT(accountFirstseen,'%Y') AS walletyear, DATE_FORMAT(accountFirstseen,'%m') AS walletmonth FROM ".DB_PRE."chain_accounts GROUP BY MONTH(accountFirstseen), YEAR(accountFirstseen) ORDER BY accountFirstseen DESC");
                foreach ($walletData AS $walletdata) {
                        if ($walletdata['walletmonth'] == "08" AND $walletdata['walletyear'] == "2014") {
                                $monthdays = 21;
                        } elseif ($walletdata['walletmonth'] == date("m") AND $walletdata['walletyear'] == date("Y")) {
                                $monthdays = date("d");
                        }  else {
                                $monthdays = date("t", mktime(0, 0, 0, $walletdata['walletmonth'], 1, $walletdata['walletyear']));
                        }
                        $parsedWalletsMonth[] = array(                            
                            'wallets' => $walletdata['wallets'],
                            'walletyear' => $walletdata['walletyear'],
                            'walletmonth' => $walletdata['walletmonth'],
                            'monthdays' => $monthdays
                            );
                }
                $this->smarty->assign('walletmonth', $parsedWalletsMonth);
                
                $this->template = 'Charts/wallets-per-day';
        }
        
        /**
         * Adressen nach Guthaben
         */
        function addressesBalance() {
                // Lese die Anzahl der vorhandenen Burstcoins aus
                $totalBlockReward = $this->db->query("SELECT SUM(blockReward) AS blockrewards FROM ".DB_PRE."chain_blocks WHERE height<>'0'");
                $this->smarty->assign('blockrewards', $totalBlockReward[0]['blockrewards']);
                
                // Ermittle die Anzahl an Accounts
                $totalAccounts = $this->db->query("SELECT COUNT(*) AS accounts FROM ".DB_PRE."chain_accounts");                                
                $this->smarty->assign('totalAccounts', $totalAccounts[0]['accounts']);
                $maxPages = ceil($totalAccounts[0]['accounts']/100);
                $this->smarty->assign('maxPages', $maxPages);

                // Ermittle die anzuzeigende Seite
                $page = 1;
                if (isset($_GET['p0']) AND $_GET['p0'] > 0 AND $_GET['p0'] <= $maxPages) {
                        $page = $_GET['p0'];
                }
                $this->smarty->assign('page', $page);

                // Bereite den Abfrage-Filter vor
                $sqlOffset = 0;
                if ($page > 1) {
                        $page = $page-1;
                        $sqlOffset = $page*100;
                }                
                
                // Lese die Adressen sortiert nach Guthaben aus                
                $this->addressData = $this->db->query("SELECT account, accountRS, unconfirmedBalanceNQT, name FROM ".DB_PRE."chain_accounts ORDER BY unconfirmedBalanceNQT DESC LIMIT ".$sqlOffset.",100");
                $this->smarty->assign('addressdata', $this->addressData);
                
                if (isset($this->addressData[0])) {
                        $this->template = 'Charts/addresses-balance';
                }
        }
        
        /**
         * Adressen nach erhaltene Burstcoins
         */
        /**function addressesReceived() {
                // Lese die Adressen mit den am meisten erhaltenen Burstcoins aus                
                $this->addressData = $this->db->query("SELECT COUNT(*) AS transactions, SUM(t1.amountNQT) AS totalreceived, t2.account, t2.accountRS, t2.unconfirmedBalanceNQT, t2.name FROM ".DB_PRE."chain_transactions AS t1 LEFT JOIN ".DB_PRE."chain_accounts AS t2 ON t1.recipient=t2.account GROUP BY t1.recipient ORDER BY totalreceived DESC LIMIT 500");
                $this->smarty->assign('addressdata', $this->addressData);
                
                $this->template = 'Charts/addresses-received';
        }**/
        
        /**
         * Adressen nach gefundenen Blöcken
         */
        function addressesForged() {
                // Prüfe auf Filter
                $blockFilter = '';
                if ($this->statsPeriod == "day") {
                        $blockFilter = " WHERE t1.blockdate>='".$this->tsDay."'";
                } elseif ($this->statsPeriod == "week") {
                        $blockFilter = " WHERE t1.blockdate>='".$this->tsWeek."'";
                } elseif ($this->statsPeriod == "month") {
                        $blockFilter = " WHERE t1.blockdate>='".$this->tsMonth."'";
                }
                
                // Lese die Anzahl der erzeugten Blöcke im gewählten Zeitraum aus
                $this->blockData = $this->db->query("SELECT COUNT(*) AS blocks FROM ".DB_PRE."chain_blocks".str_replace('t1.', '', $blockFilter));
                $this->smarty->assign('blockdata', $this->blockData[0]);
                
                // Lese die Adressen aus die am meisten Blöcke gefunden haben                
                $this->addressData = $this->db->query("SELECT COUNT(*) AS blocks, t2.account, t2.accountRS, t2.unconfirmedBalanceNQT, t2.name FROM ".DB_PRE."chain_blocks AS t1 LEFT JOIN ".DB_PRE."chain_accounts AS t2 ON t1.generator=t2.account".$blockFilter." GROUP BY t1.generator ORDER BY blocks DESC LIMIT 500");
                $this->smarty->assign('addressdata', $this->addressData);
                
                $this->template = 'Charts/addresses-forged';
        }
        
        /**
         * Verteilung des Guthabens
         */
        function balanceDistribution() {
                // Lade JS-Charts
                $this->smarty->assign('jsCharts', true);
                
                // Auslesen der Guthabenverteilung
                $blockData = $this->db->query("SELECT COUNT(*) AS accounts FROM ".DB_PRE."chain_accounts WHERE unconfirmedBalanceNQT<'1'");
                $this->smarty->assign('blockData1', $blockData[0]['accounts']);
                $blockData = $this->db->query("SELECT COUNT(*) AS accounts FROM ".DB_PRE."chain_accounts WHERE unconfirmedBalanceNQT>='1' AND unconfirmedBalanceNQT<'10'");
                $this->smarty->assign('blockData2', $blockData[0]['accounts']);
                $blockData = $this->db->query("SELECT COUNT(*) AS accounts FROM ".DB_PRE."chain_accounts WHERE unconfirmedBalanceNQT>='10' AND unconfirmedBalanceNQT<'100'");
                $this->smarty->assign('blockData3', $blockData[0]['accounts']);
                $blockData = $this->db->query("SELECT COUNT(*) AS accounts FROM ".DB_PRE."chain_accounts WHERE unconfirmedBalanceNQT>='100' AND unconfirmedBalanceNQT<'1000'");
                $this->smarty->assign('blockData4', $blockData[0]['accounts']);
                $blockData = $this->db->query("SELECT COUNT(*) AS accounts FROM ".DB_PRE."chain_accounts WHERE unconfirmedBalanceNQT>='1000' AND unconfirmedBalanceNQT<'10000'");
                $this->smarty->assign('blockData5', $blockData[0]['accounts']);
                $blockData = $this->db->query("SELECT COUNT(*) AS accounts FROM ".DB_PRE."chain_accounts WHERE unconfirmedBalanceNQT>='10000' AND unconfirmedBalanceNQT<'100000'");
                $this->smarty->assign('blockData6', $blockData[0]['accounts']);
                $blockData = $this->db->query("SELECT COUNT(*) AS accounts FROM ".DB_PRE."chain_accounts WHERE unconfirmedBalanceNQT>='100000' AND unconfirmedBalanceNQT<'1000000'");
                $this->smarty->assign('blockData7', $blockData[0]['accounts']);
                $blockData = $this->db->query("SELECT COUNT(*) AS accounts FROM ".DB_PRE."chain_accounts WHERE unconfirmedBalanceNQT>='1000000' AND unconfirmedBalanceNQT<'10000000'");
                $this->smarty->assign('blockData8', $blockData[0]['accounts']);
                $blockData = $this->db->query("SELECT COUNT(*) AS accounts FROM ".DB_PRE."chain_accounts WHERE unconfirmedBalanceNQT>='10000000'");
                $this->smarty->assign('blockData9', $blockData[0]['accounts']);
                              
                $this->template = 'Charts/balance-distribution';
        }
        
        /**
         * Durschnittliche Zeit bis ein Block gefunden wird
         */
        function averageBlockTime() {
                // Lade JS-Charts
                $this->smarty->assign('jsCharts', true);
                
                // Auslesen der Blockdauer
                $blockData = $this->db->query("SELECT COUNT(*) AS blocks FROM ".DB_PRE."chain_blocks WHERE height<>'0' AND duration<'60'");
                $this->smarty->assign('blockDuration1', $blockData[0]['blocks']);
                $blockData = $this->db->query("SELECT COUNT(*) AS blocks FROM ".DB_PRE."chain_blocks WHERE height<>'0' AND duration>='60' AND duration<='119'");
                $this->smarty->assign('blockDuration2', $blockData[0]['blocks']);
                $blockData = $this->db->query("SELECT COUNT(*) AS blocks FROM ".DB_PRE."chain_blocks WHERE height<>'0' AND duration>='120' AND duration<='239'");
                $this->smarty->assign('blockDuration3', $blockData[0]['blocks']);
                $blockData = $this->db->query("SELECT COUNT(*) AS blocks FROM ".DB_PRE."chain_blocks WHERE height<>'0' AND duration>='240' AND duration<='359'");
                $this->smarty->assign('blockDuration4', $blockData[0]['blocks']);
                $blockData = $this->db->query("SELECT COUNT(*) AS blocks FROM ".DB_PRE."chain_blocks WHERE height<>'0' AND duration>='360' AND duration<='599'");
                $this->smarty->assign('blockDuration5', $blockData[0]['blocks']);
                $blockData = $this->db->query("SELECT COUNT(*) AS blocks FROM ".DB_PRE."chain_blocks WHERE height<>'0' AND duration>='600' AND duration<='900'");
                $this->smarty->assign('blockDuration6', $blockData[0]['blocks']);
                $blockData = $this->db->query("SELECT COUNT(*) AS blocks FROM ".DB_PRE."chain_blocks WHERE height<>'0' AND duration>'900'");
                $this->smarty->assign('blockDuration7', $blockData[0]['blocks']);
                              
                $this->template = 'Charts/average-block-generation';
        }
        
        /**
         * Geschätze Netzwerk Größe
         */
        function estimatedNetworkSize() {
                // Lade JS-Charts
                $this->smarty->assign('jsStocks', true);
                
                // Auslesen der Blöcke
                $blockData = $this->db->query("SELECT baseTarget, DATE_FORMAT(blockdate,'%Y') AS blockyear, DATE_FORMAT(blockdate,'%m') AS blockmonth, DATE_FORMAT(blockdate,'%e') AS blockday, DATE_FORMAT(blockdate,'%k') AS blockhour, DATE_FORMAT(blockdate,'%i') AS blockmin FROM ".DB_PRE."chain_blocks WHERE height<>'0' ORDER BY height ASC");
                $i = 0;
                $baseTarget = 0;
                foreach ($blockData AS $blockdata) {
                        $i++;
                        $baseTarget = $baseTarget+$blockdata['baseTarget'];
                        if ($i == 50) {
                                $baseTarget = $baseTarget/50;
                                $baseTarget = $baseTarget*960000000;
                                $networksize = pow(2, 64)/$baseTarget;
                                $parsedBlockData[] = array( 
                                    'networksize' => round($networksize, 0),
                                    'blockyear' => $blockdata['blockyear'],
                                    'blockmonth' => $blockdata['blockmonth']-1,
                                    'blockday' => $blockdata['blockday'],
                                    'blockhour' => $blockdata['blockhour'],
                                    'blockmin' => $blockdata['blockmin']
                                    );
                                $i = 0;
                                $baseTarget = 0;
                        }
                }
                $this->smarty->assign('blockdata', $parsedBlockData);
                
                $this->template = 'Charts/estimated-network-size';
        }
}