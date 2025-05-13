<?php

abstract class BaseModel
{
    protected static ?PDO $pdo = null;

    public static function setConnection(PDO $pdo): void
    {
        self::$pdo = $pdo;
    }

    protected static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            throw new \Exception("Database connection not set. Call BaseModel::setConnection() first.");
        }
        return self::$pdo;
    }

    abstract protected static function getTableName(): string;

    abstract protected static function fromDatabaseRow(array $row): static;

    protected function toDatabaseData(): array
    {
        $data = get_object_vars($this);
        return $data;
    }

    abstract protected function setId(?int $id): void;

    abstract public function getId(): ?int;

    public function toArray(): array
    {
        return $this->toDatabaseData();
    }

    public static function all(): array
    {
        $pdo = self::getConnection();
        $tableName = static::getTableName();

        $stmt = $pdo->prepare("SELECT * FROM {$tableName}");
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $items = [];
        foreach ($results as $row) {
            $items[] = static::fromDatabaseRow($row);
        }

        return $items;
    }

    public static function find(int $id): ?static
    {
        $pdo = self::getConnection();
        $tableName = static::getTableName();

        $stmt = $pdo->prepare("SELECT * FROM {$tableName} WHERE id = ?");
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return static::fromDatabaseRow($row);
        }

        return null;
    }

    public function save(): bool
    {
        $pdo = self::getConnection();
        $tableName = static::getTableName();
        $data = $this->toDatabaseData();

        $id = $this->getId();
        if ($id === null && isset($data['id'])) {
            unset($data['id']);
        }

        if ($id === null) {
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            $values = array_values($data);

            $sql = "INSERT INTO {$tableName} ({$columns}) VALUES ({$placeholders})";
            $stmt = $pdo->prepare($sql);
            $success = $stmt->execute($values);

            if ($success) {
                $this->setId((int)$pdo->lastInsertId());
            }

            return $success;

        } else {
            $updateParts = [];
            $updateValuesForSet = [];
            foreach ($data as $key => $value) {
                if ($key !== 'id') {
                    $updateParts[] = "{$key} = ?";
                    $updateValuesForSet[] = $value;
                }
            }

            if (empty($updateParts)) {
                return true;
            }

            $updateString = implode(', ', $updateParts);
            $updateValuesForSet[] = $id;

            $sql = "UPDATE {$tableName} SET {$updateString} WHERE id = ?";
            $stmt = $pdo->prepare($sql);

            return $stmt->execute($updateValuesForSet);
        }
    }

    public function delete(): bool
    {
        $pdo = self::getConnection();
        $tableName = static::getTableName();

        if ($this->getId() === null) {
            return false;
        }

        $sql = "DELETE FROM {$tableName} WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([$this->getId()]);

        if ($success) {
            $this->setId(null);
        }

        return $success;
    }
}