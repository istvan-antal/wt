#!/usr/bin/php
<?php
require_once 'usr/loader.php';

JSOP::setWorkDir(getcwd());
JSOP::setScriptDir(dirname(__FILE__));

// Remove script filename, we don't really need it
array_shift($argv);

// No parameters
if (empty ($argv)) {
    echo "JSOP v0.1\n";
    echo "Istvan Miklós Antal <istvan.m.antal@gmail.com>\n\n";
    echo "Try `jsop help` for a list of available commands.\n";
    exit(0);
}

$action = array_shift($argv);

if (method_exists('Commands', $action)) {
    // Execute command
    Commands::$action($argv);
} else {
    // Invalid command
    echo "Please specify a valid command.\n";
    exit(1);
}

