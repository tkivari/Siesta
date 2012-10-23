<?php

    require_once('../restclient.php');
    
    namespace Siesta\twitter;
    
    class restClient extends Siesta\restClient {
        
        public function __construct($config) {
            parent::__construct($config);
        }
        
    }