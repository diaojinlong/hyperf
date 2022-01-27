<?php

declare(strict_types=1);

namespace App\Exception\Handler;

use App\Constants\ErrorCode;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Hyperf\Validation\ValidationException;
use App\Service\ResponseService;
use Hyperf\Di\Annotation\Inject;
use Throwable;

class ValidationExceptionHandler extends ExceptionHandler
{
    /**
     * @Inject
     * @var ResponseService
     */
    protected $response;

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation();
        /** @var \Hyperf\Validation\ValidationException $throwable */
        $body = $throwable->validator->errors()->first();
        return $this->response->error(ErrorCode::ERR_EXCEPTION_PARAMETER, $body);
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ValidationException;
    }
}
