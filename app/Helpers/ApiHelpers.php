<?php
namespace App\Helpers;

class ApiHelpers {
    public function createApiResponse($error, $status, $message, $content) {
        $result = [];

        if($error) {
            $result['success'] = false;
            $result['status'] = $status;
            $result['message'] = $message;
        }
        else {
            $result['success'] = true;
            $result['status'] = $status;
            if($content == null) {
                $result['message'] = $message;
            }
            else {
                $result['data'] = $content;
            }
        }
    }
}