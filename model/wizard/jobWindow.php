<?php

require_once(__DIR__.'/../OActiveRecord.php');

/**
 * model a realation 1:N + the related object itself.
 * (the window)
 */
class JobWindow extends OActiveRecord {

    const METHOD = 'modify_window.php';

    public function __constructor() {
    	parent::__construct();
        $this->window_id = 'new';
    }

    public $job_token;
    public $window_id;
    public $new_window_id;
    /**
    * Nome della tipologia nella commessa
    * Optional
    */
 	public $window_name;
    /**
    * in mm
    * Optional
    */
 	public $w;
     /**
    * in mm
    * Optional
    */
	public $h;
    /**
     * 
     * Optional
     */
 	public $preset;
    /**
     * 
     * Optional
     */
	public $internal_color;
    /**
     * 
     * Optional
     */
	public $external_color;
    /**
     * string nel formato OPZ1(VALORE), OPZ2(VALORE),...
     * Optional
     */
	public $options;
    /**
     * 
     * Optional
     */
	public $category;

	public function getAttrToSave() {
		return ['job_token', 'category', 'window_id'];
	}

}

?>