<?php

class ViewBase {

	/**
	 * template dir
	 * @var string
	 **/
	protected $tplSubDir = '';

	/**
	 * elenco variabili accessibile nel template
	 * @var array
	 **/
	protected $vars = array();

	/**
	public function __construct($tplSubDir, $template) {
		$this->vars = array();
		// prevent directory traversal
		if (preg_match('/^(\w+)$/', $tplSubDir, $matches) && 
				preg_match('/^(\w+)$/', $template, $matches)) {
			$this->tplSubDir = $tplSubDir;
			$this->file = $template;
		} else {
			throw new Exception('Invalid paths');
		}
	}
	**/

	/**
	 * @param string $name
	 * @param string $val
	 **/
	public function setTplParam($name, $val) {
		$this->vars[ $name ] = $val;
	}

	public function setPostHandler($value) {
		$this->setTplParam('action', $value);		
	}	

	/**
	 * Generate HTTP payload
	 **/
	public function fetch($tplFile) {
		foreach ($this->vars as $var => $val) {
			$var = 'v_'.$var;
			$$var = $val;
		}
		$file = $this->tplSubDir.'/'.$tplFile.'.tpl';
		ob_start();
		require($file);
		$output = ob_get_clean();
		return $output;
	}
}

?>
