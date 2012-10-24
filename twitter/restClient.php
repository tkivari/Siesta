<?php

    namespace Siesta\twitter;
    
    require_once('../restclient.php');
    
    class restClient extends Siesta\restClient {
        
        public function __construct($config) {
            parent::__construct($config);
        }
        
    }