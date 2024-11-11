<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Registry;

use PDO;
use Ubeliakou\OneTimeOperationSdk\Registry\Exception\RegistryExceptionFactory;

class PdoRegistry implements RegistryInterface
{
    private PDO $connection;
    private string $tableName;

    public function __construct(string $tableName, PDO $connection)
    {
        $this->tableName = $tableName;
        $this->connection = $connection;
    }

    public function ensureTableExists(): void
    {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS {$this->tableName} (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        operation VARCHAR(14) NOT NULL
                    )";
            $this->connection->exec($sql);
        } catch (\Throwable $e) {
            throw RegistryExceptionFactory::createTableCreationException($e);
        }
    }

    /**
     * @return string[]
     */
    public function getExecuted(): array
    {
        try {
            $sql = "SELECT operation FROM {$this->tableName}";
            $stmt = $this->connection->query($sql);
            if ($stmt === false) {
                throw RegistryExceptionFactory::createQueryException($this->connection->errorCode());
            }

            $results = $stmt->fetchAll(PDO::FETCH_COLUMN);

            return $results ?: [];
        } catch (\Throwable $e) {
            throw RegistryExceptionFactory::createFetchTimestampException($e);
        }
    }

    public function markAsExecuted(string $timestamp): void
    {
        try {
            $sql = "INSERT INTO {$this->tableName} (operation) VALUES (?)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$timestamp]);
        } catch (\Throwable $e) {
            throw RegistryExceptionFactory::createUpdateTimestampException($e);
        }
    }
}