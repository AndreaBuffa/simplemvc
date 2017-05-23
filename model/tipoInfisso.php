<?php

require_once('OActiveRecord.php');

class Category extends OActiveRecord {

    const METHOD = 'main_categories_list.php';

    public $name;
    public $img_URL;

}

?>