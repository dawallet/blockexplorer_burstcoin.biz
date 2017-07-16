<?php
class ControllerAPI extends Controller {
        function construct() {  
                // Prüfe Aufruf
                if (!isset($_GET['p0'])) {
                        $this->notFound();
                }
                
                // Lade Module
                $this->load->model('Wallet');
        }
        
        /**
         * API: Account
         */
        function account() {
                // Setze Parameter
                if (!isset($_POST['account'])) { $_POST['account'] = ''; }
                
                // API Call: Balance
                if (isset($_GET['p0']) AND $_GET['p0'] == "balance") {
                        $accountData = $this->model_wallet->request('getAccount', 'account='.$_POST['account'], true);
                        $this->output($accountData);
                }
                
                $this->notFound();
        }
                
        /**
         * API: Output
         */
        function output($data) {
                echo json_encode($data);
                exit();
        }
        
        /**
         * API: Call nicht gefunden
         */
        function notFound() {
                echo "Cannot find ".$_GET['p'];
                exit();                
        }
}