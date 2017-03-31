<?php

/** 
 *  tutte le classi che saranno create come Modelli dovranno avere come
 *  nome Model<NomeTabella> , es: ModelPersone , ModelCitta , ...
 **/
class ModelBase {

	static protected $instance;
	private function __construct() {}

	/**
	 * @var string
	 **/
	protected $table = '';


	/**
	 * id relativo al record "modellato" dalla classe Model in uso.
	 * @var int
	 **/
	public $campo__id = -1;

	/**
	 * @param  mixed $value valore da "pulire"
	 * @return string
	 **/
	public function cleanString($value) {
		return mysql_escape_string($value);
	}

	/**
	 * @param  mixed $value valore da "pulire"
	 * @return int
	 **/
	public function cleanNumberInt($value) {
		return intval($value);
	}

	/**
	 * @param  mixed $value valore da "pulire"
	 * @return float
	 **/
	public function cleanNumberFloat($value) {
		//@todo non copre tutti i casi. vedi documentazione. 
		return floatval($value);
	}

	/**
	 * @param string $attr
	 * @param string $value valore del campo da usare per la query
	 * @return array
	 **/
	public function select($attr, $value) {
		if (!$attr) {
			return array(); 
		}
		$sql = "SELECT * FROM {$this->table} WHERE $attr='{$this->cleanString($value)}';";
		return DB::getRecordsAsArray($sql, $this->table);
	}

	/**
	 * @return int
	 **/
	public function save() {
		$id = $this->campo__id;
		$campi = array();
		foreach ($this as $prop => $val) {
			if (substr($prop,0,7) == 'campo__') {
				$campi[ substr($prop,7) ] = $val;
			}
		}
		if ($id == -1) {
			$valori = array();
			foreach ($campi as $v) {
				$valori[] = "'".$v."'";
			}
			$sql = 'INSERT INTO '.$this->table.' ('.implode(',',array_keys($campi)).') VALUES ('.implode(',',$valori).')';
		} else {
			$id = $this->cleanNumberInt($id);
			$valori = array();
			foreach ($campi as $k=>$v) {
				$valori[] = $k . " = '".$v."' ";
			}
			$sql = 'UPDATE '.$this->table.' SET '.implode(', ',$valori).' WHERE id='.$id;
		}
		return DB::executeAndReturnID($sql);
	}

	public function getRecordBy($attrName, $attrValue) {
		$sql = 'Select * FROM '.$this->table.' ';
	}
}
?>
