<?php
class Bootstrap {
        /**
         * Erzeugt eine Bootstrap Alert-Meldung.
         * @param string $message Text
         * @param string $type success|info|warning|danger
         * @param string $headline Überschrift
         * @return string HTML Alert-Meldung
         */
	public function alert($message, $type = '', $headline = '', $style = '') {
                if ($type == 'success' || $type == 'info' || $type == 'warning' || $type == 'danger') {
                        $type = ' alert-'.$type;
                }
                
                if (!empty($headline)) {
                        $headline = '<strong>'.$headline.'</strong> ';
                }
                
                return '<div class="alert'.$type.' alert-dismissable" style="'.$style.'"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$headline.$message.'</div>';
	}
        
        /**
         * Erzeugt eine JavaScript Weiterleitung.
         */
        public function redirect($url, $return = false) {
                if ($return == false) {
                        echo '<script>self.location.href="'.HTTP_ROOT.$url.'"</script>';
                } else {
                        return '<script>self.location.href="'.HTTP_ROOT.$url.'"</script>';
                }
        }
}