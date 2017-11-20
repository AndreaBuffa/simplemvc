<?php
require_once(__DIR__.'/../OActiveRecord.php');

class Technology extends OActiveRecord {
    const MAP_NAME = 'technology';

    public $id;
    /**
    * LUCE L, NATHURA XL
    */
    public $code;
    public $name;
    /**
    * true or false
    */
    public $active;

    public $performance;
   // public $documents
    public function getAttrToSave() {
        return [];
    }
}

class Group extends OActiveRecord {
    const MAP_NAME = 'group';
    public $technology_group_id;
    public $technologies = array();

    public function getRelatedObject($type) {
        switch ($type) {
            case 'technologies':
                //return new Technology();
                return "Technology";
                break;
            default:
                //log
                break;
        }
    }
    public function getAttrToSave() {
        return [];
    }
}

class Window extends OActiveRecord{
    const MAP_NAME = 'window';

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

    public function getAttrToSave() {
        return [];
    }
}


class JobPrice extends OActiveRecord {

    const METHOD = 'job_analysis.php';
    const MAP_NAME = 'job';

    public $job_token;
    public $token;
    public $windows = array();
    public $groups = array();


    public function getRelatedObject($type) {
        switch ($type) {
            case 'windows':
                return "Window";
                break;
            case 'groups':
                return "Group";
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