<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .bod{
            background-image: url("{{ asset('logo/pexels-k-zoltan-544554.jpg') }}");
        }
    </style>
</head>
<body class="bod">
    @if (count($errors) > 0)
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning alert-dismissible" role="alert">
          
                <p> {{ trans('restaurantes.corregirErrores') }} </p>
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
    @if (Session::has('message_error'))
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning alert-dismissible" role="alert">
                😓 {{ Session::get('message_error') }} 😒
            </div>
        </div>
    </div>
@endif
    @if (Session::has('message'))
    <script>
        window.close();
    </script>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-success alert-dismissible" role="alert">
                                😉 {{ Session::get('message') }} 😉
                            </div>
                        </div>
                    </div>
                        
                    @else
                    @if ($reservation->receptioninfo == null)
                    <div class="container mt-10">
                        <form action="{{ route('reserva.cliente.post') }}" method="post">
                            @csrf
                            <div class="card mt-10" style="margin-top: 10px">
                                <div class="card-body ">
                                    <br/>
                                    <br/>
                                    <br/>
                                    <input type="hidden" name="idReserva" value="{{ $idReservation }}">
                                    <input type="hidden" name="email" value="{{ $infoClient->email ?? '' }}">
                                    <input type="hidden" name="email2" value="{{ $infoClient->email2 ?? '' }}">
                                    <input type="hidden" name="telefono1" value="{{ $infoClient->tel1 ?? '' }}">
                                    <input type="hidden" name="telefono2" value="{{ $infoClient->tel2 ?? '' }}">
                                    <input type="hidden" name="canva" value="{{ $infoClient->canva ?? '' }}">
                                    <input type="hidden" name="direccion" value="{{ $infoClient->direccion ?? '' }}">
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-8 mb-3 text-justify " >
                                            <h3 class="card-title text-center">Sesión de Derechos Fotográficos / Copyright Release</h3>
                                            <br/>
                                            <div class="card mt-10" style="margin-top: 10px">
                                                <br/>
                                                <div class="mb-3 text-center">
                                                    <img src="{{ asset('logo/love-light-studio-logo.jpg') }}" alt="Love Light Studio" width="50%"  srcset="">
                                                </div>
                                                <span style="text-align: center"><strong>Español</strong></span>
                                                <div class="card-body " style="text-align: justify">
                                                    <span >Yo <strong>{{ $reservation->client->name }}</strong>, por buena y valiosa contraprestación cuya recepción
                                                        se reconoce por la presente autorizo irrevocablemente a <strong>Love Light Studio.</strong></span>
                                                    <br/>
                                                    <br/>
                                                    <span>Para usar fotografias de mi persona o de mi propiedad la cual le doy a (el / sus y ellas / ella /  sus asignados)
                                                        licencias, representantes legales y sus cesionarios usar y publicar (con o sin nombre, nombre de la compañia o con un
                                                    nombre ficticio) fotografías, retratos o imagenes las cuales fueron otorgadas a la compañia.</span>
                                                    <br/>
                                                    <br/>
                                                    <span>En cualquier y de todas las formas de medios y en todas las maneras incluyendo  imágenes compuestas o representaciones
                                                    distorsionadas.</span>
                                                    <br/>
                                                    <br/>
                                                    <span>Los propósitos de la ilustración  de la publicidad (incluyendo la publicación en formato fisico o digital, en Cd,
                                                        redes sociales o para sitios web de internet), para cualquier producto o servicio u otros usos legales que puedan
                                                    ser determinados por el fotógrafo o nombre del estudio</span>
                                                    <br/>
                                                    <br/>
                                                    <span>Renuncio además a todos y cada uno de los derechos de revisar o aprobar cualquier uso de las imágenes, de cualquier
                                                    copia escrita o producto terminado.</span>
                                                    <br/>
                                                    <br/>
                                                    <span><strong>Soy mayor de edad legal y he leído y entiendo completamente los términos de este comunicado.</strong></span>
                                                </div>

                                                <span style="text-align: center"><strong>English</strong></span>
                                                <div class="card-body " style="text-align: justify">
                                                    <span>I <strong>{{ $reservation->client->name }}</strong>, for good and valuable consideration whose reception is hereby
                                                        acknowledged, irrevocably authorize <strong>Love Light Studio</strong>.</span>
                                                    <br/>
                                                    <br/>
                                                    <span>To use photographs of my person or property which I give (him / her and they / her / his / her assigned) licenses,
                                                    legal representatives and assignees use and publish (with or without name, company name or with a ficticious name)
                                                    photographs, portraits or images which were granted to the company.</span>
                                                    <br/>
                                                    <br/>
                                                    <span>In any and all forms of media and in all ways including composite images or distorted representations.</span>
                                                    <br/>
                                                    <br/>
                                                    <span>The purposes of the illustration of advertising (including publication in physical or digital format, on Cd, social
                                                        networks or for internet websites),for any product or service or other legal uses that may be determined by the
                                                    photographer or name of the study.</span>
                                                    <br/>
                                                    <br/>
                                                    <span>I also waive each and every right to review or approve any use of the images, of any written copy or finished product.</span>
                                                    <br/>
                                                    <br/>
                                                    <span><strong>I am of legal age and have read and fully understand the terms of this release.</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="mb-3 col-8">
                                            <label for="autoriza"  class="form-label">¿Autoriza poder utilizar sus fotografías como publicidad interna? /
                                                Do you authorize the use of your photographs as internal advertising?<span class="text-danger">*</span></label>
                                            <select name="autoriza" id="autoriza" class="form-control">
                                                <option value="si" selected>Si / Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                        <div class="col-2"></div>
                                    </div>
                                    <div class="mb-3 text-center">
                                        <button type="submit" class="btn btn-primary">Enviar / Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    @else
                        <div class="alert alert-danger">Formulario ya ingresado</div>
                    @endif
               
                    @endif
       
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>