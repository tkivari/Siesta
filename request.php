<?php

    namespace Siesta;
    
    require_once('formdata.php');
    require_once('oauth.php');
    require_once('restutils.php');
    
    class request {
        
        public $method = 'GET';
        public $response = null;
        
        protected $oauth = null;
        protected $config = null;
        protected $headers = array();
        protected $user_agent;
        
        private $delimiter;
        private $form_data;
        
        const HEADER_CONTENT_TYPE = 'Content-Type';
        const HEADER_CONTENT_LENGTH = 'Content-Length';
        
        const CONTENT_TYPE_JSON = 'application/json';
        const CONTENT_TYPE_MULTIPART = 'multipart/form-data';
        
        public function __construct($config=array()) {
            
            $this->delimiter = '-----' . uniqid();
            $this->config = $config;
            $user_agent = 'Siesta - Little REST Client ' . $ssl . ' - //github.com/tkivari/Siesta';
            $this->user_agent = $this->set_user_agent((array_key_exists('user_agent',$this->config)) ? $this->config['user_agent'] : $user_agent);
            
            // If the user has specified any headers to use, then use them.
            if (count($this->config['headers'])) {
                foreach ($this->config['headers'] as $header => $val) {
                    $this->set_header($header,$val);
                }
            }
            
            if (count($this->config['oauth_opts'])) {
                $this->oauth = new \Siesta\oauth($this->config);
                $this->headers = array_merge($this->headers,$this->oauth->authorization_header());
            }
        }
        
        public function execute($url,$data) {
            
            if ($this->oauth !== null) {
                    $this->oauth->set_signature_base_string($data);
            }
            
            if (json_decode($data) != null) {
                $this->set_header(self::HEADER_CONTENT_TYPE, self::CONTENT_TYPE_JSON);
            }
            
            if ($this->config['multipart'] == true) {
                $this->set_header(self::HEADER_CONTENT_TYPE, self::CONTENT_TYPE_MULTIPART . '; boundary=' . $this->delimiter);
                $this->form_data = new \Siesta\Utils\form_data($this->delimiter);
                $this->form_data->build(\Siesta\Utils\util::format_data($data,'array'));
                $this->set_header(self::HEADER_CONTENT_LENGTH, strlen($this->form_data));
                curl_setopt($c, CURLOPT_POSTFIELDS, $this->form_data);
            }
            
            $c = curl_init($url);
            
            foreach($this->curl_opts() as $name => $value) {
                curl_setopt($c, constant($name), $value);
            }
            
            $this->response['data'] = curl_exec($c);
            $this->response['curl_info'] = curl_getinfo($c);
            $this->response['error_no'] = curl_errno($c);
            $this->response['error'] = curl_error($c);
            curl_close($c);

            // If the call was made and there were no errors
            if ($this->response['error_no'] == 0) {
                if ($this->response['curl_info']['http_code'] == 200) {
                    return $this->response['data'];
                }
                else {
                    throw new \Exception($url . " returned HTTP response: " . $this->response['curl_info']['http_code'], $this->response['error_no']);
                }
            }
            else {
                // If we get here, there was an error:
                throw new \Exception("Scraping " . $url . " failed: " . $this->response['error'], $this->response['error_no']);
            }
            
        }
        
        private function curl_opts() {
            return array_merge(array(
                // Don't use response header
                'CURLOPT_HEADER'            => false,
                // Return results as string
                'CURLOPT_RETURNTRANSFER'    => true,

                // Connection timeout, in seconds
                'CURLOPT_CONNECTTIMEOUT'    => 10,
                // Total timeout, in seconds
                'CURLOPT_TIMEOUT'           => 45,

                // Set a dummy useragent
                'CURLOPT_USERAGENT'         => $this->user_agent,

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
                'CURLOPT_AUTOREFERER'       => true,
                
                // Configure HTTP headers
                'CURLOPT_HTTPHEADER'        => $this->headers
            ), $this->config['curl_opts']);
        }
        
        private function set_header($header,$val) {
            $this->headers[$header] = $val;
        }
        
        private function set_user_agent($user_agent) {
            $this->user_agent = $user_agent;
        }
        
    }