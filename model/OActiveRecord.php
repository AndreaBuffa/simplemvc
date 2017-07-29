<?php
require_once('activeRecord.php');
require_once(__DIR__.'/../db/customDB.php');

/**
 * Implements an ActiveRecord that is using
 * a custom DB (O.) data getaway 
 */
class OActiveRecord extends ActiveRecord {
    
    protected static function getDataGateway() {
        return CustomDB::getInstance();   
    }

    public static function findAll() {
        return OActiveRecord::getDataGateway()->executeQuery(get_called_class(), "");
    }

    public function save() {}

    public function delete() {}
}

?>
