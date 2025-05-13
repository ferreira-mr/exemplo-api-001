<?php

require_once __DIR__ . '/../Infrastructure/Request.php';
require_once __DIR__ . '/../Infrastructure/Response.php';

abstract class BaseHandler
{
    protected Request $request;
    protected Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    abstract protected static function getModelClass(): string;

    abstract protected function fillModelWithRequestData(BaseModel $model, array $requestData): void;

    public function get(): void
    {
        try {
            $id = $this->request->getParams('id');
            $modelClass = static::getModelClass();

            if ($id !== null && $id !== '') {
                $item = $modelClass::find((int)$id);
                if ($item) {
                    $this->response->sendSuccess($item->toArray(), 200);
                } else {
                    $this->response->sendNotFound(ucfirst($this->request->getResource()), $id);
                }
            } else {
                $items = $modelClass::all();
                $responseData = [];
                foreach ($items as $item) {
                    $responseData[] = $item->toArray();
                }
                $this->response->sendSuccess($responseData, 200);
            }
        } catch (\Exception $e) {
            $this->response->sendError("An error occurred.", 500, $e->getMessage());
        }
    }

    public function post(): void
    {
        try {
            $requestData = $this->request->getAllPostParams();
            $modelClass = static::getModelClass();

            $newItem = new $modelClass(null, '', 0);

            $this->fillModelWithRequestData($newItem, $requestData);
            $newItem->save();

            $this->response->sendSuccess($newItem->toArray(), 201);

        } catch (\Exception $e) {
            $this->response->sendError("An error occurred during creation.", 500, $e->getMessage());
        }
    }

    public function put(): void
    {
        try {
            $id = $this->request->getParams('id');
            $modelClass = static::getModelClass();

            $itemToUpdate = $modelClass::find((int)$id);

            if (!$itemToUpdate) {
                $this->response->sendNotFound(ucfirst($this->request->getResource()), $id);
                return;
            }

            $requestData = $this->request->getAllPostParams();
            $this->fillModelWithRequestData($itemToUpdate, $requestData);
            $itemToUpdate->save();

            $this->response->sendSuccess($itemToUpdate->toArray(), 200);

        } catch (\Exception $e) {
            $this->response->sendError("An error occurred during update.", 500, $e->getMessage());
        }
    }

    public function delete(): void
    {
        try {
            $id = $this->request->getParams('id');
            $modelClass = static::getModelClass();

            $itemToDelete = $modelClass::find((int)$id);

            if (!$itemToDelete) {
                $this->response->sendNotFound(ucfirst($this->request->getResource()), $id);
                return;
            }

            $itemToDelete->delete();
            $this->response->sendSuccess([], 204); // 204 No Content

        } catch (\Exception $e) {
            $this->response->sendError("An error occurred during deletion.", 500, $e->getMessage());
        }
    }

    public function execute(): void
    {
        $method = $this->request->getMethod();
        if (method_exists($this, $method)) {
            call_user_func([$this, $method]);
        } else {
            $this->response->sendError(
                "Method '" . strtoupper($method) . "' not supported for this resource.",
                405 // Method Not Allowed
            );
        }
    }
}