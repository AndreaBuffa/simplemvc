<?php
require_once(__DIR__.'/../OActiveRecord.php');

class Window {
    public $id;
    public $name;
    public $description;
    public $category;
    public $technology_group_id;
    public $position;
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
    public $price;
}


class JobPrice extends OActiveRecord {

    const METHOD = 'job_analysis.php';
    const MAP_NAME = 'job';

    public $job_token;
    public $windows = array();

    public function getRelatedObject($type) {
        switch ($type) {
            case 'windows':
                return new Window();
                break;
            case 'groups':
                # code...
                break;
            
            default:
                # code...
                break;
        }
    }

	public function getAttrToSave() {
		return ['job_token'];
	}

}

?>