<?php

require_once(__DIR__.'/../ImgActiveRecord.php');

/**
 * Model a rendering image 
 */
class Rendering extends ImgActiveRecord {

    const PATH = 'static/images';

    /**
     * the path including the filename
     * @see $prefixPath
     */
    public $oath;
    /**
     * path include Rendering::PATH
     * It does not ever include the filename
     * To have the complete relative path,
     * class client must concatenate this 
     * attribute and Rendering::path
     */
    public $prefixPath;

}

?>
