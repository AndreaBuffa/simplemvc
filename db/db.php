<?php

class DB {
	
	/**
	 * esegue una query e ritorna tutti i record estratti, ciascuno
	 * come "modello" memorizzato in un elemento di un array. 
	 * 
	 * @param  string $sql query da eseguire
	 * @param  string $tabella nome della tabella, usato per identificare
	 * 						   il Model corretto da usare
	 * 
	 * @return array
	 **/
	static public function getRecordsAsArray($sql,$tabella) {
		$records = array();
		/**
		 * generata dalla classe ModelXXX relativa alla tabella indicata
		 **/		
		return $records;
	}
	
	/**
	 * @param  string $sql
	 * @return int    the record ID. -1 if error.
	 **/
	static public function executeAndReturnID($sql) {
		$id = -1;
		return $id;
	}
}
?>
