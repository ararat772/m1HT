<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\AppException;
use App\Kernel;
use App\Trait\ResponseWithJsonTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

class BaseJsonErrorController extends AbstractController
{
    use ResponseWithJsonTrait;

    public function __construct(
        private readonly Kernel $kernel,
    ) {
    }

    public function show(Throwable $exception): JsonResponse
    {
        $context = [
            'code'    => $exception->getCode(),
            'message' => $exception instanceof HttpException
                ? $exception->getMessage()
                : Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
        ];

        if ($exception instanceof HttpException) {
            $previousException = $exception->getPrevious();
            if ($previousException instanceof ValidationFailedException) {
                $context = $this->buildValidationFailedContext($previousException);
            }

            return $this->traceableError($exception, $context, $exception->getStatusCode());
        }

        return $this->traceableError($exception, $context);
    }

    private function traceableError(
        Throwable $exception,
        array $context,
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse {
        if ($this->kernel->isDebug()) {
            $context['reason'] = $exception instanceof AppException
                ? $exception->getReason()
                : $exception->getMessage();

            $context['trace'] = $exception->getTrace();
        }

        return $this->error($status, $context);
    }

    private function buildValidationFailedContext(ValidationFailedException $exception): array
    {
        $violations = [];

        foreach ($exception->getViolations() as $violation) {
            $violations[] = [
                'name'    => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }

        return [
            'code'    => 0,
            'message' => 'Validation failed.',
            'context' => $violations
        ];
    }
}
