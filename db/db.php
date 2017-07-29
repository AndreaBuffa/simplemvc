<?php

abstract class DB {

	/**
	* Implement me as a singleton
	*/
    protected static $instance;

	/**
	 * @param  string $query
	 * @return int    the record ID. -1 if error.
	 **/
	abstract public function executeQuery($type, $query);

}
?>
