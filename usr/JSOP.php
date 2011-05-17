<?php

class JSOP {

    private static $working_dir;
    private static $script_dir;
    private static $is_work_dir = true;

    public static function setWorkDir($dir) {
        JSOP::$working_dir = $dir;
    }

    public static function setScriptDir($dir) {
        JSOP::$script_dir = $dir;
    }

    public static function getWorkDir() {
        return JSOP::$working_dir;
    }

    public static function getScriptDir() {
        return JSOP::$script_dir;
    }

    public static function isWorkDir() {
        return JSOP::$is_work_dir;
    }

    public static function isScriptDir() {
        return!JSOP::$is_work_dir;
    }

    public static function toWorkDir() {
        if (!JSOP::isWorkDir()) {
            chdir(JSOP::$working_dir);
        }
    }

    public static function toScriptDir() {
        if (!JSOP::isScriptDir()) {
            chdir(JSOP::$script_dir);
        }
    }

    /**
     * Color constants
     */
    private static $shell_foreground_colors = array(
        'black' => '0;30',
        'dark_gray' => '1;30',
        'blue' => '0;34',
        'light_blue' => '1;34',
        'green' => '0;32',
        'light_green' => '1;32',
        'cyan' => '0;36',
        'light_cyan' => '1;36',
        'red' => '0;31',
        'light_red' => '1;31',
        'purple' => '0;35',
        'light_purple' => '1;35',
        'brown' => '0;33',
        'yellow' => '1;33',
        'light_gray' => '0;37',
        'white' => '1;37'
    );
    
    private static $shell_background_colors = array(
        'black' => '40',
        'red' => '41',
        'green' => '42',
        'yellow' => '43',
        'blue' => '44',
        'magenta' => '45',
        'cyan' => '46',
        'light_gray' => '47'
    );

    public static function colorize($string, $foreground_color = null, $background_color = null) {
        
        if (isset(JSOP::$shell_foreground_colors[$foreground_color])) {
            $string = "\033[" . JSOP::$shell_foreground_colors[$foreground_color] . "m". $string;
        }
        
        // Check if given background color found
        if (isset(JSOP::$shell_background_colors[$background_color])) {
            $string = "\033[" . JSOP::$shell_background_colors[$background_color] . "m". $string;
        }

        // Add string and end coloring
        $string .= "\033[0m";
        
        return $string;
    }
    
    public static function error($string, $terminate = true) {
        echo JSOP::colorize($string."\n", 'light_red', 'black');
        if ($terminate)
        exit(1);
    }
    
    public static function success($string, $terminate = true) {
        echo JSOP::colorize($string."\n", 'green');
        if ($terminate)
        exit(0);
    }

}
