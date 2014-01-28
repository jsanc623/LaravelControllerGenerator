<?php

namespace jsanc623\LaravelControllerGenerator{

    class Builder{
        public $name;
        public $email;
        public $version;

        public function __construct(Lexer $Lexer, Parser $Parser, array $options){
            array_map(function($option){
                switch($option){
                    case 'name'    : { $this->name = $option;    }
                        break;
                    case 'version' : { $this->version = $option; }
                        break;
                    case 'email'   : { $this->email = $option;   }
                        break;
                }
            }, $options);
        }

        private static function _generateMethod($method_name, $visibility = "public", $is_static){
            $func = (string)"\t";

            # Append the visibility to the method
            $func .= $visibility . " ";

            # If the function is static, add static keyword
            $func .= ($is_static === TRUE ? "static " : "");

            $docstring  = "\t/**";
            $docstring .= "*  method";
            $docstring .= "*";
            $docstring .= "* @return string  Service status if authorized, 403 Unauthorized otherwise";
            $docstring .= "* @since  1.0.0 First time this was introduced";
            $docstring .= "* @author <>";
            $docstring .= "*/";


            # Create the function
            $func .= $docstring;
            $func .= "function " . $method_name . "(){\n";
            $func .= "\n";
            $func .= "\t}\n\n";

            return $func;
        }

        private static function _generateClass($class_name, $class_body){
            $class = (string)"";

            $class .= "class " . $class_name . "{";
            $class .= "\n";
            $class .= $class_body;
            $class .= "\n}";

            return $class;
        }
    }

    class Lexer{
        public function __construct(){

        }

        private function _loadFile(){

        }
    }

    class Parser{
        public function __construct(){

        }

        private function _parse(){

        }
    }

}