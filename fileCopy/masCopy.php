<?php
require 'vendor/autoload.php';
global $ds;
$ds = DIRECTORY_SEPARATOR;

use App\FileCopier;
use App\SQLiteConnection;
USE App\Config;

$fileCopier = new FileCopier();

if (!file_exists(Config::SOURCE_PATH)) {
    print("path " . Config::SOURCE_PATH . "does exit\n\n");
    die();
}

if (!file_exists(Config::TARGET_PATH)) {
    print("path " . Config::TARGET_PATH . " does exit\n\n");
    die();
}

print "collecting files \n";
//$fileCopier->collectFiles(Config::SOURCE_PATH, Config::TARGET_PATH);
$source = '/Volumes/Public/brother7360n';
$target = '/Volumes/data/brother7360n';
$fileCopier->processSourcePath($source, $target);
print "copying files\n";
$fileCopier->processCommands();

print "done.\n\n";

