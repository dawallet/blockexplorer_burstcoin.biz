<?php
class ControllerTaskManager extends Controller {       
        /**
         * Sucht nach offenen Tasks und starte diese.
         */
        function index() {
                // Prüfe auf Zyklus
                if (isset($_GET['action']) AND $_GET['action'] == "cycle") {
                        $this->cycledata = $this->db->query("SELECT * FROM ".DB_PRE."tasks_cycle WHERE ts_nextcycle<'".time()."'");
                        if (isset($this->cycledata[0])) {
                                foreach ($this->cycledata AS $cycledata) {
                                        $nextcycle = time()+$cycledata['cycleinterval'];
                                        $this->db->query("UPDATE ".DB_PRE."tasks_cycle SET ts_lastcycle='".time()."', ts_nextcycle='".$nextcycle."' WHERE cycleid='".$cycledata['cycleid']."'");
                                        $startNewTask = 1;
                                        // Prüfe ob Task bereits ausgeführt wird
                                        if ($cycledata['taskonce'] == 1) {
                                                $this->countTasks = $this->db->query("SELECT COUNT(*) AS taskRunning FROM ".DB_PRE."tasks_list WHERE taskid='".$cycledata['taskid']."' AND statusid<>'2'");
                                                if ($this->countTasks[0]['taskRunning'] != 0) {
                                                        $startNewTask = 0;
                                                }
                                        }
                                        // Lege eine neue Task an
                                        if ($startNewTask == 1) {
                                                // Lege eine Aufgabe an
                                                $this->db->query("INSERT INTO ".DB_PRE."tasks_list (taskid) VALUES ('".$cycledata['taskid']."')");
                                        }
                                }
                        }
                // Führe eine einzelne Task aus
                } else {                        
                        $this->taskdata = $this->db->query("SELECT * FROM ".DB_PRE."tasks_list WHERE statusid='0' ORDER BY tasklistid ASC LIMIT 1");
                        if (isset($this->taskdata[0])) {
                                // Setze Task auf "in Arbeit"
                                if ($this->taskdata[0]['ts_start'] == 0) {
                                        $this->taskStartTime = time();
                                } else {
                                        $this->taskStartTime = $this->taskdata[0]['ts_start'];
                                }
                                $this->db->query("UPDATE ".DB_PRE."tasks_list SET statusid='1', ts_start='".$this->taskStartTime."', ts_update='".time()."' WHERE tasklistid='".$this->taskdata[0]['tasklistid']."'");
                                        
                                // Ausführen der Task
                                $taskname = 'task'.$this->taskdata[0]['taskid'];
                                $this->$taskname();
                        }
                }
        }
        
        /**
         * Aufgabe: Prüft auf neuen Block.
         */
        function task1() {
                // Lade Module                
                $this->load->model('Wallet');
                
                // Auslesen der Blockchain-Info
                if ($walletData = $this->model_wallet->request('getBlockchainStatus')) {
                        $statsdata = $this->db->query("SELECT blocks FROM ".DB_PRE."stats");
                        // Prüfe ob neuer Block vorhanden ist
                        if ($walletData['numberOfBlocks'] > $statsdata[0]['blocks']) {
                                // Aktualisiere die Anzahl der Blocks in der Statistik
                                $this->db->query("UPDATE ".DB_PRE."stats SET blocks='".$walletData['numberOfBlocks']."'");
                                
                                // Lese den höchsten Block aus für den eine Task angelegt wurde
                                $taskstatus = $this->db->query("SELECT referenceid FROM ".DB_PRE."tasks_list WHERE taskid='2' ORDER BY referenceid DESC LIMIT 1");
                                if (isset($taskstatus[0]['referenceid'])) {
                                        $newestBlock = $walletData['numberOfBlocks']-1;
                                        $lastBlock = $taskstatus[0]['referenceid']+1;
                                        // Lege neue Tasks für ein oder mehrere Blöcke zum auslesen an
                                        for ($i = $lastBlock; $i <= $newestBlock; $i++) {
                                                $this->db->query(
                                                        "INSERT INTO ".DB_PRE."tasks_list ".
                                                        "(taskid, referenceid) ".
                                                        "VALUES ".
                                                        "('2', '".$i."')");
                                        }
                                }
                        }
                }

                // Task abschließen
                $this->task_finish();
        }
        
