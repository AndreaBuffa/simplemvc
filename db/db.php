<?php

abstract class DB {

	/**
	* Implement me as a singleton
	*/
    protected static $instance;

	/**
	 * @param  string $query
	 * @param  array  $query
	 * @return array  the result list
	 **/
	abstract public function executeQuery($type, $criteria);

}
?>
