<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h4>Estimada familia</h4>
    <h4>¡Muchas gracias por permitirnos ser parte de su experiencia en  {{ $reserva->center->name }} !</h4>
    <h4>A continuación encontrarán su link y su password para que pueda ver sus fotos y compartirlas de manera sencilla:</h4>
    
    <h4><a href="{{ $reserva->receptioninfo->linkPagina }}" target="_blank" rel="noopener noreferrer">{{ $reservainfo->linkPagina }}</a></h4>

    <h4>Tu password es: {{ $reservainfo->codigoFoto }} </h4>

    <h4>Por favor, cualquier cosa que necesiten no duden en contactarnos, pues para nosotros, ¡es un placer atenderlos!</h4>
    <h4>Si les gustaron sus fotos , ¿nos podrían regalar un Like?</h4>
    <h4><a href="https://www.facebook.com/LLSmexico/" target="_blank" rel="noopener noreferrer">https://www.facebook.com/LLSmexico/</a></h4>
    <h4><a href="https://www.instagram.com/LLSmexico" target="_blank" rel="noopener noreferrer">https://www.instagram.com/LLSmexico</a></h4>
    <h4>¡Mándanos un whatsapp!</h4>

    <h4><a href="http://bit.ly/WHTSLLS" target="_blank" rel="noopener noreferrer">http://bit.ly/WHTSLLS</a></h4>
    <h4>¡Gracias!</h4>

</body>
</html>