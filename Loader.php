<?php

/**
 * Note:: Not using namespace
 * This is just a sample class... 
 */
class Loader 
{

    static $anime = 'dotted'; 
    static $animes = ['dotted', 'roller']; 

    static function type($anime = 'dotted'){
        if(in_array($anime, self::$animes)){
            self::$anime = $anime;
        }
    }

    /**
     * Static method to run progressbar
     *
     * @param array|string $function
     * @param array|string $final_callback
     * @return void
     */
    static function start($function, $final_callback = []) {

        (new self)->run($function, $final_callback);

    }

    /**
     * Display a message to a console or page
     *
     * @param string $message
     * @param integer $spaces
     * @return void
     */
    static function view(string $message, $return = true){
        
        static $count = 0;
        $count++;

        $br = (php_sapi_name() == 'cli') ? PHP_EOL : '<br>';
    
        if($count == 1){
          $message = $br.$message.$br.$br; 
        }else{
          $message = $message.$br.$br; 
        }

        if($return) return $message;

        print $message;
    
    }

    /**
     * Instance method to run progressbar
     *
     * @param array|string $function
     * @param array|string $final_callback
     * @return void
     */
    function run($function, $final_callback) {

        $this->load(true, $function, $final_callback);

    }
    
    /**
     * A sample iterable heavy function to test progress bar
     *
     * @return void
     */
    function heavy(){
        $i = 0;

        
        yield 1; // Stage 1 - function processing
        // while($i < 20){ usleep(50000); $i++; if($i == 20){ $i = 0; break; } }
        
        yield 2; // Stage 2 - function processing
        // while($i < 20){ usleep(50000); $i++; if($i == 20){ $i = 0; break; } }

        yield 3; // Stage 3 - function processing
        //slows progress bar  
        while($i < 20){ usleep(50000); $i++; if($i == 20){ $i = 0; break; } }

        yield 4; // Stage 4 - function processing
        //slows progress bar more 
        while($i < 100){ usleep(50000); $i++; if($i == 100){ $i = 0; break; } }

        yield 5; // Stage 5 - function processing
        //slows progress bar even more  
        while($i < 200){ usleep(50000); $i++; if($i == 200){ $i = 0; break; } }

        yield 6; // Stage 5 - function processing
        //slows progress bar much more      
        // while($i < 1000){ usleep(50000); $i++; if($i == 1000){ $i = 0; break; } }

        // last stage (yield true) completed here
        yield true;
    }

    /**
     * A sample final callback function
     *
     * @return void
     */
    function done() {
        print 'process completed successfully';
    }

    /**
     * To use this function, $callback must be iterable
     *
     * @param bool $isLoading tells method to start loading
     * @param array|string $callback an iterable function or method 
     *    - Formats:
     *      - 'function'   => function
     *      - ['method'] => [current_class_object, 'method']
     *      - ['class', 'method'] => ['class', 'method']
     * @param array|string $final a final callback once loading is completed
     * @return void
     */
    private function load(bool $isLoading, $callback = [], $final = []) {
        
        static $start = 0;
        static $posit = 0; 
        $anime = self::$anime;

        if($posit > 2) $posit = 0;

        static $chr   = ['/', '-', '\\']; 

        // static $chr   = ['<', '|', '>']; 

        if($anime == 'dotted') print PHP_EOL;

        // $this->cls(); //clears console

        /**
         * Set all array having a single key to call method within the current class.
         * if $callback = ['method']
         *    then,
         *    $callback becomes [$this, 'method']
         */
        if($callback && is_array($callback) && (count($callback) == 1)){
            $callback = [$this, $callback[0]];
        }

        if(!$isLoading) {
            if(is_callable($callback)) {
                if($callback instanceof \Closure){
                   $response = $callback();
                   if($response) print $response;
                }else{
                    call_user_func($callback);
                }
            }
            return;
        }

        if(is_callable($callback)) {
            $loop = call_user_func($callback);

            foreach($loop as $process){

                //rolls progress bar at least 5 times for each yield
                for($i = 0; $i <= 5; $i++) {
                    
                    if($posit > 2) $posit = 0;

                    echo $chr[$posit];
                    usleep(60000);           
                    echo chr(8); 
                    $posit++;
                    
                }
                       
                echo '.'; 
                if($anime == 'roller') echo chr(8); 
            }            
        }

        echo ' '.chr(8); //prevent any left over character 
        $this->load(false, $final); //set function loading to false.

        $start++;
    }

    /**
     * Clears console
     *
     * @return void
     */
    public function cls(){
        echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
    }

}

