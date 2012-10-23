<?php
    
    namespace Siesta;
    
    class restUtils {
        
        public function __construct() {
            
        }
        
        public static function format_data($data,$format='json') {
            if (!in_array($format,array('json','object'))) return false;
            if ($format == 'object') { 
                if (is_object($data)) {
                    return $data; 
                }
                return json_decode($data);
            }
            if ($format == 'json') { 
                
                if (!is_array($data) && !is_object($data)) {
                    if (json_decode($data) != null) { return $data; } // the data is already in json format
                    else {
                       // $data is not an array, object or valid json string.  Convert to json:
                       $data = "{ \"data\" : \"" . $data . "\" }";
                    }
                }
                return $data;
            }
        }
        
    }