<?php
    
    namespace Siesta\Utils;
    
    class util {
        
        public static function array_to_object($array) {
            $obj = new stdClass;
            foreach($array as $k => $v) {
               if(is_array($v)) {
                  $obj->{$k} = self::array_to_object($v);
               } else {
                  $obj->{$k} = $v;
               }
            }
            return $obj;
        }
        
        public static function object_to_array($object)
        {
            $arr = array();
            foreach ($object as $k => $v) {
                if (is_object($v)) {
                    $arr[$k] = self::object_to_array($v);
                }
                else {
                    $arr[$k] = $v;
                }
            }
            return $arr;
        }
        
        public static function parse_query_string($str) {

            $op = array(); 
            $pairs = explode("&", $str);
            if (count($pairs) <= 1) {
                return false;
            }
            foreach ($pairs as $pair) { 
                list($k, $v) = array_map("urldecode", explode("=", $pair)); 
                $op[$k] = $v; 
            }
            return $op; 

        }
        
        public static function format_data($data,$output_format='json') {
            
            if (empty($data)) { return null; }
            
            if (!in_array($output_format,array('json','object','array'))) return false;
            
            if ($output_format == 'object') { 
                
                if (is_object($data)) { return $data; }
                
                if (is_array($data)) { return self::array_to_object($data); }
                
                $d = json_decode($data);
                if ($d != null) { return $d; }
                else {
                    // Maybe the data is in a query string type format
                    $q = self::parse_query_string($data);
                    if ($q) { return self::array_to_object($q); }
                    else {
                        return null;
                    }
                }
            }
            
            if ($output_format == 'json') { 
                
                if (!is_array($data) && !is_object($data)) {
                    if (json_decode($data) != null) { return $data; } // the data is already in json format
                    else {
                       // Maybe the data is in a query string type format
                       $d = self::parse_query_string($data);
                       if ($d) { return json_encode($d); }
                       else {
                           // $data is not an array, object, query string or valid json string.  Convert to json:
                           $d = "{ \"data\" : \"" . $data . "\" }";
                           return $d;
                       }
                    }
                }
                else { return json_encode($data); }
                
                return $data;
            }
            
            if ($output_format == 'array') {
                if (is_array($data)) { return $data; }
                
                if (is_object($data)) { return self::object_to_array($data); }
                
                $d = json_decode($data);
                if ($d != null) { return self::object_to_array($d); }
                else {
                    // Maybe the data is in a query string type format
                    $q = self::parse_query_string($data);
                    return $q ?: null;
                }
            }
            
            return $data; // could not format data; return unchanged
        }
        
        /**
         * Encodes the string or array passed in a way compatible with OAuth.
         * If an array is passed each array value will will be encoded.
         * 
         * Adapted from safe_encode function from tmhOauth by @themattharris - http://www.github.com/themattharris/tmhOauth
         *
         * @param mixed $data the scalar or array to encode
         * @return $data encoded in a way compatible with OAuth
         */
        public static function oauth_encode($data) {
            if (!is_array($data) && !is_scalar($data)) {
                $data = self::format_data($data,'array');
            }
            if (is_array($data)) {
                return array_map(array($this, 'safe_encode'), $data);
            } else if (is_scalar($data)) {
                return str_ireplace(
                    array('+', '%7E'),
                    array(' ', '~'),
                    rawurlencode($data)
                );
            } else {
                return '';
            }
        }
        
    }