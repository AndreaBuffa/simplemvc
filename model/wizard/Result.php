<?php

require_once(__DIR__.'/../OActiveRecord.php');

class Result extends OActiveRecord {

    const METHOD = 'modify_window.php';
    const MAP_NAME = 'result';

    public function __construct() {
    	parent::__construct();
        //$this->_methodList[ActiveRecord::STATE_NEW] = 'job_token_from_reference.php';
        //$this->_methodList[ActiveRecord::STATE_LOADED] = 'modify_window.php';
        $this->window_id = 'new';
    }

    public $error_code;
    public $new_window_id;
    public $job_token;
	public $window_id;
	public $category;
	public $preset;

	public function getAttrToSave() {
		return ['job_token', 'category', 'window_id', 'preset'];
	}

}

?>