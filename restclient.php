<?php

    require_once('request.php');
    
    namespace Siesta;
    
    interface SeistaRestClient {
        
        private $config;
        private $method;
        
        public function get($url,$data);
        public function post($url,$data);
        public function put($url,$data);
        public function delete($url,$data);
        
        public static function format_data($data,$format);
        
    }
     
   class restClient implements SiestaRestClient {
        
        private $config = null;
        private $request;
                
        public function __construct($config = array()) {
            
            $this->config = array_merge(
                array(
                    'multipart'     =>  false
                ),
                $config
            );
            
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
        
        private function rest($data,$url) {
            $data = Siesta\restUtils::data_format($data,'json');
            return $this->request->execute($url,$data);
        }
        
    }