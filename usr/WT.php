<?php

class WT {

    private static $working_dir;
    private static $script_dir;
    private static $is_work_dir = true;

    public static function setWorkDir($dir) {
        WT::$working_dir = $dir;
    }

    public static function setScriptDir($dir) {
        WT::$script_dir = $dir;
    }

    public static function getWorkDir() {
        return WT::$working_dir;
    }

    public static function getScriptDir() {
        return WT::$script_dir;
    }

    public static function isWorkDir() {
        return WT::$is_work_dir;
    }

    public static function isScriptDir() {
        return!WT::$is_work_dir;
    }

    public static function toWorkDir() {
        if (!WT::isWorkDir()) {
            chdir(WT::$working_dir);
        }
    }

    public static function toScriptDir() {
        if (!WT::isScriptDir()) {
            chdir(WT::$script_dir);
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
        if (!WT::$config['colors']) {
            return $string;
        }
        if (isset(WT::$shell_foreground_colors[$foreground_color])) {
            $string = "\033[" . WT::$shell_foreground_colors[$foreground_color] . "m" . $string;
        }

        // Check if given background color found
        if (isset(WT::$shell_background_colors[$background_color])) {
            $string = "\033[" . WT::$shell_background_colors[$background_color] . "m" . $string;
        }

        // Add string and end coloring
        $string .= "\033[0m";

        return $string;
    }

    public static function error($string, $terminate = true) {
        echo WT::colorize($string . "\n", 'light_red', 'black');
        if ($terminate)
            exit(1);
    }

    public static function success($string, $terminate = true) {
        echo WT::colorize($string . "\n", 'green');
        if ($terminate)
            exit(0);
    }

    /*
     * Config section
     */

    private static $config = array(
        'colors' => true,
        'jsdoc' => array(
            'theme' => 'jq'
        )
    );
    private static $home_dir;
    private static $conf_file;

    public static function loadLocalConfig() {
        exec('echo ~', $output);
        WT::$home_dir = $output[0];
        WT::$conf_file = WT::$home_dir . '/' . '.WTconf';
        if (file_exists(WT::$conf_file)) {
            $conf = json_decode(file_get_contents(WT::$conf_file), true);
            WT::mashArrays(WT::$config, $conf);
        }
    }

    public static function getConf($key) {
        $keys = explode('.', $key);

        $val = &WT::$config;
        do {
            $key = array_shift($keys);
            $val = &$val[$key];
        } while (!empty($keys));

        return $val;
    }

    public static function setConf($key, $value = null) {
        $keys = explode('.', $key);

        switch ($value) {
            case 'false':
                $value = false;
                break;
            case 'true':
                $value = true;
                break;
        }


        if (file_exists(WT::$conf_file)) {
            $conf = json_decode(file_get_contents(WT::$conf_file), true);
        } else {
            $conf = array();
        }
        $val = &$conf;
        do {
            $key = array_shift($keys);
            if (is_null($value) && empty($keys)) {
                unset($val[$key]);
            } else {
                $val = &$val[$key];
            }
        } while (!empty($keys));
        if (!is_null($value)) {
            $val = $value;
        }
        if (!empty($conf)) {
            file_put_contents(WT::$conf_file, json_encode($conf));
        } else {
            unlink(WT::$conf_file);
        }
    }

    public static function mashArrays(&$target, $new) {
        if (!empty($new)) {
            foreach ($new as $k => $v) {
                if (is_array($v)) {
                    WT::mashArrays($target[$k], $v);
                } else {
                    $target[$k] = $v;
                }
            }
        }
    }

    public static function removeLocalConfig() {
        unlink(WT::$conf_file);
    }

    public static function showConfig() {
        $conf = WT::$config;
        WT::printArray($conf);
        echo "\n";
    }

    public static function printArray($conf, $indent = 0) {
        foreach ($conf as $k => $v) {
            echo str_repeat(' ', $indent) . "$k = ";
            if (is_array($v)) {
                echo "\n";
                WT::printArray($v, $indent + 2);
            } else {
                if (is_bool($v)) {
                    echo ($v ? 'true' : 'false');
                } else {
                    echo $v;
                }
                echo "\n";
            }
        }
    }

}
