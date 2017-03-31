<?php

/**
 *  third party HTTP based API database 
 */
class CustomDB extends DB {

    const SCRIPT_NAME = 'METHOD';

	protected static $instance;

	private function __constructor() {}

	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}
    
    protected function getScriptName(OActiveRecord $record)
    {
        $class = new ReflectionClass($record);
        if ($class->hasConstant(self::SCRIPT_NAME)) {
            $value = $class->getConstant(self::SCRIPT_NAME);
            if (!empty($value))
                return $value;
        }
        throw new Exception('Invalid OActiveRecord');
    }

    /**
     * @param  string $query
     * @return int    the record ID. -1 if error.
     **/
    public function executeQuery($record, $query) {
        $path = $this->getScriptName($record);
        if ($query) {
            $path  +=  '?' + $query;
        }
        //execute
    }
}

?>
