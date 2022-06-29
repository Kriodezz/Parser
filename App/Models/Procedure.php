<?php

namespace App\Models;

use App\Services\Db;

class Procedure
{
    protected int $number;
    protected int $oosNumber;
    protected string $linkProcedure;
    protected string $email;
    protected array $documents;
    protected static string $table = 'procedures';

    /**
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    /**
     * @param int $oosNumber
     */
    public function setOosNumber(int $oosNumber): void
    {
        $this->oosNumber = $oosNumber;
    }

    /**
     * @param string $linkProcedure
     */
    public function setLinkProcedure(string $linkProcedure): void
    {
        $this->linkProcedure = $linkProcedure;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param array $documents
     */
    public function setDocuments(array $documents): void
    {
        $this->documents = $documents;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @return int
     */
    public function getOosNumber(): int
    {
        return $this->oosNumber;
    }

    /**
     * @return string
     */
    public function getLinkProcedure(): string
    {
        return $this->linkProcedure;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function getDocuments(): array
    {
        return $this->documents;
    }

    public function save(): void
    {
        $sql = 'INSERT INTO ' . static::$table . ' (number, oos_number, link_procedure, email, documents)
                VALUES (:number, :oos_number, :link_procedure, :email, :documents)';

        $params = [
            ':number' => $this->number,
        ':oos_number' => $this->oosNumber,
        ':link_procedure' => $this->linkProcedure,
        ':email' => $this->email,
        ':documents' => json_encode($this->documents)
        ];

        $db = Db::getInstance();
        $db->execute($sql, $params);
    }
}
