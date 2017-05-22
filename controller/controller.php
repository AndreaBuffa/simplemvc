<?php
//define("IS_TEST", true);

abstract class ControllerBase {

	protected $view;

	static public function exec() {
		$controllerName = '';
		$action = '';

		$tokens = explode('&', $_SERVER['QUERY_STRING']);
		$params = array();
		foreach ($tokens as $key => $value) {
			# code...
			$tmp = explode('=', $value);
			if (count($tmp) == 2) {
				$params[$tmp[0]] = $tmp[1];
			}
		}

		if (defined('IS_TEST') && IS_TEST) {
			$controllerName = 'test';
			$action = 'dummy';
		} else {
			require_once(__DIR__.'/wizard.php');
			$factory = new WizardFactory($params['page']);
			$controller = $factory->getController();
			if ($controller) {
				return $controller->processRequest();
			} else {
				//@todo not found page
				echo 'not found';
				return "";
			}
		}
	}

	abstract public function processRequest();

	public function redirect($pageName) {
		//@todo check the page
		header('Location: index.php?page='.$pageName);
	}

}

?>
