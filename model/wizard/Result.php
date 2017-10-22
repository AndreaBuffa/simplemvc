<?php

require_once(__DIR__.'/../OActiveRecord.php');

class Result extends OActiveRecord {

    const METHOD = 'modify_window.php';

    public function __construct() {
    	parent::__construct();
        $this->window_id = '11';
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