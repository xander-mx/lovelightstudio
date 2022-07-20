<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <p>Estimada familia</p>
    <p>¡Muchas gracias por permitirnos ser parte de su experiencia en  {{ $reserva->center->name }} !</p>
    <p>Bajo éstas lineas, encontrarán su link y su password para que pueda ver nuevamente sus fotos y compartirlas de manera sencilla:</p>
    
    <p><a href="{{ $reserva->receptioninfo->linkPagina }}" target="_blank" rel="noopener noreferrer">{{ $reservainfo->linkPagina }}</a></p>

    <p>Tu password es: {{ $reservainfo->codigoFoto }} </p>
    {{-- {{ dd($reserva->receptioninfo,$reservainfo) }} --}}

    <p>Por favor, cualquier cosa que necesiten no duden en contactarnos, pues para nosotros, ¡es un placer atenderlos!</p>
    <p>Si les gustaron sus fotos , ¿nos podrían regalar un Like?</p>
    <p><a href="https://www.facebook.com/LLSmexico/" target="_blank" rel="noopener noreferrer">https://www.facebook.com/LLSmexico/</a></p>
    <p><a href="https://www.instagram.com/LLSmexico" target="_blank" rel="noopener noreferrer">https://www.instagram.com/LLSmexico</a></p>
    <p>¡Mándanos un whatsapp!</p>

    <p><a href="http://bit.ly/WHTSLLS" target="_blank" rel="noopener noreferrer">http://bit.ly/WHTSLLS</a></p>
    <p>¡Gracias!</p>

</body>
</html>