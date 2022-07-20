<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
     {{-- {{ dd($reservation,date('Y-m-d', strtotime($reservation->date)) ,$reservation->schedule ) }} --}}
        <H1>¡ Hola ! </H1>
        <p>Tienes una  cita  agendada correctamente a continuaciòn te enviaremos la informacion de esta</p>
        <p><strong>Lugar de sesión:</strong> {{ $reservation->center->name }}</p>
        <p><strong>Nombre del cliente:</strong> {{ $reservation->client->name}} {{ $reservation->client->lastname}}</p>
        <p><strong>Numero de habitación:</strong> {{ $reservation->num_habitacion}}</p>
        <p><strong>Pax:</strong> {{ $reservation->pax}}</p>
        <p><strong>Fecha de sesión:</strong>{{ date('d/F/Y', strtotime($reservation->date)) }}  {{ $reservation->schedule->hour }}:{{  $reservation->schedule->minute  }}</p>
        <p> Recuerda llegar 30 min antes</p>
</body>
</html>