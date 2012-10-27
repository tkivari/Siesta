<?php

    namespace Siesta\twitter;
    
    require_once('../restclient.php');
    
    class restclient extends Siesta\restclient {
        
        public function __construct($config) {
            parent::__construct($config);
        }
        
    }