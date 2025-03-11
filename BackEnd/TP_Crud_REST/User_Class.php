<?php
    /**
     * Summary of User
     * User class
     * 
     * @method __construct
     * @method __get
     * @method __set
     * @method db
     * @method query
     * @method initPDO
     */
    class User{
        private int $id;
        private $data = [];
        private static $pdo;

        /**
         * Summary of __construct
         * Constructor of User
         * fetch data from database
         * @param [type] $id
         * @return void
         * @access public
         * @example $user = new User(1);
         */
        public function __construct($id){
            $this->id = $id;
            $st = self::query("SELECT * FROM users WHERE id = $id");
            $this->data = $st->fetch();
        }

        /**
         * Summary of initPDO
         * Initialize PDO for all User
         * @access public
         * @example User::initPDO($pdo);
         * @param PDO $pdo
         * @return void
         */
        public static function initPDO(PDO $pdo){
            self::$pdo = $pdo;
        }
        /**
         * Summary of __get
         * Get data from User
         * @param  $name
         * @return mixed
         * @access public
         */
        public function __get($name){
            if (array_key_exists($name, $this->data)) {
                return $this->data[$name];
            }
        }
        /**
         * Summary of __set
         * Set data to User
         * @param  $name
         * @param  $value
         * @return void
         * @access public
         */
        public function __set($name, $value){
            $this->data[$name] = $value;
        }
        /**
         * Summary of db
         * Get PDO
         * @return PDO
         * @access protected
         */
        protected static function db(){
            return self::$pdo;
        }
        /**
         * Summary of query
         * Execute a query
         * @param string $sql SQL query
         * @return PDOStatement
         * @access public
         */
        public static function query(string $sql){
            $st = static::db()->query($sql) or die("sql query error ! request : " . $sql);
            $st->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, get_called_class());
            return $st;
        }
    }