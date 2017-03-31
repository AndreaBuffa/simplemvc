<?php

class Modeluser extends ModelBase {

	private $campo__firstname;
	private $campo__lastname;
	private $campo__email;
	private $campo__datetime;
	private $campo__emailDomain;

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
