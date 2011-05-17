<?php

class Commands {

    public static function help() {
        echo "Available commands:\n\n";
        echo "`jsop module create namespace <modulename>` - Creates a client side JS module namespace scaffold.\n";
        echo "`jsop module create object <modulename>` - Creates a client side JS module object scaffold.\n";
        echo "`jsop lint <file1.js file2.js ...>` - Checks the files for bad coding habbits and errors.\n";
        echo "`jsop doc create <file1.js file2.js ...> <documentation directory>` - Creates API documentation for the specified files.\n";
        echo "`jsop compile <file1.js file2.js ...> <output file>` - Compiles the input files into an output file.\n";
        echo "`jsop build <file1.js file2.js ...> <output file>` - Checkes the files and compiles them into an output file on success.\n";
        echo "\n";
        echo "References:\n";
        echo "http://code.google.com/p/jsdoc-toolkit/\n";
        echo "http://code.google.com/closure/compiler/\n";
        echo "http://www.jslint.com/\n";
    }

    public static function module($params) {
        $command = array_shift($params);

        switch ($command) {
            case 'create':
                $type = array_shift($params);
                $module_name = array_shift($params);

                $template = new Template();
                $template->assign('module_name', $module_name);

                file_put_contents($module_name . '.js', $template->fetch('module-' . $type . '.js'));
                echo "Module created.\n";
                exit(0);
                break;
            default :
                echo "Invalid module command\n";
                exit(1);
        }
    }

    public static function doc($params) {
        ///var/www/sandbox/Foo.js /var/www/sandbox/Foo.bar.js /var/www/sandbox/Foo.UI.js 
        $command = array_shift($params);

        switch ($command) {
            case 'create':
                $sdir = JSOP::getScriptDir();
                
                $docdir = array_pop($params);
                $files = implode(' ', $params);

                system("java -jar $sdir/tools/jsdoc/jsrun.jar $sdir/tools/jsdoc/app/run.js $files -t=$sdir/tools/jsdoc/templates/jsdoc -d=$docdir");

                echo "API documentation created.\n";
                exit(0);
                break;
            default :
                echo "Invalid documentation command\n";
                exit(1);
        }
    }

    public static function lint($params) {
        $sdir = JSOP::getScriptDir();
        $files = implode(' ', $params);
        //echo "java -jar $sdir/tools/jslint/js.jar $sdir/tools/jslint/jslint-check.js $sdir/tools/jslint $files";
        @exec("java -jar $sdir/tools/jslint/js.jar $sdir/tools/jslint/jslint-check.js $sdir/tools/jslint $files", $output, $status);
        if ($status) {
            foreach ($output as $line) {
                if (!strlen($line)) {
                    echo "\n";
                } else {
                    echo trim($line);
                }
            }
        } else {
            echo "Check passed!\n";
        }
        return !$status;
    }

    public static function compile($params) {
        $sdir = JSOP::getScriptDir();
        $output = array_pop($params);
        foreach ($params as &$file) {
            $file = "--js=$file";
        }
        $files = implode(' ', $params);

        system("java -jar $sdir/tools/closure/compiler.jar $files --js_output_file=$output");
        echo "Build successful.\n";
        exit(0);
    }
    
    public static function build($params) {
        $bfiles = $params;
        $lfiles = $params;
        
        array_pop($lfiles);
        
        $status = Commands::lint($lfiles);
        if (!$status) {
            echo "Lint check failed!\n";
            exit(1);
        }
        Commands::compile($bfiles);
    }

}