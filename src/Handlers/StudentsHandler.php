<?php

require_once __DIR__ . '/BaseHandler.php';

class StudentsHandler extends BaseHandler
{
    protected static function getModelClass(): string
    {
        return StudentModel::class;
    }

    protected function fillModelWithRequestData(BaseModel $model, array $requestData): void
    {

        if (!isset($requestData['name'])) {
            throw new \InvalidArgumentException("'name' is required.");
        }
        if (!is_string($requestData['name']) || mb_strlen(trim($requestData['name'])) < 3) {
            throw new \InvalidArgumentException("'name' must be a string with at least 3 characters.");
        }

        if (!isset($requestData['age'])) {
            throw new \InvalidArgumentException("'age' is required.");
        }
        if (!is_numeric($requestData['age']) || (int)$requestData['age'] <= 15) {
            throw new \InvalidArgumentException("'age' must be a number greater than 15.");
        }

        $model->setName(trim($requestData['name']));
        $model->setAge((int)$requestData['age']);
    }
}