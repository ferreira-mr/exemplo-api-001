<?php

require_once __DIR__ . '/BaseModel.php';

class StudentModel extends BaseModel
{
    protected ?int $id;
    protected string $name;
    protected int $age;

    public function __construct(?int $id, string $name, int $age)
    {
        $this->id = $id;
        $this->name = $name;
        $this->age = $age;
    }

    protected static function getTableName(): string
    {
        return 'students';
    }

    protected static function fromDatabaseRow(array $row): static
    {
        return new static($row['id'], $row['name'], $row['age']);
    }

    protected function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setAge(int $age): void
    {
        $this->age = (int)$age;
    }
}