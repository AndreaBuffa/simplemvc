<?php


/** classe base che **/
class ControllerBase {

	protected $view;

	static public function exec() {
		$controller = '';
		$action     = '';

		$tokens = split('&', $_SERVER['QUERY_STRING']);
		$params = array();
		foreach ($tokens as $key => $value) {
			# code...
			$tmp = split('=', $value);
			if (count($tmp) == 2) {
				$params[$tmp[0]] = $tmp[1];	
			}
		}
		//$tokens = split($_SERVER['REQUEST_URI'], '/');
		 if (defined('IS_TEST') && IS_TEST) {
			 $controller = 'test';
			 $action = 'dummy';
		 } else {
		 	switch ($params['page']) {
		 		case 'start': {
		 			$controller = $params['page'];
		 			$action = 'start';
		 			break;
		 			defaut:
		 				print 'not found'
		 				;
		 		}
		 	}
		 }
		 // inclusione file definizione controller
		 require_once(__DIR__.'/'.strtolower($controller).'.php');
		 // creazione oggetti 
		 $class_controller = 'Controller'.ucfirst(strtolower($controller));
		 $view = new ViewBase(strtolower('wizard/'.$controller));
		 $obj = new $class_controller($view);
		 return $obj->$action();
	}


	/**
	 * costruttore
	 * @param object $view oggetto relativo alla view usare nella
	 *                     creazione dell'html da inviare all'utente
	 **/
	public function __construct(&$view) {
		$this->view = &$view;
	}

}

?>