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
<h4>Para informarte que tus videos y fotos están listos ❤️</h4>
<h4>Esperamos sean de tu agrado</h4>
<h4>¡Con un copia y pega, tu familia, amigos y todos podrán verlas!</h4>
<h4>Estamos a tus ordenes para continuar con la selección de fotos para libros y canvas.</h4>
<h4>Quedamos a tus ordenes</h4>
<h4>Fotos:</h4>
<h4><a href="{{ $event->photos }}">Click para ver fotos de la boda</a></h4>
<h4>Video:</h4>
<h4><a href="{{ $event->video }}">Click para ver el video de la Boda</a></h4>
<h4>Canvas y Libros:</h4>
<h4><a href="{{ URL::signedRoute('weddings.events.canvas',['id' => $event->id, 'code' => $event->code]) }}">Click para elegir fotos para canvas y libros</a></h4>
<h4>Código:</h4> <h2><strong>{{$event->code}}</strong></h2>
</body>
</html>