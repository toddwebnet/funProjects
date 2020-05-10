<?php
require 'vendor/autoload.php';
global $ds;
$ds = DIRECTORY_SEPARATOR;

use App\FileCopier;
use App\SQLiteConnection;
USE App\Config;

$fileCopier = new FileCopier();
print_r($fileCopier->flushCommands());
