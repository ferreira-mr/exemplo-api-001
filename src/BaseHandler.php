<?php

require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/BaseModel.php';

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
        $id = $this->request->getParams('id');
        $modelClass = static::getModelClass();

        if ($id !== null && $id !== '') {
            $item = $modelClass::find((int)$id);

            if ($item) {
                $responseData = $item->toArray();
                $this->response->sendSuccess($responseData, 200);
            } else {
                $this->response->sendError("Item with ID '$id' not found for resource '" . $this->request->getResource() . "'.", 404);
            }

        } else {
            $items = $modelClass::all();

            $responseData = [];
            foreach ($items as $item) {
                $responseData[] = $item->toArray();
            }

            $this->response->sendSuccess($responseData, 200);
        }
    }

    public function post(): void
    {
        $requestData = $this->request->getAllPostParams();
        $modelClass = static::getModelClass();

        try {
            $newItem = new $modelClass(null, '', 0);

            $this->fillModelWithRequestData($newItem, $requestData);

            $success = $newItem->save();

            if ($success) {
                $responseData = $newItem->toArray();
                $finalResponse = $responseData;
                $finalResponse['status'] = 'success';
                $finalResponse['message'] = 'Item created successfully.';

                $this->response->sendSuccess($finalResponse, 201);
            } else {
                $this->response->sendError("Failed to create item for resource '" . $this->request->getResource() . "'.", 500);
            }
        } catch (\InvalidArgumentException $e) {
            $this->response->sendError("Invalid input data: " . $e->getMessage(), 400);
        } catch (\Exception $e) {
            $this->response->sendError("An error occurred during creation: " . $e->getMessage(), 500);
        }
    }

    public function put(): void
    {
        $requestData = $this->request->getAllPostParams();
        $id = $this->request->getParams('id');
        $modelClass = static::getModelClass();

        if ($id === null || $id === '') {
            $this->response->sendError(
                "Missing required parameter: Item ID in URL is required for update.",
                400
            );
            return;
        }

        $itemToUpdate = $modelClass::find((int)$id);

        if (!$itemToUpdate) {
            $this->response->sendError("Item with ID '$id' not found for resource '" . $this->request->getResource() . "'.", 404);
            return;
        }

        try {
            $this->fillModelWithRequestData($itemToUpdate, $requestData);

            $success = $itemToUpdate->save();

            if ($success) {
                $responseData = $itemToUpdate->toArray();
                $finalResponse = $responseData;
                $finalResponse['status'] = 'success';
                $finalResponse['message'] = 'Item updated successfully.';

                $this->response->sendSuccess($finalResponse, 200);
            } else {
                $this->response->sendError("Failed to update item with ID '$id' for resource '" . $this->request->getResource() . "'.", 500);
            }
        } catch (\InvalidArgumentException $e) {
            $this->response->sendError("Invalid input data: " . $e->getMessage(), 400);
        } catch (\Exception $e) {
            $this->response->sendError("An error occurred during update: " . $e->getMessage(), 500);
        }
    }

    public function delete(): void
    {
        $id = $this->request->getParams('id');
        $modelClass = static::getModelClass();

        if ($id === null || $id === '') {
            $this->response->sendError(
                "Missing required parameter: Item ID in URL is required for delete.",
                400
            );
            return;
        }

        $itemToDelete = $modelClass::find((int)$id);

        if (!$itemToDelete) {
            $this->response->sendError("Item with ID '$id' not found for resource '" . $this->request->getResource() . "'.", 404);
            return;
        }

        $success = $itemToDelete->delete();

        if ($success) {
            $this->response->sendSuccess([], 204);
        } else {
            $this->response->sendError("Failed to delete item with ID '$id' for resource '" . $this->request->getResource() . "'.", 500);
        }
    }

    public function execute(): void
    {
        $method = $this->request->getMethod();
        if (method_exists($this, $method)) {
            call_user_func([$this, $method]);
        } else {
            $this->response->sendError(
                "Method '$method' not supported for this resource.",
                405
            );
        }
    }
}