        /**
         * Aufgabe: Importiert einen neuen Block und Transaktionen.
         */
        function task2() {
                // Lade Module
                $this->load->model('Wallet');
                if ($this->model_wallet->syncBlock($this->taskdata[0]['referenceid'])) {                
                        // Task abschließen
                        $this->task_finish();
                }
        }
        
        /**
         * Aufgabe: Aktualisiere den Bitcoin/Burstcoin Kurs.
         */
        function task3() {
                // Bitcoin USD-Kurs
                if ($handle = fopen("http://api.bitcoinaverage.com/ticker/global/USD/", "rb")) {
                        $content = json_decode(stream_get_contents($handle), true);
                        fclose($handle);                        
                        $this->db->query("UPDATE ".DB_PRE."stats SET btcUSD='".$content['last']."', btcUSDts='".time()."'");
                }
                
                // Bitcoin EUR-Kurs
                if ($handle = fopen("http://api.bitcoinaverage.com/ticker/global/EUR/", "rb")) {
                        $content = json_decode(stream_get_contents($handle), true);
                        fclose($handle);                        
                        $this->db->query("UPDATE ".DB_PRE."stats SET btcEUR='".$content['last']."', btcEURts='".time()."'");
                }
                
                // Burstcoin Kurs
                if ($handle = fopen("https://c-cex.com/t/burst-btc.json", "rb")) {
                        $content = json_decode(stream_get_contents($handle), true);
                        fclose($handle);
                        $this->db->query("UPDATE ".DB_PRE."stats SET burstBTC='".$content['ticker']['lastprice']."'");                        
                }
                
                // Task abschließen
                $this->task_finish();
        }
        
        /**
         * Aufgabe: Prüfe auf zu synchronisierende Blöcke.
         */
        function task4() {
                // Lade Module
                $this->load->model('Wallet');
                
                // Lese die letzte Blockhöhe aus
                $this->getBlockHeight = $this->db->query("SELECT blocks FROM ".DB_PRE."stats");
                $syncUntilBlock = $this->getBlockHeight[0]['blocks'+1];
                $syncFromBlock = $this->getBlockHeight[0]['blocks'-500];

                // Lese die zu synchronisierenden Blöcke aus
//                $this->syncBlocks = $this->db->query("SELECT blockid, height FROM ".DB_PRE."chain_blocks WHERE height<'".$syncUntilBlock."' AND resynced='0' ORDER BY height ASC LIMIT 100");
//                $this->syncBlocks = $this->db->query("SELECT blockid, height FROM ".DB_PRE."chain_blocks WHERE height<'".$syncUntilBlock."' ORDER BY height ASC LIMIT 5000");
//                if (isset($this->syncBlocks[0])) {
//                        foreach ($this->syncBlocks AS $syncBlock) {
                            for ($h = $syncFromBlock; $h < syncUntilBlock; $h++) {
// for ($h = 351480; $h < 351502; $h++) {
                                // Synchronisiere den Block
//                                if ($this->model_wallet->syncBlock($syncBlock['height'])) {   
                                if ($this->model_wallet->syncBlock($h)) {   
                                        $this->db->query("UPDATE ".DB_PRE."chain_blocks SET resynced='1' WHERE blockid='".$syncBlock['blockid']."'");
                                }
                        }
                $this->task_finish();
                }
                
                // Task abschließen
//                $this->task_finish();
//    }
        
