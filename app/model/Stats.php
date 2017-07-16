<?php
class ModelStats extends Model {
	/**
	 * Bereitet Zeitangaben zum Filtern von Queries vor
	 */
        function constructDateTime() {
	    $this->today = time();
	    $this->last30d = mktime(0, 0, 0, date("m")-1, date("d"), date("Y"));
	}
	
	/**
	 * Ließt alle Burstcoins aus die sich im Umlauf befinden.
	 */
        public function totalSupply() {
		$this->constructDateTime();
		
		// Ermittle Anzahl aller Burstcoins
                $getStats = $this->db->query("SELECT SUM(blockReward) AS totalSupply FROM ".DB_PRE."chain_blocks WHERE height<>'0'");
		$this->smarty->assign('globalStatsTotalSupply', $getStats[0]['totalSupply']);
                
                // Auslesen der neu generierten Burstcoins pro Tag
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
		$this->smarty->assign('globalStatsNewBurstcoinsChart', $parsedBlockData);
        }
	
	/**
	 * Ermittelt die Anzahl der Transaktionen.
	 */
	public function totalTransactions() {
		$this->constructDateTime();
		
		// Ermittle Anzahl aller Transaktionen
                $getStats = $this->db->query("SELECT COUNT(*) AS totalTransactions FROM ".DB_PRE."chain_transactions");            
		$this->smarty->assign('globalStatsTotalTransactions', $getStats[0]['totalTransactions']);
		
                // Auslesen der vorhandenen Burstcoins
                $transactionData = $this->db->query("SELECT COUNT(*) AS transactions, DATE_FORMAT(transactiondate,'%Y') AS transactionyear, DATE_FORMAT(transactiondate,'%m') AS transactionmonth, DATE_FORMAT(transactiondate,'%e') AS transactionday FROM ".DB_PRE."chain_transactions WHERE transactiondate>='".date("Y-m-d", $this->last30d)."' GROUP BY DATE(transactiondate) ORDER BY transactiondate ASC");
                foreach ($transactionData AS $transactiondata) {
                        $parsedTransactionData[] = array(
                            'transactions' => $transactiondata['transactions'],
                            'transactionyear' => $transactiondata['transactionyear'],
                            'transactionmonth' => $transactiondata['transactionmonth']-1,
                            'transactionday' => $transactiondata['transactionday']
                            );
                }
		$this->smarty->assign('globalStatsNewTransactionsChart', $parsedTransactionData);
	}
	
	/**
	 * Ermittelt die Anzahl der Wallets.
	 */
	public function totalWallets() {
		$this->constructDateTime();
		
		// Ermittle Anzahl aller Wallets
                $getStats = $this->db->query("SELECT COUNT(*) AS totalWallets FROM ".DB_PRE."chain_accounts");		
		$this->smarty->assign('globalStatsTotalWallets', $getStats[0]['totalWallets']);           
	    
                // Lese die neuen Wallets aus den letzten 30 Tagen aus
                $walletData = $this->db->query("SELECT COUNT(*) AS wallets, DATE_FORMAT(accountFirstseen,'%Y') AS walletyear, DATE_FORMAT(accountFirstseen,'%m') AS walletmonth, DATE_FORMAT(accountFirstseen,'%e') AS walletday FROM ".DB_PRE."chain_accounts WHERE accountFirstseen>='".date("Y-m-d", $this->last30d)."' GROUP BY DATE(accountFirstseen) ORDER BY accountFirstseen ASC");
                foreach ($walletData AS $walletdata) {
                        $parsedWalletsData[] = array(                            
                            'wallets' => $walletdata['wallets'],
                            'walletyear' => $walletdata['walletyear'],
                            'walletmonth' => $walletdata['walletmonth']-1,
                            'walletday' => $walletdata['walletday']
                            );
                }
		$this->smarty->assign('globalStatsNewWalletsChart', $parsedWalletsData);
	}
	
	/**
	 * Ermittelt die Hashrate.
	 */
	public function networkSize() {
		$this->constructDateTime();
	    
		// Berechne die aktuelle Hashrate
		$blockBaseTarget = $this->db->query("SELECT baseTarget FROM ".DB_PRE."chain_blocks ORDER BY height DESC LIMIT 50");
		$baseTarget = 0;
		foreach ($blockBaseTarget AS $blockbasetarget) {
			$baseTarget = $baseTarget+$blockbasetarget['baseTarget'];
		}
		$baseTarget = $baseTarget/50;
		$baseTarget = $baseTarget*960000000;
		$networksize = pow(2, 64)/$baseTarget;
		$this->smarty->assign('globalStatsNetworkSize', round($networksize, 0));
		
		// Berechne die Hashrate-Entwicklung der letzten 30 Tage
                $blockData = $this->db->query("SELECT baseTarget, DATE_FORMAT(blockdate,'%Y') AS blockyear, DATE_FORMAT(blockdate,'%m') AS blockmonth, DATE_FORMAT(blockdate,'%e') AS blockday, DATE_FORMAT(blockdate,'%k') AS blockhour, DATE_FORMAT(blockdate,'%i') AS blockmin FROM ".DB_PRE."chain_blocks WHERE blockdate>='".date("Y-m-d", $this->last30d)."' ORDER BY height ASC");
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
		$this->smarty->assign('globalStatsNetworkSizeChart', $parsedBlockData);
	}
}