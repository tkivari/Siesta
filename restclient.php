<?php

    namespace Siesta;
    
    require_once('request.php');
    require_once('restutils.php');
    
    interface SiestaRestClient {
        
        public function get($url, $data);
        public function post($url, $data);
        public function put($url, $data);
        public function delete($url, $data);
        
    }
     
   class restClient implements SiestaRestClient {
        
        protected $config = null;
        protected $headers;
        protected $request;
                
        public function __construct($config = array()) {
            
            $this->config = array_merge(
                array(
                    'multipart'     =>  false,
                    'use_oauth'     =>  false
                ),
                $config
            );
            
            $this->setup_request();
        }
        
        private function setup_request() {
            $this->request = new \Siesta\request($config);
        }
        
        public function get($url = null,$data = null) {
            $this->request->method = 'GET';
            return $this->rest($data,$url);
        }
        
        public function post($url = null,$data = null) {
            $this->request->method = 'POST';
            return $this->rest($data,$url);
        }
        
        public function put($url = null,$data = null) {
            $this->request->method = 'PUT';
            return $this->rest($data,$url);
        }
        
        public function delete($url = null,$data = null) {
            $this->request->method = 'DELETE';
            return $this->rest($data,$url);
        }
        
        private function rest($url,$data) {
            $data = \Siesta\Utils\util::format_data($data,'json');
            return $this->request->execute($url,$data);
        }
        
    }