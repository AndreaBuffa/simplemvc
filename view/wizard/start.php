<?php

class Start extends ViewBase {

	/**
	 * template dir
	 * @var string
	 **/
	protected $tplSubDir = __DIR__.'/tpl/';

	public function chooseStyle() {
		require_once(__DIR__.'/../../model/style.php');
		//$styleList = Style::getInstance('Style', null)->findAll();
		$styleList = Style::findAll();
		$this->setPostHandler('http://localhost/configuratore/index.php?page=start');
		$this->setTplParam('styleList', $styleList);
		return $this->fetch('start');
	}

	public function choosePanorama() {
		//require_once(__DIR__.'/../../model/style.php');
	}
}

?>