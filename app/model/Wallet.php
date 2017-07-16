<?php
class ModelWallet extends Model {       
        /**
         * Sendet Anfrage an die Wallet API.
         * @param varchar $requestType Name des Requests
         * @param varchar $parameter Parameter
         * @param boolean $returnError Gibt an ob die API auch Fehlermeldungen ausgeben soll
         * @return array Gibt die Antwort der Wallet als ein Array zurück.
         * @return boolean Lifert false falls Wallet offline ist oder die Anfrage ungültig ist.
         */
        public function request($requestType, $parameter = '', $returnError = false) {
                if ($handle = fopen("http://".BURST_API.":8125/burst?requestType=$requestType&$parameter", "rb")) {
                        $content = json_decode(stream_get_contents($handle), true);
                        fclose($handle);
                        
                        // Setze den Status der Wallet auf online
                        $this->db->query("UPDATE ".DB_PRE."stats SET walletstatus='1'");
                        // Gebe die API-Daten nur zurück wenn kein Fehler vorliegt
                        if (!isset($content['errorCode'])) {
                                return $content;
                        } elseif ($returnError == true AND isset($content['errorCode'])) {
                                return $content;
                        }
                        
                } else {
                        // Setze den Status der Wallet auf offline
                        $this->db->query("UPDATE ".DB_PRE."stats SET walletstatus='0'");
                }
                
                return false;
        }
        
