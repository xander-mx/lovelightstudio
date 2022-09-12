<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Canvas & Photobooks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="{{ asset('assets/css/bootstrap-icons.css') }}" rel="stylesheet" />
    <style>
        .bod{
            background: url("{{ asset('logo/v722-aum-35a.jpg') }}") no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            }
    </style>
</head>
    <body class="bod">
    <div class="container mt-10">
        <form action="{{ route('weddings.gifts.save') }}" method="post">
            @csrf
            <input type="hidden" name="id" id="id" value="{{ $event->id }}">
            <input type="hidden" name="code" id="code" value="{{ $event->code }}">
            <div class="card mt-10" style="margin-top: 10px">
                <div class="card-body ">
                    <br/>
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-8 mb-3 text-justify " >
                            <div class="card mt-10" style="margin-top: 10px">
                                <br/>
                                <div class="mb-3 text-center">
                                    <img src="{{ asset('logo/love-light-studio-logo.jpg') }}" alt="Love Light Studio" width="50%"  srcset="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="mb-3 col-8 text-justify">
                            <div class="mb-3 text-center">
                                <br/>
                                <span style="text-align: center"><h1>¡GRACIAS!,  {{ $event->customer }}</h1></span>
                                <div class="card-body " style="text-align: justify">
                                    <span>Por permitirnos ser parte del momento más importante de tu vida. Para finalizar
                                        tu servicio es necesario escoger las fotos para tus canvas y libros.
                                        A continuación ingresa el número de fotos para uno</span>
                                </div>

                                <br/>
                                <span style="text-align: center"><h1>THANK YOU!, {{ $event->customer }}</h1></span>
                                <div class="card-body " style="text-align: justify">
                                    <span>For allowing us to be part of the most important moment of your life.
                                    To finish your service it is necessary to choose the photos for your canvas and books.
                                    Then enter the number of photos for each one</span>
                                </div>
                                <br/>

                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="address">Domicilio / Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-house-door"></i>
                                            </span>
                                            <input name="address" id="address" type="text" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                @for($i=0; $i < $event->canvas; $i++)
                                <br/>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="giftcanva{{$i}}">Canva {{$i+1}}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-postage-heart"></i>
                                            </span>
                                            <input name="giftcanva{{$i}}" id="giftcanva{{$i}}" type="text" class="form-control" placeholder="example: 23,56,34" />
                                        </div>
                                    </div>
                                </div>
                                @endfor

                                @for($i=0; $i < $event->photobooks; $i++)
                                <br/>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="giftphotobook{{$i}}">Photobook {{$i+1}}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-journal-bookmark"></i>
                                            </span>
                                            <input name="giftphotobook{{$i}}" id="giftphotobook{{$i}}" type="text" class="form-control" placeholder="example: 23,56,34"/>
                                        </div>
                                    </div>
                                </div>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-3 text-center">
                            <button type="submit" class="btn btn-primary">Enviar / Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>
</html>
