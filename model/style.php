<?php

require_once('OActiveRecord.php');

class Style extends OActiveRecord {

    const METHOD = 'styles_list.php';

    public $name;
    public $description;
    public $note;
    public $icon_URL;
    public $img_URL;

    public function getImgURL() {
		return O_METHOD."://".O_HOST."/".$this->img_URL;
    }
}

?>
