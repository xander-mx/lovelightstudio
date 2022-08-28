<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<h4>¡Hola! </h4>
<h4>Tiene una reserva con Love Light Studio el día  {{ date('d/F/Y', strtotime($reserva->date)) }}  {{ $reserva->schedule->hour }}:{{  $reserva->schedule->minute  }} en el hotel {{ $reserva->center->name }}</h4>
<h4>¡Gracias!</h4>
<h4> Fecha de sesión : </strong>{{ date('d/F/Y', strtotime($reserva->date)) }}  {{ $reserva->schedule->hour }}:{{  $reserva->schedule->minute  }}</h4>
<h4> Nombre de cliente : {{ $reserva->customer }}</h4>
<h4> Pax : {{ $reserva->pax }}</h4>
<h4> Teléfono : {{ $reserva->phone }}</h4>
<h4> Whatsapp : <a href="https://api.whatsapp.com/send?phone={{ str_replace('+','',$reserva->phone) }}">{{ $reserva->phone }}</a></h4>
</body>
</html>