<?php
class Database {
	private $MySQLiObj              = null;
	public  $queryStatus            = null;
	public  $numRows                = null;
	
        /**
         * Verbindungsaufbau zum MySQL-Server und setzt die nötigen Einstellungen.
         */
	public function __construct() {
		$this->MySQLiObj = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
		
		if (mysqli_connect_errno()) {
			ErrorHandler::writeLog('database', 'mysql connection error: can\'t connect to database');
			header("Location: ".HTTP_ROOT."errors/db.htm");
                        exit();                        
		}
		
		$this->query("SET NAMES ".DB_CHARSET);
	}

        /**
         * Schließt die Verbindung zum MySQL-Server.
         */
	public function __destruct() {
		$this->MySQLiObj->close();                
	}

        /**
         * Führt einen Query aus und liefert Ergebnisse zurück.
         * @param string $query SQL-Query
         * @param boolean $resultset False liefert Ergebnisse als Array zurück und true liefert nur den Status des Queries (Default: false)
         * @return array|boolean Ergebnisse als Array oder Query-Status 
         */
	public function query($query, $resultset = false) {
		$result = $this->MySQLiObj->query($query);

                if (isset($this->MySQLiObj->error) AND !empty($this->MySQLiObj->error)) {
                        ErrorHandler::writeLog('database', 'mysql query error: '.$this->MySQLiObj->error.' (query: '.$query.')');
                }
                
		if ($resultset === true) {
			if ($result === false) {
                                $this->queryStatus = false;
			} else {
				$this->queryStatus = true;
			}
                        $this->numRows = $result->num_rows;
                        
			return $result;
		}

		return $this->makeArrayResult($result);
	}
        
        /**
         * Liefert die ID eines neuen MySQL-Queries.
         * @return int MySQL Insert-ID
         */
        public function getId() {
                return $this->MySQLiObj->insert_id;
        }
        
        /**
         * Liefert die Fehlermeldung eines Queries.
         * @return string MySQL-Query Fehler
         */
	public function getError() {
		return $this->MySQLiObj->error;
	}
        
        /**
         * Bereinigt einen String von Zeichen wie NUL (ASCII 0), \n, \r, \, ', ", und Control-Z.
         * @param string $value String der bereinigt werden soll
         * @return string Liefert einen bereinigten String
         */
	public function escapeString($value) {
		return $this->MySQLiObj->real_escape_string($value);
	}

        /**
         * Erzeugt einen Array für die Results.
         * @param object $resultObj Query-Object
         * @return boolean|array Liefert Query-Status oder gibt Results als Array zurück sofern vorhanden
         */
	private function makeArrayResult($resultObj) {
		if ($resultObj === false) {
			$this->queryStatus = false;
                        
			return false;
                }

                if ($resultObj === true) {
                        $this->queryStatus = true;

                        return true;
                } else {
                        $this->numRows = $resultObj->num_rows;
                        if ($resultObj->num_rows == 0) {
                                $this->queryStatus = true;
                                
                                return array ();
                        } else {
                                $array = array ();

                                while ($line = $resultObj->fetch_array(MYSQL_ASSOC)) {
                                        array_push($array, $line);
                                }

                                $this->queryStatus = true;
                                
                                return $array;
                        }
                }                
	}
}