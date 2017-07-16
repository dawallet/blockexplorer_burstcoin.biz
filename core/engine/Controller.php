<?php
abstract class Controller {    
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
}