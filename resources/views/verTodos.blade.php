<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .cliente{
            margin: 0 auto;
            margin-top: 1em;
            background-color: #2a9055;
            width: 200px;
        }
    </style>
</head>
<body>
<div>
    <h1>Saludos</h1>
</div>
    @foreach($clientes as $client)
        <div class="cliente">
            {{ $client->id }}: {{ $client->name }}
        </div>
    @endforeach
</body>
</html>
