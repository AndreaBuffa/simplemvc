<?php
//define("IS_TEST", true);
define('METHOD', 'http');
define('HOST', 'localhost');
define('APP', 'configuratore');
class ControllerBase {

	protected $view;

	static public function exec() {
		$controller = '';
		$action     = '';

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
			 $controller = 'test';
			 $action = 'dummy';
		 } else {
		 	switch ($params['page']) {
		 		case 'start':
		 		case 'panorama':
		 		case 'opening':
		 		case 'config': 
		 		{
		 			$controller = $params['page'];
		 			$action = 'start';
		 			break;
		 			defaut:
		 				print 'not found';
		 		}
		 	}
		 }

		 require_once(__DIR__.'/'.strtolower($controller).'.php');

		 $class_controller = 'Controller'.ucfirst(strtolower($controller));
		 $view = new ViewBase(strtolower('wizard/'.$controller));
		 $obj = new $class_controller($view);
		 return $obj->$action();
	}


	public function __construct(&$view) {
		$this->view = &$view;
		$this->view->assign('HOST', HOST);
		$this->view->assign('METHOD', METHOD);
		$this->view->assign('APP', APP);
	}

}

?>
