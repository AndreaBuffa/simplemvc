<?php

interface iState {
	public function process($method, $page);
}

abstract class State implements iState {}

class StartState extends State {
	const NAME = 'start';

	public function process($method, $page) {
		//reset all session data
		$_SESSION['wizState'] = new StyleState();
		return header('Location: index.php?page='.StyleState::NAME);
	}
}

class StyleState extends State {
	const NAME = 'style';

	public function process($method, $page) {
		if ($page !== self::NAME) {
			return header('Location: index.php?page='.self::NAME);
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
				return header('Location: index.php?page='.PanoramaState::NAME);
			}
		}
	}
}

class PanoramaState extends State {
	const NAME = 'panorama';

	public function process($method, $page) {
		if ($page !== self::NAME) {
			return header('Location: index.php?page='.self::NAME);
		}		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
		} else {
			require_once(__DIR__.'/../view/wizard/wizView.php');
			$this->view = new WizView();
			$this->view->setTplParam('HOST', HOST);
			$this->view->setTplParam('METHOD', METHOD);
			$this->view->setTplParam('APP', APP);
			return $this->view->choosePanorama();
		}
	}
}

class OpeningState extends State {

	public function process($method, $page) {}
}

class ConfigState extends State {
	public function process($method, $page) {}
}

class WizardFactory {
	protected $controller;
	public function __construct($pageName) {
		switch ($pageName) {
			case StartState::NAME:
			case StyleState::NAME:
			case PanoramaState::NAME:
			case OpeningState::NAME:
			case ConfigState::NAME:
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

	private $pageName;

	public function __construct($pageName) {
		session_start();
		if ($_SESSION['wizState'] && ($pageName !== StartState::NAME)) {
			$this->currentState = $_SESSION['wizState'];
		} else{
			$this->currentState = new StartState();
			$_SESSION['wizState'] = $this->currentState;
		}
		$this->pageName = $pageName;
	}

	public function processRequest() {
		$method = $_SERVER['REQUEST_METHOD'];
		switch ($method) {
			case 'GET':
			case 'POST':
				//var_dump($this->currentState);
				return $this->currentState->process($method, $this->pageName);
			break;
		}
	}
}

?>
