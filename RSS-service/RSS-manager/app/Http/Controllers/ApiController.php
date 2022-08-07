<?php

namespace App\Http\Controllers;

use Illuminate\Support\MessageBag;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    /**
     * @var int
     */
    private $statusCode;
    /**
     * @var int
     */
    private $code;
    /**
     * @var string
     */
    private $message;

    /**
     * return object result in returning one item response
     * @param $data
     * @return mixed
     */
    public function respondItemResult($data)
    {
        array_walk_recursive($data, function(&$value)
        {
            $value = is_string($value) ? rtrim($value) : $value;
        });
        return $this
            ->setStatusCode(Response::HTTP_OK)
            ->respond([
                'data' => $data
            ]);
    }

    /**
     * return array result in pagination format response
     * @param $data
     * @param $total_count
     * @return mixed
     */
    public function respondArrayResult($data)
    {
        $total_count = $data['count'] ?? count($data);
        unset($data['count']);
        array_walk_recursive($data, function(&$value)
            {
                $value = is_string($value) ? rtrim($value) : $value;
            });
        return $this
            ->setStatusCode(Response::HTTP_OK)
            ->respond([
                'odata.count' => $total_count,
                'value' => $data
            ]);
    }

    /**
     * @param $message
     * @return mixed
     */
    public function respondNoFound($message)
    {
        return $this
            ->setStatusCode(Response::HTTP_NOT_FOUND)
            ->setMessage($message)
            ->respondWithError();
    }

    /**
     * @param $message
     * @param $statusCode
     * @param $code
     * @return mixed
     */
    public function respondError($message, $statusCode, $code)
    {
        return $this
            ->setCode($code)
            ->setStatusCode($statusCode)
            ->setMessage($message)
            ->respondWithError();
    }

    /**
     * @param $code int
     * @param $fields MessageBag
     * @param string $message
     * @return mixed
     */
    public function respondInvalidParams($code, $fields, $message = '')
    {
        if (!$message) {
            $message = trans('messages.custom.'.Response::HTTP_BAD_REQUEST);
        }

        return $this
            ->setCode($code)
            ->setStatusCode(Response::HTTP_BAD_REQUEST)
            ->setMessage($message)
            ->respondWithParamsError($fields);
    }

    /**
     * @param $message
     * @param $code
     * @return mixed
     */
    public function respondInternalError($message, $code)
    {
        return $this
            ->setCode($code)
            ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->setMessage($message)
            ->respondWithError();
    }

    /**
     * @param $returnData array can be id(int/string) or an object you like(array)
     * @param string $message
     * @return mixed
     */
    public function respondSuccessCreate($returnData, $message = '')
    {
        if (!$message) {
            $message = trans('messages.custom.'.Response::HTTP_CREATED);
        }

        return $this
            ->setStatusCode(Response::HTTP_CREATED)
            ->setMessage($message)
            ->respondWithSuccess($returnData);
    }

    /**
     * @param $id
     * @param string $message
     * @return mixed
     */
    public function respondSuccessDelete($id, $message = '')
    {
        if (!$message) {
            $message = trans('messages.custom.success.delete');
        }

        return $this
            ->setStatusCode(Response::HTTP_OK)
            ->setMessage($message)
            ->respondWithSuccess($id);
    }

    /**
     * @param $id
     * @param string $message
     * @return mixed
     */
    public function respondSuccessUpdate($id, $message = '')
    {
        if (!$message) {
            $message = trans('messages.custom.success.update');
        }

        return $this
            ->setStatusCode(Response::HTTP_OK)
            ->setMessage($message)
            ->respondWithSuccess($id);
    }

    /**
     * @return int
     */
    private function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return int
     */
    private function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    private function getMessage()
    {
        return $this->message;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    private function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param $Code
     * @return $this
     */
    private function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param $message
     * @return $this
     */
    private function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return mixed
     */
    private function respondWithError()
    {
        return $this->respond([
            'status' => $this->getStatusCode(),
            'message' => $this->getMessage(),
            'code'  => $this->getCode()
        ]);
    }

    /**
     * @param $returnObject
     * @return mixed
     */
    private function respondWithSuccess($returnObject)
    {
        if (is_string($returnObject) || is_int($returnObject)) {
            $returnObject = [
                'id' => $returnObject
            ];
        } elseif (is_array($returnObject) || is_object($returnObject)) {
            $returnObject = [
                'data' => $returnObject
            ];
        } elseif ($returnObject == null) {
            $returnObject = [];
        }

        return $this->respond(array_merge($returnObject, [
            'message' => $this->getMessage()
        ]));
    }

    /**
     * @param $fields
     * @return mixed
     */
    private function respondWithParamsError($fields)
    {
        return $this->respond([
            'status' => $this->getStatusCode(),
            'message' => $this->getMessage(),
            'fields' => $fields,
            'code'  => $this->getCode()
        ]);
    }

    /**
     * @param $data
     * @param array $headers
     * @return mixed
     */
    private function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }
}
