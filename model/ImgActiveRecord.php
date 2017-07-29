<?php
require_once('activeRecord.php');
require_once(__DIR__.'/../db/imageDB.php');

/**
 * Implements an ActiveRecord that is using
 * a custom DB (imageDB) data getaway 
 */
class ImgActiveRecord extends ActiveRecord {
    
    protected static function getDataGateway() {
        return imageDB::getInstance();   
    }

    public static function findAll() {
        return ImgActiveRecord::getDataGateway()->executeQuery(get_called_class(), "");
    }

    public function save() {
    	//not implemented
    }

    public function delete() {}
}

?>