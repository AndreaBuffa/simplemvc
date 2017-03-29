<?php

class ViewBase {

	/**
	 * template dir
	 * @var string
	 **/
	private $tplSubDir;
	/**
	 * template file
	 * @var string
	 **/
	private $file;

	/**
	 * elenco variabili accessibile nel template
	 * @var array
	 **/
	private $vars = array();

	/**
	 * costruttore oggetto
	 * @param string $azione file specifico da caricare
	 **/
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
	/**
	 * @param string $name
	 * @param string $val
	 **/
	public function setTplParam($name, $val) {
		$this->vars[ $name ] = $val;
	}

	/**
	 * Generate HTTP payload
	 **/
	public function fetch() {
		foreach ($this->vars as $var => $val) {
			$var = 'v_'.$var;
			$$var = $val;
		}
		$file = __DIR__.'/tpl/'.$this->tplSubDir.'/'.$this->file.'.tpl';
		ob_start();
		require($file);
		$output = ob_get_clean();
		return $output;
	}
}

?>
