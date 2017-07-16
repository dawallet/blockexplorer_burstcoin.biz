<?php       
class LibraryMailer extends Library {
        /**
         * Sendet eine Nachricht über die PHPMailer-Klasse.
         * @param string $message Name des Mail-Templates
         * @param string $mailTo E-Mail des Empfängers
         * @param string $mailToName Name des Empfängers
         * @param array $mailStrings Zu ersetzende Platzhalter (Default: leerer Array)
         * @param array $stringData Werte der Platzhalter (Default: leerer Array)
         * @param string $type Template-Typ (Default: global)
         * @param string $mailFrom E-Mail des Absenders (Default: MAIL_FROM aus config.php)
         * @param string $mailFromName Name des Absenders (Default: MAIL_FROM_NAME aus config.php)
         * @param string $mailReply E-Mail der Antwortadresse (Default: MAIL_REPLY aus config.php)
         * @param string $mailReplyName Name der Antwortadresse (Default: MAIL_REPLY_NAME aus config.php)
         * @param string $mailSubejct Individueller Betreff der nicht aus einem Mail-Template kommt
         * @param boolean true|false Sagt dem Mail-Server ob es sich um einen Massenversand von E-Mails handelt
         * @parram array $attachments Pfad zu einem oder mehreren Datei-Anhängen
         * @return boolean true|false Gibt false bei fehlgeschlagenem Mailversand, ansonsten true zurück
         */
	public function send($message, $mailTo, $mailToName, $mailStrings = array(), $stringData = array(), $type = 'global', $mailFrom = MAIL_FROM, $mailFromName = MAIL_FROM_NAME, $mailReply = MAIL_REPLY, $mailReplyName = MAIL_REPLY_NAME, $mailSubject = '', $bulkMail = false, $attachments = array()) {
                require_once DIR_SYSTEM.DS.'core'.DS.'libs'.DS.'phpmailer'.DS.'class.phpmailer.php';

                $mailer = new PHPMailer();
                $mailer->CharSet        = 'utf-8';
                if (isset($_SESSION['lang']) AND $_SESSION['lang'] == "en") {
                        $mailer->SetLanguage("en");
                } else {
                        $mailer->SetLanguage("de");
                }
                $mailer->IsSMTP();
                $mailer->SMTPDebug      = 0;
                $mailer->Debugoutput    = 'html';
                $mailer->Host           = SMTP_HOST;
                $mailer->Port           = SMTP_PORT;
                $mailer->SMTPAuth       = true;
                if ($bulkMail == true) { $mailer->SMTPKeepAlive = true; }
                $mailer->Username       = SMTP_USER;
                $mailer->Password       = SMTP_PASSWORD;
                $mailer->SetFrom($mailFrom, $mailFromName);
                $mailer->AddReplyTo($mailReply, $mailReplyName);
                $mailer->AddAddress($mailTo, $mailToName);
                if (!empty($mailSubject)) {
                        $mailer->Subject = $mailSubject;
                } else {
                        if (isset($_SESSION['lang']) AND file_exists(DIR_SYSTEM.DS.'app'.DS.'view'.DS.'Mails'.DS.$message.'_subject_'.$_SESSION['lang'].'.tpl')) {
                                $mailer->Subject = file_get_contents(DIR_SYSTEM.DS.'app'.DS.'view'.DS.'Mails'.DS.$message.'_subject_'.$_SESSION['lang'].'.tpl');
                        } else {
                                $mailer->Subject = file_get_contents(DIR_SYSTEM.DS.'app'.DS.'view'.DS.'Mails'.DS.$message.'_subject.tpl');
                        }
                }
                if (count($attachments) > 0) {
                        foreach ($attachments AS $attachment) {
                                $mailer->AddAttachment($attachment);
                        }
                }
                $mailer->MsgHTML($this->getMailMessage($message, $type, $mailStrings, $stringData));

                if(!$mailer->Send()) {
                        return false;
                }
                
                return true;
	}
        
        /**
         * Ließt das Mail-Template.
         * @param string $message Name des Mail-Templates
         * @param string $type Template-Typ
         * @param array $mailStrings Zu ersetzende Platzhalter
         * @param array $stringData Werte der Platzhalter
         * @return type Gibt vollständige E-Mail Nachricht zurück
         */
        private function getMailMessage($message, $type, $mailStrings, $stringData) {
                $this->msg = file_get_contents(DIR_SYSTEM.DS.'app'.DS.'view'.DS.'Mails'.DS.$type.'_header.tpl');
                if (isset($_SESSION['lang']) AND file_exists(DIR_SYSTEM.DS.'app'.DS.'view'.DS.'Mails'.DS.$message.'_'.$_SESSION['lang'].'.tpl')) {
                        $this->msg.= file_get_contents(DIR_SYSTEM.DS.'app'.DS.'view'.DS.'Mails'.DS.$message.'_'.$_SESSION['lang'].'.tpl');
                } else {
                        $this->msg.= file_get_contents(DIR_SYSTEM.DS.'app'.DS.'view'.DS.'Mails'.DS.$message.'.tpl');
                }
                $this->msg.= file_get_contents(DIR_SYSTEM.DS.'app'.DS.'view'.DS.'Mails'.DS.$type.'_footer.tpl');
                
                $this->msg = str_replace($mailStrings, $stringData, $this->msg);
                
                return $this->msg;                
        }
}