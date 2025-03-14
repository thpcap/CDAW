<?php

    class DefaultController extends Controller {

        public function __construct($name, $request) {
            parent::__construct($name, $request);
        }

        public function processRequest() {
        return Response::errorResponse('{ "message" : "Default controller, Unsupported endpoint","name":"'.$this->name.'","request":"'.$this->request->getHttpMethod().'"}' );
        }

    }