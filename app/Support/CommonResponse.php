<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class CommonResponse
{
    public const STATUS_CODE_SUCCESS = 200;
    public const STATUS_CODE_FAILED  = 422;
    public const STATUS_CODE_ERROR   = 500;
    public const STATUS_CODE_UNAUTH  = 401;
    public const STATUS_CODE_FORBID  = 403;

    public bool $status           = true;
    public int $status_code       = self::STATUS_CODE_SUCCESS;
    public string $status_message = 'Success';
    public mixed $data            = null;

    public function success(string $message = 'Success', mixed $data = null, int $code = self::STATUS_CODE_SUCCESS): self
    {
        $this->status         = true;
        $this->status_code    = $code;
        $this->status_message = $message;
        $this->data           = $data;
        return $this;
    }

    public function fail(string $message = 'Failed', mixed $data = null, int $code = self::STATUS_CODE_FAILED): self
    {
        $this->status         = false;
        $this->status_code    = $code;
        $this->status_message = $message;
        $this->data           = $data;
        return $this;
    }

    public function commonApiResponse(): JsonResponse
    {
        return response()->json([
            'status'         => $this->status,
            'status_code'    => $this->status_code,
            'status_message' => $this->status_message,
            'data'           => $this->data,
        ], $this->status_code);
    }
}
