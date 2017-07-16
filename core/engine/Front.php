<?php
final class Front {
	protected $registry;
        protected $smarty;
        public $template;
	private $output;
        private $content;
	
        /**
         * Initialisierung der Registry und Smarty-Klasse.
         * @param object $registry Registry-Objekt
         * @param object $smarty Smart-Objekt
         */
	public function __construct($registry, $smarty) {
		$this->registry = $registry;
                
                $this->smarty   = $smarty;
	}
        
        /**
         * Rendert den Controller und ruft den View auf.
         * @param string $action Name der auszuführenden Action
         * @param string $class Name der auszuführenden Klasse
         * @return boolean Gibt false bei Fehler zurück
         */
        public function render($action, $class) {
                $this->controller = str_replace('Controller', '', $action).'.php';
                
                if (file_exists(DIR_SYSTEM.DS.'app'.DS.'controller'.DS.$this->controller)) {
                        require_once DIR_SYSTEM.DS.'app'.DS.'controller'.DS.$this->controller;
                } else {
                        ErrorHandler::writeLog('internal', 'controller error: can\'t find controller "'.$this->controller.'" in '.DIR_SYSTEM.DS.'app'.DS.'controller'.DS);
                        return false;
                }

                $controller = new $action($this->registry);
                
                if (method_exists($controller, 'construct')) {
                        call_user_func_array(array($controller, 'construct'), array());
                }

                if (method_exists($controller, $class)) {
                        call_user_func_array(array($controller, $class), array());
                } else {
                        ErrorHandler::writeLog('internal', 'controller error: can\'t find class "'.$class.'" in controller "'.$this->controller.'"');
                        return false;
                }
                
                $this->output = $this->getView($controller->template);   
        }

        /**
         * Erzeugt den View eines Templates.
         * @param string $template Name des Templates
         * @return boolean Gibt false bei Fehler zurück oder übergibt den gerenderten Smarty-Output
         */
	private function getView($template) {                
                if (!empty($template)) {
                        ob_start();

                        $this->template = str_replace('/', DS, $template).'.tpl';

                        if (!file_exists(DIR_SYSTEM.DS.'app'.DS.'view'.DS.$this->template)) {
                                ErrorHandler::writeLog('internal', 'template error: can\'t find template "'.$this->template.'" in '.DIR_SYSTEM.DS.'app'.DS.'view'.DS);
                                return false;
                        } else {
                                $this->content = $this->smarty->fetch($this->template, md5($_SERVER['REQUEST_URI']));
                        }

                        if (COMPRESSION_HTML === true) {
                                $this->content = str_replace(array("\r\n", "\n", "\t", "  "), array(''), $this->content);
                        }

                        $this->content.= ob_get_contents();

                        ob_end_clean();
                }
     
                return $this->content;
	}
        
        /**
         * Erzeugt den Output und prüft ob Komprimierung aktiviert ist.
         */
        public function output() {
                if (COMPRESSION === true) {
                        if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
                                $encoding = 'gzip';
                        }

                        if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
                                $encoding = 'x-gzip';
                        }
                
                }

		if (!isset($encoding) OR COMPRESSION !== true OR (strpos($this->output, '/*STOP_SERVERSIDE_ENCODING*/') === 0 OR strpos($this->output, '/*STOP_SERVERSIDE_ENCODING*/') > 0)) {
			echo $this->output;
                        
                } elseif (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
			echo $this->output;
                        
		} elseif (headers_sent()) {
			echo $this->output;
                        
		} elseif (connection_status()) { 
			echo $this->output;
                        
		} else {
                        header('Content-Encoding: ' . $encoding);

                        echo gzencode($this->output, COMPRESSION_LEVEL);
                }
        }
}