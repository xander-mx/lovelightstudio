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
                        aria-hidden="true">&times;</span></button>
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
                    @if ($reser->receptioninfo == null)
                    <div class="container mt-10">
                        <form action="{{ route('reserva.cliente.post') }}" method="post">
                            @csrf
                        <div class="card mt-10" style="margin-top: 10px">
                            {{-- <img src="{{ asset('logo/love-light-studio-logo copia.jpg') }}" class="card-img-top "width="200px" height="200px" alt="" srcset=""> --}}
                            
                            <div class="card-body ">
                            <h5 class="card-title text-center">Formulario de finalizaciòn</h5>
                                <input type="hidden" name="idReserva" value="{{ $idCliente }}">
                                {{-- {{ dd($idCliente) }} --}}
                                    <div class="mb-3">                                        
                                        <label for="exampleInputEmail1" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                      </div>
                                      <div class="mb-3">                                        
                                        <label for="exampleInputEmail1" class="form-label">Email 2  </label>
                                        <input type="email" class="form-control" name="email2" id="exampleInputEmail1" aria-describedby="emailHelp" >
                                      </div>
                                      <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Telefono </label>
                                        <input type="text" class="form-control" name="tel1" id="exampleInputEmail1" aria-describedby="emailHelp" value="+52">
                                      </div>
                                      <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Telefono 2  </label>
                                        <input type="text" class="form-control" name="tel2" id="exampleInputEmail1" aria-describedby="emailHelp" value="+52"  >
                                      </div>
                                      <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Canva </label>
                                        <input type="text"  class="form-control" name="canva" id="exampleInputEmail1" aria-describedby="emailHelp"   >
                                      </div>
                                      <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Direccion   </label>
                                        <input type="text"  class="form-control" name="direccion" id="exampleInputEmail1" aria-describedby="emailHelp"   >
                                      </div>
                                      <div class="mb-3 text-center">
                                          <img src="{{ asset('logo/form.PNG') }}" alt="" width="800px"  srcset="">
                                      </div>
                                      <div class="mb-3">
                                        <label for="exampleInputEmail1"  class="form-label">¿Autoriza poder utilizar sus fotografías como publicidad interna? <span class="text-danger">*</span></label>
                                        <select name="autoriza" id="" class="form-control">
                                            <option value="si" selected>Si</option>
                                            <option value="no">No</option>
                                        </select>
                                      </div>
                                      
                                      <div class="mb-3 text-center">
                                          <button type="submit" class="btn btn-primary">Enviar</button>
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