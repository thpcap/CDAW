<?php
        class AutoLoader {

        public function __construct() {
            spl_autoload_register( array($this, 'load') );
            // spl_autoload_register(array($this, 'loadComplete'));
        }

        // This method will be automatically executed by PHP whenever it encounters an unknown class name in the source code
        private function load($className) {
            if (!defined('DIRECTORY')) {
                define("DIRECTORY", "c:\\UwAmp\\www\\CDAW\\TPMVC\\");
            }
            $folders=[
                "classes"=>"/classes/",
                "models"=>"/model/",
                "controllers"=>"/controller/"
            ];
            foreach($folders as $key => $value){
                $link=DIRECTORY.$value.$className.".class.php";
                if(is_readable($link)){
                    require_once($link);
                }
            }
            $link=DIRECTORY."/sql/".$className.".sql.php";
            if(is_readable($link)){
                require_once($link);
            }
            // TODO : compute path of the file to load (cf. PHP function is_readable)
            // it is in one of these subdirectory '/classes/', '/model/', '/controller/'
            // if it is a model, load its sql queries file too in sql/ directory

        }
    }

    $__LOADER = new AutoLoader();