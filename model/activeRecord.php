<?php

abstract class ActiveRecord {

    const STATE_NEW = 0;
    const STATE_LOADED = 1;
    const STATE_DELETED = 2;

    protected $_recordState;

    protected $_methodList = [];

    public function __construct() {
        $this->_recordState = self::STATE_NEW;
    }

    public static function getInstance($type, $data) {
        //if (empty($data))
            //return null;
        $record = new $type($data);
        $record->_recordState = self::STATE_LOADED;
        return $record;
    }

    abstract protected static function getDataGateway();

    abstract public static function findAll();

    abstract public function delete();

    abstract public function save();

}

?>
