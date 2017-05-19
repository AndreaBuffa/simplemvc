<?php


class Wizard extends ControllerBase {

	private $current;

	private $stepList = array('start' => '');

	public function start() {
		require_once(__DIR__.'/../view/wizard/start.php');
		$this->view = new Start();
		$this->view->setTplParam('HOST', HOST);
		$this->view->setTplParam('METHOD', METHOD);
		$this->view->setTplParam('APP', APP);
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			http_redirect('', array("page" => "panorama"), true);
		} else {
			return $this->view->chooseStyle();
		}
	}

	public function panorama() {
		require_once(__DIR__.'/../view/wizard/start.php');
		$this->view = new Start();
		$this->view->setTplParam('HOST', HOST);
		$this->view->setTplParam('METHOD', METHOD);
		$this->view->setTplParam('APP', APP);
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			http_redirect('', array("page" => "opening"), true);
		} else {
			return $this->view->choosePanorama();
		}
	}
}

?>
