<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
   <p> Teléfono {{ $reserva->client->cellphone }}</p>
   <p><strong>Fecha de sesión:</strong>{{ date('d/F/Y', strtotime($reserva->date)) }}  {{ $reserva->schedule->hour }}:{{  $reserva->schedule->minute  }}</p>
   <p> Nombre cliente {{ $reserva->client->name }} {{ $reserva->client->lastname }}</p>

<p>¡Hola! </p>
<p>Tienen una reserva con Love Light Studio el día  <strong>{{ date('d/F/Y', strtotime($reserva->date)) }}  {{ $reserva->schedule->hour }}:{{  $reserva->schedule->minute  }}</strong> en el hotel <strong>{{ $reserva->center->name }}</strong></p>
<p>¡Gracias!</p>
</body>
</html>