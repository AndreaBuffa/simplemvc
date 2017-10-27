<?php
require_once(__DIR__.'/../OActiveRecord.php');

class JobResult extends OActiveRecord {

    const METHOD = 'job_modify.php';
    const MAP_NAME = 'result';

    public function __construct() {
    	parent::__construct();
        //$this->window_id = '11';
    }

    public $error_code;
    public $job_token;
	public $window_id;
	public $technology_id;
	public $technology_group_id;

	public function getAttrToSave() {
		return ['job_token', 'window_id', 'technology_id', 'technology_group_id'];
	}

}

?>