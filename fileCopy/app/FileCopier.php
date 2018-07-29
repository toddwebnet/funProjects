<?php

namespace App;

class FileCopier extends SQLiteConnection
{

    private $orderId = 0;

    public function __construct()
    {
        $this->orderId = 0;
        $this->connect();
        $this->buildTables();
    }

    private function buildTables()
    {
        print "building tables\n";
        $tables = [
            "
            CREATE TABLE IF NOT EXISTS copies
            (
              cmd text,
              source text, 
              target text,
              orderid int              
            )
            ",

        ];
        foreach ($tables as $table) {
            $this->pdo->exec($table);
        }
        $this->flushCommands();
    }

    public function flushCommands()
    {
        $sql = "delete from copies";
        $this->pdo->exec($sql);
    }

    public function addCommand($command, $source, $target)
    {
        $this->orderId++;
        $sql = "insert into copies (cmd, source, target, orderid) values ('{$command}','{$source}','{$target}', {$this->orderId})";
        $this->pdo->exec($sql);
    }

    public function getCommands()
    {
        $sql = "select * from copies order by orderid";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCommandsRev()
    {
        $sql = "select * from copies order by orderid desc";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCommand()
    {
        $sql = "select * from copies order by orderid limit 1";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getCommandRev()
    {
        $sql = "select * from copies order by orderid desc limit 1";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function dropCommand($id)
    {
        $sql = "delete from copies where orderid = {$id}";
        $this->pdo->exec($sql);
    }

    public function collectFiles($sourcePath, $targetPath)
    {
        $ds = DIRECTORY_SEPARATOR;
        $targetPath = $targetPath . $ds . date("Y.m.d");
        $this->addCommand('mkdir', $targetPath, '');
        $this->processSourcePath($sourcePath, $targetPath);
    }

    public function processSourcePath($source, $target)
    {
        print "\n";
        $ds = DIRECTORY_SEPARATOR;
        foreach (scandir($source) as $file) {
            print $source . $ds . $file . "\n";
            if (in_array($file, ['.', '..'])) {
                continue;
            }
            if (is_file($source . $ds . $file)) {
                $this->addCommand('cp',
                    $source . $ds . $file,
                    $target . $ds . $file
                );
            }
            if (is_dir($source . $ds . $file)) {
                $this->addCommand('mkdir',
                    $target . $ds . $file,
                    ''
                );
                $this->processSourcePath(
                    $source . $ds . $file,
                    $target . $ds . $file
                );
            }
        }
    }

    public function processCommands()
    {
        $row = $this->getCommand();
        while ($row != null) {
            $this->runCommand($row['cmd'], $row['source'], $row['target']);
            $this->dropCommand($row['orderid']);
            $row = $this->getCommand();
        }
    }

    public function processCommandsRev()
    {
        $row = $this->getCommandRev();

        while ($row != null) {
            $this->runCommand($row['cmd'], $row['source'], $row['target']);
            $this->dropCommand($row['orderid']);
            $row = $this->getCommandRev();
        }
    }

    private function runCommand($cmd, $source, $target)
    {
        print "{$cmd} {$source} {$target} \n";
        if ($cmd == 'mkdir') {
            if(!file_exists($source)) {
                mkdir($source);
            }
        }
        if ($cmd == 'cp') {
            if(!file_exists($target)) {
                copy($source, $target);
            }
        }
        if ($cmd == 'rmdir') {
            rmdir($source);
        }
        if ($cmd == 'del') {
            unlink($source);
        }
    }

    public function collectOldFolders($targetPath)
    {
        $ds = DIRECTORY_SEPARATOR;
        $fiveDaysAgo = strtotime(date("Y-m-d") . " -5 days");
        foreach (scandir($targetPath) as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }
            $path = $targetPath . $ds . $file;
            if (is_dir($path)) {
                if ($this->derivePathDate($file) < $fiveDaysAgo) {
                    $this->addCommand('rmdir', $path, '');
                    $this->processSourcePathForDelete($path);
                }
            }
        }
    }

    private function processSourcePathForDelete($source)
    {
        $ds = DIRECTORY_SEPARATOR;
        foreach (scandir($source) as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }
            if (is_file($source . $ds . $file)) {
                $this->addCommand('del', $source . $ds . $file, '');
            }
            if (is_dir($source . $ds . $file)) {
                $this->addCommand('rmdir', $source . $ds . $file, '');
                $this->processSourcePathForDelete($source . $ds . $file);
            }
        }
    }

    private function derivePathDate($path)
    {
        $ar = explode('.', $path);
        if (count($ar) == 3 && checkdate($ar[1], $ar[2], $ar[0])) {
            return strtotime(str_replace('.', '-', $path));
        }
        return time();
    }

}
