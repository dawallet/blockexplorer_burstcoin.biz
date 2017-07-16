<?php
class ControllerStats extends Controller {
        /**
         * Stats
         */
        function index() {
                // Prüfe auf Parameter
                $statsPeriod = 'day';
                
                // Berechnen der Filter
                $tsDay = date("Y-m-d H:i:s", time()-86400);
                $blockFilter = '';
                $transactionFilter = '';
                if ($statsPeriod == "day") {
                        $blockFilter = " AND blockdate>='".$tsDay."'";
                        $transactionFilter = " WHERE transactiondate>='".$tsDay."'";
                }
		
		// Lese die Höhe des zuletzt gefundenen Blocks aus
                $lastBlockData = $this->db->query("SELECT height FROM ".DB_PRE."chain_blocks ORDER BY height DESC LIMIT 1");
		$this->smarty->assign('lastBlock', $lastBlockData[0]['height']);
		
                // Auslesen der gefundenen Blöcke                
                $this->db->query("SELECT blockid FROM ".DB_PRE."chain_blocks WHERE blockdate>='".$tsDay."'");
                $blocksMined = $this->db->numRows;
                $this->smarty->assign('blocksMined', $blocksMined);
                
                // Auslesen der Dauer zwischen den Blocks, Block Belohnung             
                $blockData = $this->db->query("SELECT SUM(duration) AS blocktime, SUM(blockReward) AS blockrewards FROM ".DB_PRE."chain_blocks WHERE height<>'0'".$blockFilter);
                $blockDuration = $blockData[0]['blocktime']/$blocksMined/60;
                $this->smarty->assign('blockTime', $blockDuration);
                $this->smarty->assign('blockReward', $blockData[0]['blockrewards']);
                
                // Ermittle Anzahl aller Transaktionen der letzten 24h
                $transactionData = $this->db->query("SELECT COUNT(*) AS transactions FROM ".DB_PRE."chain_transactions WHERE transactiondate>='".$tsDay."'");
                $this->smarty->assign('newTransactions', $transactionData[0]['transactions']);
		
		// Ermittle Anzahl aller neuen Wallets der letzten 24h
                $getStats = $this->db->query("SELECT COUNT(*) AS totalWallets FROM ".DB_PRE."chain_accounts WHERE accountFirstseen>='".$tsDay."'");		
		$this->smarty->assign('newWallets', $getStats[0]['totalWallets']);  
                
                // Berechnung der Markt Kapitalisierung
                $globalStatsData = $this->db->query("SELECT * FROM ".DB_PRE."stats"); 
                $totalBlockReward = $this->db->query("SELECT SUM(blockReward) AS blockrewards FROM ".DB_PRE."chain_blocks WHERE height<>'0'");
                $this->smarty->assign('marketCapUSD', $totalBlockReward[0]['blockrewards']*$globalStatsData[0]['burstBTC']*$globalStatsData[0]['btcUSD']);
                $this->smarty->assign('marketCapEUR', $totalBlockReward[0]['blockrewards']*$globalStatsData[0]['burstBTC']*$globalStatsData[0]['btcEUR']);
                $this->smarty->assign('totalMinedCoins', $totalBlockReward[0]['blockrewards']);
                $totalMinedCoinsPercent = round($totalBlockReward[0]['blockrewards']/2158812800*100, 2);
                $this->smarty->assign('totalMinedCoinsPercent', $totalMinedCoinsPercent);
                $this->smarty->assign('totalUnminedCoinsPercent', 100-$totalMinedCoinsPercent);
                
                // Berechnen der Network Size
		$blockBaseTarget = $this->db->query("SELECT baseTarget FROM ".DB_PRE."chain_blocks ORDER BY height DESC LIMIT 50");
		$baseTarget = 0;
		foreach ($blockBaseTarget AS $blockbasetarget) {
			$baseTarget = $baseTarget+$blockbasetarget['baseTarget'];
		}
		$baseTarget = $baseTarget/50;
		$baseTarget = $baseTarget*960000000;
		$networksize = pow(2, 64)/$baseTarget;
		$this->smarty->assign('networksize', round($networksize, 0));
                
                $this->template = 'Stats/index';
        }
}