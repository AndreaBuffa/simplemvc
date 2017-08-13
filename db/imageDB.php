<?php
require_once('db.php');
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

    private function readInventory($_path = '', &$_records) {
        $readCommand = 'cat ' . $_path . '/inventory';
        $res = 0;
        exec($readCommand, $_records, $res);
        return $res;
    }

    private function createInventory($_path='') {
        // create the file
        //$command = 'touch ' . $_path .  '/inventory';
        $handle = fopen($_path .  '/inventory', 'w');
        $err = 0;
        if (is_resource($handle)) {
            // fill it
            $command = 'tree -if ' . $_path . ' | grep .jpg';
            $fileList = array();
            exec($command, $fileList, $err);
            if (!$err) {
                foreach ($fileList as $line) {
                    # code...
                    fwrite($handle, "$line\n");
                }
            }
        } else {
            echo "SMVC: cannot touching inventory";
            $err = 1;
        }
        fclose($handle);
        return $err;
    }

    /**
     * @param  string $query not used for this particular DB
     * @return int    the record ID. -1 if error.
     **/
    public function executeQuery($type, $criteria) {
        $results = array();

        $reflect = new ReflectionClass($type);
        if ($reflect->hasConstant(self::DATA_SOURCE)) {
            $basePath = $reflect->getConstant(self::DATA_SOURCE);
            if (empty($basePath))
                throw new Exception('Invalid ImgActiveRecord (' . $type . ')');
        }
        $basePath = ltrim($basePath, '/');

//montagna/chiari/stili/classico/battente/sistemi/luce/interno/colore/avorio/maniglie/cremonese/
        //@todo fix GUI
        $criteria["category"] = 'battente';
        $criteria["style"] = 'classico';

        $builder = new PathBuilder($basePath, $criteria);
        $pathList = $builder->getPathList();
        $records = array();
        foreach ($pathList as $path) {
            if ($this->readInventory($path, $records)) {
                if ($this->createInventory($path)) {
                    if ($this->readInventory($path, $records)) {
                        echo 'SMVC: no inventory';
                    }
                } else {
                    echo "SMVC: cannot create inventory";
                    return $results;
                }
            }
        }
        //var_dump($records);
        return $records;
    }
}

/**
 * Builds every combination with the given criteria.
 * the list is then filter client side interacting with 
 * user
 */
class PathBuilder {

    const STYLE_DIR = 'stili';
    const BRIGHT_DIR = 'chiari';
    const DARK_DIR = 'scuri';
    private $pathList = array();

    function __construct($basePath='', $criteria=array()) {
        //$criteria["panorama"]/*/stili///$criteria["style"]//$criteria["category"]/

        array_push(
            $this->pathList,
            $basePath . '/' . $criteria["panorama"] . '/' . PathBuilder::BRIGHT_DIR .
            '/' . PathBuilder::STYLE_DIR . '/' . $criteria["style"] . '/' .
            $criteria["category"]
            );

        array_push(
            $this->pathList,
            $basePath . '/' . $criteria["panorama"] . '/' . PathBuilder::DARK_DIR .
            '/' . PathBuilder::STYLE_DIR . '/' . $criteria["style"] . '/' .
            $criteria["category"]
            );

        //echo $rootPath;
    }

    public function getPathList() {
        return $this->pathList;
    }

}

?>