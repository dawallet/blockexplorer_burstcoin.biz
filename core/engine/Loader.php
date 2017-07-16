<?php
final class Loader {
	protected $registry;

        /**
         * Initialisierung der Registry.
         * @param object $registry Registry-Objekt
         */
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
        /**
         * Holt sich den Wert eines Keys aus der Registry.
         * @param string $key Key
         * @return string Liefert den Wert eines Keys
         */
	public function __get($key) {
		return $this->registry->get($key);
	}

        /**
         * Setzt einen Wert in der Registry.
         * @param string $key Key
         * @param string $value Wert
         */
	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
		
        /**
         * Lädt ein Model aus /app/model/
         * @param string $model Name des Model
         */
	public function model($model) {
		$file  = DIR_SYSTEM.DS.'app'.DS.'model'.DS.$model.'.php';
		$class = 'Model'.preg_replace('/[^a-zA-Z0-9]/', '', $model);

		if (file_exists($file)) { 
			include_once($file);
			
			$this->registry->set('model_'.strtolower(str_replace('/', '_', $model)), new $class($this->registry));
		} else {
                        ErrorHandler::writeLog('internal', 'model load error: can\'t find model '.$model.'.php in '.DIR_SYSTEM.DS.'app'.DS.'model'.DS);
		}
	}
        
        /**
         * Lädt eine Library aus /core/library/
         * @param string $library Name der Library
         */
        public function library($library) {
		$file  = DIR_SYSTEM.DS.'core'.DS.'library'.DS.$library.'.php';
		$class = 'Library'.preg_replace('/[^a-zA-Z0-9]/', '', $library);

		if (file_exists($file)) { 
			include_once($file);
			
			$this->registry->set('library_'.strtolower(str_replace('/', '_', $library)), new $class($this->registry));
		} else {
                        ErrorHandler::writeLog('internal', 'library load error: can\'t find library '.$library.'.php in '.DIR_SYSTEM.DS.'core'.DS.'library'.DS);
		}
        }
}