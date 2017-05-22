<?php

class WizView extends ViewBase {

	/**
	 * template dir
	 * @var string
	 **/
	protected $tplSubDir = __DIR__.'/tpl/';

	public function chooseStyle() {
		require_once(__DIR__.'/../../model/style.php');
		$styleList = Style::findAll();
		$this->setPostHandler('http://localhost/configuratore/index.php?page=style');
		$this->setTplParam('styleList', $styleList);
		return $this->fetch('style');
	}

	public function choosePanorama() {
		//require_once(__DIR__.'/../../model/style.php');
		$this->setPostHandler('http://localhost/configuratore/index.php?page=panorama');
		return $this->fetch('panorama');
	}
}

?>