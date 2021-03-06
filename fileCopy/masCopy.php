<?php
require 'vendor/autoload.php';
global $ds;
$ds = DIRECTORY_SEPARATOR;

use App\FileCopier;
use App\SQLiteConnection;
USE App\Config;



$files = [
	'Sentry',
	'shoregroup',
	'Software',
	'songs',
	'templates',
	'tutorials',
	'Vlogs',
];
$copies = [];
foreach($files as $file)
$copies [] =   
    [
        'source' => '/mnt/public/' . $file,
        'target' => '/media/bigboy/data/' . $file,
    ];
// print_r($copies);die();


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

