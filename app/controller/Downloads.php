<?php
class ControllerDownloads extends Controller {
        /**
         * Übersicht aller Downloads
         */
        function index() {
                // Prüfe auf Filter
                $addFilter = "";
                if (isset($_GET['p0']) AND ($_GET['p0'] === "Miner" OR $_GET['p0'] === "Plotter" OR $_GET['p0'] === "Wallet")) {
                        $addFilter = " WHERE category='".$this->db->escapeString($_GET['p0'])."'";
                }
                
                // Auslesen der Downloads
                $downloadData = $this->db->query("SELECT * FROM ".DB_PRE."downloads".$addFilter." ORDER BY category, name ASC");
                $this->smarty->assign('downloaddata', $downloadData);
                
                $this->template = 'Downloads/index';
        }
        
        /**
         * Zeige Details für einen Download an.
         */
        function download() {
                // Prüfe Parameter
                if (isset($_GET['p0']) AND $_GET['p0'] > 0) {
                        // Auslesen des Downloads
                        $downloadData = $this->db->query("SELECT * FROM ".DB_PRE."downloads WHERE downloadid='".$this->db->escapeString($_GET['p0'])."'");
                        if (isset($downloadData[0])) {
                                // Prüfe ob Download gestreamt werden soll
                                if (isset($_GET['p1']) AND $_GET['p1'] == "start") {
                                        $filename = $downloadData[0]['filename'];
                                        // Prüfe ob alte Version zum Download angefordert wurde
                                        if (isset($_GET['p2']) AND $_GET['p2'] == "old" AND isset($_GET['p3']) AND $_GET['p3'] > 0) {
                                                $downloadHistory = $this->db->query("SELECT filename FROM ".DB_PRE."downloads_history WHERE historyid='".$this->db->escapeString($_GET['p3'])."'");
                                                if (isset($downloadHistory[0])) {
                                                        $filename = $downloadHistory[0]['filename'];
                                                }
                                        }
                                        // Aktualisiere Download-Statistik
                                        $this->db->query("UPDATE ".DB_PRE."downloads SET downloads=downloads+'1' WHERE downloadid='".$downloadData[0]['downloadid']."'");
                                        $file = DIR_PUBLIC.DS.'media'.DS.'dl'.DS.$filename;
                                        if (file_exists($file)) {
                                            header('Content-Description: File Transfer');
                                            header('Content-Type: application/octet-stream');
                                            header('Content-Disposition: attachment; filename='.basename($file));
                                            header('Expires: 0');
                                            header('Cache-Control: must-revalidate');
                                            header('Pragma: public');
                                            header('Content-Length: ' . filesize($file));
                                            readfile($file);
                                            exit();
                                        }                                        
                                } else {
                                        // Auslesen von älteren Versionen
                                        $downloadHistory = $this->db->query("SELECT * FROM ".DB_PRE."downloads_history WHERE downloadid='".$downloadData[0]['downloadid']."' ORDER BY historyid DESC");
                                        if (isset($downloadHistory[0])) {
                                                $this->smarty->assign('downloadhistory', $downloadHistory);
                                        }
                                        
                                        $this->smarty->assign('downloaddata', $downloadData[0]);
                                        $this->smarty->assign('source', rtrim(strtr(base64_encode($downloadData[0]['source']), '+/', '-_'), '='));
                                        $this->smarty->assign('support', rtrim(strtr(base64_encode($downloadData[0]['support']), '+/', '-_'), '='));

                                        $this->template = 'Downloads/download';
                                }
                        } else {
				$this->request->redirect(HTTP_ROOT.'downloads');
			}
                } else {
			$this->request->redirect(HTTP_ROOT.'downloads');
		}
        }
}