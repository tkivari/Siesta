<?php

    include('restclient.php');
    
    $config = array(
        'multipart'     => true,
        'oauth_opts'    => array(
            '' => ''
        )
    );
    
    $data = array(
        'status' => 'plz ignore testing tweets'
    );
    
    $media = array(
        'status'    => 'plz ignore testing tweets',
        'media[]'   => '@blackeye.jpg'
    );
    
    $twitter = new \Siesta\twitter\restclient($config);
    
    $twitter->update($data);
    
    $twitter->update_with_media($data);