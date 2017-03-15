<?php


/** classe base che **/
class ControllerBase {

	protected $view;

	/**
	 * metodo che analizza l'url, identifica quale controller chiamare
	 * ed esegue la chiamata dopo aver istanziato l'oggetto.
	 * gli url saranno del tipo http://....../<controller>/<azione>
	 * esempio: 
	 *    url: http://......../test/dummy
	 * 	  controller: test
	 * 	  azione: dummy
	 **/
	static public function exec() {
		$controller = '';
		$azione     = '';
		/**
		 * codice di identificazione controller e azione non presente, 
		 * supporre che sia presente e funzionante
		 * . . .
		 * 
		 **/
		 if (defined('IS_DEBUG') && IS_DEBUG) {
			 $controller = 'test';
			 $azione = 'dummy';
		 }
		 // inclusione file definizione controller
		 require_once(__DIR__.'/'.strtolower($controller).'.php');
		 // creazione oggetti 
		 $class_controller = 'Controller'.ucfirst(strtolower($controller));
		 $view       = new ViewBase(strtolower($controller).'/'.$azione);
		 $obj = new $class_controller($view);
		 return $obj->$azione();
	}


	/**
	 * costruttore
	 * @param object $view oggetto relativo alla view usare nella
	 *                     creazione dell'html da inviare all'utente
	 **/
	public function __construct(&$view) {
		$this->view = &$view;
	}

}

?>