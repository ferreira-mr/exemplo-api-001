<?php

require_once __DIR__ . '/BaseHandler.php';
require_once __DIR__ . '/StudentModel.php';

class StudentsHandler extends BaseHandler
{
    protected static function getModelClass(): string
    {
        return StudentModel::class;
    }

    protected function fillModelWithRequestData(BaseModel $model, array $requestData): void
    {
        if (!$model instanceof StudentModel) {
            throw new \LogicException("Invalid model instance provided to StudentHandler::fillModelWithRequestData.");
        }

        if (!isset($requestData['name']) || !is_string($requestData['name']) || empty($requestData['name'])) {
            throw new \InvalidArgumentException("'name' is required and must be a non-empty string.");
        }
        if (!isset($requestData['age']) || !is_numeric($requestData['age']) || (int)$requestData['age'] <= 0) {
            throw new \InvalidArgumentException("'age' is required and must be a positive number.");
        }

        $model->setName($requestData['name']);
        $model->setAge((int)$requestData['age']);
    }
}