<?php

namespace App\Libraries;

class ApiResponse
{
    public function __construct()
    {
        // check if the api is active
        if (!API_ACTIVE) {
            echo $this->_api_not_active();
            die(1);
        }
    }

    public function validate_request($method)
    {
        // validate request method
        if ($_SERVER['REQUEST_METHOD'] != strtoupper($method)) {
            echo $this->set_response_error(400, 'invalid request method');
            die(1);
        }
    }

    public function set_response($status = 200, $message = 'success', $data = [])
    {
        // api generic success response
        return json_encode(
            [
                'status' => $status,
                'message' => $message,
                'info' => [
                    'version' => API_VERSION,
                    'datetime' => date('Y-m-d H:i:s'),
                    'timestamp' => time()
                ],
                'data' => $data,
            ],
            JSON_PRETTY_PRINT
        );
    }

    public function set_response_error($status = 404, $message = 'error', $errors = [])
    {
        // api generic error response
        return json_encode(
            [
                'status' => $status,
                'message' => $message,
                'info' => [
                    'version' => API_VERSION,
                    'datetime' => date('Y-m-d H:i:s'),
                    'timestamp' => time()
                ],
                'errors' => $errors
            ],
            JSON_PRETTY_PRINT
        );
    }

    private function _api_not_active()
    {
        return json_encode(
            [
                'status' => 400,
                'message' => 'API is not active',
                'info' => [
                    'version' => API_VERSION,
                    'datetime' => date('Y-m-d H:i:s'),
                    'timestamp' => time()
                ],
                'data' => []
            ],
            JSON_PRETTY_PRINT
        );
    }
}
