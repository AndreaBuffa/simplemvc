<?php

/** classe per la gestione base delle view e dei template **/
class ViewBase {

	/**
	 * file di template da utilizzare
	 * @var string
	 **/
	private $file;

	/**
	 * elenco variabili accessibile nel template
	 * @var array
	 **/
	private $vars = array();

	/**
	 * costruttore oggetto
	 * @param string $azione file specifico da caricare
	 **/
	public function __construct($azione) {
		$this->vars = array();
		$this->file = $azione;
	}

	/**
	 * metodo per assegnare un valore ad una variabile della view
	 * @param string $nome    nome della variabile
	 * @param string $valore  valore della variabile
	 **/
	public function assign($nome,$valore) {
		$this->vars[ $nome ] = $valore;
	}

	/**
	 * metodo per caricare un template php, eseguirlo e ritornare 
	 * quanto generato. per tutte le variabili registrate nella view
	 * vado a generare una nuova variabile, accessibile dal template,
	 * che avrà come nome view_<nome> (es: test --> view_test )
	 **/
	public function fetch() {		
		foreach ($this->vars as $var=>$val) {
			$var = 'v_'.$var;
			$$var = $val;
		}
		$file = __DIR__.'/tpl/'.$this->file.'.tpl';
		ob_start();		
		require($file);
		$output = ob_get_clean();
		return $output;
	}

}

?>
