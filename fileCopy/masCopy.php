<?php
require 'vendor/autoload.php';
global $ds;
$ds = DIRECTORY_SEPARATOR;

use App\FileCopier;
use App\SQLiteConnection;
USE App\Config;

$copies = [
    [
        'source' => '/Volumes/Public/Sentry',
        'target' => '/Volumes/data/Sentry',
    ],
    [
        'source' => '/Volumes/Public/Reliance',
        'target' => '/Volumes/data/Reliance',
    ],
    [
        'source' => '/Volumes/Public/projects',
        'target' => '/Volumes/data/projects'
    ],
    [
        'source' => '/Volumes/Public/PrinterLogic',
        'target' => '/Volumes/data/PrinterLogic',
    ],
    [
        'source' => '/Volumes/Public/njt',
        'target' => '/Volumes/data/njt',
    ],
    [
        'source' => '/Volumes/Public/netbook',
        'target' => '/Volumes/data/netbook',
    ],
    [
        'source' => '/Volumes/Public/Movies',
        'target' => '/Volumes/data/Movies',
    ],
    [
        'source' => '/Volumes/Public/Media',
        'target' => '/Volumes/data/Media',
    ],
];
foreach ($copies as $block) {
    $source = $block['source'];
    $target = $block['target'];

    if (!file_exists($target)) {
        mkdir($target);
    }
    goCopy($source, $target);
}
function goCopy($source, $target)
{
    $fileCopier = new FileCopier();

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
}

