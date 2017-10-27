<?php

require_once(__DIR__.'/../OActiveRecord.php');

class Job extends OActiveRecord {

    const METHOD = 'job_token_from_reference.php';
    const MAP_NAME  = 'job';

    public $token;

	public function getAttrToSave() {
		return [];
	}

}

?>