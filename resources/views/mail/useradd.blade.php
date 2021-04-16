<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>你好呀</title>
    <style>
        body {
            font-size: 14px;
        }
        div {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div>您的账号：{{ $userModel->username }}</div>
    <div>您的密码：{{ $pwd }}</div>
</body>
</html>
