<?php

require_once(__DIR__.'/../../view/view.php');

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
		var_dump($_SESSION);
		require_once(__DIR__.'/../../model/wizard/rendering.php');
		$criteria["style"] = strtolower($_SESSION["style"]);
		$criteria["panorama"] = strtolower($_SESSION["panorama"]);
		$criteria["category"] = strtolower($_SESSION["category"]);
		$currRendering = Rendering::findAll($criteria);

		//$this->setTplParam('renderingList', $currRendering);
		$this->setTplParam('rendering', "static/images/montagna/chiari/stili/classico/battente/sistemi/classic/esterno/colore/6638.jpg");
		return $this->fetch('config');	
	}
}

?>