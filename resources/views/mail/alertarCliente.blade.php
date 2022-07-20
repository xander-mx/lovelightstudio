<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
     <img src="{{ asset('logo/love-light-studio-logo copia.jpg') }}" alt="" width="200px">
     {{-- {{ dd($reservation,date('Y-m-d', strtotime($reservation->date)) ,$reservation->schedule ) }} --}}
        <H1>¡ Hola {{ $reservation->client->name }} !</H1>
        <p>¡Tu sesión fotográfica se ha agendado con éxito!</p>
        <p><strong>Lugar de sesion:</strong> {{ $reservation->center->name }}</p>
        <p><strong>Fecha de sesion:</strong>{{ date('d/F/Y', strtotime($reservation->date)) }}  {{ $reservation->schedule->hour }}:{{  $reservation->schedule->minute  }}</p>

        @if ($reservation->center->extra != null)
            <p><strong>Info extra : </strong>{{ $reservation->center->extra }}</p>
        @endif
        <p>¡Gracias por ser parte de Love Light Studio!</p>
</body>
</html>