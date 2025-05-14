<?php


require_once __DIR__ . '/BaseHandler.php';
require_once __DIR__ . '/../Models/ClassroomModel.php';
require_once __DIR__ . '/../Models/BaseModel.php';

class ClassroomHandler extends BaseHandler
{
    protected static function getModelClass(): string
    {
        return ClassroomModel::class;
    }

    protected function fillModelWithRequestData(BaseModel $model, array $requestData): void
    {
        if (!isset($requestData['name'])) {
            throw new \InvalidArgumentException("'name' is required.");
        }
        $name = trim($requestData['name']);
        if (!is_string($name) || mb_strlen($name) < 3) {
            throw new \InvalidArgumentException("'name' must be a string with at least 3 characters.");
        }
        $model->setName($name);


        if (!isset($requestData['capacity'])) {
            throw new \InvalidArgumentException("'capacity' is required.");
        }
        $capacity = $requestData['capacity'];
        if (!is_numeric($capacity) || (int)$capacity <= 0) {
            throw new \InvalidArgumentException("'capacity' must be a positive number.");
        }
        $model->setCapacity((int)$capacity);
    }
}