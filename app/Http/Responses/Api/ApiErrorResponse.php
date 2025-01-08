<?php

namespace App\Http\Responses\Api;

use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\Storage\EntryModel;

class ApiErrorResponse implements Responsable
{
    public function __construct(
        private readonly ?Exception $exception,
        private string $message = 'Something went wrong',
        private int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        private readonly array $headers = [],
        private readonly int $options = 0
    ) {}

    public function toResponse($request): JsonResponse|\Symfony\Component\HttpFoundation\Response
    {

        if (! is_null($this->exception)) {
            if (method_exists($this->exception, 'getStatus')) {
                $this->statusCode = $this->exception->getStatus();
                $this->message = $this->exception->getMessage();
            }
        }

        $response = ['message' => __($this->message)];

        if (! empty($this->exception) && $this->statusCode == Response::HTTP_INTERNAL_SERVER_ERROR && config('app.debug')) {
            $response['debug'] = [
                'message' => $this->exception->getMessage(),
                'file' => $this->exception->getFile(),
                'line' => $this->exception->getLine(),
                'trace' => $this->exception->getTrace(),
            ];
        }

        return response()->json(
            $response,
            $this->statusCode,
            $this->headers,
            $this->options
        );
    }
}
