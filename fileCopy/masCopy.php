<?php
require 'vendor/autoload.php';
global $ds;
$ds = DIRECTORY_SEPARATOR;

use App\FileCopier;
use App\SQLiteConnection;
USE App\Config;

$fileCopier = new FileCopier();
$source = '/Volumes/Public/shoregroup';
$target = '/Volumes/data/shoregroup';

if (!file_exists($source)) {
    print("path " . $source . "does exit\n\n");
    die();
}

if (!file_exists($target)) {
    print("path " . $target . " does exit\n\n");
    die();
}
$fileCopier->flushCommands();
print "collecting files \n";
//$fileCopier->collectFiles(Config::SOURCE_PATH, Config::TARGET_PATH);

$fileCopier->processSourcePath($source, $target);
print "copying files\n";
$fileCopier->processCommands();

print "done.\n\n";

