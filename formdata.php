<?php

    namespace Siesta;
    
    require_once('restutils.php');
    
    /*
     * @package Siesta
     * @author Tyler Kivari
     * @email ty.kivari@gmail.com
     * 
     * Generates multipart form data with checks to ensure that any specified files exist before allowing curl to parse data.
     */
    
    class form_data {
        
        private $data;
        private $request_params;
        
        private $delimiter= '';
        
        public function __construct($delimiter) {
            $this->delimiter = (!empty($delimiter)) ? $delimiter : '----------' . uniqid();            
        }
        
        public function build($params) {
            $this->request_params = \Siesta\Utils\util::format_data($params, 'array');
            $this->data = "";
      
            foreach ($this->request_params as $key => $param) {
                if (substr($param,0,1) == "@") {
                    @list($file,$type,$filename) = $this->get_media_attributes($param);
                    if(!empty($file) && file_exists($file)) {
                        $formData .= $this->file_field($key,$file,$filename,$type);
                    } else { // It's not a file - it's a string!
                        $formData .= $this->text_field($key,$param); // just a plain text field
                    }
                } else {
                    $formData .= $this->text_field($key,$param); // just a plain text field
                }
            }

            $formData .= "--" . $this->delimiter . "--\r\n\r\n"; // final post header delimiter

            return $formData;
        }
        
        private function text_field($key,$param) {
            $field = "--" . $this->delim . "\r\n";
            $field .= 'Content-Disposition: form-data; name="' . $key . '"';
            $field .= "\r\n\r\n";
            $field .= $param . "\r\n";

            return $field;
        }
        
        private function file_field($key,$file,$filename,$type) {
            $field = "--" . $this->delim . "\r\n";
            $field .= 'Content-Disposition: form-data; name="' . $key . '"; filename="'.$filename.'"' . "\r\n";
            $field .= 'Content-Type: ' . $type . "\r\n";
            $field .= "\r\n";
            $field .= file_get_contents($file) . "\r\n";
      
            return $field;
        }
        
        /*
         * file_get_mime_type
         * 
         * @param String $filename : The full path to the file on the server
         * @return String : the mime type of the file specified in $filename
         */
        private function file_get_mime_type($filename) {
            if(is_callable('finfo_open')) { 
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                return finfo_file($finfo, $filename);
            }
            else {
                return mime_content_type($filename);
            }
        }
        
        
        /*
         * @param String $param
         * @return Array: [$file, $type, $filename]
         */
        private function get_media_attributes($param) {
            // if there are already semicolons in the string, the user has specified all of the required fields in the request.  
            // No need to continue...
            if (strpos($param,";")) {
                // strip the "@", we're not gonna need it where we're going
                if (substr($param,0,1) == "@") $param = substr($param,1,strlen($param));
                return explode(";",$param);
            }

            $file = substr($param,1,strlen($param));

            // if the file doesn't exist, we're just treating it as if it were a twitter username
            if (!file_exists($file)) 
                 return array(null,null,null);

            // we're going to have to get the mime type manually
            // we'll also have to set $filename to be the same as the last part of the $file string

            $filetype = $this->file_get_mime_type($filename);

            $filename = substr($file,strrpos($file,DIRECTORY_SEPARATOR),strlen($file));

            return array($file,$filetype,$filename);
        }
        
        public function get_data() {
            return $this->data;
        }
        
    }