        /**
         * Synchronisiert einen Block.
         * @param integer $height Block Höhe
         * @return boolean Liefert true zurück wenn Block synchronisert wurde oder false falls Block nicht synchronisert werden konnte.
         */
        public function syncBlock($height) {
                // Lese die Blockdaten aus
                if ($walletData = $this->request('getBlock', 'height='.$height.'&includeTransactions=true')) {
                        // Lese vorherigen Block aus um zu berechnen wie schnell dieser Block gefunden wurde
                        $duration = 0;
                        if ($walletData['height'] > 0) {
                                $previousBlock = $walletData['height']-1;
                                $previousBlockData = $this->db->query("SELECT timestamp FROM ".DB_PRE."chain_blocks WHERE height='".$previousBlock."'");
                                if (isset($previousBlockData[0])) {
                                        $duration = $walletData['timestamp']-$previousBlockData[0]['timestamp'];
                                }
                        }
                        
                        // Aufbereiten der Blockdaten
                        $blockReward = $walletData['blockReward'];
                        if ($walletData['blockReward'] > 10000) {
                                $blockReward = $walletData['blockReward']/100000000;
                        }
                        $totalAmountNQT = 0;
                        if ($walletData['totalAmountNQT'] > 0) {
                                $totalAmountNQT = $walletData['totalAmountNQT']/100000000;
                        }
                        $totalFeeNQT = 0;
                        if ($walletData['totalFeeNQT'] > 0) {
                                $totalFeeNQT = $walletData['totalFeeNQT']/100000000;
                        }
                        
                        // Legen den Block in der Datenbank an falls dieser noch nicht importiert wurde
                        $this->db->query("SELECT height FROM ".DB_PRE."chain_blocks WHERE height='".$walletData['height']."'");
                        if ($this->db->numRows == 0) {
                                // Lege den neuen Block an                        
                                $this->db->query(
                                        "INSERT INTO ".DB_PRE."chain_blocks ".
                                        "(height, generator, blockReward, ".
                                        "timestamp, blockdate, duration, ".
                                        "block, nonce, version, ".
                                        "baseTarget, numberOfTransactions, totalAmountNQT, ".
                                        "totalFeeNQT, scoopNum, blockSignature, ".
                                        "payloadLength, payloadHash, generationSignature) ".
                                        "VALUES ".
                                        "('".$walletData['height']."', '".$walletData['generator']."', '".$blockReward."', ".
                                        "'".$walletData['timestamp']."', '".date("Y-m-d H:i:s", BURST_EXIST+$walletData['timestamp'])."', '".$duration."', ".
                                        "'".$walletData['block']."', '".$walletData['nonce']."', '".$walletData['version']."', ".
                                        "'".$walletData['baseTarget']."', '".$walletData['numberOfTransactions']."', '".$totalAmountNQT."', ".
                                        "'".$totalFeeNQT."', '".$walletData['scoopNum']."', '".$walletData['blockSignature']."', ".
                                        "'".$walletData['payloadLength']."', '".$walletData['payloadHash']."', '".$walletData['generationSignature']."')");

                                // Aktualisiere den Kontostand vom Finder-Account
                                $this->updateAccount($walletData['generator'], $walletData['timestamp'], 1);

                                // Importiere die Transaktionen
                                if ($walletData['numberOfTransactions'] > 0) {
                                        $sqlTransactions = '';
                                        foreach ($walletData['transactions'] AS $transaction) {
                                                // Aufbereiten der Transaktionsdaten                                                
                                                $recipient = '';
                                                if (isset($transaction['recipient'])) {
                                                        $recipient = $transaction['recipient'];
                                                }
                                                $amountNQT = 0;
                                                if ($transaction['amountNQT'] > 0) {
                                                        $amountNQT = $transaction['amountNQT']/100000000;
                                                }
                                                $feeNQT = 0;
                                                if ($transaction['feeNQT'] > 0) {
                                                        $feeNQT = $transaction['feeNQT']/100000000;
                                                }
                                                $attachment = '';
                                                if (isset($transaction['attachment'])) {
                                                        $attachment = serialize($transaction['attachment']);
                                                }
                                                
                                                // Lege eine neue Transaktion an
                                                if (!empty($sqlTransactions)) { $sqlTransactions.= ", "; }
                                                $sqlTransactions.= "('".$transaction['transaction']."', '".$transaction['type']."', '".$transaction['subtype']."', ".
                                                        "'".$transaction['sender']."', '".$recipient."', '".$transaction['block']."', ".
                                                        "'".$amountNQT."', '".$feeNQT."', '".$transaction['timestamp']."', ".
                                                        "'".date('Y-m-d H:i:s', BURST_EXIST+$transaction['timestamp'])."', '".$transaction['signature']."', '".$transaction['signatureHash']."', ".
                                                        "'".$transaction['fullHash']."', '".$this->db->escapeString($attachment)."')";
                                                
                                                // Aktualisiere den Kontostand vom Sender und Empfänger
                                                $this->updateAccount($transaction['sender'], $walletData['timestamp']);
                                                $this->updateAccount($recipient, $walletData['timestamp']);                                                
                                        }
                                        
                                        // Lege die Transaktionen an
                                        if (!empty($sqlTransactions)) {
                                                $this->db->query(
                                                        "INSERT INTO ".DB_PRE."chain_transactions ".
                                                        "(transaction, type, subtype, ".
                                                        "sender, recipient, block, ".
                                                        "amountNQT, feeNQT, timestamp, ".
                                                        "transactiondate, signature, signatureHash, ".
                                                        "fullHash, attachment) ".
                                                        "VALUES ".$sqlTransactions);
                                                   
                                        }
                                }
                        // Synchronisiere Blockdaten
                        } else {
                                // Aktualisiere die Block Daten                    
                                $this->db->query(
                                        "UPDATE ".DB_PRE."chain_blocks SET ".
                                        "generator='".$walletData['generator']."', blockReward='".$blockReward."', ".
                                        "timestamp='".$walletData['timestamp']."', blockdate='".date("Y-m-d H:i:s", BURST_EXIST+$walletData['timestamp'])."', duration='".$duration."', ".
                                        "block='".$walletData['block']."', nonce='".$walletData['nonce']."', version='".$walletData['version']."', ".
                                        "baseTarget='".$walletData['baseTarget']."', numberOfTransactions='".$walletData['numberOfTransactions']."', totalAmountNQT='".$totalAmountNQT."', ".
                                        "totalFeeNQT='".$totalFeeNQT."', scoopNum='".$walletData['scoopNum']."', blockSignature='".$walletData['blockSignature']."', ".
                                        "payloadLength='".$walletData['payloadLength']."', payloadHash='".$walletData['payloadHash']."', generationSignature='".$walletData['generationSignature']."' ".
                                        "WHERE height='".$walletData['height']."'");

                                // Aktualisiere den Kontostand vom Finder-Account
                                $this->updateAccount($walletData['generator'], $walletData['timestamp'], 1);

                                // Importiere die Transaktionen
                                if ($walletData['numberOfTransactions'] > 0) {
                                        foreach ($walletData['transactions'] AS $transaction) {                                                
                                                // Aufbereiten der Transaktionsdaten                                                
                                                $recipient = '';
                                                if (isset($transaction['recipient'])) {
                                                        $recipient = $transaction['recipient'];
                                                }
                                                $amountNQT = 0;
                                                if ($transaction['amountNQT'] > 0) {
                                                        $amountNQT = $transaction['amountNQT']/100000000;
                                                }
                                                $feeNQT = 0;
                                                if ($transaction['feeNQT'] > 0) {
                                                        $feeNQT = $transaction['feeNQT']/100000000;
                                                }
                                                $attachment = '';
                                                if (isset($transaction['attachment'])) {
                                                        $attachment = serialize($transaction['attachment']);
                                                }                                                
                                                
                                                // Überprüfe ob die Transaktion bereits in der Datenbank existiert
                                                $this->db->query("SELECT transaction FROM ".DB_PRE."chain_transactions WHERE transaction='".$transaction['transaction']."'");
                                                // Aktualisiere die Transaktions Daten
                                                if ($this->db->numRows != 0) {
                                                        $this->db->query(
                                                                "UPDATE ".DB_PRE."chain_transactions SET ".
                                                                "type='".$transaction['type']."', subtype='".$transaction['subtype']."', ".
                                                                "sender='".$transaction['sender']."', recipient='".$recipient."', block='".$transaction['block']."', ".
                                                                "amountNQT='".$amountNQT."', feeNQT='".$feeNQT."', timestamp='".$transaction['timestamp']."', ".
                                                                "transactiondate='".date('Y-m-d H:i:s', BURST_EXIST+$transaction['timestamp'])."', signature='".$transaction['signature']."', signatureHash='".$transaction['signatureHash']."', ".
                                                                "fullHash='".$transaction['fullHash']."', attachment='".$this->db->escapeString($attachment)."' ".
                                                                "WHERE transaction='".$transaction['transaction']."'");
                                                // Füge die fehlende Transaktion in die Datenbank ein                                                        
                                                } else {
                                                        $this->db->query(
                                                                "INSERT INTO ".DB_PRE."chain_transactions ".
                                                                "(transaction, type, subtype, ".
                                                                "sender, recipient, block, ".
                                                                "amountNQT, feeNQT, timestamp, ".
                                                                "transactiondate, signature, signatureHash, ".
                                                                "fullHash, attachment) ".
                                                                "VALUES ".
                                                                "('".$transaction['transaction']."', '".$transaction['type']."', '".$transaction['subtype']."', ".
                                                                "'".$transaction['sender']."', '".$recipient."', '".$transaction['block']."', ".
                                                                "'".$amountNQT."', '".$feeNQT."', '".$transaction['timestamp']."', ".
                                                                "'".date('Y-m-d H:i:s', BURST_EXIST+$transaction['timestamp'])."', '".$transaction['signature']."', '".$transaction['signatureHash']."', ".
                                                                "'".$transaction['fullHash']."', '".$this->db->escapeString($attachment)."')");                                                        
                                                }
                                                
                                                // Aktualisiere den Kontostand vom Sender und Empfänger
                                                $this->updateAccount($transaction['sender'], $walletData['timestamp']);
                                                $this->updateAccount($recipient, $walletData['timestamp']);                                                
                                        }
                                }
                        }
                        
                        return true;
                }
                
                return false;
        }
        
