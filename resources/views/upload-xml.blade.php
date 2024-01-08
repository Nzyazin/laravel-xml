<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Загрузка XML</title>
</head>
<body>
    <h1>Загрузка XML</h1>

    <form action="{{ route('upload-xml') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="xmlFile" accept=".xml">
        <button type="submit">Загрузить XML</button>
    </form>
</body>
</html>