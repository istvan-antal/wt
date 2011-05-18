<?php

class Commands {

    public static function help() {
        echo JSOP::colorize("Available commands:\n\n", 'white');

        // Module Create Namespace
        echo "`";
        echo JSOP::colorize('jsop module create namespace ', 'light_blue');
        echo JSOP::colorize('<modulename>', 'red');
        echo "`";
        echo JSOP::colorize(" - Creates a client side JS module namespace scaffold.\n", 'white');

        // Module Create Object
        echo "`";
        echo JSOP::colorize('jsop module create object ', 'light_blue');
        echo JSOP::colorize('<modulename>', 'red');
        echo "`";
        echo JSOP::colorize(" - Creates a client side JS module object scaffold.\n", 'white');
        
        // Module Create Widget
        echo "`";
        echo JSOP::colorize('jsop module create widget ', 'light_blue');
        echo JSOP::colorize('<modulename>', 'red');
        echo "`";
        echo JSOP::colorize(" - Creates a client side JS widget scaffold.\n", 'white');

        // Lint JS FilesJSOP::colorize(
        echo "`";
        echo JSOP::colorize('jsop lint ', 'light_blue');
        echo JSOP::colorize('<file1.js file2.js ...>', 'red');
        echo "`";
        echo JSOP::colorize(" - Checks the files for bad coding habbits and errors.\n", 'white');

        // Create API documentation
        echo "`";
        echo JSOP::colorize('jsop doc create ', 'light_blue');
        echo JSOP::colorize('<file1.js file2.js ...> ', 'red');
        echo JSOP::colorize('<documentation directory>', 'light_red');
        echo "`";
        echo JSOP::colorize(" - Creates API documentation for the specified files.\n", 'white');

        // Complile JS files
        echo "`";
        echo JSOP::colorize('jsop compile ', 'light_blue');
        echo JSOP::colorize('<file1.js file2.js ...>  ', 'red');
        echo JSOP::colorize('<output file>', 'light_red');
        echo "`";
        echo JSOP::colorize(" - Compiles the input files into an output file.\n", 'white');

        // Build JS files
        echo "`";
        echo JSOP::colorize('jsop build ', 'light_blue');
        echo JSOP::colorize('<file1.js file2.js ...>  ', 'red');
        echo JSOP::colorize('<output file>', 'light_red');
        echo "`";
        echo JSOP::colorize(" - Checkes the files and compiles them into an output file on success.\n", 'white');

        echo "\n";
        echo JSOP::colorize("Local configuration:\n", 'white');
        
        // Local Config set
        echo "`";
        echo JSOP::colorize('jsop config set ', 'light_blue');
        echo JSOP::colorize('<key>  ', 'red');
        echo JSOP::colorize('<value>', 'light_red');
        echo "`";
        echo JSOP::colorize(" - Sets a local value for a specific key.\n", 'white');
        
        // Local Config unset
        echo "`";
        echo JSOP::colorize('jsop config unset ', 'light_blue');
        echo JSOP::colorize('<key>  ', 'red');
        echo JSOP::colorize('<value>', 'light_red');
        echo "`";
        echo JSOP::colorize(" - Unsets the local value for a specific key.\n", 'white');
        
        // Local Config purge
        echo "`";
        echo JSOP::colorize('jsop config purge', 'light_blue');
        echo "`";
        echo JSOP::colorize(" - Removes the local config file.\n", 'white');
        
        // Local Config show
        echo "`";
        echo JSOP::colorize('jsop config show', 'light_blue');
        echo "`";
        echo JSOP::colorize(" - Lists the current config keys.\n", 'white');
        
        echo "\n";
        echo JSOP::colorize("References:\n", 'white');
        echo JSOP::colorize('JSDOC - ', 'light_gray') . JSOP::colorize("http://code.google.com/p/jsdoc-toolkit/", 'blue') . "\n";
        echo JSOP::colorize('Google Closure Compiler - ', 'light_gray') . JSOP::colorize("http://code.google.com/closure/compiler/", 'blue') . "\n";
        echo JSOP::colorize('JSLint - ', 'light_gray') . JSOP::colorize("http://www.jslint.com/", 'blue') . "\n";
    }

    public static function module($params) {
        $command = array_shift($params);

        switch ($command) {
            case 'create':
                $types = array('namespace', 'object', 'widget');

                $type = array_shift($params);
                $module_name = array_shift($params);

                if (!in_array($type, $types)) {
                    JSOP::error('Invalid module type.');
                }

                $template = new Template();
                $template->assign('module_name', $module_name);

                file_put_contents($module_name . '.js', $template->fetch('module-' . $type . '.js'));
                JSOP::success('Module created.');
                break;
            default :
                JSOP::error("Invalid module command.");
        }
    }

    public static function doc($params) {
        $command = array_shift($params);

        switch ($command) {
            case 'create':
                $sdir = JSOP::getScriptDir();

                $docdir = array_pop($params);
                $files = implode(' ', $params);

                if (file_exists($docdir)) {
                    if (!is_dir($docdir)) {
                        JSOP::error('The file you specified is not a directory.');
                    }
                    echo JSOP::colorize("WARNING: You are about to overwrite $docdir. Are you sure about this? [N]\n", 'yellow');
                    $response = readline(null);
                    if (strtolower($response) !== 'y') {
                        exit(0);
                    }
                }

                system("java -jar $sdir/tools/jsdoc/jsrun.jar $sdir/tools/jsdoc/app/run.js $files -t=$sdir/tools/jsdoc/templates/".JSOP::getConf('jsdoc.theme')." -d=$docdir");

                JSOP::success("API documentation created.");
                exit(0);
                break;
            default :
                JSOP::error("Invalid documentation command.");
        }
    }

    public static function lint($params, $terminate = true) {
        $sdir = JSOP::getScriptDir();
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
            JSOP::error('Check failed');
        } else {
            JSOP::success("Check passed.", $terminate);
        }
    }

    public static function compile($params) {
        $sdir = JSOP::getScriptDir();
        $output = array_pop($params);
        foreach ($params as &$file) {
            $file = "--js=$file";
        }
        $files = implode(' ', $params);

        if (file_exists($output)) {
            echo JSOP::colorize("WARNING: You are about to overwrite $output. Are you sure about this? [N]\n", 'yellow');
            $response = readline(null);
            if (strtolower($response) !== 'y') {
                exit(0);
            }
        }

        system("java -jar $sdir/tools/closure/compiler.jar --compilation_level SIMPLE_OPTIMIZATIONS $files --js_output_file=$output");
        JSOP::success("Build successful.");
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
                JSOP::setConf($key, $value);
                break;
            case 'unset':
                $key = array_shift($params);
                JSOP::setConf($key);
                break;
            case 'show':
                JSOP::showConfig();
                break;
            case 'purge':
                JSOP::removeLocalConfig();
                break;
            default:
                JSOP::error('Invalid operation.');
        }
    }

}