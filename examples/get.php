<?php

    require_once('../restclient.php');
    
    $config = array('color' => 'orange');
    
    $rest = new \Siesta\restclient($config);
    $url = 'http://www.cnn.com';
    
    $response = $rest->get($url);
    
    echo "<pre>";
    print_r($response);
    echo "</pre>";
    