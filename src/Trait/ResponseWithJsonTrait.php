<?php

namespace App\Trait;

use App\Helper\DateTimeHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

trait ResponseWithJsonTrait
{
    public function response(
        mixed $data = [],
        int $status = Response::HTTP_OK,
        array $context = ['groups' => 'json_base'],
        array $headers = []
    ): JsonResponse {
        return $this->json($data, $status, $headers, array_merge([
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            DateTimeNormalizer::FORMAT_KEY             => DateTimeHelper::FORMAT,
            'json_encode_options'                      => JsonResponse::DEFAULT_ENCODING_OPTIONS
                | JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
                | JSON_PRETTY_PRINT,
        ], $context));
    }

    public function error(
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR,
        array $context = []
    ): JsonResponse {
        return new JsonResponse($context, $status);
    }
}
