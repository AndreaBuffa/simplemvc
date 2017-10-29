<?php

class ConfigState extends State {
	const NAME = 'config';

	private function saveConf() {
		require_once(__DIR__.'/../../model/wizard/result.php');
		$jobWindow = new Result();

		if ($_SESSION["token"]) {
			$jobWindow->job_token = $_SESSION["token"];		
		} else {
			require_once(__DIR__.'/../../model/wizard/job.php');
			$job = new Job();
			$job->save();
			$_SESSION["token"] = $job->token;
			$jobWindow->job_token = $job->token;
		}
		$jobWindow->category = ($_SESSION['category'] == 'scorrevole')  ? 
			"CTG-PORTAFINESTRA-SCORREVOLE-2ANTE" : "CTG-FINESTRA-SCORREVOLE-2ANTE";
		$jobWindow->preset= strtoupper(strtolower($_SESSION["style"]));
		$jobWindow->save();
		if ($jobWindow->error_code == "0") {
			if ($jobWindow->new_window_id) {
				$jobWindow->window_id = $jobWindow->new_window_id;
			}
		}
	}

	public function process($method, $page) {
		switch ($page) {
			case self::NAME:
				break;
			case StyleState::NAME:
				$_SESSION['wizState'] = new StyleState();
				return header(HEADER_PREFIX.$page);
				break;
			case PanoramaState::NAME:
				$_SESSION['wizState'] = new PanoramaState();
				return header(HEADER_PREFIX.$page);
				break;
			case OpeningState::NAME:
				$_SESSION['wizState'] = new OpeningState();
				return header(HEADER_PREFIX.$page);
				break;
			default:
				return header(HEADER_PREFIX.self::NAME);
				break;
		}
		if ($method === 'GET') {
			require_once(__DIR__.'/../../model/wizard/rendering.php');
			$criteria["style"] = strtolower($_SESSION["style"]);
			$criteria["panorama"] = strtolower($_SESSION["panorama"]);
			$criteria["category"] = strtolower($_SESSION["category"]);
			$renderingList = Rendering::findAll($criteria);
			if (count($renderingList) == 0) {
				$_SESSION['wizState'] = new StartState();
				return header(HEADER_PREFIX.StartState::NAME);
			}
			// this is error prone
			if (isset($_SESSION[self::BRIGHTNESS_SESS_NAME])) {
				$p[self::BRIGHTNESS] = $_SESSION[self::BRIGHTNESS_SESS_NAME];
			} else {
				$p[self::BRIGHTNESS] = self::BRIGHTNESS_DEF_VAL;
			}

			// this is to avoid a false positive style/window type
			$p[self::INDOOR_STYLE] = $criteria["style"];

			if (isset($_SESSION[self::WIN_TYPE_SESS])) {
				$p[self::WIN_TYPE] = $_SESSION[self::WIN_TYPE_SESS];
			} else {
				$p[self::WIN_TYPE] = '';
			}
			// non si possono filtrare interno-esterno a priori
			$p['smvc-sideParam'] = 'interno';
			$p[self::WIN_COLOR] = (isset($_SESSION[self::WIN_COLOR_SESS])) ? 
				$_SESSION[self::WIN_COLOR_SESS] : 'avorio';

			$p[self::HANDLE_TYPE] = '';
			$p[self::HINGE_TYPE] = '';
			$p[self::HANDLE_COLOR] = '';		
			$defaultRendering = '';
			foreach ($renderingList as $key => $elem) {
				if (preg_match('/'.$p[self::BRIGHTNESS].'.+' .
					$p[self::WIN_TYPE] . '\/interno\/colore\/' .
					$p[self::WIN_COLOR] . '/', $elem)) {
					$defaultRendering = $renderingList[$key];
					break;
				}
			}

			require_once(__DIR__.'/../../view/wizard/wizView.php');
			$this->view = new WizView();
			$this->view->setTplParam('HOST', HOST);
			$this->view->setTplParam('METHOD', METHOD);
			$this->view->setTplParam('APP', APP);
			$this->view->setPostHandler(URL_PREFIX.self::NAME);
			$this->view->setTplParam('rendering', $defaultRendering);
			$this->view->setTplParam('renderingList', json_encode($renderingList));
			//hidden params sent to the client
			$this->view->setTplParam('parameters', $p);
			$this->view->setTplParam('brightParam', self::BRIGHTNESS);
			$this->view->setTplParam('winTypeParam', self::WIN_TYPE);
			$this->view->setTplParam('winColorParam', self::WIN_COLOR);
			$this->view->setTplParam('handleTypeParam', self::HANDLE_TYPE);
			$this->view->setTplParam('hingeTypeParam', self::HINGE_TYPE);
			//ucwords($_SESSION[self::WIN_COLOR_SESS];
			$this->view->setTplParam('handleColorParam', self::HANDLE_COLOR);
			$this->view->setTplParam('currSelImg', self::CURR_IMG_SEL);
			//$this->view->setTplParam('currSelImgVal', $defaultRendering);
			return $this->view->config();
		} else {
			if (isset($_POST['action'])) {
				$_SESSION[self::BRIGHTNESS_SESS_NAME] = $_POST[self::BRIGHTNESS];
				$_SESSION[self::WIN_TYPE_SESS] = $_POST[self::WIN_TYPE];
				$_SESSION[self::WIN_COLOR_SESS] = $_POST[self::WIN_COLOR];
				$_SESSION[self::HANDLE_TYPE_SESS] = $_POST[self::HANDLE_TYPE];
				$_SESSION[self::HANDLE_COLOR_SESS] = $_POST[self::HANDLE_COLOR];
				$_SESSION[self::HINGE_TYPE_SESS] = $_POST[self::HINGE_TYPE];

				if (isset($_POST[self::CURR_IMG_SEL])) {
					$_SESSION[self::CURR_IMG_SEL_SESS] = $_POST[self::CURR_IMG_SEL];
				}
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

?>