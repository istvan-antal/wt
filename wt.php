#!/usr/bin/php
<?php
require_once 'usr/loader.php';

WT::setWorkDir(getcwd());
WT::setScriptDir(dirname(__FILE__));
WT::loadLocalConfig();

// Remove script filename, we don't really need it
array_shift($argv);

// No parameters
if (empty ($argv)) {
    echo "WebTool v0.1\n";
    echo "Istvan Miklós Antal <istvan.m.antal@gmail.com>\n\n";
    echo "Try `wt help` for a list of available commands.\n";
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

