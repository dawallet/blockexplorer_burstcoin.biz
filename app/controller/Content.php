<?php
class ControllerContent extends Controller {        
        /**
         * Contact Us
         */
        function contact() {                
                $this->template = 'Content/contact';
        }
        
        /**
         * Contact Us
         */
        function contactCheck() {
                if (!isset($_POST['name']) OR empty($_POST['name'])) {
                        $this->smarty->assign('errorMsg', $this->bootstrap->alert('Please enter your name.', 'danger'));
                } elseif (!isset($_POST['email']) OR !$this->validate->isMail($_POST['email'])) {
                        $this->smarty->assign('errorMsg', $this->bootstrap->alert('Please enter a valid email.', 'danger'));
                } elseif (!isset($_POST['message']) OR empty($_POST['message'])) {
                        $this->smarty->assign('errorMsg', $this->bootstrap->alert('Please enter a message.', 'danger'));
                } else {
                        $this->load->library('Mailer');
                        $this->library_mailer->send(
                                'contact', CONTACT_FORM, '', 
                                array('[name]', '[email]', '[message]'), 
                                array($_POST['name'], $_POST['email'], nl2br($_POST['message'])),
                                'global', MAIL_FROM, MAIL_FROM_NAME, MAIL_REPLY, MAIL_REPLY_NAME);
                        
                        $this->smarty->assign('errorMsg', $this->bootstrap->alert('Thanks for your message. We will contact you soon.', 'success'));
                        $this->smarty->assign("messageSend", true);
                }
                
                $this->contact();
        }
        
        /**
         * Chat
         */
        function chat() {                
                $this->template = 'Content/chat';
        }
        
        /**
         * Weiterleitung
         */
        function redirect() {
                $url = '';
                if (isset($_GET['p0'])) {
                        $url = base64_decode(str_pad(strtr($_GET['p0'], '-_', '+/'), strlen($_GET['p0']) % 4, '=', STR_PAD_RIGHT));
                }
                $this->smarty->assign('url', $url);
                
                $this->template = 'Content/redirect';
        }
        
        /**
         * Fix
         */
        function fix() {
                set_time_limit(0);
                
                // Prüfe auf doppelte Blöcke
                $blockdata = $this->db->query("SELECT count(*), blockid, height FROM ".DB_PRE."chain_blocks GROUP BY height HAVING COUNT(*) > 1");
                $i = 0;
                if (isset($blockdata[0])) {
                        foreach ($blockdata AS $blockdata) {
                                $i++;
                                $this->db->query("DELETE FROM ".DB_PRE."chain_blocks WHERE blockid='".$blockdata['blockid']."'");
                                echo $blockdata['blockid']." (#".$blockdata['height'].")<br>";
                        }
                }
                echo "Doppelte Bl&ouml;cke gefunden und beseitigt: ".$i."<br>";
                echo "<hr>";
                // Prüfe auf doppelte Transaktionen
                $transactiondata = $this->db->query("SELECT count(*), transactionid, transaction FROM ".DB_PRE."chain_transactions GROUP BY transaction HAVING COUNT(*) > 1");
                $i = 0;
                if (isset($transactiondata[0])) {
                        foreach ($transactiondata AS $transactiondata) {
                                $i++;
                                $this->db->query("DELETE FROM ".DB_PRE."chain_transactions WHERE transactionid='".$transactiondata['transactionid']."'");
                                echo $transactiondata['transactionid']." (".$transactiondata['transaction'].")<br>";
                        }
                }
                echo "Doppelte Transaktionen gefunden und beseitigt: ".$i."<br>";
                echo "<hr>";                
                // Prüfe auf doppelte Accounts
                $accountdata = $this->db->query("SELECT count(*), accountid, account FROM ".DB_PRE."chain_accounts GROUP BY account HAVING COUNT(*) > 1");
                $i = 0;
                if (isset($accountdata[0])) {
                        foreach ($accountdata AS $accountdata) {
                                $i++;
                                $this->db->query("DELETE FROM ".DB_PRE."chain_accounts WHERE accountid='".$accountdata['accountid']."'");
                                echo $accountdata['accountid']." (".$accountdata['account'].")<br>";
                        }
                }
                echo "Doppelte Accounts gefunden und beseitigt: ".$i."<br>";
                echo "<hr>";
		echo "Block-Stats:<br><table><thead><tr><th>Block Start</th><th>Block End</th><th>Blocks</th><th>Reward/Block</th><th>Total Reward</th><th>Total Supply</th><th>(%)</th></tr></thead><tbody>";
                $sum = 0;
		$blockdata = $this->db->query("SELECT COUNT(*) AS blocks, SUM(blockReward) AS total_reward, blockReward, MIN(height) AS block_start, MAX(height) AS block_end FROM ".DB_PRE."chain_blocks GROUP BY blockReward ORDER BY height ASC");
		foreach ($blockdata AS $data) {
		    if ($data['block_start'] > 0) {
			$sum = $sum+$data['total_reward'];
			$addInfo = '';
			if ($data['blocks'] < 10799) {
			    $addBurst = 10800-$data['blocks'];
			    $addBurst = $addBurst * $data['blockReward'];
			    $addInfo = ' (+ '.  number_format($addBurst, 0).')';
			    $sum = $sum+$addBurst;
			}
			$supply = $sum/2158812800*100;
			echo "<tr><td>".$data['block_start']."</td><td>".$data['block_end']."</td><td>".$data['blocks']."</td><td>".number_format($data['blockReward'], 0)."</td><td>".number_format($data['total_reward'], 0).$addInfo."</td><td>".number_format($sum, 0)."</td><td>".number_format($supply, 2)."</td></tr>";
			$lastBlockHeight = $data['block_start'];
			$lastBlockReward = $data['blockReward'];
		    }
		}
		for ($i = 1; $i <= 24; $i++) {
		    $blockStart = $i*10800;
		    $blockStart = $blockStart+$lastBlockHeight;
		    $lastBlockReward = floor($lastBlockReward/100*95);
		    $totalReward = 10800*$lastBlockReward;
		    $sum = $sum+$totalReward;
		    $supply = $sum/2158812800*100;
		    echo "<tr style='color:grey;'><td>".$blockStart."</td><td>...</td><td>10800</td><td>".number_format($lastBlockReward, 0)."</td><td>".number_format($totalReward, 0)."</td><td>".number_format($sum, 0)."</td><td>".number_format($supply, 2)."</td></tr>";
		}
		echo "</tbody></table>";
		
                exit();
        }

        /**
         * Zugriff verweigert
         */
        function error401() {
                $this->template = 'Content/401';                
        }
        
        /**
         * Seite nicht gefunden
         */
        function error404() {
                $this->template = 'Content/404';                
        }
        
        /**
         * Unerwarteter Fehler
         */
        function error500() {
                $this->template = 'Content/500';                
        }
}