<?php

namespace App\Exception;

use App\Constants\ErrorCode;
use Hyperf\Server\Exception\ServerException;
use Throwable;

class BusinessException extends ServerException
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if (is_integer($message) && $code === 0) {
            $code = $message;
            $message = ErrorCode::getMessage($code);
        }
        parent::__construct($message, $code, $previous);
    }
}