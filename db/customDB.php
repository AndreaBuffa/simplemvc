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

        $reflect = new ReflectionClass($type);
        if ($reflect->hasConstant(self::SCRIPT_NAME)) {
            $path = $reflect->getConstant(self::SCRIPT_NAME);
            if (empty($path))
                throw new Exception('Invalid OActiveRecord (' + $type + ')');
        }

        if ($instance) {
            $queryData = array();
            foreach ($instance->getAttrToSave() as $attr) {
                $queryData[$attr] = $reflect->getProperty($attr)->getValue($instance);
            }
            $path .= '?'.http_build_query($queryData);

        } else {
            $instance = $type::getInstance($type, null);
        }

        $url = O_METHOD."://".O_HOST."/api/".$path;
        try {
            $response = file_get_contents($url);
        } catch (Exception $e) {
            echo 'SMVC error while querying O database';
        }
        //http_get($url, array("timeout"=>1), $info);
/*
        $response = <<<XML
<styles>
<style>
<name>URBANO</name>
<description>URBANO</description>
<note>La città in tutto il suo splendore</note>
<icon_URL>api/style_icon.php?style=URBANO</icon_URL>
<img_URL>api/style_icon.php?style=URBANO&amp;background&amp;image_type=JPG&amp;image_quality=50
</img_URL>
</style>
</styles>
XML;
*/
        if (!$response) {
            echo 'SMVC response from ODatabase is empty';
            return $results;
        }
        $root = new SimpleXMLElement($response);
        if (!is_object($root)) {
            return $results;
        }
        $queue = array();
        if ($root->children() instanceof Traversable) {
            $queue = $root->children();
        }

        foreach ($queue as $key => $node) {
            # ActiveRecord class name and tag name must be the same
            if (strtolower($type) == $node->getName()) {
                // now each children elem is an ActiveRecord Object

                $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
                foreach ($props as $prop) {
                    //print $prop->getName() . "\n";
                    foreach ($node->children() as $name => $valueNode) {
                        if ($prop->getName() == $name) {
                            $instance->$name = $valueNode->__toString();
                        }
                    }
                }
                array_push($results, $instance);
            }
        }

        return $results;
    }
}

?>
