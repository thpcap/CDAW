<?php

    /*
    * A Controller is dedicated to process a request
    * its responsabilities are:
    * - analyses the action to be done
    * - analyses the parameters
    * - act on the model objects to perform the action
    * - process the data
    * - call the view and passes it the data
    * - return the response
    */

    abstract class Controller {

        protected $name;
        protected $request;

        public function __construct($name, $request) {
            $this->request = $request;
            $this->name = $name;
        }

        public abstract function processRequest();

        public function execute() {
            $response = $this->processRequest();
            if(empty($response)) {
                // $response = Response::serverErrorResponse("error processing request in ". self::class); // Oh my PHP!
                $response = Response::serverErrorResponse("error processing request in ". static::class);
            }
            return $response;
        }


    }