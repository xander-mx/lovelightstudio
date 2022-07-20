<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <p>¡Hola!</p>
    <p>Este es tu enlace para que puedan acceder a su galería de fotos </p>
    <p><a href="{{ $reserva->receptioninfo->linkPagina }}" target="_blank" rel="noopener noreferrer">{{ $reservainfo->linkPagina }}</a></p>
    <p>Password: {{ $reservainfo->codigoFoto }} </p>
    <p>Gracias por permitirnos ser parte de su experiencia en Love Light Studio ❤ </p>
    <p>Nos ayudaría mucho si nos das like en</p>
    <p><a href="https://www.facebook.com/LLSmexico/" target="_blank" rel="noopener noreferrer">https://www.facebook.com/LLSmexico/</a></p>
    <p><a href="https://www.instagram.com/LLSmexico" target="_blank" rel="noopener noreferrer">https://www.instagram.com/LLSmexico</a></p>
    <p>¡Qué tenga un excelente día! 😊</p>

    <p>Numero de telefono a enviar:</p>
    <p> @if ($reservainfo->telefono1 != null)
    <a href="https://api.whatsapp.com/send?phone={{ $reservainfo->telefono1 }}">{{ $reservainfo->telefono1 }}</a>     
    @endif
    @if ($reservainfo->telefono2 != null)
       <br> 
       <a href="https://api.whatsapp.com/send?phone={{ $reservainfo->telefono2 }}"> {{ $reservainfo->telefono2 }}</a>
    @endif</p>
</body>
</html>