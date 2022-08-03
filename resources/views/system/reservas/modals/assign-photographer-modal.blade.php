@component('templates.modal', ['modalId' => 'photographer-modal', 'title' => 'Asignar Fotografo', 'backdrop' => true,
           'close' => 'cerrarModal()'])
    @slot('body')
        <div class="row">
            <div class="col" style="height: 600px;overflow: scroll;">
                <table class="table table-responsive ">
                    <thead>
                    <tr>
                        <th>Nombre fotografo</th>
                        <th>Nº Sesion </th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($trabajadores  as $key => $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $reservasNumeroHoy[$item->idUser] }}</td>
                            <td>
                                <form action="{{ route('reserva.asignar') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="idReserva" id="reserva_{{ $key }}">
                                    <input type="hidden" name="idFotografo" value="{{ $item->idUser }}">
                                    <button type="submit" class="btn btn-primary">Asignar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endslot
    @slot('footer')
        <div class="row text-center">
            <div class="col text-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cerrarModal()">Cerrar</button>
            </div>
        </div>
    @endslot
@endcomponent
