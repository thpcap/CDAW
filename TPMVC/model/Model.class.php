<?php

class Model {

   protected $props;

	public function __construct($props = array()) {
		$this->props = $props;
	}

	public function __get($prop) {
		return $this->props[$prop];
	}

	public function __set($prop, $val) {
		$this->props[$prop] = $val;
	}

	protected static function db(){
		return DatabasePDO::singleton();
	}

	// *** Queries in sql/model.sql.php ****
	protected static $requests = array();

	public static function addSqlQuery($key, $sql){
		static::$requests[$key] = $sql;
	}

	public static function sqlQueryNamed($key){
		return static::$requests[$key];
	}

	protected static function query($sql){
		$st = static::db()->query($sql)  or die("sql query error ! request : " . $sql);
		$st->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, get_called_class());
		return $st;
	}

	protected static function exec($sqlKey,$values=array()){
		$sth = static::db()->prepare(static::sqlQueryNamed($sqlKey));
		$sth->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, get_called_class());
		$sth->execute($values);
		return $sth;
    }
}