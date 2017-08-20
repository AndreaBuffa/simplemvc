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
			$this->view->setPostHandler(METHOD.'://'.HOST.'/'.APP.'/index.php?page='.self::NAME);
			return $this->view->chooseStyle();			
		} else {
			if (isset($_POST['style'])) {
				$_SESSION['style'] = $_POST['style'];
				$_SESSION['wizState'] = new PanoramaState();
				return header('Location: index.php?page='.PanoramaState::NAME);
			} else {
				return $this->process('GET', self::NAME);
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
		if ($method === 'GET') {
			require_once(__DIR__.'/../view/wizard/wizView.php');
			$this->view = new WizView();
			$this->view->setTplParam('HOST', HOST);
			$this->view->setTplParam('METHOD', METHOD);
			$this->view->setTplParam('APP', APP);
			$this->view->setPostHandler(METHOD.'://'.HOST.'/'.APP.'/index.php?page='.self::NAME);
			return $this->view->choosePanorama();
		} else {
			if (isset($_POST['panorama'])) {
				$_SESSION['panorama'] = $_POST['panorama'];
				$_SESSION['wizState'] = new OpeningState();
				return header('Location: index.php?page='.OpeningState::NAME);
			} else {
				return $this->process('GET', self::NAME);
			}
		}
	}
}

class OpeningState extends State {
	const NAME = 'opening';

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
			$this->view->setPostHandler(METHOD.'://'.HOST.'/'.APP.'/index.php?page='.self::NAME);
			return $this->view->chooseOpening();
		} else {
			if (isset($_POST['category'])) {
				$_SESSION['category'] = $_POST['category'];
				$_SESSION['wizState'] = new ConfigState();
				return header('Location: index.php?page='.ConfigState::NAME);
			} else {
				return $this->process('GET', self::NAME);
			}
		}
	}
}

class ConfigState extends State {
	const NAME = 'config';

	public function process($method, $page) {
		if ($page !== self::NAME) {
			return header('Location: index.php?page='.self::NAME);
		}
		if ($method === 'GET') {
			require_once(__DIR__.'/../model/wizard/rendering.php');
			$criteria["style"] = strtolower($_SESSION["style"]);
			$criteria["panorama"] = strtolower($_SESSION["panorama"]);
			$criteria["category"] = strtolower($_SESSION["category"]);
			$renderingList = Rendering::findAll($criteria);
			if (count($renderingList) == 0) {
				$_SESSION['wizState'] = new StartState();
				return header('Location: index.php?page='.StartState::NAME);
			}
			$defaultRendering = '';
			foreach ($renderingList as $key => $elem) {
				if (preg_match('/interno/', $elem)) {
					$defaultRendering = $renderingList[$key];
					break;
				}
			}
			require_once(__DIR__.'/../view/wizard/wizView.php');
			$this->view = new WizView();
			$this->view->setTplParam('HOST', HOST);
			$this->view->setTplParam('METHOD', METHOD);
			$this->view->setTplParam('APP', APP);
			$this->view->setPostHandler(METHOD.'://'.HOST.'/'.APP.'/index.php?page='.self::NAME);
			$this->view->setTplParam('rendering', $defaultRendering);
			$this->view->setTplParam('renderingList', $renderingList);
			return $this->view->config();
		} else {

		}
	}
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
