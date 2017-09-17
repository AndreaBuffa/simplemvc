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
		return header(HEADER_PREFIX.StyleState::NAME);
	}
}

class StyleState extends State {
	const NAME = 'style';

	public function process($method, $page) {
		if ($page !== self::NAME) {
			return header(HEADER_PREFIX.self::NAME);
		}
		if ($method === 'GET') {
			require_once(__DIR__.'/../view/wizard/wizView.php');
			$this->view = new WizView();
			$this->view->setTplParam('HOST', HOST);
			$this->view->setTplParam('METHOD', METHOD);
			$this->view->setTplParam('APP', APP);
			$this->view->setPostHandler(URL_PREFIX.self::NAME);
			return $this->view->chooseStyle();			
		} else {
			if (isset($_POST['style'])) {
				$_SESSION['style'] = $_POST['style'];
				$_SESSION['wizState'] = new PanoramaState();
				return header(HEADER_PREFIX.PanoramaState::NAME);
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
			return header(HEADER_PREFIX.self::NAME);
		}		
		if ($method === 'GET') {
			require_once(__DIR__.'/../view/wizard/wizView.php');
			$this->view = new WizView();
			$this->view->setTplParam('HOST', HOST);
			$this->view->setTplParam('METHOD', METHOD);
			$this->view->setTplParam('APP', APP);
			$this->view->setPostHandler(URL_PREFIX.self::NAME);
			return $this->view->choosePanorama();
		} else {
			if (isset($_POST['panorama'])) {
				$_SESSION['panorama'] = $_POST['panorama'];
				$_SESSION['wizState'] = new OpeningState();
				return header(HEADER_PREFIX.OpeningState::NAME);
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
			return header(HEADER_PREFIX.self::NAME);
		}
		if ($method === 'GET') {
			require_once(__DIR__.'/../view/wizard/wizView.php');
			$this->view = new WizView();
			$this->view->setTplParam('HOST', HOST);
			$this->view->setTplParam('METHOD', METHOD);
			$this->view->setTplParam('APP', APP);
			$this->view->setPostHandler(URL_PREFIX.self::NAME);
			return $this->view->chooseOpening();
		} else {
			if (isset($_POST['category'])) {
				$_SESSION['category'] = $_POST['category'];
				$_SESSION['wizState'] = new ConfigState();
				return header(HEADER_PREFIX.ConfigState::NAME);
			} else {
				return $this->process('GET', self::NAME);
			}
		}
	}
}

class ConfigState extends State {
	const NAME = 'config';
	const BRIGHTNESS = 'style-hidden';
	const BRIGHTNESS_DEF_VAL = 'chiari';
	const BRIGHTNESS_SESS_NAME = 'room-brightness';

	private function saveConf() {
		//http://alwin.orchestraweb.net/api/modify_window.php?job_token=89ddd4c28166&category=CTG-PORTAFINESTRA-SCORREVOLE-2ANTE&window_id=new
		require_once(__DIR__.'/../model/wizard/jobWindow.php');
		$jobWindow = new JobWindow();
		$jobWindow->job_token = '89ddd4c28166';
		//$jobWindow->save();
		$jobWindow->category = "CTG-PORTAFINESTRA-SCORREVOLE-2ANTE";//$_SESSION['category'];
		$jobWindow->window_id = 3;
		$jobWindow->save();
		//var_dump($jobWindow);
		//$r = new ReflectionClass('JobWindow');
        //var_dump($r->getDocComment());
	}

	public function process($method, $page) {
		if ($page !== self::NAME) {
			return header(HEADER_PREFIX.self::NAME);
		}
		if ($method === 'GET') {
			require_once(__DIR__.'/../model/wizard/rendering.php');
			$criteria["style"] = strtolower($_SESSION["style"]);
			$criteria["panorama"] = strtolower($_SESSION["panorama"]);
			$criteria["category"] = strtolower($_SESSION["category"]);
			$renderingList = Rendering::findAll($criteria);
			if (count($renderingList) == 0) {
				$_SESSION['wizState'] = new StartState();
				return header(HEADER_PREFIX.StartState::NAME);
			}
			if (isset($_SESSION[self::BRIGHTNESS_SESS_NAME])) {
				$parameters[self::BRIGHTNESS] = $_SESSION[self::BRIGHTNESS_SESS_NAME];
			} else {
				$parameters[self::BRIGHTNESS] = self::BRIGHTNESS_DEF_VAL;
			}
			$defaultRendering = '';
			foreach ($renderingList as $key => $elem) {
				if (preg_match('/'.$parameters[self::BRIGHTNESS].'.+interno/', $elem)) {
					$defaultRendering = $renderingList[$key];
					break;
				}
			}

			require_once(__DIR__.'/../view/wizard/wizView.php');
			$this->view = new WizView();
			$this->view->setTplParam('HOST', HOST);
			$this->view->setTplParam('METHOD', METHOD);
			$this->view->setTplParam('APP', APP);
			$this->view->setPostHandler(URL_PREFIX.self::NAME);
			$this->view->setTplParam('rendering', $defaultRendering);
			$this->view->setTplParam('renderingList', json_encode($renderingList));
			$this->view->setTplParam('parameters', $parameters);
			return $this->view->config();
		} else {
			if (isset($_POST['action'])) {
				$_SESSION[self::BRIGHTNESS_SESS_NAME] = $_POST[self::BRIGHTNESS];
				switch ($_POST['action']) {
					case 'configB':
						$this->saveConf();
						$_SESSION['wizState'] = new ConfigB();
						return header(HEADER_PREFIX.ConfigB::NAME);
						break;
					case 'outdoor':
						$_SESSION['wizState'] = new Outdoor();
						return header(HEADER_PREFIX.Outdoor::NAME);
						break;
					default:
						return $this->process('GET', self::NAME);
						break;
				}
			} else {
				return $this->process('GET', self::NAME);
			}
		}
	}
}

class ConfigB extends State {
	const NAME = 'configB';

	public function process($method, $page) {
		if ($page !== self::NAME) {
			return header(HEADER_PREFIX.self::NAME);
		}
		if ($method === 'GET') {
			require_once(__DIR__.'/../view/wizard/wizView.php');
			$this->view = new WizView();
			$this->view->setTplParam('HOST', HOST);
			$this->view->setTplParam('METHOD', METHOD);
			$this->view->setTplParam('APP', APP);
			$this->view->setPostHandler(URL_PREFIX.self::NAME);
			return $this->view->sizeAndQuantity();
		} else {

		}
	}
}

class Outdoor extends State {
	const NAME = 'outdoor';

	public function process($method, $page) {
		if ($page !== self::NAME) {
			return header(HEADER_PREFIX.self::NAME);
		}
		if ($method === 'GET') {
			require_once(__DIR__.'/../model/wizard/rendering.php');
			$criteria["style"] = strtolower($_SESSION["style"]);
			$criteria["panorama"] = strtolower($_SESSION["panorama"]);
			$criteria["category"] = strtolower($_SESSION["category"]);
			$renderingList = Rendering::findAll($criteria);
			if (count($renderingList) == 0) {
				$_SESSION['wizState'] = new StartState();
				return header(HEADER_PREFIX.StartState::NAME);
			}
			$defaultRendering = '';
			foreach ($renderingList as $key => $elem) {
				if (preg_match('/esterno/', $elem)) {
					$defaultRendering = $renderingList[$key];
					break;
				}
			}
			require_once(__DIR__.'/../view/wizard/wizView.php');
			$this->view = new WizView();
			$this->view->setTplParam('HOST', HOST);
			$this->view->setTplParam('METHOD', METHOD);
			$this->view->setTplParam('APP', APP);
			$this->view->setPostHandler(URL_PREFIX.self::NAME);
			$this->view->setTplParam('rendering', $defaultRendering);
			$this->view->setTplParam('renderingList', json_encode($renderingList));
			return $this->view->outdoor();
		} else {
			if (isset($_POST['action'])) {
				switch ($_POST['action']) {
					case 'configB':
						$_SESSION['wizState'] = new ConfigB();
						return header(HEADER_PREFIX.ConfigB::NAME);
						break;
					case 'config':
						$_SESSION['wizState'] = new ConfigState();
						return header(HEADER_PREFIX.ConfigState::NAME);
						break;
					default:
						return $this->process('GET', self::NAME);
						break;
				}
			} else {
				return $this->process('GET', self::NAME);
			}
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
			case ConfigB::NAME:
			case Outdoor::NAME:
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
