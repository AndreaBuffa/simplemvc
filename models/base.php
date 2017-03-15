<?php

/** classe generica che implementa un "Model"
 *  tutte le classi che saranno create come Modelli dovranno avere come
 *  nome Model<NomeTabella> , es: ModelPersone , ModelCitta , ...
 **/
class ModelBase {

	static protected instance;
	
	private __construct() {}

	/**
	 * nome della tabella di riferimento del modello
	 * @var string
	 **/
	protected $tabella = '';


	/**
	 * id relativo al record "modellato" dalla classe Model in uso.
	 * @var int
	 **/
	public $campo__id = -1;
	
	/**
	 * tutti i campi della tabella devono essere formalizzati con 
	 * proprietà con nome $campo__nomecampo ed esplicitati nella 
	 * dichiarazione dello specifico modello.
	 * es: $campo__eta , $campo__citta , ...
	 **/
	
	
	/**
	 * DA IMPLEMENTARE !
	 * metodo che riceve una stringa e la ritorna modificata facendo si
	 * che sia ri-formattata in modo da non generare problemi nella 
	 * query in cui verrà usato
	 * 	 
	 * @param  mixed $value valore da "pulire"
	 * 
	 * @return string
	 **/
	public function cleanString($value) {
		return mysql_escape_string($value);
	}
	
	/**
	 * DA IMPLEMENTARE !
	 * metodo che riceve un valore e lo ritorna modificato facendo si
	 * che sia ri-formattato in un numero intero
	 * 
	 * @param  mixed $value valore da "pulire"
	 * 
	 * @return int
	 **/
	public function cleanNumberInt($value) {
		return intval($value);
	}
	
	/**
	 * DA IMPLEMENTARE !
	 * metodo che riceve un valore e lo ritorna modificato facendo si
	 * che sia ri-formattato in un numero decimale 
	 * 
	 * @param  mixed $value valore da "pulire"
	 * 
	 * @return float
	 **/
	public function cleanNumberFloat($value) {
		//@todo non copre tutti i casi. vedi documentazione. 
		return floatval($value);
	}
	
	/**
	 * DA IMPLEMENTARE !
	 * metodo che permette di estrarre i record corrispondenti alla 
	 * condizione "$campo" = "$valore". il risultato è un array
	 * di oggetto di tipo ModelXXX
	 * 
	 * @param string $campo  nome del campo da usare per la query
	 * @param string $valore valore del campo da usare per la query
	 * 
	 * @return array
	 **/
	public function select($campo,$valore) {
		if (!$campo) {
			return array(); 
		}
		$sql = "SELECT * FROM {$this->tabella} WHERE $campo='{$this->cleanString($valore)}';";
		return DB::getRecordsAsArray($sql,$this->tabella);
	}

	/**
	 * metodo che permette di salvare un nuovo record (id=-1) oppure 
	 * aggiornarne uno già esistente (id>0) e di ritornare l'id del 
	 * record modificato/generato

	 * @return int
	 **/
	public function save() {		
		$id = $this->campo__id;
		$campi = array();
		foreach ($this as $prop=>$val) {
			if (substr($prop,0,7) == 'campo__') {
				$campi[ substr($prop,7) ] = $val;
			}
		}
		if ($id==-1) {
			$valori = array();
			foreach ($campi as $v) {
				$valori[] = "'".$v."'";
			}
			$sql = 'INSERT INTO '.$this->tabella.' ('.implode(',',array_keys($campi)).') VALUES ('.implode(',',$valori).')';
		} else {
			$id = $this->cleanNumberInt($id);
			$valori = array();
			foreach ($campi as $k=>$v) {
				$valori[] = $k . " = '".$v."' ";
			}
			$sql = 'UPDATE '.$this->tabella.' SET '.implode(', ',$valori).' WHERE id='.$id;
		}
		return DB::executeAndReturnID($sql);
	}
	
	public function getRecordBy($attrName, $attrValue) {
		$sql = 'Select * FROM '.$this->tabella.' '
	}

}



?>