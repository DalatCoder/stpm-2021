<?php

function fatal_handler()
{
    $errfile = "unknown file";
    $errstr = "shutdown";
    $errno = E_CORE_ERROR;
    $errline = 0;

    $error = error_get_last();

    if ($error !== NULL) {
        $errno = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr = $error["message"];

        echo format_error($errno, $errstr, $errfile, $errline);
    }
}

function format_error($errno, $errstr, $errfile, $errline)
{
    // $error_stack_trace = debug_backtrace(false);
    $error_stack_trace = explode("\n", $errstr);

    // Remove existing error message from PHP
    ob_clean();
    
    ob_start();
    ?>

    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Ninja Error Screen</title>

        <style>
            *,
            *::before,
            *::after {
                padding: 0;
                margin: 0;
                box-sizing: inherit;
            }

            body {
                box-sizing: border-box;
                font-family: sans-serif;
                line-height: 1.5;
            }

            .container {
                max-width: 1140px;
                margin: 0 auto;
                min-height: 100vh;
            }

            h1 {
                text-align: center;
                margin: 30px 0;
            }

            .error-message {
                margin: 15px 0;
            }

            .card {
                margin: 15px 0;
            }
            
            .text-center {
                text-align: center;
            }
        </style>
    </head>
    <body>

    <div class="container">
        <h1>Lỗi hệ thống</h1>
        
        <p class="error-message text-center"><strong>Liên hệ ngay để cùng sửa lỗi</strong></p>

        <p class="error-message">
            Tin nhắn lỗi: <strong><?= $error_stack_trace[0] ?></strong>
        </p>

        <h4>Truy vết các hàm đã thực thi đến thời điểm lỗi xảy ra</h4>566666        <?php for ($i = 2; $i < count($error_stack_trace) - 1; $i++): ?>
            <div class="card">
                <p><?= $error_stack_trace[$i] ?></p>
            </div>
        <?php endfor; ?>
    </div>

    </body>
    </html>

    <?php
    return ob_get_clean();
}

register_shutdown_function('fatal_handler');
