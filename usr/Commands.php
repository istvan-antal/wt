<?php
/*!
 * 
 * WebTool
 * http://www.istvan-antal.ro/wt.html
 *
 * Copyright 2011, Antal István Miklós
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.istvan-antal.ro/open-source.html
 * 
 */
class Commands {

    public static function help() {
        echo WT::colorize("Available commands:\n\n", 'white');

        // Module Create Namespace
        echo "`";
        echo WT::colorize('wt module create namespace ', 'light_blue');
        echo WT::colorize('<modulename>', 'red');
        echo "`";
        echo WT::colorize(" - Creates a client side JS module namespace scaffold.\n", 'white');

        // Module Create Object
        echo "`";
        echo WT::colorize('wt module create object ', 'light_blue');
        echo WT::colorize('<modulename>', 'red');
        echo "`";
        echo WT::colorize(" - Creates a client side JS module object scaffold.\n", 'white');
        
        // Module Create Function
        echo "`";
        echo WT::colorize('wt module create function ', 'light_blue');
        echo WT::colorize('<modulename>', 'red');
        echo "`";
        echo WT::colorize(" - Creates a client side JS module function scaffold.\n", 'white');
        
        // Module Create Widget
        echo "`";
        echo WT::colorize('wt module create widget ', 'light_blue');
        echo WT::colorize('<modulename>', 'red');
        echo "`";
        echo WT::colorize(" - Creates a client side JS widget scaffold.\n", 'white');

        // Lint JS FilesWT::colorize(
        echo "`";
        echo WT::colorize('wt lint ', 'light_blue');
        echo WT::colorize('<file1.js file2.js ...>', 'red');
        echo "`";
        echo WT::colorize(" - Checks the files for bad coding habbits and errors.\n", 'white');

        // Create API documentation
        echo "`";
        echo WT::colorize('wt doc create ', 'light_blue');
        echo WT::colorize('<file1.js file2.js ...> ', 'red');
        echo WT::colorize('<documentation directory>', 'light_red');
        echo "`";
        echo WT::colorize(" - Creates API documentation for the specified files.\n", 'white');

        // Complile JS files
        echo "`";
        echo WT::colorize('wt compile ', 'light_blue');
        echo WT::colorize('<file1.js file2.js ...>  ', 'red');
        echo WT::colorize('<output file>', 'light_red');
        echo "`";
        echo WT::colorize(" - Compiles the input files into an output file.\n", 'white');
        
        // Build JS files
        echo "`";
        echo WT::colorize('wt build ', 'light_blue');
        echo WT::colorize('<file1.js file2.js ...>  ', 'red');
        echo WT::colorize('<output file>', 'light_red');
        echo "`";
        echo WT::colorize(" - Checkes the files and compiles them into an output file on success.\n", 'white');

        echo "\n";
        echo WT::colorize("Local configuration:\n", 'white');
        
        // Local Config set
        echo "`";
        echo WT::colorize('wt config set ', 'light_blue');
        echo WT::colorize('<key>  ', 'red');
        echo WT::colorize('<value>', 'light_red');
        echo "`";
        echo WT::colorize(" - Sets a local value for a specific key.\n", 'white');
        
        // Local Config unset
        echo "`";
        echo WT::colorize('wt config unset ', 'light_blue');
        echo WT::colorize('<key>  ', 'red');
        echo WT::colorize('<value>', 'light_red');
        echo "`";
        echo WT::colorize(" - Unsets the local value for a specific key.\n", 'white');
        
        // Local Config purge
        echo "`";
        echo WT::colorize('wt config purge', 'light_blue');
        echo "`";
        echo WT::colorize(" - Removes the local config file.\n", 'white');
        
        // Local Config show
        echo "`";
        echo WT::colorize('wt config show', 'light_blue');
        echo "`";
        echo WT::colorize(" - Lists the current config keys.\n", 'white');
        
        echo "\n";
        echo WT::colorize("Experimental:\n", 'white');
        // Complile JS files
        echo "`";
        echo WT::colorize('wt acompile ', 'light_blue');
        echo WT::colorize('<file1.js file2.js ...>  ', 'red');
        echo WT::colorize('<output file>', 'light_red');
        echo "`";
        echo WT::colorize(" - Compiles the input files into an output file using the advanced settings for cc.\n", 'white');
        
        echo "\n";
        echo WT::colorize("References:\n", 'white');
        echo WT::colorize('JSDOC - ', 'light_gray') . WT::colorize("http://code.google.com/p/jsdoc-toolkit/", 'blue') . "\n";
        echo WT::colorize('Google Closure Compiler - ', 'light_gray') . WT::colorize("http://code.google.com/closure/compiler/", 'blue') . "\n";
        echo WT::colorize('JSLint - ', 'light_gray') . WT::colorize("http://www.jslint.com/", 'blue') . "\n";
    }