        /**
         * Prüft ob ein Account bereits in der Datenbank vorhanden ist und legt diesen an falls er nicht vorhanden ist.
         * @param integer $account Numeric Account-ID
         * @param integer $firstseen Timestamp wann der Account zum ersten mal gesehen wurde
         * @param integer $forgedBlock Gibt an ob dieser User den Block gefunden hat
         * @return boolean Liefert true zurück falls dieser Account bereits existiert. 
         */
        public function addAccount($account, $firstseen, $forgedBlock = 0) {
                $this->db->query("SELECT account FROM ".DB_PRE."chain_accounts WHERE account='".$account."'");
                if ($this->db->numRows > 0) {
                        return true;
                } else {
                        // Lese Accountdaten aus der API aus
                        if ($accountData = $this->request('getAccount', 'account='.$account)) { 
                                // Aufbereiten der Accountdaten
                                $name = '';
                                if (isset($accountData['name'])) {
                                        $name = $accountData['name'];
                                }
                                $unconfirmedBalanceNQT = 0;
                                if ($accountData['unconfirmedBalanceNQT'] > 0) {
                                        $unconfirmedBalanceNQT = $accountData['unconfirmedBalanceNQT']/100000000;
                                }
                                $guaranteedBalanceNQT = 0;
                                if ($accountData['guaranteedBalanceNQT'] > 0) {
                                        $guaranteedBalanceNQT = $accountData['guaranteedBalanceNQT']/100000000;
                                }
                                $forgedBalanceNQT = 0;
                                if ($accountData['forgedBalanceNQT'] > 0) {
                                        $forgedBalanceNQT = $accountData['forgedBalanceNQT']/100000000;
                                }
                                $publicKey = '';
                                if (isset($accountData['publicKey'])) {
                                        $publicKey = $accountData['publicKey'];
                                }
                                
                                // Lege einen neuen Account an
                                $this->db->query(
                                        "INSERT INTO ".DB_PRE."chain_accounts ".
                                        "(account, accountRS, name, ".
                                        "unconfirmedBalanceNQT, guaranteedBalanceNQT, effectiveBalanceNXT, ".
                                        "forgedBalanceNQT, forgedBlocks, publicKey, ".
                                        "accountFirstseen, lastActivity) ".
                                        "VALUES ".
                                        "('".$accountData['account']."', '".$accountData['accountRS']."', '".$this->db->escapeString($name)."', ".
                                        "'".$unconfirmedBalanceNQT."', '".$guaranteedBalanceNQT."', '".$accountData['effectiveBalanceNXT']."', ".
                                        "'".$forgedBalanceNQT."', '".$forgedBlock."', '".$publicKey."', ".
                                        "'".date('Y-m-d H:i:s', BURST_EXIST+$firstseen)."', '".date('Y-m-d H:i:s', BURST_EXIST+$firstseen)."')");
                        }
                }
                
                return false;
        }
        
