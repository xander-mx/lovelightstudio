<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <H1>¡ Hola ! </H1>
    <h4>Tienes una  cita  agendada a continuaciòn te enviaremos la informacion de esta</h4>
    <h4>Lugar de sesión: {{ $reservation->center->name }}</h4>
    <h4>Nombre del cliente: {{ $reservation->client->name}} {{ $reservation->client->lastname}}</h4>
    <h4>Número de habitación: {{ $reservation->num_habitacion}}</h4>
    <h4>Pax: {{ $reservation->pax}}</h4>
    <h4>Fecha de sesión:{{ date('d/F/Y', strtotime($reservation->date)) }}  {{ $reservation->schedule->hour }}:{{  $reservation->schedule->minute  }}</h4>
    <h4> Recuerda llegar 30 min antes</h4>
</body>
</html>