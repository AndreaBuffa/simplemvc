<?php

/*
 *  da php 5.4.0 magic_quotes è stato tolto, prima era solo deprecato.
 *  facciamo in modo di avere un solo comportamento generale, quindi per
 *  ragioni di compatibilità futura togliamo ovunque gli slash ...
 *  in questo modo tutto quanto è ricevuto via POST, GET o COOKIE sarà
 *  senza addslashes. 
 *  Esempio: se via POST si riceve un valore A'B"C esso sarà disponibile
 *  nel sistema senza modifiche, non sarà trasfromato in automatico
 *  in A\'B\"C . Questo permette di non avere effetti collaterali o 
 *  "doppie" modifiche dei valori tra ricezione parametri e "pulizia" 
 *  dati verso il database.
 */
 
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

/*
 * da questo punto in poi tutto quanto ricevuto in _GET e _POST non 
 * contiene più eventuali \ davanti a \ ' " .
 */

define('IS_DEBUG',true);

ob_start();

require_once(__DIR__.'/db/db.php');
require_once(__DIR__.'/models/base.php');
require_once(__DIR__.'/controllers/base.php');
require_once(__DIR__.'/views/base.php');

echo ControllerBase::exec();

ob_end_flush();

?>