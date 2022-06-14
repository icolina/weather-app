<?php

namespace App\Traits;

trait ResponseApi
{
    /**
     * Send any success response
     *
     * @param mixed  $data
     * @param string $message
     */
    public function success($data = [], $message = '')
    {
        return [
            'message' => $message,
            'error'   => false,
            'result'  => $data
        ];
    }

    /**
     * Send any error response
     *
     * @param string $message
     */
    public function error($message)
    {
        return [
            'message' => $message,
            'error'   => true,
        ];
    }
}
