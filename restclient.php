<?php

    namespace Siesta;
    
    require_once('request.php');
    require_once('restutils.php');
    
    interface SiestaRestClient {
        
        public function get($url, $data);
        public function post($url, $data);
        public function put($url, $data);
        public function delete($url, $data);
        
        const METHOD_GET = 'GET';
        const METHOD_POST = 'POST';
        const METHOD_PUT = 'PUT';
        const METHOD_DELETE = 'DELETE';
        
    }
     
   class restclient implements SiestaRestClient {
        
        protected $config = null;
        protected $request;
                
        public function __construct($config = array()) {
            
            $this->config = array_merge(
                array(
                    'multipart'     =>  false,
                    'curl_opts'     =>  array(),
                    'oauth_opts'    =>  array(),
                    'headers'       =>  array()
                ),
                $config
            );
            
            $this->setup_request();
        }
        
        public function get($url = null,$data = null) {
            $this->request->method = self::METHOD_GET;
            return $this->rest($url,$data);
        }
        
        public function post($url = null,$data = null) {
            $this->request->method = self::METHOD_POST;
            return $this->rest($url,$data);
        }
        
        public function put($url = null,$data = null) {
            $this->request->method = self::METHOD_PUT;
            return $this->rest($url,$data);
        }
        
        public function delete($url = null,$data = null) {
            $this->request->method = self::METHOD_DELETE;
            return $this->rest($url,$data);
        }
        
        private function rest($url,$data) {
            $data = \Siesta\Utils\util::format_data($data,'json');
            return $this->request->execute($url,$data);
        }
        
        private function setup_request() {
            $this->request = new \Siesta\request($this->config);
        }
        
    }