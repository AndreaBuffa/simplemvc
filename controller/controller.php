<?php
//define("IS_TEST", true);

class ControllerBase {

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
			switch ($params['page']) {
				case 'start':
				case 'panorama':
				case 'opening':
				case 'config': 
					$controllerName = 'Wizard';
					$action = $params['page'];
					break;
				default:
					echo 'not found';
					return "";
			}
		}

		require_once(__DIR__.'/'.strtolower($controllerName).'.php');
		$controller = new $controllerName();
		return $controller->$action();
	}

}

?>
