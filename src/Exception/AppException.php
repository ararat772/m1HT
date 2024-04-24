<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AppException extends HttpException
{
    public function __construct(
        protected mixed $reason = null,
        string $message = null,
        Throwable $previous = null,
        array $headers = [],
        int $code = 0,
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
    ) {
        parent::__construct(
            $statusCode,
            $message ?? Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
            $previous,
            $headers,
            $code,
        );
    }

    public function getReason(): mixed
    {
        return $this->reason;
    }
}
