<?php

    require_once('request.php');
    require_once('restutils.php');
    
    namespace Siesta;
    
    interface SiestaRestClient {
        
        protected $config;
        protected $headers;
        protected $request;
        
        public function get($url, $data);
        public function post($url, $data);
        public function put($url, $data);
        public function delete($url, $data);
        
        private function rest($url, $data);
        
    }
     
   class restClient implements SiestaRestClient {
        
        protected $config = null;
        protected $headers;
        protected $request;
                
        public function __construct($config = array()) {
            
            $this->config = array_merge(
                array(
                    'multipart'     =>  false
                ),
                $config
            );
            
            $this->set_request();
        }
        
        private function set_request() {
            $this->request = new Siesta\request($config);
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
            $data = Siesta\Utils\util::data_format($data,'json');
            return $this->request->execute($url,$data);
        }
        
    }