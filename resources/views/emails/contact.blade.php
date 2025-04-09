<!DOCTYPE html>
<html>
<head>
    <title>Nuevo mensaje de contacto</title>
</head>
<body>
<h2>Nuevo mensaje de contacto</h2>
<p><strong>Nombre:</strong> {{ $data['name'] }}</p>
<p><strong>Email:</strong> {{ $data['email'] }}</p>
<p><strong>Mensaje:</strong></p>
<p>{{ $data['message'] }}</p>
</body>
</html>
