<?php

    class DefaultController extends Controller {

        public function __construct($name, $request) {
            parent::__construct($name, $request);
        }

        public function processRequest() {
        return Response::errorResponse('{ "message" : "Default controller, Unsupported endpoint","endpointName":"'.$this->name.'","requestMethod":"'.$this->request->getHttpMethod().'"}' );
        }

    }