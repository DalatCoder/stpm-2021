<?php
    $title = $title ?? 'Ninja Framework Screen';
    $error_message = $error_message ?? '';
    $error_stack_trace = $error_stack_trace ?? [];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ninja Error Screen: <?= $title ?></title>

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
    </style>
</head>
<body>

<div class="container">
    <h1><?= $title ?></h1>

    <p class="error-message">
        Tin nhắn lỗi: <strong><?= $error_message ?></strong>
    </p>
    
    <h4>Truy vết các hàm đã thực thi đến thời điểm lỗi xảy ra</h4>
    <?php foreach ($error_stack_trace as $item): ?>
        <div class="card">
            <p>Tập tin: <?= $item['file'] ?? 'N/A' ?></p>
            <p>Dòng lỗi: <?= $item['line'] ?? 'N/A' ?></p>
            <p>Tên lớp: <strong><?= $item['class'] ?? 'N/A' ?></strong></p>
            <p>Tên phương thức: <strong><?= $item['function'] ?? 'N/A' ?></strong></p>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
