<?php

/** classe di interazione con il database **/
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
		 * codice non presente, supporre che sia presente e funzionante
		 * . . .
		 * tenere a mente che ogni elemento di $records è una istanza
		 * generata dalla classe ModelXXX relativa alla tabella indicata
		 **/		
		return $records;
	}
	
	/**
	 * esegue una query di insert/update e ritorna l'id del record
	 * modificato, oppure -1 in caso di errore
	 * 
	 * @param  string $sql query da eseguire
	 * 
	 * @return int
	 **/
	static public function executeAndReturnID($sql) {
		$id = -1;
		/**
		 * codice non presente, supporre che sia presente e funzionante
		 * . . .
		 * 
		 **/
		return $id;
	}
	
}




?>