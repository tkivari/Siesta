<?php

    namespace Siesta;
    
    class request {
        
        public $method = 'GET';
        private $oauth = null;
        private $config = null;
        private $headers = null;
        private $delimiter;
        
        public function __construct($config) {
            
            $this->delimiter = '-----' . uniqid();
            $this->headers = array();
            $this->config = $config;
            
            if ($this->config['use_oauth'] == true) {
                $this->oauth = new \Siesta\oauth($this->config());
                $this->headers = array_merge($this->headers,$this->oauth->authorization_header());
            }
            if ($this->config['multipart'] == true) {
                $this->headers['Content-Type'] = 'multipart/form-data; boundary=' . $this->delim;
                $this->headers['Content-Length'] = strlen($formData);
            }
        }
        
        public function execute($url,$data) {
            $h = curl_init($url);
            
            foreach($this->curl_opts() as $name => $value) {
                curl_setopt($ch, constant($name), $value);
            }
            
        }
        
        private function curl_opts() {
            return array(
                // Don't use response header
                'CURLOPT_HEADER'            => false,
                // Return results as string
                'CURLOPT_RETURNTRANSFER'    => true,

                // Connection timeout, in seconds
                'CURLOPT_CONNECTTIMEOUT'    => 10,
                // Total timeout, in seconds
                'CURLOPT_TIMEOUT'           => 45,

                // Set a dummy useragent
                'CURLOPT_USERAGENT'         => $user_agent,

                // Follow Location: headers (HTTP 30x redirects)
                'CURLOPT_FOLLOWLOCATION'    => true,
                // Set a max redirect limit
                'CURLOPT_MAXREDIRS'         => 5,

                // Force connection close
                'CURLOPT_FORBID_REUSE'      => true,
                // Always use a new connection
                'CURLOPT_FRESH_CONNECT'     => true,

                // Turn off server and peer SSL verification.
                // Probably not the best solution to the SSL Errors.
                // @TODO:  Fix this.
                'CURLOPT_SSL_VERIFYPEER'    => false,
                'CURLOPT_SSL_VERIFYHOST'    => false,

                // Allow all encodings
                'CURLOPT_ENCODING'          => '*/*',
                'CURLOPT_AUTOREFERER'       => true
            );
        }
        
    }