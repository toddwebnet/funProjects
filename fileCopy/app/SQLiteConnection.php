<?php

namespace App;
require_once('config.php');
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
    protected $pdo;


    /**
     * return in instance of the PDO object that connects to the SQLite database
     * @return \PDO
     */
    public function connect()
    {
        if ($this->pdo == null) {
            $this->pdo = new \PDO("sqlite:" . Config::PATH_TO_SQLITE_FILE);
        }
        return $this->pdo;
    }


}
