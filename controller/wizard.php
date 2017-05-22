<?php

interface iState {
	public function process($method, $page);
}

abstract class State implements iState {
	protected $context;
}

class StartState extends State {

	public function process($method, $page) {
		//reset all session data
		$_SESSION['wizState'] = new StyleState();
		return header('Location: index.php?page=style');
	}
}

class StyleState extends State {

	public function process($method, $page) {
		if ($page !== 'style') {
			return header('Location: index.php?page=style');
		} 
		if ($method === 'GET') {
			require_once(__DIR__.'/../view/wizard/wizView.php');
			$this->view = new WizView();
			$this->view->setTplParam('HOST', HOST);
			$this->view->setTplParam('METHOD', METHOD);
			$this->view->setTplParam('APP', APP);
			return $this->view->chooseStyle();			
		} else {
			if (isset($_POST['style'])) {
				$_SESSION['style'] = $_POST['style'];
				$_SESSION['wizState'] = new PanoramaState();
				return header('Location: index.php?page=panorama');
			}
		}
	}
}

class PanoramaState extends State {

	public function process($method, $page) {
		require_once(__DIR__.'/../view/wizard/wizView.php');
		$this->view = new WizView();
		$this->view->setTplParam('HOST', HOST);
		$this->view->setTplParam('METHOD', METHOD);
		$this->view->setTplParam('APP', APP);
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		} else {
			return $this->view->choosePanorama();
		}
	}
}

class OpeningState extends State {

	public function process($method) {}
}

class ConfigState extends State {
	public function process($method) {}
}

class WizardFactory {
	protected $controller;
	public function __construct($pageName) {
		switch ($pageName) {
			case 'start':
			case 'style':
			case 'panorama':
			case 'opening':
			case 'config':
				$this->controller = new Wizard($pageName);
			break;
			default:
				//@todo warning
		}
	}
	public function getController() {
		return $this->controller;
	}

}

class Wizard extends ControllerBase {

	private $currentState;

	private $stepList = array('start' => '');

	public function __construct($pageName) {
		session_start();
		if ($_SESSION['wizState'] && ($pageName !== 'start')) {
			$this->currentState = $_SESSION['wizState'];
			//var_dump($this->currentState);
		} else{
			$this->currentState = new StartState();
			$_SESSION['wizState'] = $this->currentState;
		}
	}

	public function processRequest() {
		$method = $_SERVER['REQUEST_METHOD'];
		switch ($method) {
			case 'GET':
			case 'POST':
				//var_dump($this->currentState);
				return $this->currentState->process($method, $page);
			break;
		}
	}
}

?>
