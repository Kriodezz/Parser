<?php

namespace App\Services;

class Db
{
    protected static $instance;
    protected \PDO $dbh;

    private function __construct()
    {
        $dbOptions = (require __DIR__ . '/../setting.php')['db'];

        $this->dbh = new \PDO(
            'mysql:host=' . $dbOptions['host'] . ';dbname=' . $dbOptions['dbname'],
            $dbOptions['user'],
            $dbOptions['password']
        );
        $this->dbh->exec('SET NAMES UTF8');
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function execute(string $sql, array $params = [])
    {
        $sth = $this->dbh->prepare($sql);
        return $sth->execute($params);
    }
}
