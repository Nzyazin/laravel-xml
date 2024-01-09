<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Загрузка EXCEL</title>
</head>
<body>
    <h1>Загрузка EXCEL</h1>

    <a href="{{ route('download-xlsx', ['filename' => 'example.xlsx']) }}">Скачать файл</a>
    
</body>
</html>