    public static function module($params) {
        $command = array_shift($params);

        switch ($command) {
            case 'create':
                $types = array('namespace', 'object', 'widget', 'function');

                $type = array_shift($params);
                $module_name = array_shift($params);

                if (!in_array($type, $types)) {
                    WT::error('Invalid module type.');
                }

                $template = new Template();
                $template->assign('module_name', $module_name);

                file_put_contents($module_name . '.js', $template->fetch('module-' . $type . '.js'));
                WT::success('Module created.');
                break;
            default :
                WT::error("Invalid module command.");
        }
    }

    public static function doc($params) {
        $command = array_shift($params);

        switch ($command) {
            case 'create':
                $sdir = WT::getScriptDir();

                $docdir = array_pop($params);
                $files = implode(' ', $params);

                if (file_exists($docdir)) {
                    if (!is_dir($docdir)) {
                        WT::error('The file you specified is not a directory.');
                    }
                    echo WT::colorize("WARNING: You are about to overwrite $docdir. Are you sure about this? [N]\n", 'yellow');
                    $response = readline(null);
                    if (strtolower($response) !== 'y') {
                        exit(0);
                    }
                }

                system("java -jar $sdir/tools/jsdoc/jsrun.jar $sdir/tools/jsdoc/app/run.js $files -t=$sdir/tools/jsdoc/templates/".WT::getConf('jsdoc.theme')." -d=$docdir");

                WT::success("API documentation created.");
                exit(0);
                break;
            default :
                WT::error("Invalid documentation command.");
        }
    }

    public static function lint($params, $terminate = true) {
        $sdir = WT::getScriptDir();
        $files = implode(' ', $params);
        @exec("java -jar $sdir/tools/jslint/js.jar $sdir/tools/jslint/jslint-check.js $sdir/tools/jslint $files", $output, $status);
        if ($status) {
            foreach ($output as $line) {
                if (!strlen($line)) {
                    echo "\n";
                } else {
                    echo trim($line);
                }
            }
            echo "\n";
            WT::error('Check failed');
        } else {
            WT::success("Check passed.", $terminate);
        }
    }

    public static function acompile($params) {
        Commands::compile($params, true);
    }

    public static function compile($params, $advanced = false) {
        $sdir = WT::getScriptDir();
        $output = array_pop($params);
        foreach ($params as &$file) {
            $file = "--js=$file";
        }
        $files = implode(' ', $params);

        if (file_exists($output)) {
            echo WT::colorize("WARNING: You are about to overwrite $output. Are you sure about this? [N]\n", 'yellow');
            $response = readline(null);
            if (strtolower($response) !== 'y') {
                exit(0);
            }
        }

        system("java -jar $sdir/tools/closure/compiler.jar --compilation_level ".(($advanced)?'ADVANCED':'SIMPLE')."_OPTIMIZATIONS $files --js_output_file=$output");
        WT::success("Build successful.");
    }

    public static function build($params) {
        $bfiles = $params;
        $lfiles = $params;

        array_pop($lfiles);

        Commands::lint($lfiles, false);
        Commands::compile($bfiles);
    }
    
    public static function config($params) {
        $operation = array_shift($params);
        
        switch ($operation) {
            case 'set':
                $key = array_shift($params);
                $value = array_shift($params);
                WT::setConf($key, $value);
                break;
            case 'unset':
                $key = array_shift($params);
                WT::setConf($key);
                break;
            case 'show':
                WT::showConfig();
                break;
            case 'purge':
                WT::removeLocalConfig();
                break;
            default:
                WT::error('Invalid operation.');
        }
    }

}