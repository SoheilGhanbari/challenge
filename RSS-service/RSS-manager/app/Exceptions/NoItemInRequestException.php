<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class NoItemInRequestException extends Exception
{
    public function __construct()
    {
        parent::__construct();

        self::setCode(Response::HTTP_BAD_REQUEST);
        self::setMessage(trans('messages.custom.error.no_data'));
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
}
