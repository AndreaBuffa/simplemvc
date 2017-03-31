<?php

class Wizard extends ControllerBase {

	public function start() {
		require_once(__DIR__.'/../model/style.php');
		Style::getInstance('Style', null)->findAll();
		return $this->view->fetch();
	}
}

?>
