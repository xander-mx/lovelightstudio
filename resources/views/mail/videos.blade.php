<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<img src="{{ $message->embed(public_path().'/logo/love-light-studio-logo.jpg') }}" alt="Love Light Studio" width="200px">
<H1>¡ Hola {{ $event->customer }} un placer saludarte!</H1>
<h4>Para informarte que tu video esta listo ❤️</h4>
<h4>Esperamos sea de tu agrado</h4>
<h4>¡Con un copia y pega, tu familia, amigos y todos podrán verlas!</h4>
<h4>Quedamos a tus ordenes</h4>
<h4>Video:</h4>
<h4><a href="{{ $event->video }}">Click para ver el video de la Boda</a></h4>
</body>
</html>