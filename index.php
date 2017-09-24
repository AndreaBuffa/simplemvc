<?php
 
if (version_compare(PHP_VERSION, '5.4.0')<0) {
    if (get_magic_quotes_gpc()) {
        function no_slashes(&$v) {
            if (!is_array($v))
                return stripslashes($v);
            foreach ($v as $k=>$x)
                $v[$k] = no_slashes($x);
            return $v;
        }        
        no_slashes($_GET);
        no_slashes($_POST);
        no_slashes($_COOKIE);        
    }
}

define("IS_DEBUG", true);
define("METHOD", "http");
define("HOST", "localhost");
define("APP", "configuratore");
define("URL_PREFIX", METHOD.'://'.HOST.'/'.APP.'/index.php?page=');
define("HEADER_PREFIX", "Location: index.php?page=");
define("O_METHOD", "http");
define("O_HOST", "alwin.orchestraweb.net");
define("OUTDOOR_DEF_IMG", 'http://frontend6.orchestraweb.net/alwin3d2/images/PRODOTTI/GRANDI/COUNTRY-ESTERNO-AVORIO.jpg');

ob_start();

require_once(__DIR__.'/controller/controller.php');

echo ControllerBase::exec();

ob_end_flush();

?>
