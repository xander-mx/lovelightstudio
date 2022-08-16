
@php
    $responsive = $responsive ?? true;
    $showSearch = $showSearch ?? 'true';
    $serverSide = $serverSide ?? 'false';
    $scrollX = $scrollX ?? false;
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

@section('script')
    <script type="text/javascript">
        @if(isset($dataFunction))
        $('#{{ $tableId }}').on('preXhr.dt', function ( e, settings, data ) {

        });
        @endif
        $('#{{ $tableId }}').DataTable({
            "responsive" : '{{$responsive}}',
            "bFilter": '{{$showSearch}}',
            "bSort": true,
            "scrollX": '{{ $scrollX }}',
            "serverSide": {{$serverSide}},
            "processing": true,
            "aaSorting": [[0,"desc"]],
            "bSortClasses": true,
            "bProcessing": true,
            @isset($urlTable)
            "ajax": {
                "url": '{{ $urlTable }}',
                @isset($dataFunction)
                "data": {!! $dataFunction !!}
                @endisset
            },
            @endisset
            "rowCallback" : function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                console.log('rowCallBack {{$tableId}}');
                @if(isset($customViewButtons) && !is_null($customViewButtons))
                        {!! $customViewButtons !!}
                        @endif
            },
            "preDrawCallback" : function(settings) {
                let search = $('#{{ $tableId }}').DataTable().search();
                if(search.length !== 0 && search.length < 3)
                    return false;
            },
            "columnDefs": [
                @if(isset($columnDefs))
                    @foreach($columnDefs as $def)
                        { className: '{{ $def["className"] }}', "targets": [{{ $def['target'] }}] },
                    @endforeach
                @endif
                    @if(isset($columnProp))
                        @foreach($columnProp as $def)
                            { '{{ $def["prop"] }}': '{{ $def["propValue"] }}', "targets": [{{ $def['target'] }}] },
                        @endforeach
                @endif
            ],
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
@endsection
