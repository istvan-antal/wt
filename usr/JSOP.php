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
        return !JSOP::$is_work_dir;
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
    
}
