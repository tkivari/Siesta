<?php
    
    namespace Siesta;
    
    require_once('restutils.php');
    
    class OAuth {
        
        public $timestamp;
        public $nonce;
        
        const OAUTH_VERSION = '1.0';
        const OAUTH_SIGNATURE_METHOD_HMAC_SHA1 = 'HMAC-SHA1';
        
        protected $consumer_token;
        protected $consumer_secret;
        protected $user_token;
        protected $user_secret;
        protected $signature_method = self::OAUTH_SIGNATURE_METHOD_HMAC_SHA1;
        
        protected $signing_key;
        protected $signing_params;
                
        public function __construct($config) {
            
            // encode each oath parameter as specified in 
            foreach($this->config['oauth'] as $key => $value) {
                if (property_exists($this, $key))
                    $this->{$key} = \Siesta\Utils\util::oauth_encode($value);
            }
            
            $this->prepare_request();
        }
        
        public function prepare_request() {
            $this->set_timestamp();
            $this->set_nonce();
            $this->set_signing_key();
        }
        
        public function set_signature_base_string($data) {
            
        }
        
        private function set_timestamp() {
            $this->timestamp = time();
        }
        
        private function set_nonce($length = 20) {
            $nonce = md5(uniqid(microtime().'_siesta_'.rand(1,100)));
            if (strlen($nonce) > $length) $nonce = substr($nonce,0,$length);
            $this->nonce = $nonce;
        }
        
        private function set_signing_key() {
            $this->signing_key = $this->consumer_secret . '&' . $this->user_secret;
        }
        
    }