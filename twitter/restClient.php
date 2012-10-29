<?php

    namespace Siesta\twitter;
    
    require_once('../restclient.php');
    
    class restclient extends Siesta\restclient {
        
        const DATA_FORMAT_JSON = 'json';
        
        private $api = 'api.twitter.com';
        private $protocol;
        private $host;
        private $data_format = self::DATA_FORMAT_JSON;
        
        public function __construct($config) {
            parent::__construct($config);
            
            $this->protocol = ($this->config['curl_opts']['CURLOPT_SSL_VERIFYHOST'] === true) ? 'https://' : 'http://';
            $this->host = $this->protocol.$this->api;
            
        }
        
        public function update($tweet) {
            $url = $this->host."/statuses/update.".$this->data_format;
            $this->post($url,$tweet);
        }
        
        public function update_with_media($tweet_data) {
            $url = $this->host."/statuses/update_with_media.".$this->data_format;
            $this->post($url,$tweet_data);
        }
        
    }