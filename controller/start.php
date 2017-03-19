<?php

class ControllerStart extends ControllerBase {

	public function start() {
        return $this->view->fetch();
	}
}

?>