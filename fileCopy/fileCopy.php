<?php
require 'vendor/autoload.php';
global $ds;
$ds = DIRECTORY_SEPARATOR;

use App\SQLiteConnection;
USE App\Config;


$db = (new SQLiteConnection());
$pdo = $db->connect();
if ($pdo != null) {
    echo "Connected to the SQLite database successfully!\n";
} else {
    echo 'Whoops, could not connect to the SQLite database!';
    print "\n";
    die();
}

$sql = "delete from copies";
$pdo->exec($sql);


if (!file_exists(Config::SOURCE_PATH)) {
    print("path " . Config::SOURCE_PATH . "does exit\n\n");
    die();
}


if (!file_exists(Config::TARGET_PATH)) {
    print("path " . Config::TARGET_PATH . " does exit\n\n");
    die();
}

print "collecting files \n";
$targetPath = Config::TARGET_PATH . $ds . date("Y.m.d");
$db->addCommand('mkdir', $targetPath, '');
processSourcePath($db, Config::SOURCE_PATH, $targetPath);
print "copying files\n";
$row = $db->getCommand();
while ($row != null) {
    runCommand($row['cmd'], $row['source'], $row['target']);
    $db->dropCommand($row['rowid']);
    $row = $db->getCommand();
}

print "done.\n\n";

function processSourcePath(SQLiteConnection $db, $source, $target)
{
    $ds = DIRECTORY_SEPARATOR;
    foreach (scandir($source) as $file) {
        if (in_array($file, ['.', '..'])) {
            continue;
        }
        if (is_file($source . $ds . $file)) {
            $db->addCommand('cp',
                $source . $ds . $file,
                $target . $ds . $file
            );
        }
        if (is_dir($source . $ds . $file)) {
            $db->addCommand('mkdir',
                $target . $ds . $file,
                ''
            );
            processSourcePath($db,
                $source . $ds . $file,
                $target . $ds . $file
            );
        }

    }

}


function runCommand($cmd, $source, $target)
{
    if ($cmd == 'mkdir') {
        mkdir($source);
    }
    if ($cmd == 'cp') {
        copy($source, $target);
    }
}