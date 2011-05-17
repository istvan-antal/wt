<?php
function my_loader($class_name) {
    $cdir = getcwd();
    chdir(dirname(__FILE__));
    chdir('..');
    $dirs = array(
        'lib/',
        'usr/',
    );
    foreach ($dirs as $dir) {
        $file = $dir . $class_name . '.php';
        if (file_exists($file)) {
            include $file;
            chdir($cdir);
            return;
        }
        $file = $dir . $class_name . '.class.php';
        if (file_exists($file)) {
            include $file;
            chdir($cdir);
            return;
        }
    }
    chdir($cdir);
}

spl_autoload_register('my_loader');