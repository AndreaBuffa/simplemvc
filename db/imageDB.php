<?php

/**
 *  filesystem based database (wow!)
 */
class ImageDB extends DB {

    const DATA_SOURCE = 'PATH';

    private function __construct(){}

    public static function getInstance() {
        // self is always binded to the class where it is defined
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param  string $query not used for this particular DB
     * @return int    the record ID. -1 if error.
     **/
    public function executeQuery($type, $query) {
        $results = array();

        $reflect = new ReflectionClass($type);
        if ($reflect->hasConstant(self::DATA_SOURCE)) {
            $path = $reflect->getConstant(self::DATA_SOURCE);
            if (empty($path))
                throw new Exception('Invalid ImgActiveRecord (' + $type + ')');
        }

        if ($query) {
            $path .= '?'.$query;
        }
        //$url = O_METHOD."://".O_HOST."/api/".$path;
        //$response = file_get_contents($url);
        //http_get($url, array("timeout"=>1), $info);

        return $results;
    }
}

?>