<?php

namespace jsanc623\LaravelControllerGenerator{

    function p($v){
        print_r($v);
    }

    class Builder{
        protected $name;
        protected $email;
        protected $version;
        protected $filename;

        public function __construct(array $options){
            $status = 0;

            if(empty($options) || empty($options['filename'])){
                throw new \Exception("Options not specified.");
            }

            array_walk($options, function($value, $key){
                switch($key){
                    case 'name'     : { $this->name     = $value; } break;
                    case 'version'  : { $this->version  = $value; } break;
                    case 'email'    : { $this->email    = $value; } break;
                    case 'filename' : { $this->filename = $value; } break;
                }
            });

            try{
                $Lexer = new Lexer($this->filename);
                $tokens = $Lexer->run();

                $Parser = new Parser($tokens);
                $parsed = $Parser->parse();

                $Generator = new Generator($parsed);
                $generated = $Generator->run();

                $status = new Writer($generated);
            } catch(\Exception $e){
                new Error($e->getMessage());
            }

            return $status;
        }
    }

    class Error{
        public function __construct($error_message){
            print("LaravelControllerGenerator died with exception: {$error_message}");
            die(NULL);
        }
    }

    class Writer{

    }

    class Generator{
        public function __construct(){

        }

        public function run(){
            $generated = "";

            return $generated;
        }

        public static function _generateMethod($method_name, $visibility = "public", $is_static){
            $func = (string)"\t";

            # Append the visibility to the method
            $func .= $visibility . " ";

            # If the function is static, add static keyword
            $func .= ($is_static === TRUE ? "static " : "");

            $docstring  = "\t/**";
            $docstring .= "\t*  method";
            $docstring .= "\t*";
            $docstring .= "\t* @version";
            $docstring .= "\t* @author <>";
            $docstring .= "\t*/";


            # Create the function
            $func .= $docstring;
            $func .= "function " . $method_name . "(){\n";
            $func .= "\n";
            $func .= "\t}\n\n";

            return $func;
        }

        public static function _generateClass($class_name, $class_body){
            $class = (string)"";

            $class .= "class " . $class_name . "{";
            $class .= "\n";
            $class .= $class_body;
            $class .= "\n}";

            return $class;
        }
    }

    final class Parser{
        protected $lexed_output;
        protected $parsed_output = array();

        public function __construct($lexed_output){
            $this->lexed_output = $lexed_output;
        }

        public function parse(){
            $counter = 0;
            $state = "new";
            $current_class = "";
            for($i = 0; $i <= count($this->lexed_output); $i++){
                $value = $this->lexed_output[$i];
                switch($value['token']){
                    case "T_IDENTIFIER" : {
                        switch($state){
                            case "new" : {
                                $current_class = $value['match'];
                                $this->parsed_output['controllers'][$current_class]['route-endpoint'] = $value['match'];
                            } break;
                            case "colon" : {
                                $this->parsed_output['controllers'][$current_class]["doublecolon"] = $value['match'];
                            } break;
                            case "comma" : {
                                $this->parsed_output['controllers'][$current_class]["comma"] = $value['match'];
                            } break;
                            case "space" : {
                                $this->parsed_output['controllers'][$current_class]["space"] = $value['match'];
                            } break;
                            case "blockstart" : {
                                $this->parsed_output['controllers'][$current_class]["blockstart"] = $value['match'];
                            } break;
                            case "blockend" : {
                                $this->parsed_output['controllers'][$current_class]["blockend"] = $value['match'];
                            } break;
                            default: {
                                $this->parsed_output['controllers'][$current_class]['identifier'] = $value['match'];
                            }
                        }
                    } break;
                    case "T_DOUBLECOLON" : {
                        $state = "colon";
                    } break;
                    case "T_COMMASEPARATOR" : {
                        $state = "comma";
                    } break;
                    case "T_SINGLESPACE" : {
                        $state = "space";
                    } break;
                    case "T_BLOCKSTART" : {
                        $state = "blockstart";
                    } break;
                    case "T_BLOCKEND" : {
                        $state = "blockend";
                    } break;
                    default : {
                        $state = "new"; # Start a new class
                    }
                }
            }

            p($this->parsed_output);

            return $this->parsed_output;
        }
    }

    final class Lexer{
        protected $file_contents = array();
        protected static $roots = array(
            "/^(::)/" => "T_DOUBLECOLON",
            "/^(,)/" => "T_COMMASEPARATOR",
            "/^(\s+)/" => "T_SINGLESPACE",
            "/^({)/" => "T_BLOCKSTART",
            "/^(})/" => "T_BLOCKEND",
            "/^(\w+)/" => "T_IDENTIFIER",
        );

        public function __construct($filename = ""){
            if(!empty($filename) && is_readable($filename)){
                $this->load($filename);
            } else {
                throw new \Exception("Could not load file {$filename}");
            }
        }


        /**
         * Iterate over the source array and return lexed/tokenized array
         *
         * @author Michael Nitschinger <http://nitschinger.at/Writing-a-simple-lexer-in-PHP>
         * @param array $source
         * @throws \Exception
         * @return array
         */
        public function run(array $source = array()) {
            $tokens = array();
            $source = empty($source) ? $this->file_contents : $source;

            foreach($source as $number => $line) {
                $offset = 0;
                while($offset < strlen($line)){
                    $result = $this->match($line, $number, $offset);
                    if($result === false) {
                        throw new \Exception("Unable to parse line " . ($line + 1) . ".");
                    }
                    $tokens[] = $result;
                    $offset += strlen($result['match']);
                }
            }

            return $tokens;
        }


        /**
         * Load the given text file into an array
         *
         * @author Juan Sanchez <juan.sanchez@juanleonardosanchez.com>
         * @param $filename
         * @throws \Exception
         * @return bool
         */
        public function load($filename){
            if(!is_readable($filename)){
                throw new \Exception("Cannot read file ({$filename}), check that file exists and is readable.");
            } else {
                $this->file_contents = file($filename);
                return true;
            }
        }

        /**
         * Match the root patterns to the current line and return an array with the matched tokens
         *
         * @author Michael Nitschinger <http://nitschinger.at/Writing-a-simple-lexer-in-PHP>
         * @param $line
         * @param $number
         * @param $offset
         * @return array|bool
         */
        public function match($line, $number, $offset) {
            $string = substr($line, $offset);

            foreach(static::$roots as $pattern => $name) {
                if(preg_match($pattern, $string, $matches)) {
                    return array(
                        'match' => $matches[1],
                        'token' => $name,
                        'line' => $number + 1
                    );
                } else {
                    # TODO Log error or something
                }
            }

            return false;
        }
    }

}