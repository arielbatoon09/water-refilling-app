<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function response($status, $message){
        return $this->responseFunction($status, $message);
    }

    private function responseFunction($status, $message)
    {
        return [
            'status' => $status,
            'message' => $message,
        ];
    }
}
