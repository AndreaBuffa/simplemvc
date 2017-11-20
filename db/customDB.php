<?php
require_once('db.php');
/**
 *  third party HTTP based API database 
 *  returning XML data.
 */
class CustomDB extends DB {

    const SCRIPT_NAME = 'METHOD';

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
    public function executeQuery($type, $instance) {
        $results = array();
        $api = "";
        $queryString = "";

        $reflect = new ReflectionClass($type);
        if ($reflect->hasConstant(self::SCRIPT_NAME)) {
            $api = $reflect->getConstant(self::SCRIPT_NAME);
            if (empty($api))
                throw new Exception('SMWC Invalid OActiveRecord (' . $type  . ')');
        }

        if ($instance) {
            // this is a create/update call
            $queryData = array();
            foreach ($instance->getAttrToSave() as $attr) {
                $queryData[$attr] = $reflect->getProperty($attr)->getValue($instance);
            }
            $queryString = '?'.http_build_query($queryData);
        } else {
            $instance = $type::getInstance($type, null);
        }

        //$api = $instance::METHOD; //$instance->getMethod();
        $url = O_METHOD."://".O_HOST."/api/".$api.$queryString;

        try {
            $response = file_get_contents($url);
        } catch (Exception $e) {
            echo 'SMVC error while querying O database';
        }
        //http_get($url, array("timeout"=>1), $info);
        if (!$response) {
            echo 'SMVC response from ODatabase is empty';
            return $results;
        }

        $root = new SimpleXMLElement($response);
        if (!is_object($root)) {
            echo 'SMVC error parsing XML';
            return $results;
        }
        return $this->xmlToObj($root, $type, $instance);
    }

    protected function xmlToObj(Traversable $root, $type, &$instance) {
        $results = array();
        $reflect = new ReflectionClass($type);
        $queue = array();
        if ($root instanceof Traversable) {
            array_push($queue, $root);
        }

        foreach ($queue as $key => $node) {
            //if (strtolower($type) == $node->getName()) {
            if (strtolower($type::MAP_NAME) === $node->getName()) {
                // now each children elem is an ActiveRecord Object
                $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
                foreach ($props as $prop) {
                    foreach ($node->children() as $name => $valueNode) {
                        if ($prop->getName() == $name) {   
                            if (is_array($instance->$name)) {
                                // use recursion with the new child type
                                $childType = $instance->getRelatedObject($name);

                                $instance->$name = $this->xmlToObj($valueNode->children(),
                                    $childType, $childType::getInstance($childType, null));
                            } else {
                                $instance->$name = $valueNode->__toString();
                            }   
                        }
                    }
                }
                array_push($results, $instance);
            } else {
                array_push($queue, $node->children());
            }    
        }
        return $results;
    }
}

?>
