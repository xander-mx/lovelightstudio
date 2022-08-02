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
     <H1>¡ Hola {{ $reservation->customer }} !</H1>
     <h4>¡Tu sesión fotográfica se ha agendado con éxito!</h4>
     <h4>Lugar de sesión : {{ $reservation->center->name }}</h4>
     <p><h4>Fecha de sesión : {{ date('d/F/Y', strtotime($reservation->date)) }}  {{ $reservation->schedule->hour }}:{{  $reservation->schedule->minute  }}</h4></p>

        @if ($reservation->center->extra != null)
            <p><h4>Ubicación : {{ $reservation->center->extra }}</h4></p>
        @endif
     <p><h3>¡Gracias por ser parte de Love Light Studio!</h3></p>
</body>
</html>