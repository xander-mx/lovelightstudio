<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h4>¡Hola!</h4>
    <h4>Este es tu enlace para que puedan acceder a su galería de fotos </h4>
    <h4><a href="{{ $reserva->receptioninfo->linkPagina }}" target="_blank" rel="noopener noreferrer">{{ $reservainfo->linkPagina }}</a></h4>
    <h4>Password: {{ $reservainfo->codigoFoto }} </h4>
    <h4>Gracias por permitirnos ser parte de su experiencia en Love Light Studio ❤ </h4>
    <h4>Nos ayudaría mucho si nos das like en</h4>
    <h4><a href="https://www.facebook.com/LLSmexico/" target="_blank" rel="noopener noreferrer">https://www.facebook.com/LLSmexico/</a></h4>
    <h4><a href="https://www.instagram.com/LLSmexico" target="_blank" rel="noopener noreferrer">https://www.instagram.com/LLSmexico</a></h4>
    <h4>¡Qué tenga un excelente día! 😊</h4>

    <h4>Número de teléfono a enviar:</h4>
    <h4> @if ($reservainfo->telefono1 != null)
    <a href="https://api.whatsapp.com/send?phone={{ $reservainfo->telefono1 }}">{{ $reservainfo->telefono1 }}</a>     
    @endif
    @if ($reservainfo->telefono2 != null)
       <br> 
       <a href="https://api.whatsapp.com/send?phone={{ $reservainfo->telefono2 }}"> {{ $reservainfo->telefono2 }}</a>
    @endif</h4>
</body>
</html>