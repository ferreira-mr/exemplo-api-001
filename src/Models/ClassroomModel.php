<?php

require_once __DIR__ . '/BaseModel.php';

class ClassroomModel extends BaseModel
{
    protected ?int $id;
    protected string $name;
    protected int $capacity;

    public function __construct(?int $id, string $name, int $capacity)
    {
        $this->id = $id;
        $this->name = $name;
        $this->capacity = $capacity;
    }

    protected static function getTableName(): string
    {
        return 'classrooms';
    }

    protected static function fromDatabaseRow(array $row): static
    {
        return new static($row['id'], $row['name'], $row['capacity']);
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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): void
    {
        $this->capacity = $capacity;
    }
}