<?php

    require_once('../restclient.php');
    
    $rest = new Siesta\restClient();
    $url = 'http://www.cnn.com';
    
    $response = $rest->get($url);
    