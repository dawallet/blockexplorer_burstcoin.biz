<?php
class ControllerCalculator extends Controller {
        /**
         * Calculator
         */
        function index() {
                // Auslesen der Umrechnungskurse
                $globalStatsData = $this->db->query("SELECT * FROM ".DB_PRE."stats");
                $this->smarty->assign('btcBurst', $globalStatsData[0]['burstBTC']);
                $this->smarty->assign('btcUSD', $globalStatsData[0]['btcUSD']);
                $this->smarty->assign('btcEUR', $globalStatsData[0]['btcEUR']);
                
                // Lese den Reward für den letzten Block aus
                $blockRewardData = $this->db->query("SELECT blockReward FROM ".DB_PRE."chain_blocks ORDER BY height DESC LIMIT 1");
                $this->smarty->assign('blockReward', number_format($blockRewardData[0]['blockReward'], 0, '', ''));
                
                // Berechnen der Network Size
                $blockBaseTarget = $this->db->query("SELECT baseTarget FROM ".DB_PRE."chain_blocks ORDER BY height DESC LIMIT 360");
                $baseTarget = 0;
                foreach ($blockBaseTarget AS $blockbasetarget) {
                        $baseTarget = $baseTarget+$blockbasetarget['baseTarget'];
                }
                $baseTarget = $baseTarget/360;
                $this->smarty->assign('baseTarget', round($baseTarget, 0));
                $baseTarget = $baseTarget*960000000;
                $networksize = pow(2, 64)/$baseTarget;
                $this->smarty->assign('networksize', round($networksize, 0));
                
                $this->template = 'Calculator/index';
        }
        
        /**
         * Basetarget for last 50 Blocks
         */
        function api() {
                $blockBaseTarget = $this->db->query("SELECT baseTarget FROM ".DB_PRE."chain_blocks ORDER BY height DESC LIMIT 50");
                $baseTarget = 0;
                foreach ($blockBaseTarget AS $blockbasetarget) {
                        $baseTarget = $baseTarget+$blockbasetarget['baseTarget'];
                }
                echo round($baseTarget/50, 0);
                exit();
        }
}