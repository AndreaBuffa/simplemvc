<?php
require_once('activeRecord.php');
require_once(__DIR__.'/../db/customDB.php');

class OActiveRecord extends ActiveRecord {
    
    protected static function getDataGateway() {
        return CustomDB::getInstance();   
    }

    public function findAll() {
        OActiveRecord::getDataGateway()->executeQuery($this, "");
    }
}

?>
