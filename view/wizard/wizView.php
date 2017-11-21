<?php

require_once(__DIR__.'/../../view/view.php');

class WizView extends ViewBase {

	/**
	 * template dir
	 * @var string
	 **/
	protected $tplSubDir = __DIR__.'/tpl/';

	public function chooseStyle() {
		//at the end of the day, it's a static page
		return $this->fetch('style');
	}

	public function choosePanorama() {
		$this->setTplParam('metro', $_SESSION["style"] == 'ufficio' ? 'true' : 'false');
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

	public function sizeAndQuantity() {
		return $this->fetch('configB');	
	}

	public function outdoor() {
		return $this->fetch('configOutdoor');
	}
}

?>