        /**
         * Aufgabe: Prüfe ob Surfbarlink IDs mit einem Account synchronisiert werden müssen.
         */
        function task5() {
                $getAccounts = $this->db->query("SELECT COUNT(*) AS withoutEBID, ts_create FROM ".DB_PRE."surfbar WHERE ebid='0' ORDER BY surfbarid ASC");
                if ($getAccounts[0]['withoutEBID'] > 0) {
                        $ts_account = $getAccounts[0]['ts_create']-7200;
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_USERPWD => SURFBAR_API,
                            CURLOPT_URL => 'https://www.ebesucher.de/api/visitor_exchange.json/surflinks?activeSince='.$ts_account
                        ));
                        $content = curl_exec($curl);
                        curl_close($curl);
                        $content = json_decode($content, true);
                        if (!empty($content)) {
                                foreach ($content AS $surflink) {
                                        $surfbarid = str_replace('burstcoinbiz.user', '', $surflink['fullName']);
                                        $this->db->query("UPDATE ".DB_PRE."surfbar SET ebid='".$this->db->escapeString($surflink['id'])."' WHERE surfbarid='".$this->db->escapeString($surfbarid)."'");
                                }
                        }
                }
                
                // Task abschließen
                $this->task_finish();
        }
        
        /**
         * Aufgabe: Fordere einen neuen Surfbar Report an.
         */
        function task6() {
                $curl = curl_init();
                $data = array("from" => "1", "to" => time()+7200);                                                                    
                $data_string = json_encode($data);     
                curl_setopt_array($curl, array(
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_USERPWD => SURFBAR_API,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $data_string,
                    CURLOPT_HTTPHEADER => array(                                                                          
                        'Content-Type: application/json',                                                                                
                        'Content-Length: '.strlen($data_string)),
                    CURLOPT_URL => 'https://www.ebesucher.de/api/visitor_exchange.json/account/surflink_earnings_report'
                ));
                $content = curl_exec($curl);
                curl_close($curl);
                $content = json_decode($content, true);
                if (!isset($content['error'])) {
                        $this->db->query("UPDATE ".DB_PRE."stats SET ebesucherReport='".$this->db->escapeString($content)."'");
                }
                
                // Task abschließen
                $this->task_finish();
        }
        
        /**
         * Aufgabe: Löschen von alten und stehengebliebenen Aufgaben.
         */
        function task7() {
                // Lösche alte Aufgaben
                $ts_end = time()-172800; // 2 Tage
                $this->db->query("DELETE FROM ".DB_PRE."tasks_list WHERE ts_end>'0' AND ts_end<'".$ts_end."'");
                
                // Prüfe auf stehengebliebene Aufgaben
                $ts_update = time()-10800; // 3 Stunden
                $this->db->query("DELETE FROM ".DB_PRE."tasks_list WHERE statusid='1' AND ts_update>'0' AND ts_update<'".$ts_update."'");
                
                // Task abschließen
                $this->task_finish();
        }
        
        /**
         * Aufgabe: Aktualisiere die Surfbar Punktestände.
         */
        function task8() {
                // Lese die Report ID aus
                $getReport = $this->db->query("SELECT ebesucherReport FROM ".DB_PRE."stats");
                if ($getReport[0]['ebesucherReport'] > 0) {
                        // Prüfe ob der Report fertiggestellt wurde
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_USERPWD => SURFBAR_API,
                            CURLOPT_URL => 'https://www.ebesucher.de/api/visitor_exchange.json/account/surflink_earnings_report/'.$getReport[0]['ebesucherReport'].'/status'
                        ));
                        $content = curl_exec($curl);
                        curl_close($curl);
                        $content = json_decode($content, true);
                        if (!empty($content) AND isset($content['isFinished']) AND $content['isFinished']) {
                                // Lese die Daten des Reports aus
                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                    CURLOPT_SSL_VERIFYPEER => false,
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_USERPWD => SURFBAR_API,
                                    CURLOPT_URL => 'https://www.ebesucher.de/api/visitor_exchange.json/account/surflink_earnings_report/'.$getReport[0]['ebesucherReport']
                                ));
                                $content = curl_exec($curl);
                                curl_close($curl);
                                $content = json_decode($content, true);
                                if (!empty($content)) {
                                        foreach ($content AS $surfdata) {
                                                $this->db->query("UPDATE ".DB_PRE."surfbar SET surfpoints='".$this->db->escapeString($surfdata['value'])."' WHERE ebid='".$this->db->escapeString($surfdata['surflinkID'])."'");
                                        }
                                }
                        }
                }
                
                // Task abschließen
                $this->task_finish();
        }
                
        /**
         * Setze eine Task auf beendet.
         */
        function task_finish() {
                $this->taskEndTime = time();
                $this->taskDuration = $this->taskEndTime-$this->taskStartTime;
                $this->db->query("UPDATE ".DB_PRE."tasks_list SET statusid='2', ts_update='".time()."', ts_end='".$this->taskEndTime."', duration='".$this->taskDuration."', stats_now='".$this->taskdata[0]['stats_max']."' WHERE tasklistid='".$this->taskdata[0]['tasklistid']."'");
        }
}
