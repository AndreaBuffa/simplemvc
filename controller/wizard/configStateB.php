<?php

class ConfigB extends State {
	const NAME = 'configB';

	private function buildBriefDescr() {
		$this->view->setTplParam('rendering', $_SESSION[self::CURR_IMG_SEL_SESS]);
		$this->view->setTplParam('type', strtoupper($_SESSION[self::WIN_TYPE_SESS]));
		$this->view->setTplParam('color', ucwords($_SESSION[self::WIN_COLOR_SESS]));
		$this->view->setTplParam('color_outdoor', ucwords($_SESSION[self::WIN_COLOR_OUT_SESS]));
		$this->view->setTplParam('handle', ucwords($_SESSION[self::HANDLE_TYPE_SESS]));
		$this->view->setTplParam('handle_color', ucwords($_SESSION[self::HANDLE_COLOR_SESS]));
		if ($_SESSION[self::HINGE_TYPE_SESS]) {
			$this->view->setTplParam('hinge_type', ucwords('A '.$_SESSION[self::HINGE_TYPE_SESS]));
		} else {
			$this->view->setTplParam('hinge_type', "");
		}
		require_once(__DIR__.'/../../model/wizard/jobPrice.php');
		$jobPrice = new JobPrice();
		$jobPrice->job_token = $_SESSION["token"];
		$jobPrice->save();
		$this->view->setTplParam('windows', $jobPrice->windows);
		$price = "";
		foreach ($jobPrice->windows as $win) {
			$price += $win->price;
		}
		$this->view->setTplParam('price', $price);
	}

	public function process($method, $page) {
		if ($page !== self::NAME) {
			return header(HEADER_PREFIX.self::NAME);
		}
		if ($method === 'GET') {
			require_once(__DIR__.'/../../view/wizard/wizView.php');
			$this->view = new WizView();
			$this->view->setTplParam('HOST', HOST);
			$this->view->setTplParam('METHOD', METHOD);
			$this->view->setTplParam('APP', APP);
			$this->view->setPostHandler(URL_PREFIX.self::NAME);
			$this->buildBriefDescr();
			return $this->view->sizeAndQuantity();
		} else {
			if (isset($_POST['action'])) {
				switch ($_POST['action']) {
					case 'submitForPrice':
						//$_SESSION['wizState'] = new ConfigB();
						require_once(__DIR__.'/../../view/wizard/wizView.php');
						$this->view = new WizView();
						$this->view->setTplParam('HOST', HOST);
						$this->view->setTplParam('METHOD', METHOD);
						$this->view->setTplParam('APP', APP);
						$this->view->setPostHandler(URL_PREFIX.self::NAME);
						$this->buildBriefDescr();

						return $this->view->sizeAndQuantity();
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