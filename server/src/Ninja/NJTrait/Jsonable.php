<?php

namespace Ninja\NJTrait;

trait Jsonable
{
    public function response_json($data, $status_code = 200)
    {
        // Remove any string that could create an invalid JSON
        // such as PHP Notice, Warning, logs,...
        ob_clean();

        // This will clean up any previously added headers, to start clean
        header_remove();

        // Set the content type to JSON and charset
        header('Content-Type: application/json; charset=utf-8');

        $json_response = json_encode($data);

        http_response_code($status_code);

        if ($json_response === false) {
            $json_response = json_encode([
                'status' => 'error',
                'error' => json_last_error_msg()
            ]);
            error_log(print_r(json_last_error_msg(), true));

            http_response_code(500);
        }

        echo $json_response;

        exit();
    }

    public function parse_json_from_request()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}
