<?php

namespace App;

// sqlite:db/phpsqlite.db
/**
 * SQLite connnection
 */
class SQLiteConnection
{
    /**
     * PDO instance
     * @var type
     */
    private $pdo;

    /**
     * return in instance of the PDO object that connects to the SQLite database
     * @return \PDO
     */
    public function connect()
    {
        if ($this->pdo == null) {
            $this->pdo = new \PDO("sqlite:" . Config::PATH_TO_SQLITE_FILE);
        }
        $this->buildTables();
        return $this->pdo;
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
              target text              
            )
            ",

        ];
        foreach ($tables as $table) {
            $this->pdo->exec($table);
        }
    }

    public function addCommand($command, $source, $target)
    {
        $sql = "insert into copies (cmd, source, target) values ('{$command}','{$source}','{$target}')";

        $this->pdo->exec($sql);
    }

    public function getCommands()
    {

        $sql = "select rowid, * from copies";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCommand()
    {
        $sql = "select rowid, * from copies limit 1";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function dropCommand($id)
    {
        $sql = "delete from copies where rowid = {$id}";
        $this->pdo->exec($sql);
    }

}