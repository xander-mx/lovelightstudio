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
<H1>¡ Hola {{ $reservation->customer }} un placer saludarte!</H1>
<h4>Para informarte que tus videos y fotos están listos ❤️</h4>
<h4>Esperamos sean de tu agrado</h4>
<h4>¡Con un copia y pega, tu familia, amigos y todos podrán verlas!</h4>
<h4>Estamos a tus ordenes para continuar con la selección de fotos para libros y canvas.</h4>
<h4>Quedamos a tus ordenes</h4>
<h4>Fotos:</h4>
<h4><a href="{{ $reservation->photos }}">Fotos Boda</a></h4>
<br/>
<h4>Video:</h4>
<h4><a href="{{ $reservation->video }}">Video Boda</a></h4>
<br/>
<h4>Código: <h3><strong>{{$reservation->code}}</strong></h3></h4>
</body>
</html>