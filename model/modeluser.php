<?php

/** esempio di controller : Test **/

class Modeluser extends ModelBase {
	
	public $campo__firstname;
	public $campo__lastname;
	public $campo__email;
	public $campo__datetime;
	public $campo__emailDomain;

	// la trasforma in un singleton, così uso sempre solo un'istanza... 
	// tuttavia potrebbe rivelarsi scomoda questa scelata, e il controller 
	// può occuparsi di istanziare una sola volta questa classe e assegnarla ad un 
	// membro privato del controller.
	private function __construct() {
		$this->tabella = "user";
	}

	public static getInstance() {
		if (!Modeluser::instance) {
			Modeluser::instance = new Modeluser();
		}
		return Modeluser::instance;
	}
	
	__set($prop, $val) {
		// non so se funziona così, l'alternativa è una semplice funzione "calcolaDominio"
		// da chiamare dal controller. 
		if ($prop === 'campo_email') {
			$matched = array();
			if (preg_match('(\@[a-zA-Z\'\xC0-\xFF]\.[a-z]{2,})$', $this->campo__email, $matches)) {
				if (count($matches) > 0) {
					$this->campo__emailDomain = $matched[0];
				}
			}
		}
	}

}

?>