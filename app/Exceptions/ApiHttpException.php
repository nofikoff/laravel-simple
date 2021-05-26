<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;


class ApiHttpException extends HttpResponseException
{
    public function __construct(int $code, $message, $data = null)
    {
        parent::__construct(\response(array_merge($data ?? [], [
            'success' => false,
            'message' => $message,
        ]), $code));
    }

}