        /**
         * Aktualisiert die Daten eines bereits vorhandenen Accounts.
         * @param integer $account Numeric Account-ID
         * @param integer $firstseen Timestamp wann der Account zum ersten mal gesehen wurde
         * @param integer $forgedBlock Gibt an ob dieser User den Block gefunden hat
         * @return boolean Liefert true zurück. 
         */
        public function updateAccount($account, $firstseen, $forgedBlock = 0) {
                if (!empty($account)) {
                        // Prüfe on Account in der Datenbank vorhanden ist
                        $getAccount = $this->db->query("SELECT accountid, lastActivity FROM ".DB_PRE."chain_accounts WHERE account='".$account."'");
                        if ($this->db->numRows == 0) {
                                $this->addAccount($account, $firstseen, $forgedBlock);
                        } else {
                                // Lese Accountdaten aus der API aus
                                if ($accountData = $this->request('getAccount', 'account='.$account)) { 
                                        // Aufbereiten der Accountdaten
                                        $name = '';
                                        if (isset($accountData['name'])) {
                                                $name = $accountData['name'];
                                        }
                                        $unconfirmedBalanceNQT = 0;
                                        if ($accountData['unconfirmedBalanceNQT'] > 0) {
                                                $unconfirmedBalanceNQT = $accountData['unconfirmedBalanceNQT']/100000000;
                                        }
                                        $guaranteedBalanceNQT = 0;
                                        if ($accountData['guaranteedBalanceNQT'] > 0) {
                                                $guaranteedBalanceNQT = $accountData['guaranteedBalanceNQT']/100000000;
                                        }
                                        $forgedBalanceNQT = 0;
                                        if ($accountData['forgedBalanceNQT'] > 0) {
                                                $forgedBalanceNQT = $accountData['forgedBalanceNQT']/100000000;
                                        }
                                        $publicKey = '';
                                        if (isset($accountData['publicKey'])) {
                                                $publicKey = $accountData['publicKey'];
                                        }
                                        $updateLastActivity = "";
                                        if ($getAccount[0]['lastActivity'] < date('Y-m-d H:i:s', BURST_EXIST+$firstseen)) {
                                                $updateLastActivity = ", lastActivity='".date('Y-m-d H:i:s', BURST_EXIST+$firstseen)."' ";
                                        }

                                        // Aktualisiere einen Account
                                        $this->db->query(
                                                "UPDATE ".DB_PRE."chain_accounts SET ".
                                                "name='".$this->db->escapeString($name)."', unconfirmedBalanceNQT='".$unconfirmedBalanceNQT."', ".
                                                "guaranteedBalanceNQT='".$guaranteedBalanceNQT."', effectiveBalanceNXT='".$accountData['effectiveBalanceNXT']."', ".
                                                "forgedBalanceNQT='".$forgedBalanceNQT."', forgedBlocks=forgedBlocks+'".$forgedBlock."', ".
                                                "publicKey='".$publicKey."' ".$updateLastActivity.
                                                "WHERE account='".$accountData['account']."'");
                                }
                        }
                }
                
                return true;
        }
}