@php
  use App\Http\Controllers\Admin\EventsController;
@endphp
@component('templates.modal', ['modalId' => 'info-payment-modal', 'title' => 'Pagos Aplicados', 'close' => true,
                               'backdrop' => true, 'modalSize' => 'modal-lg' ])
    @slot('body')
        <div class="row">
            <div class="card col-12">
                <div class="card-body">
                    @include('templates.datatable', [
                        'tableId' => 'info-payments-table',
                        'title' => 'Pagos Aplicados',
                        'serverSide' => 'true',
                        'showSearch' => 'false',
                        'headers' => EventsController::$headersPayments,
                        'fields' => EventsController::$fieldsPayments,
                        'urlTable' => route(EventsController::$routeIndexTablePayments,[1]),
                        'autoWidth' => 'false'
                    ])
                </div>
            </div>
        </div>
    @endslot

@endcomponent
