<?php

class WizView extends ViewBase {

	/**
	 * template dir
	 * @var string
	 **/
	protected $tplSubDir = __DIR__.'/tpl/';

	public function chooseStyle() {
		require_once(__DIR__.'/../../model/wizard/style.php');
		$styleList = Style::findAll();
		$this->setTplParam('styleList', $styleList);
		return $this->fetch('style');
	}

	public function choosePanorama() {
		//require_once(__DIR__.'/../../model/wizard/panorama.php');
		return $this->fetch('panorama');
	}

	public function chooseOpening() {
		require_once(__DIR__.'/../../model/wizard/tipoInfisso.php');
		//$types = Category::findAll();
		//$this->setTplParam('typeList', $types);
		return $this->fetch('opening');	
	}

	public function config() {
		return $this->fetch('config');	
	}
}

?>