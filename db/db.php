<?php

abstract class DB {

	/**
	 * @param  string $query
	 * @return int    the record ID. -1 if error.
	 **/
	abstract public function executeQuery($type, $query);
}
?>
