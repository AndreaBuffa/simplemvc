<?php

/** esempio di controller : Test **/

class ControllerTest extends ControllerBase {

	/**
	 * metodo per gestire l'azione "dummy" richiesta dall'utente.
	 * il metodo assegna al template delle variabili e poi 
	 * richiama il template stesso per ottenere l'html finale
	 **/
	public function dummy() {
		$this->view->assign('test','DUMMY');
		return $this->view->fetch();
	}

}

?>