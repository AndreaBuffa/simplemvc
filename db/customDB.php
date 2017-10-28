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
        $queue = array();
        if ($root instanceof Traversable) {
            array_push($queue, $root);
        }

        foreach ($queue as $key => $node) {
            # ActiveRecord class name and tag name must be the same
            //if (strtolower($type) == $node->getName()) {
            if (strtolower($type::MAP_NAME) == $node->getName()) {
                // now each children elem is an ActiveRecord Object

                $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
                foreach ($props as $prop) {
                    foreach ($node->children() as $name => $valueNode) {
                        if ($prop->getName() == $name) {
                            if (is_array($instance->$name)) {
                                $relObj = $instance->getRelatedObject($name);
                                $reflectRelObj = new ReflectionClass($relObj);
                                foreach ($reflectRelObj->getProperties(ReflectionProperty::IS_PUBLIC) as $relObjProp) {
                                    foreach ($valueNode->children() as $child) {
                                        foreach ($child as $subName => $subValue) {
                                            if ($relObjProp->getName() == $subName) {

                                                $relObj->$subName = $subValue->__toString();
                                            }
                                        }
                                    }
                                }
                                array_push($instance->$name, $relObj);
                            } else {
                                $instance->$name = $valueNode->__toString();
                            }
                        }
                    }
                }
                array_push($results, $instance);
            }
            //enquue $node->children
        }

        return $results;
    }
}

?>
