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
<H1>¡ Hola, un placer saludarte!</H1>
<h4>Para informarte que el video y fotos de la boda {{ $event->customer }} estan listos ❤️</h4>
<h4>Se encuentran en los siguientes links:</h4>
@isset($event->video)
<h4>Video:</h4>
<h4><a href="{{ $event->video }}">Click para ver el video de la Boda</a></h4>
@endisset
@isset($event->photos)
<h4>Fotos:</h4>
<h4><a href="{{ $event->photos }}">Click para ver fotos de la boda</a></h4>
@endisset
<h4>Compartelos al cliente por medio de este link</h4>
<h4> Whatsapp : <a href="https://api.whatsapp.com/send?phone={{ str_replace('+','',$reserva->phone) }}">{{ $reserva->phone }}</a></h4>
</body>
</html>