
@php
    $responsive = $responsive ?? true;
    $showSearch = $showSearch ?? 'true';
@endphp

<div class="row">
    <div class="col-md-12 text-left">
        <h2>{{ $title }}</h2>
    </div>
    <div class="col-12">
        <table id="{{ $tableId }}" class="table table-responsive">
            <thead>
            @foreach($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@push('js')
    <script type="text/javascript">
        $('#{{ $tableId }}').DataTable({
            "responsive" : '{{$responsive}}',
            "bFilter": '{{$showSearch}}',
            "bSort": true,
            "aaSorting": [[0,"desc"]],
            "bSortClasses": true,
            "bProcessing": true,
            "columns": [
                    @foreach($fields as $field)
                {"data" : "{{ $field }}" }@if(!$loop->last),@endif
                @endforeach
            ],
            "language": {
                "decimal":        "",
                "emptyTable":     "No hay registros para mostrar",
                "info":           "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty":      "Mostrando 0 a 0 de 0 registros",
                "infoFiltered":   "",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Mostrar _MENU_ registros",
                "loadingRecords": "Cargando...",
                "processing":     "<span class='fa-stack fa-lg'><i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i></span>Procesando...",
                "search":         "Buscar:",
                "zeroRecords":    "No se encontraron registros",
                "paginate": {
                    "first":      "Primero",
                    "last":       "Último",
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                },
                "select":{
                    "rows": "%d registros seleccionados"
                },
                "buttons": {
                    "selectAll":  "Seleccionar todo",
                    "selectNone": "Deseleccionar todo"
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            }
        });
    </script>
@endpush
