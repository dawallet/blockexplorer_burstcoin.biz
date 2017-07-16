<?php
class ControllerBlockExplorer extends Controller {
        /**
         * Blöcke nach Tagen
         */
        function blocks() {                 
                // Prüfe Parameter
                if (isset($_GET['p0']) AND $_GET['p0'] >= 2014 AND isset($_GET['p1']) AND $_GET['p1'] >= 1 AND $_GET['p1'] <= 12 AND isset($_GET['p2']) AND $_GET['p2'] >= 1 AND $_GET['p2'] <= 31 AND checkdate($_GET['p1'], $_GET['p2'], $_GET['p0']) AND strtotime($_GET['p0'].'-'.$_GET['p1'].'-'.$_GET['p2']) >= strtotime("2014-08-11") AND strtotime($_GET['p0'].'-'.$_GET['p1'].'-'.$_GET['p2']) <= strtotime(date("Y-m-d"))) {
                        $blockdate = $_GET['p0'].'-'.$_GET['p1'].'-'.$_GET['p2'];
                } else {
                        $blockdate = date("Y-m-d");
                        $_GET['p0'] = date("Y");
                        $_GET['p1'] = date("m");
                        $_GET['p2'] = date("d");
                }
                $blockdatePrev = date("Y-m-d", mktime(0, 0, 0, $_GET['p1'], $_GET['p2']-1, $_GET['p0']));
                $blockdateNext = date("Y-m-d", mktime(0, 0, 0, $_GET['p1'], $_GET['p2']+1, $_GET['p0']));
                $this->smarty->assign('blockdate', $blockdate);
                if (strtotime($blockdatePrev) >= strtotime("2014-08-11")) {
                        $this->smarty->assign('blockdatePrev', $blockdatePrev);
                }
                if (strtotime($blockdateNext) <= strtotime(date("Y-m-d"))) {
                        $this->smarty->assign('blockdateNext', $blockdateNext);
                }
                
                // Auslesen der Blocks für den ausgewählten Tag
                $blockData = $this->db->query("SELECT * FROM ".DB_PRE."chain_blocks WHERE DATE(blockdate)='".$blockdate."' ORDER BY height DESC");
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
                            'blockdate'                 => $blockData['blockdate'],
                            'numberOfTransactions'      => $blockData['numberOfTransactions'],
                            'totalAmountNQT'            => $blockData['totalAmountNQT'],
                            'totalFeeNQT'               => $blockData['totalFeeNQT'],
                            'blockReward'               => $blockData['blockReward'],
                            'payloadLength'             => $payloadLength);
                }
                $this->smarty->assign('blockdata', $parsedBlockData);
                
                $this->template = 'BlockExplorer/blocks';
        }
        
        /**
         * Block Details
         */
        function block() {                
                // Prüfe Parameter
                if (isset($_GET['p0']) AND $_GET['p0'] > 0) {
                        // Auslesen des Blocks
                        $blockData = $this->db->query("SELECT t1.*, t2.accountRS FROM ".DB_PRE."chain_blocks AS t1 LEFT JOIN ".DB_PRE."chain_accounts AS t2 ON t1.generator=t2.account WHERE t1.block='".$this->db->escapeString($_GET['p0'])."'");
                        if (isset($blockData[0])) {
                                $this->smarty->assign('blockdata', $blockData[0]);
                                $this->smarty->assign('duration', $blockData[0]['duration']/60);
                                $this->smarty->assign('payloadLength', $blockData[0]['payloadLength']/1024);
                                
                                // Suche nach vorherigen Block
                                $previousBlockHeight = $blockData[0]['height']-1;
                                $previousBlock = $this->db->query("SELECT block FROM ".DB_PRE."chain_blocks WHERE height='".$previousBlockHeight."'");
                                if (isset($previousBlock[0])) {
                                        $this->smarty->assign('previousblock', $previousBlock[0]);
                                }
                                
                                // Suche nach nächsten Block
                                $nextBlockHeight = $blockData[0]['height']+1;
                                $nextBlock = $this->db->query("SELECT block FROM ".DB_PRE."chain_blocks WHERE height='".$nextBlockHeight."'");
                                if (isset($nextBlock[0])) {
                                        $this->smarty->assign('nextblock', $nextBlock[0]);
                                }
                                
                                // Auslesen aller Transaktionen in diesem Block
                                if ($blockData[0]['numberOfTransactions'] > 0) {
                                        $transactionData = $this->db->query("SELECT t1.transaction, t1.sender, t1.recipient, t1.amountNQT, t1.feeNQT, t1.transactiondate, t2.accountRS AS senderRS, t3.accountRS AS recipientRS FROM ".DB_PRE."chain_transactions AS t1 LEFT JOIN ".DB_PRE."chain_accounts AS t2 ON t1.sender=t2.account LEFT JOIN ".DB_PRE."chain_accounts AS t3 ON t1.recipient=t3.account WHERE t1.block='".$blockData[0]['block']."' ORDER BY transactiondate DESC");
                                        $this->smarty->assign('transactiondata', $transactionData);
                                }
                                
                                $this->template = 'BlockExplorer/block';
                        } else {
			    $this->request->redirect(HTTP_ROOT.'blocks');
			}
                } else {
		    $this->request->redirect(HTTP_ROOT.'blocks');
		}
        }
        
        /**
         * Account Details
         */
        function address() {                 
                // Prüfe Parameter
                if (isset($_GET['p0']) AND $_GET['p0'] > 0) {
                        // Auslesen der Adresse
                        $addressData = $this->db->query("SELECT *, UNIX_TIMESTAMP(accountFirstseen) AS accountFirstseenTS, UNIX_TIMESTAMP(lastActivity) AS lastActivityTS FROM ".DB_PRE."chain_accounts WHERE account='".$this->db->escapeString($_GET['p0'])."'");
                        if (isset($addressData[0])) {
                                $this->smarty->assign('addressdata', $addressData[0]);                                
                                $this->smarty->assign('firstseenAge', $this->validate->convertAge(time()-$addressData[0]['accountFirstseenTS']));                                
                                $this->smarty->assign('lastActivityAge', $this->validate->convertAge(time()-$addressData[0]['lastActivityTS']));
                                
                                // QR-Code erstellen
                                if (!file_exists(DIR_PUBLIC.DS.'media'.DS.'qr'.DS.$addressData[0]['accountRS'].'.png')) {
                                        QRcode::png($addressData[0]['accountRS'], DIR_PUBLIC.DS.'media'.DS.'qr'.DS.$addressData[0]['accountRS'].'.png', QR_ECLEVEL_H, 3, 1);
                                }
                                
                                // Zähle die gefundenen Blöcke
                                $countBlockData = $this->db->query("SELECT COUNT(*) AS forgedBlocks, SUM(blockReward) AS totalMined, SUM(totalFeeNQT) AS minedFees FROM ".DB_PRE."chain_blocks WHERE generator='".$addressData[0]['account']."'");
                                $this->smarty->assign('forgedBlocks', $countBlockData[0]['forgedBlocks']);
                                $this->smarty->assign('totalMinedBurst', $countBlockData[0]['totalMined']+$countBlockData[0]['minedFees']);
                                if ($countBlockData[0]['forgedBlocks'] == 0) {
                                        $blockPages = 0;
                                } else {
                                        $blockPages = ceil($countBlockData[0]['forgedBlocks']/20);
                                }
                                $this->smarty->assign('blockPages', $blockPages);
                                
                                // Ermittle die Anzahl an Transaktionen
                                $totalTransactions = $this->db->query("SELECT COUNT(*) AS transactions FROM ".DB_PRE."chain_transactions WHERE sender='".$addressData[0]['account']."' OR recipient='".$addressData[0]['account']."'");                                
                                $this->smarty->assign('totalTransactions', $totalTransactions[0]['transactions']);
                                if ($totalTransactions[0]['transactions'] == 0) {
                                        $transactionsPages = 0;
                                } else {
                                        $transactionsPages = ceil($totalTransactions[0]['transactions']/50);
                                }
                                $this->smarty->assign('transactionsPages', $transactionsPages);
                                
                                // Ermittle die anzuzeigende Seite
                                $pageBlocks = 1;
                                if (isset($_GET['p1']) AND $_GET['p1'] == "blocks" AND isset($_GET['p2']) AND $_GET['p2'] > 0 AND $_GET['p2'] <= $blockPages) {
                                        $pageBlocks = $_GET['p2'];
                                }
                                $this->smarty->assign('pageBlocks', $pageBlocks);
                                $pageTransactions = 1;
                                if (isset($_GET['p1']) AND $_GET['p1'] == "transactions" AND isset($_GET['p2']) AND $_GET['p2'] > 0 AND $_GET['p2'] <= $transactionsPages) {
                                        $pageTransactions = $_GET['p2'];
                                }
                                $this->smarty->assign('pageTransactions', $pageTransactions);
                                
                                // Suche nach Transaktionen
                                $sqlOffset = 0;
                                if ($pageTransactions > 1) {
                                        $pageTransactions = $pageTransactions-1;
                                        $sqlOffset = $pageTransactions*50;
                                }
                                $transactionData = $this->db->query("SELECT t1.transaction, t1.sender, t1.recipient, t1.amountNQT, t1.feeNQT, t1.transactiondate, t2.accountRS AS senderRS, t3.accountRS AS recipientRS FROM ".DB_PRE."chain_transactions AS t1 LEFT JOIN ".DB_PRE."chain_accounts AS t2 ON t1.sender=t2.account LEFT JOIN ".DB_PRE."chain_accounts AS t3 ON t1.recipient=t3.account WHERE t1.sender='".$addressData[0]['account']."' OR t1.recipient='".$addressData[0]['account']."' ORDER BY t1.transactiondate DESC LIMIT ".$sqlOffset.",50");                                
                                $this->smarty->assign('transactiondata', $transactionData);
                                
                                // Ermittle die Summe des empfangenen Guthabens
                                $totalReceived = $this->db->query("SELECT SUM(amountNQT) AS totalReceived FROM ".DB_PRE."chain_transactions WHERE recipient='".$addressData[0]['account']."'");                                
                                $this->smarty->assign('totalReceived', $totalReceived[0]['totalReceived']);
                                
                                // Ermittle die Summe des gesendeten Guthabens
                                $totalSent = $this->db->query("SELECT SUM(amountNQT) AS totalSent FROM ".DB_PRE."chain_transactions WHERE sender='".$addressData[0]['account']."'");                                
                                $this->smarty->assign('totalSent', $totalSent[0]['totalSent']);
                                
                                // Ermittle die Pool Mined Balance
                                require_once DIR_SYSTEM.DS.'config'.DS.'site.php';
                                $poolAddr = '';
                                foreach ($conf['pools'] AS $pool) {
                                        if (!empty($poolAddr)) { $poolAddr.= ', '; }
                                        $poolAddr.= $pool['addr'];
                                }
                                $poolMinedBalance = $this->db->query("SELECT SUM(amountNQT) AS balance FROM ".DB_PRE."chain_transactions WHERE recipient='".$addressData[0]['account']."' AND sender IN (".$poolAddr.")");                         
                                $this->smarty->assign('poolMinedBalance', $poolMinedBalance[0]['balance']);
                                
                                // Ermittle den Rang in der Rich List
                                $richListRank = $this->db->query("SELECT COUNT(*) AS rank FROM ".DB_PRE."chain_accounts WHERE unconfirmedBalanceNQT>='".$addressData[0]['unconfirmedBalanceNQT']."'");                         
                                $this->smarty->assign('richListRank', $richListRank[0]['rank']);
                                
                                // Suche nach gefundenen Blöcken
                                $sqlOffset = 0;
                                if ($pageBlocks > 1) {
                                        $pageBlocks = $pageBlocks-1;
                                        $sqlOffset = $pageBlocks*20;
                                }
                                $blockData = $this->db->query("SELECT height, block, blockReward, totalFeeNQT, UNIX_TIMESTAMP(blockdate) AS blockdateTS FROM ".DB_PRE."chain_blocks WHERE generator='".$addressData[0]['account']."' ORDER BY height DESC LIMIT ".$sqlOffset.",20");
                                if (isset($blockData[0])) {
                                        $parsedBlockData = '';
                                        foreach ($blockData AS $blockdata) {
                                                $parsedBlockData[] = array(
                                                    'height'            => $blockdata['height'],
                                                    'block'             => $blockdata['block'],
                                                    'blockReward'       => $blockdata['blockReward'],
                                                    'totalFeeNQT'       => $blockdata['totalFeeNQT'],
                                                    'blockdate'         => $this->validate->convertAge(time()-$blockdata['blockdateTS']),
                                                );
                                        }
                                        $this->smarty->assign('blockdata', $parsedBlockData);
                                }
                                
                                $this->template = 'BlockExplorer/address';
                        } else {
			    $this->request->redirect(HTTP_ROOT.'blocks');
			}
                } else {
		    $this->request->redirect(HTTP_ROOT.'blocks');
		}
        }
        
        /**
         * Chart: Anzahl der Transaktionen pro Tag per Adresse
         */
        function addressChartTransactions() {                
                // Prüfe Parameter
                if (isset($_GET['p0']) AND $_GET['p0'] > 0) {
                        // Auslesen der Adresse
                        $addressData = $this->db->query("SELECT *, UNIX_TIMESTAMP(accountFirstseen) AS accountFirstseenTS FROM ".DB_PRE."chain_accounts WHERE account='".$this->db->escapeString($_GET['p0'])."'");
                        if (isset($addressData[0])) {
                                $this->smarty->assign('addressdata', $addressData[0]);                                
                                $datetime1 = new DateTime(date("Y-m-d", $addressData[0]['accountFirstseenTS']));
                                $datetime2 = new DateTime(date("Y-m-d"));
                                $interval = $datetime1->diff($datetime2)->format('%a');
                                $starttime = mktime(0, 0, 0, date("m", $addressData[0]['accountFirstseenTS']), date("d", $addressData[0]['accountFirstseenTS'])-1, date("Y", $addressData[0]['accountFirstseenTS']));
                                $starttime = $starttime+7200;
                                
                                // Lade JS-Charts
                                $this->smarty->assign('jsStocks', true);

                                // Auslesen der vorhandenen Burstcoins
                                $transactionData = $this->db->query("SELECT COUNT(*) AS transactions, DATE_FORMAT(transactiondate,'%Y') AS transactionyear, DATE_FORMAT(transactiondate,'%m') AS transactionmonth, DATE_FORMAT(transactiondate,'%e') AS transactionday FROM ".DB_PRE."chain_transactions WHERE sender='".$addressData[0]['account']."' OR recipient='".$addressData[0]['account']."' GROUP BY DATE(transactiondate) ORDER BY transactiondate ASC");
                                foreach ($transactionData AS $transactiondata) {
                                        $parsedTransactionData[date("Y-m-d", mktime(0, 0, 0, $transactiondata['transactionmonth'], $transactiondata['transactionday'], $transactiondata['transactionyear']))] = array(
                                            'transactions' => $transactiondata['transactions'],
                                            'transactionyear' => $transactiondata['transactionyear'],
                                            'transactionmonth' => $transactiondata['transactionmonth']-1,
                                            'transactionday' => $transactiondata['transactionday']
                                            );
                                }
                                for ($i = 1; $i <= $interval; $i++) {
                                        $starttime = $starttime+86400;
                                        if (!isset($parsedTransactionData[date("Y-m-d", $starttime)])) {
                                                $parsedTransactionData[date("Y-m-d", $starttime)] = array(
                                                    'transactions' => 0,
                                                    'transactionyear' => date("Y", $starttime),
                                                    'transactionmonth' => date("m", $starttime)-1,
                                                    'transactionday' => date("j", $starttime)
                                                    );                                                
                                        }
                                }
                                ksort($parsedTransactionData);
                                $this->smarty->assign('transactiondata', $parsedTransactionData);
                                
                                $this->template = 'BlockExplorer/address-chart-transactions';
                        } else {
			    $this->request->redirect(HTTP_ROOT.'blocks');
			}
                } else {
		    $this->request->redirect(HTTP_ROOT.'blocks');
		}
        }
        
        /**
         * Chart: Empfange Burstcoins pro Tag per Adresse
         */
        function addressChartTotalReceived() {                
                // Prüfe Parameter
                if (isset($_GET['p0']) AND $_GET['p0'] > 0) {
                        // Auslesen der Adresse
                        $addressData = $this->db->query("SELECT *, UNIX_TIMESTAMP(accountFirstseen) AS accountFirstseenTS FROM ".DB_PRE."chain_accounts WHERE account='".$this->db->escapeString($_GET['p0'])."'");
                        if (isset($addressData[0])) {
                                $this->smarty->assign('addressdata', $addressData[0]);                                
                                $datetime1 = new DateTime(date("Y-m-d", $addressData[0]['accountFirstseenTS']));
                                $datetime2 = new DateTime(date("Y-m-d"));
                                $interval = $datetime1->diff($datetime2)->format('%a');
                                $starttime = mktime(0, 0, 0, date("m", $addressData[0]['accountFirstseenTS']), date("d", $addressData[0]['accountFirstseenTS'])-1, date("Y", $addressData[0]['accountFirstseenTS']));
                                $starttime = $starttime+7200;
                                
                                // Lade JS-Charts
                                $this->smarty->assign('jsStocks', true);

                                // Auslesen der empfangenen Burstcoins
                                $transactionData = $this->db->query("SELECT SUM(amountNQT) AS transactions, DATE_FORMAT(transactiondate,'%Y') AS transactionyear, DATE_FORMAT(transactiondate,'%m') AS transactionmonth, DATE_FORMAT(transactiondate,'%e') AS transactionday FROM ".DB_PRE."chain_transactions WHERE recipient='".$addressData[0]['account']."' GROUP BY DATE(transactiondate) ORDER BY transactiondate ASC");
                                foreach ($transactionData AS $transactiondata) {
                                        $parsedTransactionData[date("Y-m-d", mktime(0, 0, 0, $transactiondata['transactionmonth'], $transactiondata['transactionday'], $transactiondata['transactionyear']))] = array(
                                            'transactions' => round($transactiondata['transactions'], 2),
                                            'transactionyear' => $transactiondata['transactionyear'],
                                            'transactionmonth' => $transactiondata['transactionmonth']-1,
                                            'transactionday' => $transactiondata['transactionday']
                                            );
                                }
                                for ($i = 1; $i <= $interval; $i++) {
                                        $starttime = $starttime+86400;
                                        if (!isset($parsedTransactionData[date("Y-m-d", $starttime)])) {
                                                $parsedTransactionData[date("Y-m-d", $starttime)] = array(
                                                    'transactions' => 0,
                                                    'transactionyear' => date("Y", $starttime),
                                                    'transactionmonth' => date("m", $starttime)-1,
                                                    'transactionday' => date("j", $starttime)
                                                    );                                                
                                        }
                                }
                                ksort($parsedTransactionData);
                                $this->smarty->assign('transactiondata', $parsedTransactionData);
                                
                                $this->template = 'BlockExplorer/address-chart-total-received';
                        } else {
			    $this->request->redirect(HTTP_ROOT.'blocks');
			}
                } else {
		    $this->request->redirect(HTTP_ROOT.'blocks');
		}
        }
        
        /**
         * Chart: Gesendete Burstcoins pro Tag per Adresse
         */
        function addressChartTotalSent() {                
                // Prüfe Parameter
                if (isset($_GET['p0']) AND $_GET['p0'] > 0) {
                        // Auslesen der Adresse
                        $addressData = $this->db->query("SELECT *, UNIX_TIMESTAMP(accountFirstseen) AS accountFirstseenTS FROM ".DB_PRE."chain_accounts WHERE account='".$this->db->escapeString($_GET['p0'])."'");
                        if (isset($addressData[0])) {
                                $this->smarty->assign('addressdata', $addressData[0]);                                
                                $datetime1 = new DateTime(date("Y-m-d", $addressData[0]['accountFirstseenTS']));
                                $datetime2 = new DateTime(date("Y-m-d"));
                                $interval = $datetime1->diff($datetime2)->format('%a');
                                $starttime = mktime(0, 0, 0, date("m", $addressData[0]['accountFirstseenTS']), date("d", $addressData[0]['accountFirstseenTS'])-1, date("Y", $addressData[0]['accountFirstseenTS']));
                                $starttime = $starttime+7200;
                                
                                // Lade JS-Charts
                                $this->smarty->assign('jsStocks', true);

                                // Auslesen der gesendeten Burstcoins
                                $transactionData = $this->db->query("SELECT SUM(amountNQT) AS transactions, DATE_FORMAT(transactiondate,'%Y') AS transactionyear, DATE_FORMAT(transactiondate,'%m') AS transactionmonth, DATE_FORMAT(transactiondate,'%e') AS transactionday FROM ".DB_PRE."chain_transactions WHERE sender='".$addressData[0]['account']."' GROUP BY DATE(transactiondate) ORDER BY transactiondate ASC");
                                foreach ($transactionData AS $transactiondata) {
                                        $parsedTransactionData[date("Y-m-d", mktime(0, 0, 0, $transactiondata['transactionmonth'], $transactiondata['transactionday'], $transactiondata['transactionyear']))] = array(
                                            'transactions' => round($transactiondata['transactions'], 2),
                                            'transactionyear' => $transactiondata['transactionyear'],
                                            'transactionmonth' => $transactiondata['transactionmonth']-1,
                                            'transactionday' => $transactiondata['transactionday']
                                            );
                                }
                                for ($i = 1; $i <= $interval; $i++) {
                                        $starttime = $starttime+86400;
                                        if (!isset($parsedTransactionData[date("Y-m-d", $starttime)])) {
                                                $parsedTransactionData[date("Y-m-d", $starttime)] = array(
                                                    'transactions' => 0,
                                                    'transactionyear' => date("Y", $starttime),
                                                    'transactionmonth' => date("m", $starttime)-1,
                                                    'transactionday' => date("j", $starttime)
                                                    );                                                
                                        }
                                }
                                ksort($parsedTransactionData);
                                $this->smarty->assign('transactiondata', $parsedTransactionData);
                                
                                $this->template = 'BlockExplorer/address-chart-total-sent';
                        } else {
			    $this->request->redirect(HTTP_ROOT.'blocks');
			}
                } else {
		    $this->request->redirect(HTTP_ROOT.'blocks');
		}
        }
        
        /**
         * Chart: Gefunde Blöcke pro Tag per Adresse
         */
        function addressChartForgedBlocks() {                
                // Prüfe Parameter
                if (isset($_GET['p0']) AND $_GET['p0'] > 0) {
                        // Auslesen der Adresse
                        $addressData = $this->db->query("SELECT *, UNIX_TIMESTAMP(accountFirstseen) AS accountFirstseenTS FROM ".DB_PRE."chain_accounts WHERE account='".$this->db->escapeString($_GET['p0'])."'");
                        if (isset($addressData[0])) {
                                $this->smarty->assign('addressdata', $addressData[0]);                                
                                $datetime1 = new DateTime(date("Y-m-d", $addressData[0]['accountFirstseenTS']));
                                $datetime2 = new DateTime(date("Y-m-d"));
                                $interval = $datetime1->diff($datetime2)->format('%a');
                                $starttime = mktime(0, 0, 0, date("m", $addressData[0]['accountFirstseenTS']), date("d", $addressData[0]['accountFirstseenTS'])-1, date("Y", $addressData[0]['accountFirstseenTS']));
                                $starttime = $starttime+7200;
                                
                                // Lade JS-Charts
                                $this->smarty->assign('jsStocks', true);

                                // Auslesen der gefundenen Blöcke
                                $transactionData = $this->db->query("SELECT COUNT(*) AS transactions, DATE_FORMAT(blockdate,'%Y') AS transactionyear, DATE_FORMAT(blockdate,'%m') AS transactionmonth, DATE_FORMAT(blockdate,'%e') AS transactionday FROM ".DB_PRE."chain_blocks WHERE generator='".$addressData[0]['account']."' GROUP BY DATE(blockdate) ORDER BY blockdate ASC");
                                foreach ($transactionData AS $transactiondata) {
                                        $parsedTransactionData[date("Y-m-d", mktime(0, 0, 0, $transactiondata['transactionmonth'], $transactiondata['transactionday'], $transactiondata['transactionyear']))] = array(
                                            'transactions' => $transactiondata['transactions'],
                                            'transactionyear' => $transactiondata['transactionyear'],
                                            'transactionmonth' => $transactiondata['transactionmonth']-1,
                                            'transactionday' => $transactiondata['transactionday']
                                            );
                                }
                                for ($i = 1; $i <= $interval; $i++) {
                                        $starttime = $starttime+86400;
                                        if (!isset($parsedTransactionData[date("Y-m-d", $starttime)])) {
                                                $parsedTransactionData[date("Y-m-d", $starttime)] = array(
                                                    'transactions' => 0,
                                                    'transactionyear' => date("Y", $starttime),
                                                    'transactionmonth' => date("m", $starttime)-1,
                                                    'transactionday' => date("j", $starttime)
                                                    );                                                
                                        }
                                }
                                ksort($parsedTransactionData);
                                $this->smarty->assign('transactiondata', $parsedTransactionData);
                                
                                $this->template = 'BlockExplorer/address-chart-forged-blocks';
                        } else {
				$this->request->redirect(HTTP_ROOT.'blocks');
			}
                } else {
			$this->request->redirect(HTTP_ROOT.'blocks');
		}
        }        
        
        /**
         * Chart: Pool Mined Burstcoins pro Tag per Adresse
         */
        function addressChartPoolMined() {                
                // Prüfe Parameter
                if (isset($_GET['p0']) AND $_GET['p0'] > 0) {
                        // Auslesen der Adresse
                        $addressData = $this->db->query("SELECT *, UNIX_TIMESTAMP(accountFirstseen) AS accountFirstseenTS FROM ".DB_PRE."chain_accounts WHERE account='".$this->db->escapeString($_GET['p0'])."'");
                        if (isset($addressData[0])) {
                                $this->smarty->assign('addressdata', $addressData[0]);                                
                                $datetime1 = new DateTime(date("Y-m-d", $addressData[0]['accountFirstseenTS']));
                                $datetime2 = new DateTime(date("Y-m-d"));
                                $interval = $datetime1->diff($datetime2)->format('%a');
                                $starttime = mktime(0, 0, 0, date("m", $addressData[0]['accountFirstseenTS']), date("d", $addressData[0]['accountFirstseenTS'])-1, date("Y", $addressData[0]['accountFirstseenTS']));
                                $starttime = $starttime+7200;
                                
                                // Lade JS-Charts
                                $this->smarty->assign('jsStocks', true);

                                // Auslesen der Pool Mined Burstcoins                                
                                require_once DIR_SYSTEM.DS.'config'.DS.'site.php';
                                $poolAddr = '';
                                foreach ($conf['pools'] AS $pool) {
                                        if (!empty($poolAddr)) { $poolAddr.= ', '; }
                                        $poolAddr.= $pool['addr'];
                                }
                                $transactionData = $this->db->query("SELECT SUM(amountNQT) AS transactions, DATE_FORMAT(transactiondate,'%Y') AS transactionyear, DATE_FORMAT(transactiondate,'%m') AS transactionmonth, DATE_FORMAT(transactiondate,'%e') AS transactionday FROM ".DB_PRE."chain_transactions WHERE recipient='".$addressData[0]['account']."' AND sender IN (".$poolAddr.") GROUP BY DATE(transactiondate) ORDER BY transactiondate ASC");
                                foreach ($transactionData AS $transactiondata) {
                                        $parsedTransactionData[date("Y-m-d", mktime(0, 0, 0, $transactiondata['transactionmonth'], $transactiondata['transactionday'], $transactiondata['transactionyear']))] = array(
                                            'transactions' => round($transactiondata['transactions'], 2),
                                            'transactionyear' => $transactiondata['transactionyear'],
                                            'transactionmonth' => $transactiondata['transactionmonth']-1,
                                            'transactionday' => $transactiondata['transactionday']
                                            );
                                }
                                for ($i = 1; $i <= $interval; $i++) {
                                        $starttime = $starttime+86400;
                                        if (!isset($parsedTransactionData[date("Y-m-d", $starttime)])) {
                                                $parsedTransactionData[date("Y-m-d", $starttime)] = array(
                                                    'transactions' => 0,
                                                    'transactionyear' => date("Y", $starttime),
                                                    'transactionmonth' => date("m", $starttime)-1,
                                                    'transactionday' => date("j", $starttime)
                                                    );                                                
                                        }
                                }
                                ksort($parsedTransactionData);
                                $this->smarty->assign('transactiondata', $parsedTransactionData);
                                
                                $this->template = 'BlockExplorer/address-chart-pool-mined';
                        } else {
				$this->request->redirect(HTTP_ROOT.'blocks');
			}
                } else {
			$this->request->redirect(HTTP_ROOT.'blocks');
		}
        }        
        
        /**
         * Export: Gefunde Blöcke für eine Adresse als Excel exportieren
         */
        function addressExportForgedBlocks() {
                set_time_limit(0);
                ini_set('max_execution_time', 0);
                                
                // Prüfe Parameter
                if (isset($_GET['p0']) AND $_GET['p0'] > 0) {
                        // Auslesen der Adresse
                        $addressData = $this->db->query("SELECT * FROM ".DB_PRE."chain_accounts WHERE account='".$this->db->escapeString($_GET['p0'])."'");
                        if (isset($addressData[0])) {
                                require_once DIR_SYSTEM.DS.'config'.DS.'site.php';
                                
                                // Lade PHP-Excel Library
                                require_once DIR_SYSTEM.DS.'core'.DS.'libs'.DS.'phpexcel'.DS.'Classes'.DS.'PHPExcel.php';

                                // Erstelle PHPExcel Objekt und setze Dokumenten Eigenschaften
                                $objPHPExcel = new PHPExcel();
                                $objPHPExcel->getProperties()->setCreator("burstcoin.biz")
                                                                 ->setLastModifiedBy("burstcoin.biz")
                                                                 ->setTitle("Forged Blocks for address ".$addressData[0]['accountRS'])
                                                                 ->setSubject("Forged Blocks for address ".$addressData[0]['accountRS'])
                                                                 ->setDescription("Forged Blocks for address ".$addressData[0]['accountRS'])
                                                                 ->setKeywords("burstcoin.biz forged blocks ".$addressData[0]['accountRS'])
                                                                 ->setCategory("Export");

                                // Setze die Überschriften
                                $objPHPExcel->setActiveSheetIndex(0)
                                            ->setCellValue('A1', 'Block')
                                            ->setCellValue('B1', 'Height')
                                            ->setCellValue('C1', 'Date')
                                            ->setCellValue('D1', 'Reward')
                                            ->setCellValue('E1', 'Fee')
                                            ->setCellValue('F1', 'Base Target')
                                            ->setCellValue('G1', 'Nonce')
                                            ->setCellValue('H1', 'Block Generation Time');
                                $objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFont()->setBold(true);

                                // Definiere die Text-Formatierung
                                $link_style_array = [
                                        'font'  => [
                                                'color' => ['rgb' => '0000FF'],
                                                'underline' => 'single'
                                        ]
                                ];
                                
                                // Lese alle Blöcke für eine Adresse aus
                                $blockData = $this->db->query("SELECT height, block, blockReward, totalFeeNQT, blockdate, baseTarget, nonce, duration FROM ".DB_PRE."chain_blocks WHERE generator='".$addressData[0]['account']."' ORDER BY height DESC");
                                if (isset($blockData[0])) {
                                        $i = 1;
                                        foreach ($blockData AS $block) {
                                                $i++;
                                                $objPHPExcel->getActiveSheet()
                                                            ->setCellValue('A'.$i, $block['block'])
                                                            ->setCellValue('B'.$i, $block['height'])
                                                            ->setCellValue('C'.$i, $block['blockdate'])
                                                            ->setCellValue('D'.$i, $block['blockReward'])
                                                            ->setCellValue('E'.$i, $block['totalFeeNQT'])
                                                            ->setCellValue('F'.$i, $block['baseTarget'])
                                                            ->setCellValue('G'.$i, $block['nonce'])
                                                            ->setCellValue('H'.$i, round($block['duration']/60, 2)." minutes");
                                                
                                                // Formatiere die Zellen
                                                $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode('0');
                                                $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getHyperlink()->setUrl("http://burstcoin.biz/block/".$block['block']);
                                                $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($link_style_array);
                                        }
                                }

                                // Benenne das Arbeitsblatt und setze es auf aktiv
                                $objPHPExcel->getActiveSheet()->setTitle('Forged Blocks');
                                $objPHPExcel->setActiveSheetIndex(0);

                                // Spaltenbereite automatisch setzen
                                for ($colCount = 'A'; $colCount != 'I'; $colCount++) {
                                        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($colCount)->setAutoSize(true);
                                }

                                // Ausgabe der Datei an Browser weiterleiten (Excel5)
                                header('Content-Type: application/vnd.ms-excel');
                                header('Content-Disposition: attachment;filename="burst_forged_blocks_'.date("Y-m-D_H-i-s").'.xls"');
                                header('Cache-Control: max-age=0'); // Bei IE 9 max-age evtl. auf 1 setzen
                                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Für IE über SSL-Verbindung
                                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
                                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                                header ('Pragma: public'); // HTTP/1.0
                                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                                $objWriter->save('php://output');
                                exit();
                        } else {
				$this->request->redirect(HTTP_ROOT.'blocks');
			}
                } else {
			$this->request->redirect(HTTP_ROOT.'blocks');
		}
        }
        
        /**
         * Export: Transaktionen für eine Adresse als Excel exportieren
         */
        function addressExportTransactions() {
                set_time_limit(0);
                ini_set('max_execution_time', 0);
                
                // Prüfe Parameter
                if (isset($_GET['p0']) AND $_GET['p0'] > 0) {
                        // Auslesen der Adresse
                        $addressData = $this->db->query("SELECT * FROM ".DB_PRE."chain_accounts WHERE account='".$this->db->escapeString($_GET['p0'])."'");
                        if (isset($addressData[0])) {
                                require_once DIR_SYSTEM.DS.'config'.DS.'site.php';
                                
                                // Lade PHP-Excel Library
                                require_once DIR_SYSTEM.DS.'core'.DS.'libs'.DS.'phpexcel'.DS.'Classes'.DS.'PHPExcel.php';

                                // Erstelle PHPExcel Objekt und setze Dokumenten Eigenschaften
                                $objPHPExcel = new PHPExcel();
                                $objPHPExcel->getProperties()->setCreator("burstcoin.biz")
                                                                 ->setLastModifiedBy("burstcoin.biz")
                                                                 ->setTitle("Transactions for address ".$addressData[0]['accountRS'])
                                                                 ->setSubject("Transactions for address ".$addressData[0]['accountRS'])
                                                                 ->setDescription("Transactions for address ".$addressData[0]['accountRS'])
                                                                 ->setKeywords("burstcoin.biz transactions ".$addressData[0]['accountRS'])
                                                                 ->setCategory("Export");

                                // Setze die Überschriften
                                $objPHPExcel->setActiveSheetIndex(0)
                                            ->setCellValue('A1', 'Transaction')
                                            ->setCellValue('B1', 'Date')
                                            ->setCellValue('C1', 'Sender')
                                            ->setCellValue('D1', 'Recipient')
                                            ->setCellValue('E1', 'Amount')
                                            ->setCellValue('F1', 'Fee')
                                            ->setCellValue('G1', 'Type')
                                            ->setCellValue('H1', 'Block');
                                $objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFont()->setBold(true);

                                // Definiere die Text-Formatierung
                                $link_style_array = [
                                        'font'  => [
                                                'color' => ['rgb' => '0000FF'],
                                                'underline' => 'single'
                                        ]
                                ];
                                $text_red_array = [
                                        'font'  => [
                                                'color' => ['rgb' => 'aa1f1f']
                                        ]
                                ];
                                $text_green_array = [
                                        'font'  => [
                                                'color' => ['rgb' => '3c763d']
                                        ]
                                ];
                                
                                // Lese alle Transaktionen für eine Adresse aus
                                $transactionData = $this->db->query("SELECT t1.transaction, t1.sender, t1.recipient, t1.amountNQT, t1.feeNQT, t1.transactiondate, t1.type, t1.subtype, t1.block, t2.accountRS AS senderRS, t3.accountRS AS recipientRS FROM ".DB_PRE."chain_transactions AS t1 LEFT JOIN ".DB_PRE."chain_accounts AS t2 ON t1.sender=t2.account LEFT JOIN ".DB_PRE."chain_accounts AS t3 ON t1.recipient=t3.account WHERE t1.sender='".$addressData[0]['account']."' OR t1.recipient='".$addressData[0]['account']."' ORDER BY t1.transactiondate DESC", true);
                                if ($this->db->numRows > 0) {
                                        $i = 1;
                                        while ($transaction = $transactionData->fetch_array(MYSQL_ASSOC)) {
                                                $i++;
                                                $objPHPExcel->getActiveSheet()
                                                            ->setCellValue('A'.$i, $transaction['transaction'])
                                                            ->setCellValue('B'.$i, $transaction['transactiondate'])
                                                            ->setCellValue('C'.$i, $transaction['senderRS'])
                                                            ->setCellValue('D'.$i, $transaction['recipientRS'])
                                                            ->setCellValue('E'.$i, $transaction['amountNQT'])
                                                            ->setCellValue('F'.$i, $transaction['feeNQT'])
                                                            ->setCellValue('G'.$i, $conf['transactiontypes'][$transaction['type']][$transaction['subtype']])
                                                            ->setCellValue('H'.$i, $transaction['block']);
                                                
                                                // Formatiere die Zellen
                                                $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode('0');
                                                $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getHyperlink()->setUrl("http://burstcoin.biz/transaction/".$transaction['transaction']);
                                                $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($link_style_array);
                                                $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getHyperlink()->setUrl("http://burstcoin.biz/address/".$transaction['sender']);
                                                $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($link_style_array);
                                                $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getHyperlink()->setUrl("http://burstcoin.biz/address/".$transaction['recipient']);
                                                $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($link_style_array);
                                                if ($transaction['sender'] == $addressData[0]['account']) {
                                                        $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($text_red_array);
                                                } else {                                                        
                                                        $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($text_green_array);
                                                }
                                                $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode('0');
                                                $objPHPExcel->getActiveSheet()->getCell('H'.$i)->getHyperlink()->setUrl("http://burstcoin.biz/block/".$transaction['block']);
                                                $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($link_style_array);
                                        }
                                }

                                // Benenne das Arbeitsblatt und setze es auf aktiv
                                $objPHPExcel->getActiveSheet()->setTitle('Transactions');
                                $objPHPExcel->setActiveSheetIndex(0);

                                // Spaltenbereite automatisch setzen
                                for ($colCount = 'A'; $colCount != 'I'; $colCount++) {
                                        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($colCount)->setAutoSize(true);
                                }

                                // Ausgabe der Datei an Browser weiterleiten (Excel5)
                                header('Content-Type: application/vnd.ms-excel');
                                header('Content-Disposition: attachment;filename="burst_transactions_'.date("Y-m-D_H-i-s").'.xls"');
                                header('Cache-Control: max-age=0'); // Bei IE 9 max-age evtl. auf 1 setzen
                                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Für IE über SSL-Verbindung
                                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
                                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                                header ('Pragma: public'); // HTTP/1.0
                                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                                $objWriter->save('php://output');
                                exit();
                        } else {
				$this->request->redirect(HTTP_ROOT.'blocks');
			}
                } else {
			$this->request->redirect(HTTP_ROOT.'blocks');
		}
        }
        
        /**
         * Transaktion Details
         */
        function transaction() {                
                // Prüfe Parameter
                if (isset($_GET['p0']) AND $_GET['p0'] > 0) {
                        // Auslesen der Transaktion
                        $transactionData = $this->db->query("SELECT t1.*, t2.accountRS AS senderRS, t3.accountRS AS recipientRS, t4.height FROM ".DB_PRE."chain_transactions AS t1 LEFT JOIN ".DB_PRE."chain_accounts AS t2 ON t1.sender=t2.account LEFT JOIN ".DB_PRE."chain_accounts AS t3 ON t1.recipient=t3.account LEFT JOIN ".DB_PRE."chain_blocks AS t4 ON t1.block=t4.block WHERE t1.transaction='".$this->db->escapeString($_GET['p0'])."'");
                        if (isset($transactionData[0])) {
                                $this->smarty->assign('transactiondata', $transactionData[0]);
                                
                                // Berechne die Anzahl der Confirmations
                                $this->globalstatsData = $this->db->query("SELECT blocks FROM ".DB_PRE."stats");
                                $this->smarty->assign('confirmations', $this->globalstatsData[0]['blocks']-1-$transactionData[0]['height']);
                                
                                // Prüfe auf Anhang
                                if (!empty($transactionData[0]['attachment'])) {
                                        $attachment = unserialize($transactionData[0]['attachment']);
                                        $this->smarty->assign('attachment', $attachment);
                                }
                                
                                // Ermittle den Transaktionstyp
                                require_once DIR_SYSTEM.DS.'config'.DS.'site.php';
                                $this->smarty->assign('type', $conf['transactiontypes'][$transactionData[0]['type']][$transactionData[0]['subtype']]);
                                
                                $this->template = 'BlockExplorer/transaction';
                        } else {
				$this->request->redirect(HTTP_ROOT.'blocks');
			}
                } else {
			$this->request->redirect(HTTP_ROOT.'blocks');
		}
        }
}